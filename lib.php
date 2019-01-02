<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * lib.php - Contains Plagiarism plugin specific functions called by Modules.
 *
 * @package    plagiarism_safeassign
 * @copyright  Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); // It must be included from a Moodle page.
}

define('SAFEASSIGN_SUBMISSION_MAX_SIZE', 10000000);
// Get global class.
global $CFG;
require_once($CFG->dirroot.'/plagiarism/lib.php');
use plagiarism_safeassign\api\safeassign_api;
use plagiarism_safeassign\api\rest_provider;
use plagiarism_safeassign\event\sync_content_log;
use plagiarism_safeassign\event\score_sync_log;
use plagiarism_safeassign\event\score_sync_fail;
use plagiarism_safeassign\event\license_log;
use plagiarism_safeassign\local;

use plagiarism_safeassign\api\event_processor_creator;

/**
 * Extends plagiarism core base class.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plagiarism_plugin_safeassign extends plagiarism_plugin {

    /**
     * @var array $supportedmodules Attribute to list the supported modules.
     */
    private $supportedmodules = ['mod_assign'];

    /**
     * Return the list of form element names.
     *
     * @return array contains the form element names.
     */
    public function get_configs() {
        return array('safeassign_enabled', 'safeassign_originality_report', 'safeassign_global_reference');
    }

    /**
     * Process an incoming event. Will call a factory and execute a class depending on the event.
     * @param \core\event\base $event
     * @throws coding_exception
     */
    public function process_event(\core\event\base $event) {
        event_processor_creator::processor_factory($event);
    }

    /**
     * This function should be used to initialise settings and check if plagiarism is enabled.
     *
     * @return array|bool - false if not enabled, or return an array of relevant settings.
     */
    static public function get_settings() {
        static $plagiarismsettings;
        if (!empty($plagiarismsettings) || $plagiarismsettings === false) {
            return $plagiarismsettings;
        }
        $safeassignenabled = get_config('plagiarism', 'safeassign_use');
        // Check if enabled.
        if (!empty($safeassignenabled)) {
            // Now check to make sure required settings are set.
            $plagiarismsettings = (array)get_config('plagiarism_safeassign');
            if (empty($plagiarismsettings['safeassign_api'])) {
                debugging("SafeAssign API URL not set!");
                return false;
            }
            return $plagiarismsettings;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     * @param array $linkarray
     */
    public function get_links($linkarray) {
        global $DB;

        $cmid = $linkarray['cmid'];

        // Check if the user has the right capabilities to see the report.
        $cm = context_module::instance($cmid);
        if (!has_capability('plagiarism/safeassign:report', $cm)) {
            return '';
        }

        // Check if SafeAssign is enabled and configured at global level.
        $plagiarismsettings = $this->get_settings();
        if (!$plagiarismsettings) {
            return '';
        }

        // Check that the activity has SafeAssign enabled.
        static $courseconfiguration;
        $courseconfiguration = $DB->get_records_menu('plagiarism_safeassign_config', array('cm' => $cmid), '', 'name, value');

        if (!empty($courseconfiguration['safeassign_enabled'])) {
            // The activity has SafeAssign enabled.
            $message = '';
            $file = null;
            $userid = $linkarray['userid'];
            $submissionid = $this->get_submission_id($linkarray);
            if ($submissionid != 0) {
                $submissionobject = \plagiarism_safeassign\api\abstract_submission_processor::get_submission($submissionid);
                if ($submissionobject->__get("status") ===
                        \plagiarism_safeassign\api\safeassign_submission::STATUS_SUBMISSION_SUBMITTED) {
                    $fileinfo = $this->get_file_info($linkarray, $submissionobject);
                    $message = $this->get_message_result($fileinfo, $cm, $courseconfiguration, $userid);
                } else if ($submissionobject->__get("status") ===
                        \plagiarism_safeassign\api\safeassign_submission::STATUS_SUBMISSION_IS_INSTRUCTOR) {
                    $message .= get_string('safeassign_submission_not_supported', 'plagiarism_safeassign');
                } else if ($submissionobject->__get("status") ===
                        \plagiarism_safeassign\api\safeassign_submission::STATUS_SUBMISSION_MAX_SIZE) {
                    $message .= get_string('safeassign_file_limit_exceeded', 'plagiarism_safeassign');
                }
            }
            return $message;
        } else {
            // The activity is not configured with SafeAssign.
            return '';
        }

    }

    /**
     * Retrieve the SafeAssign information from a submission's file
     * @param int $cmid
     * @param int $userid
     * @param int $fileid
     * @return array containing at least:
     *   - 'analyzed' - whether the file has been successfully analyzed
     *   - 'score' - similarity score - ('' if not known)
     *   - 'reporturl' - url of originality report - '' if unavailable
     * @throws dml_missing_record_exception
     * @throws dml_multiple_records_exception
     */
    public function get_file_results($cmid, $userid, $fileid) {
        global $DB;
        $analyzed = 0;
        $score = '';
        $reporturl = '';

        $sql = "SELECT s.reportgenerated as analyzed,
                       f.similarityscore as score,
                       f.reporturl
                  FROM {plagiarism_safeassign_subm} s
                  JOIN {plagiarism_safeassign_files} f ON s.submissionid = f.submissionid
                 WHERE f.fileid = :fileid";

        $record = $DB->get_record_sql($sql, ["fileid" => $fileid]);
        if ($record) {
            $analyzed = $record->analyzed;
            $score = $record->score;
            $reporturl = $record->reporturl;
        }
        return ['analyzed' => $analyzed, 'score' => $score, 'reporturl' => $reporturl];
    }

    /**
     * Retrieves the submission id from SafeAssign tables given a linkarray.
     * @param array $linkarray
     * @return int
     */
    private function get_submission_id($linkarray) {
        global $DB;
        $submissionid = 0;
        $cmid = $linkarray['cmid'];
        $userid = $linkarray['userid'];
        if (isset($linkarray['file'])) {
            $file = $linkarray['file'];
            // Get submission id from files that have submissions associated with SafeAssign.
            $sql = "
                    SELECT sasub.submissionid
                      FROM {plagiarism_safeassign_subm} sasub
                      JOIN {plagiarism_safeassign_files} safil ON safil.submissionid = sasub.submissionid
                     WHERE safil.fileid = :fileid
                    ";

            $record = $DB->get_record_sql($sql,
                    ['fileid' => $file->get_id()]
                );

            if ($record) {
                $submissionid = $record->submissionid;
            }
        } else if (isset($linkarray['content']) && !empty($linkarray['content'])) {
            $record = $DB->get_fieldset_select('plagiarism_safeassign_subm', "submissionid", "cmid = :cmid AND userid = :userid",
                    ["cmid" => $cmid, "userid" => $userid]);
            $submissionid = $record[0];
        }
        return $submissionid;
    }

    /**
     * Retrieves the file info from SafeAssign file table given a linkarray.
     * @param $linkarray
     * @return object containing all information for the file received.
     * @throws dml_missing_record_exception
     */
    private function get_file_info($linkarray, $submissionobject) {
        global $DB;

        $submissionid = $submissionobject->__get('submissionid');

        $filesubtype = '';
        if (isset($linkarray['file'])) {
            $filesubtype = 'file';
            $fileid = $linkarray['file']->get_id();
        } else if (isset($linkarray['content']) && !empty($linkarray['content'])) {
            $filesubtype = 'text';
            $record = $DB->get_fieldset_select('plagiarism_safeassign_files', "fileid", "submissionid = :submissionid",
                ["submissionid" => $submissionid]);
            $fileid = $record[0];
        }

        $fileinfo = $DB->get_record('plagiarism_safeassign_files',
            ['submissionid' => $submissionid, 'fileid' => $fileid]);

        // Set supported as default.
        $fileinfo->supported = $fileinfo->supported == null ? 1 : $fileinfo->supported;
        $fileinfo->filesubtype = $filesubtype;
        $fileinfo->analyzed = $submissionobject->reportgenerated;
        $fileinfo->avgscore = $submissionobject->avgscore;
        $fileinfo->subuuid = $submissionobject->uuid;

        return $fileinfo;
    }

    /**
     * Returns the message to display for a specific file depending the state of that submission.
     * @param object $file
     * @param int $cm
     * @param array $courseconfiguration
     * @param int $userid
     * @return string
     */
    private function get_message_result($file, $cm, array $courseconfiguration, $userid) {
        global $USER, $OUTPUT, $COURSE, $PAGE, $DB;

        $onlinetextclass = $file->filesubtype === 'text' ? 'online-text-div' : '';
        $message = '<div class="plagiarism-inline ' . $onlinetextclass . '">';

        // Instructor submissions are not supported.
        if ($DB->record_exists('plagiarism_safeassign_instr', array('courseid' => $COURSE->id,
                'instructorid' => $userid, 'unenrolled' => 0))) {
            $message .= get_string('safeassign_submission_not_supported', 'plagiarism_safeassign');
            $message .= $OUTPUT->help_icon('safeassign_submission_not_supported', 'plagiarism_safeassign');
            $message .= '</div>';
            return $message;
        }

        if ($file->supported) {
            if ($file->analyzed) {
                // We have a valid report for this file.
                $message .= get_string('safeassign_file_similarity_score', 'plagiarism_safeassign',
                    intval($file->similarityscore * 100));

                // We need to validate that the user can see the link to the similarity report.
                $role = get_user_roles($cm, $USER->id);
                $roleid = key($role);
                if (empty($role) || $role[$roleid]->shortname != 'student' ||
                    $courseconfiguration['safeassign_originality_report']) {
                    // The report is enabled for this user.
                    $reporturl = new moodle_url('/plagiarism/safeassign/view.php', [
                        'courseid' => $COURSE->id,
                        'uuid' => $file->subuuid,
                        'fileuuid' => $file->uuid
                    ]);
                    $message .= html_writer::link($reporturl,
                        get_string('safeassign_link_originality_report', 'plagiarism_safeassign'),
                        ['target' => '_sa_originality_report']);
                }

                // Print the overall score for this submission.
                $PAGE->requires->js_call_amd('plagiarism_safeassign/score', 'init',
                    array(intval($file->avgscore * 100), $userid));
            } else {
                $message .= get_string('safeassign_file_in_review', 'plagiarism_safeassign');
            }
        } else {
            // This file is not supported by SafeAssign.
            $message .= get_string('safeassign_file_not_supported', 'plagiarism_safeassign');
            $message .= $OUTPUT->help_icon('safeassign_file_not_supported', 'plagiarism_safeassign');
        }
        $message .= '</div>';
        return $message;
    }

    /**
     * hook to add plagiarism specific settings to a module settings page
     * @param object $mform  - Moodle form
     * @param object $context - current context
     * @param string $modulename - Name of the module
     */
    public function get_form_elements_module($mform, $context, $modulename = "") {

        global $DB, $PAGE;

        if (!$this->is_supported_module($modulename)) {
            return;
        }

        $cmid = optional_param('update', 0, PARAM_INT);

        $plagiarismelements = $this->get_configs();
        $plagiarismvalues = $DB->get_records_menu('plagiarism_safeassign_config', array('cm' => $cmid),
            '', 'name, value');

        if (has_capability('plagiarism/safeassign:enable', $context)) {
            safeassign_get_form_elements($mform);

            foreach ($plagiarismelements as $element) {

                // Disable all plagiarism elements if use_plagiarism eg 0.
                if ($element != 'safeassign_enabled') { // Ignore this var.
                    $mform->disabledIf($element, 'safeassign_enabled');
                }

                // Load old configuration values for this assignment.
                if (isset($plagiarismvalues[$element])) {
                    $mform->setDefault($element, $plagiarismvalues[$element]);
                }
            }

            $PAGE->requires->js_call_amd('plagiarism_safeassign/formelements', 'init', array());

        }
    }

    /**
     * Returns if a module is supported or not.
     * @param string $modulename
     * @return boolean true if the module is supported, false otherwise.
     */
    public function is_supported_module($modulename) {
        return in_array( $modulename, $this->supportedmodules);
    }

    /**
     * hook to save plagiarism specific settings on a module settings page
     * @param object $data - data from an mform submission.
     */
    public function save_form_elements($data) {
        global $DB;

        $existingelements = $DB->get_records_menu('plagiarism_safeassign_config', array('cm' => $data->coursemodule),
            '', 'name, id');

        // First, update config table. If SafeAssign is enabled, update other tables.
        $plagiarismelements = $this->get_configs();
        $saenabled = isset($data->safeassign_enabled) && $data->safeassign_enabled;
        foreach ($plagiarismelements as $element) {
            $newelement = new stdClass();
            $newelement->cm = $data->coursemodule;
            $newelement->name = $element;
            $newelement->value = isset($data->$element) && $saenabled ? $data->$element : 0;
            if (isset($existingelements[$element])) {
                $newelement->id = $existingelements[$element];
                $DB->update_record('plagiarism_safeassign_config', $newelement);
            } else {
                $DB->insert_record('plagiarism_safeassign_config', $newelement);
            }
        }
        if ($saenabled) {
            $this->safeassign_course_dbsaver($data->course);
            // We have to set the object in safeassign_mod table.
            $moddata = new stdClass();
            $moddata->moduleid = $data->module;
            $moddata->courseid = $data->course;
            $moddata->instanceid = $data->instance;
            $moddata->cmid = $data->coursemodule;
            $this->safeassign_module_dbsaver($moddata);
        }
    }

    /**
     * Adds a module to plagiarism_safeassign_mod table when its created on a course.
     *
     * @param object $moddata
     * @return null
     */
    private function safeassign_module_dbsaver($moddata) {
        global $DB;

        // Let's check that the module does not exist previously on db.
        if (!$DB->record_exists('plagiarism_safeassign_mod',
                ['moduleid' => $moddata->moduleid, 'instanceid' => $moddata->instanceid])) {
            $DB->insert_record('plagiarism_safeassign_mod', $moddata);
        }
    }

    /**
     * hook to allow a disclosure to be printed notifying users what will happen with their submission.
     * @param int $cmid - course module id.
     * @return string $output - HTMl to be rendered.
     */
    public function print_disclosure($cmid) {
        global $USER, $PAGE, $DB, $COURSE;
        $checked = false;
        $form = '';
        $value = 0;
        $cmenabled = $DB->get_record('plagiarism_safeassign_config', array('cm' => $cmid, 'name' => 'safeassign_enabled'));
        $cmglobalref = $DB->get_record('plagiarism_safeassign_config',
            array('cm' => $cmid, 'name' => 'safeassign_global_reference'));
        $siteglobalref = get_config('plagiarism_safeassign', 'safeassign_referencedbactivity');

        if ( !is_object($cmenabled) || $cmenabled->value == 0) {
            return '';
        }
        $info = $DB->get_record('plagiarism_safeassign_config', array('cm' => $cmid, 'name' => $USER->id));
        if (!empty($info->value)) {
            $checked = true;
            $value = $info->value;
        }
        $checkbox = '';
        if ($cmglobalref->value == 0) {
            $checkbox = html_writer::checkbox('agreement', $value, $checked, get_string('agreement', 'plagiarism_safeassign'));
        }
        if ($DB->record_exists('plagiarism_safeassign_instr', array('courseid' => $COURSE->id,
                'instructorid' => $USER->id, 'unenrolled' => 0))) {
            $form = html_writer::tag('div', get_string('plagiarism_tools', 'plagiarism_safeassign'), array('class' => 'col-md-3'));
            $form .= get_string('safeassign_submission_not_supported_help', 'plagiarism_safeassign');
        } else {
            if ($siteglobalref == 1) {
                $institutionrelease = get_config('plagiarism_safeassign', 'safeassign_new_student_disclosure');
                if (empty($institutionrelease)) {
                    $institutionrelease = get_string('studentdisclosuredefault', 'plagiarism_safeassign');
                    $institutionrelease .= '<br><br>';
                } else {
                    $institutionrelease .= '<br><br>';
                }
                $institutionrelease .= get_string('files_accepted', 'plagiarism_safeassign');
                $institutionrelease .= '<br><br>'.$checkbox;
                $col1 = html_writer::tag('div', get_string('plagiarism_tools', 'plagiarism_safeassign'),
                    array('class' => 'col-md-3'));
                $col2 = html_writer::tag('div', $institutionrelease, array('class' => 'col-md-9'));
                $output = html_writer::tag('div', $col1.$col2, array('class' => 'row generalbox boxaligncenter intro'));
                $form = html_writer::tag('form', $output);
                $PAGE->requires->js_call_amd('plagiarism_safeassign/disclosure', 'init', array($cmid, $USER->id));
            }
        }
        return $form;
    }

    /**
     * hook to allow status of submitted files to be updated - called on grading/report pages.
     *
     * @param object $course - full Course object
     * @param object $cm - full cm object
     */
    public function update_status($course, $cm) {
    }

    /**
     * Deprecated cron method.
     *
     * This method was added by mistake in the previous versions of Moodle, do not override it since it is never called.
     * To implement cron you need to register a scheduled task, see https://docs.moodle.org/dev/Task_API.
     * For backward compatibility with the old cron API the method cron() from this class can also be used.
     *
     * @deprecated since Moodle 3.1 MDL-52702 - please use scheduled tasks instead.
     */
    public function plagiarism_cron() {
        debugging('plagiarism_plugin::plagiarism_cron() is deprecated. Please use scheduled tasks instead', DEBUG_DEVELOPER);
    }

    /**
     * Adds courses to plagiarism_safeassign_course table when a cm is created in that course.
     *
     * @param int $courseid
     */
    private function safeassign_course_dbsaver($courseid) {
        global $DB, $USER;

        // Let's check that the course does not exist previously on db.
        if (!$DB->record_exists('plagiarism_safeassign_course', ['courseid' => $courseid])) {
            // We have to set the object in safeassign_course table.
            $coursedata = new stdClass();
            $coursedata->uuid = null;
            $coursedata->courseid = $courseid;
            $coursedata->creatorid = $USER->id;
            $DB->insert_record('plagiarism_safeassign_course', $coursedata);
            $this->set_course_instructors($courseid);
        }
    }

    /**
     * Checks if submission has to be sent to global reference db or not depending on user settings.
     * @param array $configdata Contains the data returned by check_assignment_config() function
     * @param int $userid The ID of the submitter
     * @return bool $response
     */
    public function should_send_to_global_check($configdata, $userid) {
        $siteglobalref = get_config('plagiarism_safeassign', 'safeassign_referencedbactivity');
        $response = false;
        if ($siteglobalref) {
            if (!$configdata['safeassign_global_reference']) {
                $submitterpreference = 0;
                if (isset($configdata[$userid])) {
                    $submitterpreference = $configdata[$userid];
                }
                if ($submitterpreference) {
                    $response = true;
                }
            }
        }
        return $response;
    }

    /**
     * Returns the SafeAssign configuration for the assignment.
     * @param object $eventdata
     * @return array $config
     */
    public static function check_assignment_config($eventdata) {
        global $DB;
        $config = $DB->get_records_menu('plagiarism_safeassign_config', array('cm' => $eventdata['contextinstanceid']),
            '', 'name, value');
        return $config;
    }

    /**
     * Function to be run periodically according to the scheduled task.
     * Checks if the submissions already have a report generated on SafeAssign side and mark the flag.
     */
    public function safeassign_get_scores() {
        global $DB, $CFG;
        $updatedsubmissions = array();
        $gradedsubmissions = array(); // Array of cmids and number of submissions processed by SafeAssign.

        $sql = '
                SELECT *
		          FROM {plagiarism_safeassign_subm} s
                 WHERE s.uuid IS NOT NULL';

        $submissions = $DB->get_records_sql($sql);
        $count = 0;
        $baseurl = get_config('plagiarism_safeassign', 'safeassign_api');
        foreach ($submissions as $submission) {
            $cmid = $submission->cmid;
            if (!array_key_exists($cmid, $gradedsubmissions)) {
                $gradedsubmissions[$cmid] = 0;
            }

            $userid = $submission->userid;
            $submissionuuid = $submission->uuid;
            $result = '';
            if ($submissionuuid) {
                $result = safeassign_api::get_originality_report_basic_data($userid, $submissionuuid);
            } else {
                continue;
            }
            if (!empty($result)) {
                $convhighscore = floatval($result->highest_score / 100);
                $convavgscore = floatval($result->average_score / 100);
                $submission->highscore = $convhighscore;
                $submission->avgscore = $convavgscore;
                $submission->reportgenerated = 1;
                if (isset($result->submission_files)) {
                    foreach ($result->submission_files as $fileuuid => $score) {
                        $DB->set_field('plagiarism_safeassign_files', 'similarityscore',
                            floatval($score / 100), array('uuid' => $fileuuid));
                    }
                }
                unset($submission->userid);
                $DB->update_record('plagiarism_safeassign_subm', $submission);
                $count ++;
                array_push($updatedsubmissions, $submission);
                $gradedsubmissions[$cmid] += 1;
            } else {
                $params = array();
                if (!empty($CFG->plagiarism_safeassign_debugging)) {
                    $params['User ID'] = $userid;
                    $params['Submission UUID'] = $submissionuuid;
                    $params['Url'] = $baseurl . '/api/v1/submissions/' . $submissionuuid . '/report/metadata';
                }
                $event = score_sync_fail::create_from_error_handler($submission->id, false, 'api_error', $params);
                $event->trigger();
            }
        }
        if ($count > 0) {
            $event = score_sync_log::create_log_message($count);
            $event->trigger();
        }
        $event = score_sync_log::create();
        $event->trigger();

        // Send a message to the teachers.
        if (!local::duringphptesting()) {
            foreach ($gradedsubmissions as $cmid => $counter) {
                if ($counter > 0) {
                    $modinfo = $DB->get_record('plagiarism_safeassign_mod', array("cmid" => $cmid), 'instanceid, moduleid');
                    if ($modinfo) {
                        $instanceid = $modinfo->instanceid;
                        $moduleid = $modinfo->moduleid;
                        $modulename = $DB->get_field('modules', 'name', array('id' => $moduleid));
                        list($course, $cm) = get_course_and_cm_from_instance($instanceid, $modulename);
                        $courseid = $course->id;
                        $context = context_course::instance($courseid);
                        $teachers = get_enrolled_users($context, 'plagiarism/safeassign:get_messages');

                        // Search for assignment's name.
                        $assignment = $DB->get_record($modulename, array('id' => $instanceid));
                        $assignmentname = $assignment->name;
                        foreach ($teachers as $teacher) {
                            $teacherid = $teacher->id;
                            self::send_notification_to_teacher($teacherid, $courseid, $cm->id, $counter, $assignmentname);
                        }
                    }
                }
            }
        }
    }

    /**
     * Gets the courses that already have been synced.
     *
     * @return array object
     */
    public function get_valid_courses() {
        global $DB;

        $sql = '
       SELECT DISTINCT courseid, MAX(creatorid) as creatorid
                  FROM {plagiarism_safeassign_course}
                 WHERE uuid IS NOT NULL
              GROUP BY courseid';

        return $DB->get_records_sql($sql, array());
    }

    /**
     * Gets the modules that already have been synced.
     *
     * @return array object
     */
    public function get_valid_modules() {
        global $DB;

        $sql = 'SELECT *
                  FROM {plagiarism_safeassign_mod}
                 WHERE uuid IS NOT NULL';

        return $DB->get_records_sql($sql, array());
    }

    /**
     * Syncs the local SafeAssign course table with the identifier from the SafeAssign service.
     * @param stdClass[] $courses
     */
    public function sync_courses($courses) {
        global $DB, $CFG;
        $count = 0;
        $baseurl = get_config('plagiarism_safeassign', 'safeassign_api');

        foreach ($courses as $course) {
            if ($course->creatorid != 0) {
                $validation = safeassign_api::get_course($course->creatorid, $course->courseid);
                if ($validation === false) {
                    $response = safeassign_api::create_course($course->creatorid, $course->courseid);
                    $params = array();
                    if (!empty($CFG->plagiarism_safeassign_debugging)) {
                        $params['Instructor Id'] = $course->creatorid;
                        $params['Course ID'] = $course->courseid;
                        $params['Url'] = $baseurl . '/api/v1/courses';
                    }
                    if ($response) {
                        $lastresponse = json_decode(rest_provider::instance()->lastresponse());
                        if (isset($lastresponse->uuid)) {
                            $course->uuid = $lastresponse->uuid;
                            $DB->update_record('plagiarism_safeassign_course', $course);
                            safeassign_api::put_instructor_to_course($course->creatorid, $course->uuid);
                            $count ++;
                            continue;
                        } else {
                            $event = sync_content_log::create_log_message('Course', $course->courseid, true, null, $params);
                            $event->trigger();
                        }
                    } else {
                        $event = sync_content_log::create_log_message('Course', $course->courseid, true, null, $params);
                        $event->trigger();
                    }
                } else if (isset($validation->uuid)) {
                    $course->uuid = $validation->uuid;
                    $DB->update_record('plagiarism_safeassign_course', $course);
                    safeassign_api::put_instructor_to_course($course->creatorid, $course->uuid);
                    $count ++;
                }
            }
        }
        if ($count > 0) {
            $event = sync_content_log::create_log_message('Courses', $count, false);
            $event->trigger();
        }
    }

    /**
     * Syncs the existing modules. It is necessary that the course have the corresponding uuid.
     */
    public function sync_course_modules() {
        global $DB, $CFG;
        $courses = $this->get_valid_courses();
        if (!empty($courses)) {
            foreach ($courses as $course) {
                $ids[] = $course->courseid;
            }
            $sql = "SELECT sa_module.moduleid,
                           sa_module.instanceid,
                           sa_module.instanceid,
                           sa_course.courseid,
                           sa_course.uuid AS courseuuid,
                           m.name as modulename
                      FROM {plagiarism_safeassign_mod} sa_module
                      JOIN {plagiarism_safeassign_course} sa_course ON sa_course.courseid = sa_module.courseid
                      JOIN {plagiarism_safeassign_config} sa_config ON sa_config.cm = sa_module.cmid
                      JOIN {modules} m ON sa_module.moduleid = m.id
                     WHERE sa_module.uuid IS NULL
                       AND sa_config.name = 'safeassign_enabled'
                       AND sa_config.value = 1
                       AND sa_module.courseid ";

            list($sqlin, $params) = $DB->get_in_or_equal($ids);
            $modules = $DB->get_records_sql($sql . $sqlin, $params);
            $count = 0;
            $baseurl = get_config('plagiarism_safeassign', 'safeassign_api');

            foreach ($modules as $module) {
                // Check that the module exists in the SafeAssign database.
                $validation = safeassign_api::check_assignment($courses[$module->courseid]->creatorid,
                    $module->courseuuid, $module->moduleid, $module->instanceid);
                $params = array();

                $record = $DB->get_fieldset_select($module->modulename, "name", "id = :id", ["id" => $module->instanceid]);
                $assignmentname = $record[0];

                if (!empty($CFG->plagiarism_safeassign_debugging)) {
                    $params['Instructor ID'] = $courses[$module->courseid]->instructorid;
                    $params['Course UUID'] = $module->courseuuid;
                    $params['Assignment ID'] = $module->assignmentid;
                    $params['Assignment Name'] = $assignmentname;
                    $params['Url'] = $baseurl . '/api/v1/courses/' . $module->courseuuid . '/assignments';
                }

                if ($validation === false) {
                    $response = safeassign_api::create_assignment($courses[$module->courseid]->instructorid,
                        $module->courseuuid, $module->assignmentid, $assignmentname);
                    if ($response) {
                        $lastresponse = json_decode(rest_provider::instance()->lastresponse());
                        if (isset($lastresponse->uuid)) {
                            $DB->set_field('plagiarism_safeassign_assign', 'uuid',
                                $lastresponse->uuid, array('assignmentid' => $module->assignmentid));
                            $count ++;
                        }
                    } else if ($response === false) {
                        $event = sync_content_log::create_log_message('Assignment', $module->assignmentid, true, null, $params);
                        $event->trigger();
                    }
                } else if ($validation) {
                    $lastresponse = json_decode(rest_provider::instance()->lastresponse());
                    if (isset($lastresponse->uuid)) {
                        $DB->set_field('plagiarism_safeassign_mod', 'uuid',
                            $lastresponse->uuid, array('moduleid' => $module->moduleid, 'instanceid' => $module->instanceid));
                        $count ++;
                    } else {
                        $event = sync_content_log::create_log_message('Assignment', $module->instanceid, true, null, $params);
                        $event->trigger();
                    }
                }
            }
            if ($count > 0) {
                $event = sync_content_log::create_log_message('Assignments', $count, false);
                $event->trigger();
            }
        }
    }

    /**
     * Gets the courseuuid and the moduleuuid for the given modules.
     * @param array $modules ids of valid SafeAssign modules
     * @return array object
     */
    public function get_course_credentials($modules) {
        global $DB;

        $sql = "
                SELECT sa_mod.instanceid,
                       sa_mod.moduleid,
                       sa_course.uuid AS courseuuid,
                       sa_mod.uuid AS assignuuid,
                       sa_course.courseid
                  FROM {plagiarism_safeassign_mod} sa_mod
                  JOIN {plagiarism_safeassign_course} sa_course ON sa_course.courseid = sa_mod.courseid
                 WHERE sa_mod.uuid IS NOT NULL
                   AND sa_mod.id ";
        list($sqlin, $params) = $DB->get_in_or_equal($modules);
        $records = $DB->get_records_sql($sql . $sqlin, $params);

        return $records;
    }

    /**
     * Returns the submissions that needs to be synced.
     * data: userid, submissionid, globalcheck, courseuuid and moduleuuid
     * @return array object
     */
    public function get_unsynced_submissions() {
        global $DB;
        $sql = "SELECT sas.userid,
                       sas.submissionid,
                       sas.globalcheck,
                       sac.uuid as courseuuid,
                       sam.uuid as moduleuuid
                  FROM {plagiarism_safeassign_subm} sas
                  JOIN {plagiarism_safeassign_mod} sam ON sam.cmid = sas.cmid
                  JOIN {plagiarism_safeassign_course} sac ON sam.courseid = sac.courseid
                 WHERE sas.uuid IS NULL";

        $records = $DB->get_records_sql($sql, array());

        return $records;
    }

    /**
     * Sync the module submissions.
     * @return bool
     */
    public function sync_module_submissions() {
        $modules = $this->get_valid_modules();
        if (empty($modules)) {
            return false;
        }
        $ids = [];

        foreach ($modules as $module) {
            $ids[] = $module->id;
        }

        $credentials = $this->get_course_credentials($ids);
        // Can't sync anything without credentials for courses and assignments.
        if (!empty($credentials)) {
            $unsynced = $this->get_unsynced_submissions();
            if (empty($unsynced)) {
                return false;
            }
            // Check each submission.
            $count = 0;
            foreach ($unsynced as $unsyncsubmission) {
                if ($this->sync_submission($unsyncsubmission)) {
                    $count++;
                }
            }

            if ($count > 0) {
                $event = sync_content_log::create_log_message('Submissions', $count, false);
                $event->trigger();
            }
        }
    }

    /**
     * Get all the files that belong to the unsynced submission.
     *
     * @param int $submissionid Id of the submission
     * @return array Array with the files belonging to this submission
     */
    private function get_unsynced_files($submissionid) {
        global $DB;
        $files = array();
        $filenames = array();

        $sql = "SELECT fileid
                  FROM {plagiarism_safeassign_files} safiles
                 WHERE submissionid = :submissionid
                   AND uuid IS NULL";

        $params = ["submissionid" => $submissionid];

        $records = $DB->get_records_sql($sql, $params);
        foreach ($records as $record) {
            $fs = get_file_storage();
            $fileobj = $fs->get_file_by_id($record->fileid);

            // If file object does not exist, log an error and skip.
            if (!$fileobj) {
                $errortext = 'File with submission ID: ' . $submissionid .
                    ' and ID: ' . $record->fileid . ' could not be found on database';
                $event = sync_content_log::create_log_message('error', null, true, $errortext);
                $event->trigger();
                continue;
            }
            $files[] = $fileobj;
            $filenames[] = $fileobj->get_filename();
        }

        return array("files" => $files, "filenames" => $filenames);
    }

    /**
     * Sends the submission info for the given module to the SafeAssign service.
     * @param stdClass $data
     * @return bool
     */
    public function sync_submission(stdClass $data) {
        global $DB, $CFG;

        $userid         = $data->userid;
        $submissionid   = $data->submissionid;
        $courseuuid     = $data->courseuuid;
        $assignuuid     = $data->moduleuuid;
        $globalcheck    = $data->globalcheck;

        // Build the object to pass in to service.
        $wrapper = new stdClass();
        $wrapper->userid = $userid;
        $wrapper->courseuuid = $courseuuid;
        $wrapper->assignuuid = $assignuuid;

        $unsyncedfiles = self::get_unsynced_files($submissionid);
        // If there are no unsynced files, skip. Errors were thrown on function.
        if (empty($unsyncedfiles)) {
            return false;
        }

        $wrapper->files = $unsyncedfiles['files'];
        $wrapper->filenames = $unsyncedfiles['filenames'];
        $wrapper->globalcheck = ($globalcheck) ? true : false;
        $wrapper->grouppermission = true;

        $result = safeassign_api::create_submission($wrapper->userid, $wrapper->courseuuid,
            $wrapper->assignuuid, $wrapper->files, $wrapper->globalcheck, $wrapper->grouppermission);
        $responsedata = json_decode(rest_provider::instance()->lastresponse());

        if ($result === true && $responsedata) {
            $record = $DB->get_record('plagiarism_safeassign_subm', array('submissionid' => $submissionid,
                'uuid' => null, 'submitted' => 0));
            $record->submitted = 1;
            $this->sync_submission_files($submissionid, $responsedata);
            if (!empty($responsedata->submissions[0])) {
                $record->uuid = $responsedata->submissions[0]->submission_uuid;
            }
            $DB->update_record('plagiarism_safeassign_subm', $record);
            return true;
        } else {
            $params = array();
            if (!empty($CFG->plagiarism_safeassign_debugging)) {
                $baseurl = get_config('plagiarism_safeassign', 'safeassign_api');
                $params['User Id'] = $wrapper->userid;
                $params['Course UUID'] = $wrapper->courseuuid;
                $params['Assign UUID'] = $wrapper->assignuuid;
                $params['Global Check'] = $wrapper->globalcheck;
                $params['Group Permission'] = $wrapper->grouppermission;
                $params['Url'] = $baseurl . '/api/v1/courses/' . $wrapper->courseuuid . '/assignments/'
                    . $wrapper->assignuuid . '/submissions';
            }
            $event = sync_content_log::create_log_message('submission', $submissionid, true, null, $params);
            $event->trigger();
            return false;
        }
    }

    /**
     * Fill the safeassign_files datatable with the server response.
     * @param int $submissionid
     * @param stdClass $responsedata
     * @throws \dml_exception
     */
    public function sync_submission_files($submissionid, stdClass $responsedata) {
        global $DB;
        try {
            $transaction = $DB->start_delegated_transaction();
            // Get all ids for files in submissions by filename.
            $sql = "SELECT f.filename, saf.id AS safilesid
                      FROM {files} f
                      JOIN {plagiarism_safeassign_files} saf ON saf.fileid = f.id
                     WHERE saf.submissionid = :submissionid";

            $records = $DB->get_records_sql($sql, array("submissionid" => $submissionid));

            $sasubmfiles = array();
            foreach ($records as $record) {
                $sasubmfiles[trim($record->filename)] = $record->safilesid;
            }

            if (!empty($responsedata->submissions[0]->submission_files)) {
                foreach ($responsedata->submissions[0]->submission_files as $file) {
                    $said = $sasubmfiles[trim($file->file_name)];
                    $record = new stdClass();
                    $record->id = $said;
                    $record->supported = 1;
                    $record->uuid = $file->file_uuid;
                    $DB->update_record('plagiarism_safeassign_files', $record);
                }
            }
            if (!empty($responsedata->unprocessed_file_names)) {
                foreach ($responsedata->unprocessed_file_names as $unsupportedfilename) {
                    $said = $sasubmfiles[trim($unsupportedfilename)];
                    $record = new stdClass();
                    $record->id = $said;
                    $record->supported = 0;
                    $record->uuid = null;
                    $DB->update_record('plagiarism_safeassign_files', $record);
                }
            }
            $transaction->allow_commit();
        } catch (Exception $e) {
            $transaction->rollback($e);
            throw new \dml_exception("Syncing submission " . $submissionid. " files could not be completed");
        }
    }

    /**
     * Gets the course module ID for the given assignment id.
     * @param int $assignmentid
     * @return mixed
     */
    public function get_cmid($assignmentid) {
        list ($course, $cm) = get_course_and_cm_from_instance($assignmentid, 'assign');
        return $cm;

    }

    /**
     * Deletes all deleted submissions from the SafeAssign server.
     */
    public function delete_submissions() {
        global $DB, $CFG;
        $sql = "SELECT sa_subm.id, sa_subm.uuid, sa_course.creatorid
                  FROM {plagiarism_safeassign_subm} sa_subm
                  JOIN {plagiarism_safeassign_mod} sa_mod ON sa_mod.cmid = sa_subm.cmid
                  JOIN {plagiarism_safeassign_course} sa_course ON sa_mod.courseid = sa_course.courseid
                 WHERE sa_subm.uuid IS NOT NULL
                   AND sa_subm.status = :status";
        $submissions = $DB->get_records_sql($sql,
            ["status" => \plagiarism_safeassign\api\safeassign_submission::STATUS_SUBMISSION_DELETED]);
        $count = 0;
        $baseurl = get_config('plagiarism_safeassign', 'safeassign_api');

        foreach ($submissions as $submission) {
            $response = safeassign_api::delete_submission($submission->uuid, $submission->creatorid);
            if ($response) {
                try {
                    $transaction = $DB->start_delegated_transaction();
                    // Method delete_records is not transactional safe.
                    // Transaction for each deletion so only failed will be rolledbacked.
                    $DB->delete_records("plagiarism_safeassign_subm", ["uuid" => $submission->uuid]);
                    $count ++;
                    $transaction->allow_commit();
                } catch (exception $e) {
                    $transaction->rollback($e);
                    // If transaction was rolled back, update SafeAssign submission tables to show error.
                    $updatesubm = new stdClass();
                    $updatesubm->id = $submission->id;
                    $updatesubm->status = \plagiarism_safeassign\api\safeassign_submission::STATUS_SUBMISSION_ERROR_DELETION;
                    $DB->update_record("plagiarism_safeassign_subm", $updatesubm);
                }
            } else {
                $params = array();
                if (!empty($CFG->plagiarism_safeassign_debugging)) {
                    $params['Instructor id'] = $submission->instructorid;
                    $params['Submission UUID'] = $submission->uuid;
                    $params['Url'] = $baseurl . '/api/v1/submissions/' . $submission->uuid;
                }
                $event = sync_content_log::create_log_message('delete submissions', $submission->uuid, true, null, $params);
                $event->trigger();
            }
        }
        if ($count > 0) {
            $event = sync_content_log::create_log_message('delete submissions', $count, false);
            $event->trigger();
        }
    }

    /**
     * Pings the server and returns the status of the actual credentials.
     * @return mixed
     */
    public function test_credentials_before_tasks() {
        global $USER;

        if (!defined('SAFEASSIGN_OMIT_CACHE')) {
            define('SAFEASSIGN_OMIT_CACHE', true);
        }
        $storedval = get_config('plagiarism_safeassign');
        $username = $storedval->safeassign_instructor_username;
        $password = $storedval->safeassign_instructor_password;
        $baseurl = $storedval->safeassign_api;
        $result = safeassign_api::test_credentials($USER->id, $username, $password, $baseurl);

        return $result;
    }

    /**
     * Marks a submission as not scored.
     * @param string $uuid Submission UUID
     */
    public function resubmit_acknowlegment($uuid) {
        global $DB;
        $submission = $DB->get_record('plagiarism_safeassign_subm', ['uuid' => $uuid]);
        $submission->reportgenerated = 0;
        $DB->update_record('plagiarism_safeassign_subm', $submission);
    }

    /**
     * Sends a notification message to the teacher
     * @param int $teacherid
     * @param int $courseid
     * @param int $cmid
     * @param int $counter
     * @param string $assignmentname
     * @throws coding_exception
     */
    public static function send_notification_to_teacher($teacherid, $courseid, $cmid, $counter, $assignmentname = "") {
        global $DB, $PAGE;
        $fromuser = \core_user::get_noreply_user();
        $context = \context_module::instance($cmid);
        $user = $DB->get_record('user', array('id' => $teacherid, 'deleted' => 0 ), '*');
        $htmllink = new moodle_url('/mod/assign/view.php', ['id' => $cmid, 'action' => 'grading']);
        $plural = get_string('safeassign_notification_subm_plural', 'plagiarism_safeassign');
        if ($counter == 1) {
            $plural = get_string('safeassign_notification_subm_singular', 'plagiarism_safeassign');
        }
        $htmlmessage = '<p>' . get_string('safeassign_notification_message', 'plagiarism_safeassign',
                [
                    'counter' => $counter,
                    'plural' => $plural,
                    'assignmentname' => format_string($assignmentname, true, ['context' => $context]),
                ]);
        $htmlmessage .= '</p><br><a href="' . $htmllink->out(false) . '">' .
            get_string('safeassign_notification_grading_link', 'plagiarism_safeassign').'</a>';
        $event = new \core\message\message();
        $event->component = 'plagiarism_safeassign';
        $event->name = 'safeassign_graded';
        $event->userfrom = $fromuser;
        $event->userto = $user;
        $event->subject = get_string('safeassign_notification_message_hdr', 'plagiarism_safeassign');
        $event->fullmessage = '';
        $event->fullmessageformat = FORMAT_PLAIN;
        $event->fullmessagehtml = $htmlmessage;
        $event->smallmessage = '';
        $event->notification = 1;
        $event->courseid = $courseid;

        // Setting the context for being able to format strings in sent message.
        if (empty($PAGE->context)) {
            $PAGE->set_context(\context_course::instance($courseid));
        }
        \message_send($event);
    }

    /**
     * Sends a notification to the Admins when a new SafeAssign license is available.
     */
    public function new_safeassign_license_notification() {
        global $DB, $CFG;
        $adminids = get_config(null, 'siteadmins');
        $adminids = explode(',', $adminids);

        foreach ($adminids as $adminid) {
            $user = $DB->get_record('user', array('id' => $adminid, 'deleted' => 0 ), '*');
            $settingslink = '<a href="'.$CFG->wwwroot.'/plagiarism/safeassign/settings.php'.'">';
            $settingslink .= get_string('settings_page', 'plagiarism_safeassign').'</a>';
            $subject = get_string('license_agreement_notification_subject', 'plagiarism_safeassign');
            $message = get_string('license_agreement_notification_message', 'plagiarism_safeassign', $settingslink);
            $fromuser = \core_user::get_noreply_user();

            $event = new \core\message\message();
            $event->component = 'plagiarism_safeassign';
            $event->name = 'safeassign_notification';
            $event->userfrom = $fromuser;
            $event->userto = $user;
            $event->subject = $subject;
            $event->fullmessage = '';
            $event->fullmessageformat = FORMAT_PLAIN;
            $event->fullmessagehtml = $message;
            $event->smallmessage = '';
            $event->notification = 1;
            $event->courseid = SITEID;
            \message_send($event);
        }
    }

    /**
     * Gets all editing teachers from a course and puts them in to the safeassign_instructor table.
     * @param string $courseid
     */
    public function set_course_instructors($courseid = null) {
        global $DB, $CFG;

        $courses = array($courseid);
        // This should run only for backwards compatibility (old courses).
        if (is_null($courseid)) {
            $select = 'SELECT sa_course.courseid
                         FROM {plagiarism_safeassign_course} sa_course
                         JOIN {course} c ON c.id = sa_course.courseid';
            $courses = $DB->get_fieldset_sql($select);
        }

        // Handle creation for new course.
        if (!empty($courses)) {
            $contexts = array();
            $contextids = array();
            foreach ($courses as $course) {
                $coursecontext = \context_course::instance($course);
                $contexts[$coursecontext->id] = $course;
                $contextids[] = $coursecontext->id;
            }

            $admins = get_config('plagiarism_safeassign', 'siteadmins');
            if ($admins) {
                $admins = explode(',', $admins);
            } else {
                // First time SA is being used.
                $admins = explode(',', $CFG->siteadmins);
            }
            $select = 'SELECT id
                     FROM {role}
                    WHERE archetype = "editingteacher" OR archetype = "manager"';
            $editingroles = $DB->get_fieldset_sql($select);

            // Get the enrolled users.
            $sql = 'SELECT id, userid AS instructorid, contextid
                  FROM {role_assignments}
                 WHERE contextid ';
            list($sql2, $contextids) = $DB->get_in_or_equal($contextids);
            $sql3 = '';
            if (!empty($editingroles)) {
                list($sql3, $editingroles) = $DB->get_in_or_equal($editingroles);
            }
            $sql4 = '';
            $adminids = array();
            $users = array();
            $users = $DB->get_records_sql($sql . $sql2 . ' AND roleid ' . $sql3, array_merge($contextids, $editingroles));

            // Check what additional system roles should be added to each course.
            $additionalroles = get_config('plagiarism_safeassign', 'safeassign_additional_roles');
            $plususers = array();
            if (!empty($additionalroles)) {
                // Since the id is being introduced by DB, we still need to validate.
                $select = 'SELECT *
                             FROM {role}
                            WHERE id ';
                $sql5 = '';
                $params = array();
                list($sql5, $params) = $DB->get_in_or_equal($additionalroles);
                $plusroles = $DB->get_records_sql($select . $sql5, $params);
                $context = context_system::instance();
                foreach ($plusroles as $plusrole) {
                    foreach (get_users_from_role_on_context($plusrole, $context) as $user) {
                        $plususers[] = $user;
                    }
                }
            }
            if ($users) {
                // We handle first enrolled users.
                foreach ($users as $user) {
                    $user->courseid = $contexts[$user->contextid];
                    unset($user->contextid);
                    unset($user->id);
                }

                // Now we handle site administrators and additional roles provided in the settings.
                foreach ($courses as $course) {
                    foreach ($admins as $admin) {
                        $adminuser = new stdClass();
                        $adminuser->instructorid = $admin;
                        $adminuser->courseid = $course;
                        $users[] = $adminuser;
                    }
                    foreach ($plususers as $plususer) {
                        $plususer2 = new stdClass();
                        $plususer2->instructorid = $plususer->userid;
                        $plususer2->courseid = $course;
                        $users[] = $plususer2;
                    }
                }
                // Only 1 record per user is needed, even if the user has multiple roles in a course EG: admin/teacher.
                $users = array_unique($users, SORT_REGULAR);
                $DB->insert_records('plagiarism_safeassign_instr', $users);
                // This should run only when the upgrade is applied or SA is being used for first time.
                if (!get_config('plagiarism_safeassign', 'synced_admins') && !empty($admins)) {
                    set_config('syncedadmins', $CFG->siteadmins, 'plagiarism_safeassign');
                }
            }
        }
    }

    /**
     * Process role assignments and removals.
     * @param object $data
     * @param string $eventtype
     */
    public function process_role_assignments($data, $eventtype) {
        global $DB, $CFG;
        $select = "SELECT id
                     FROM {role}
                    WHERE archetype = 'editingteacher' OR archetype = 'manager'";
        $editingroles = $DB->get_fieldset_sql($select);
        $systemcontext = context_system::instance();
        $additionalroles = explode(',', get_config('plagiarism_safeassign', 'safeassign_additional_roles'));
        if (($DB->record_exists('plagiarism_safeassign_course', array('courseid' => $data['courseid'])) &&
            in_array($data['objectid'], $editingroles) && !empty($editingroles)) ||
            ($data['contextid'] == $systemcontext->id) && in_array($data['objectid'], $additionalroles)) {

            if ($eventtype === 'create') {
                if ($systemcontext->id == $data['contextid']) {
                    // Process system level enrollment.
                    $role = $DB->get_record('role', array('id' => $data['objectid']));
                    $sql = 'SELECT sa_ins.courseid
                              FROM {plagiarism_safeassign_instr} sa_ins
                              JOIN {course} c ON c.id = sa_ins.courseid
                             WHERE sa_ins.courseid NOT IN (SELECT courseid
                                                      FROM {plagiarism_safeassign_instr}
                                                     WHERE instructorid = ? GROUP BY courseid)
                          GROUP BY courseid';
                    // Get the courses in which the user is not in.
                    $pendingcourses = $DB->get_records_sql($sql, array($data['relateduserid']));
                    if ($pendingcourses) {
                        $users = array();
                        foreach ($pendingcourses as $pendingcourse) {
                            $user = new stdClass();
                            $user->courseid = $pendingcourse->courseid;
                            $user->instructorid = $data['relateduserid'];
                            $users[] = $user;
                        }
                        $DB->insert_records('plagiarism_safeassign_instr', $users);
                    }
                } else {
                    $record = $DB->get_record('plagiarism_safeassign_instr', array('courseid' => $data['courseid'],
                        'instructorid' => $data['relateduserid']));
                    if ($record === false) {
                        $user = new stdClass();
                        $user->instructorid = $data['relateduserid'];
                        $user->courseid = $data['courseid'];
                        $DB->insert_record('plagiarism_safeassign_instr', $user);
                    } else if ($record->id) {
                        $record->synced = 0;
                        $record->unenrolled = 0;
                        $DB->update_record('plagiarism_safeassign_instr', $record);
                    }
                }

            } else if ($eventtype === 'delete') {
                if ($systemcontext->id == $data['contextid']) {
                    $users = array();
                    $sql = 'SELECT id
                          FROM {role}
                          WHERE id ';
                    $sql2 = '';
                    $params = array();
                    list($sql2, $params) = $DB->get_in_or_equal($additionalroles);
                    $roles = $DB->get_records_sql($sql . $sql2, $params);
                    foreach ($roles as $role) {
                        $users = array_merge($users, get_users_from_role_on_context($role, $systemcontext));
                    }
                    $userids = array_map(function($o) {
                        return $o->userid;
                    }, $users);
                    if (!in_array($data['relateduserid'], $userids)) {
                        // Find user enrollemnts in all SafeAssign courses.
                        $sql = 'SELECT sa_c.courseid
                                  FROM {plagiarism_safeassign_course} sa_c
                                  JOIN {course} c ON c.id = sa_c.courseid';
                        $courses = $DB->get_fieldset_sql($sql);
                        $params = array();
                        $params[] = $data['relateduserid'];
                        $params[] = CONTEXT_COURSE;
                        $params[] = $data['relateduserid'];
                        $sql2 = '';
                        list($sql2, $editingroles) = $DB->get_in_or_equal($editingroles);
                        $sql3 = '';
                        list($sql3, $courses) = $DB->get_in_or_equal($courses);
                        $sql = 'SELECT *
                                  FROM {plagiarism_safeassign_instr} sa_instr
                                 WHERE sa_instr.instructorid = ?
                                       AND NOT EXISTS (
                                       SELECT 1
                                         FROM {plagiarism_safeassign_course} sa_c
                                         JOIN {context} ctx ON ctx.instanceid = sa_c.courseid
                                              AND ctx.contextlevel = ?
                                         JOIN {role_assignments} ra ON ctx.id = ra.contextid
                                        WHERE ra.userid = ?
                                              AND ra.roleid '. $sql2 .
                                            ' AND sa_c.courseid '. $sql3 .
                                            ' AND sa_instr.courseid = sa_c.courseid)';
                        $params = array_merge($params, $editingroles, $courses);
                        $notenrollcourses = $DB->get_records_sql($sql, $params);
                        if ($notenrollcourses) {
                            foreach ($notenrollcourses as $notenrollcourse) {
                                if ($notenrollcourse->synced == 1) {
                                    $notenrollcourse->unenrolled = 1;
                                    $DB->update_record('plagiarism_safeassign_instr', $notenrollcourse);
                                } else {
                                    $DB->delete_records('plagiarism_safeassign_instr',
                                        array('id' => $notenrollcourse->id));
                                }
                            }
                        }
                    }

                } else if ($DB->record_exists('plagiarism_safeassign_course', array('courseid' => $data['courseid'])) &&
                    in_array($data['objectid'], $editingroles)
                ) {
                    $record = $DB->get_record('plagiarism_safeassign_instr', array('courseid' => $data['courseid'],
                        'instructorid' => $data['relateduserid']));
                    if ($record) {
                        if ($record->synced == 1 && !in_array($data['relateduserid'], explode(',', $CFG->siteadmins))) {
                            $DB->set_field('plagiarism_safeassign_instr', 'unenrolled', 1, array('courseid' => $data['courseid'],
                            'instructorid' => $data['relateduserid']));
                        } else {
                            $sql = 'SELECT id, userid
                                      FROM {role_assignments}
                                     WHERE contextid = ? AND userid = ? AND roleid ';
                            list($sqlin, $editingroles) = $DB->get_in_or_equal($editingroles);
                            $params = array_merge(array($data['contextid'], $data['relateduserid']), $editingroles);
                            if (!in_array($data['relateduserid'], explode(',', $CFG->siteadmins))) {
                                if (!$DB->get_records_sql($sql . $sqlin, $params)) {
                                    $DB->delete_records('plagiarism_safeassign_instr', array('id' => $record->id));
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Puts the corresponding instructors in to a SafeAssign course.
     */
    public function sync_instructors() {
        global $DB, $CFG;

        $courses = $this->get_valid_courses();
        $courseids = array();
        foreach ($courses as $course) {
            $courseids[] = $course->courseid;
        }
        list($sqlin, $params) = $DB->get_in_or_equal($courseids);
        $sql = "
       SELECT DISTINCT sa_tchr.id, sa_tchr.instructorid, sa_tchr.courseid, sa_course.uuid
                  FROM {plagiarism_safeassign_instr} sa_tchr
                  JOIN {plagiarism_safeassign_course} sa_course ON sa_course.courseid = sa_tchr.courseid
                 WHERE sa_tchr.synced = 0
                   AND sa_tchr.unenrolled = 0
                   AND sa_tchr.deleted = 0
                   AND sa_tchr.courseid {$sqlin}";

        $instructors = $DB->get_records_sql($sql, $params);
        $count = 0;
        $baseurl = get_config('plagiarism_safeassign', 'safeassign_api');
        foreach ($instructors as $instructor) {
            $result = safeassign_api::put_instructor_to_course($instructor->instructorid, $instructor->uuid);

            if ($result === true) {
                $DB->set_field('plagiarism_safeassign_instr', 'synced', 1, array('instructorid' => $instructor->instructorid,
                    'courseid' => $instructor->courseid));
                $count++;
            } else {
                $params = array();
                if (!empty($CFG->plagiarism_safeassign_debugging)) {
                    $params['Instructor id'] = $instructor->instructorid;
                    $params['Course UUID'] = $instructor->uuid;
                    $params['Url'] = $baseurl . '/api/v1/'. $instructor->uuid .'/members';
                }
                $event = sync_content_log::create_log_message('instructor', $instructor->instructorid, true, null, $params);
                $event->trigger();
            }
        }

        if ($count > 0) {
            $event = sync_content_log::create_log_message('Instructors', $count, false);
            $event->trigger();
        }
    }

    /**
     * Checks if there is a new admin user and creates records in the instrcutor table.
     */
    public function set_siteadmins() {
        global $CFG, $DB;

        $syncedadmins = explode(',', get_config('plagiarism_safeassign', 'syncedadmins'));
        $siteadmins = explode(',', $CFG->siteadmins);
        $newadmins = array_diff($siteadmins, $syncedadmins);
        if (!empty($newadmins)) {
            $sql = 'SELECT DISTINCT courseid
                               FROM {plagiarism_safeassign_instr}
                              WHERE instructorid ';
            $sql2 = '';
            if (count($newadmins) > 1) {
                list($sql2, $newadmins) = $DB->get_in_or_equal($newadmins, SQL_PARAMS_QM, 'param', false);
            } else {
                $sql .= '<> ?';
            }
            $courses = $DB->get_records_sql($sql . $sql2, $newadmins);
            $records = array();
            foreach ($courses as $course) {
                foreach ($newadmins as $newadmin) {
                    $user = new stdClass();
                    $user->courseid = $course->courseid;
                    $user->instructorid = $newadmin;
                    $records[] = $user;
                }
            }
            $DB->insert_records('plagiarism_safeassign_instr', $records);
            set_config('siteadmins', $CFG->siteadmins, 'plagiarism_safeassign');
            set_config('syncedadmins', $CFG->siteadmins, 'plagiarism_safeassign');
        }
    }

    /**
     * Checks if there is a new additional role and creates records in plagiarism_safeassign_instr for new users.
     * @param string $additionalroles - Gotten from the config_plugins table which containes the user roles to sync.
     * @param string $syncedroles - Value from config_plugins that indicates the additional synced roles.
     */
    public function set_additional_role_users($additionalroles, $syncedroles) {
        global $DB;

        $additionalroles = explode(',', $additionalroles);
        $syncedroles = explode(',', $syncedroles);

        $newroles = array_diff($additionalroles, $syncedroles);
        $deletedroles = array_diff($syncedroles, $additionalroles);
        $systemcontext = context_system::instance();
        $sql = 'SELECT sa_c.courseid
                  FROM {plagiarism_safeassign_course} sa_c
                  JOIN {course} c ON c.id = sa_c.courseid';
        // Update or create only existing courses.
        $courses = $DB->get_fieldset_sql($sql);
        if (!empty($newroles) && !empty($courses)) {
            $sql = 'SELECT *
                    FROM {role}
                    WHERE id ';
            $sql2 = '';
            list($sql2, $newroles) = $DB->get_in_or_equal($newroles);
            $roles = $DB->get_records_sql($sql . $sql2, $newroles);
            $users = array();
            foreach ($roles as $role) {
                $users = array_merge($users, get_users_from_role_on_context($role, $systemcontext));
            }
            if (!empty($users)) {
                // All users from the new roles.
                $userids = array_map(function($o) {
                    return $o->userid;
                }, $users);
                $userids = array_unique($userids, SORT_REGULAR);
                $sql = 'SELECT courseid
                      FROM {plagiarism_safeassign_instr}
                     WHERE instructorid = ?';
                $sql2 = '';
                list($sql2, $courses) = $DB->get_in_or_equal($courses);
                $sql .= ' AND courseid ' . $sql2;
                $pending = array();
                $sqlupdate = 'UPDATE {plagiarism_safeassign_instr}
                             SET synced = 0, unenrolled = 0, deleted = 0
                           WHERE instructorid ';
                $sqlupdatein = '';
                $params = array();
                list($sqlupdatein, $params) = $DB->get_in_or_equal($userids);
                // If there are any records for the given user, sync them again.
                $DB->execute($sqlupdate . $sqlupdatein, $params);
                foreach ($userids as $userid) {
                    $params = array_merge(array($userid), $courses);
                    $pending[$userid] = array_diff($courses, $DB->get_fieldset_sql($sql, $params));

                }
                $records = array();
                foreach ($pending as $userid => $courses) {
                    foreach ($courses as $course) {
                        $record = new stdClass();
                        $record->instructorid = $userid;
                        $record->courseid = $course;
                        $records[] = $record;
                    }
                }
                if (!empty($records)) {
                    $DB->insert_records('plagiarism_safeassign_instr', $records);
                }
            }
        }

        if (!empty($deletedroles)) {

            $sql = 'SELECT *
                    FROM {role}
                    WHERE id ';
            $sql2 = '';
            list($sql2, $newroles) = $DB->get_in_or_equal($additionalroles);
            $roles = $DB->get_records_sql($sql . $sql2, $additionalroles);
            $existingusers = array();
            // Get users from additional roles, so we don't affect multirole users.
            foreach ($roles as $role) {
                $existingusers = array_merge($existingusers, get_users_from_role_on_context($role, $systemcontext));
            }
            $existinguserids = array_map(function($o) {
                return $o->userid;
            }, $existingusers);
            $existinguserids = array_unique($existinguserids, SORT_REGULAR);
            $sql = 'SELECT *
                    FROM {role}
                    WHERE id ';
            $sql2 = '';
            list($sql2, $deletedroles) = $DB->get_in_or_equal($deletedroles);
            $droles = $DB->get_records_sql($sql . $sql2, $deletedroles);
            $users = array();
            foreach ($droles as $drole) {
                $users = array_merge($users, get_users_from_role_on_context($drole, $systemcontext));
            }
            $userids = array_map(function($o) {
                return $o->userid;
            }, $users);
            $userids = array_unique($userids, SORT_REGULAR);
            $userstodelete = array_diff($userids, $existinguserids);
            if (!empty($userstodelete)) {
                $select = 'SELECT id
                     FROM {role}
                    WHERE archetype = "editingteacher" OR archetype = "manager"';
                $editingroles = $DB->get_fieldset_sql($select);
                $inuserid = '';
                list($inuserid, $userstodelete) = $DB->get_in_or_equal($userstodelete);
                $sql2 = '';
                list($sql2, $editingroles) = $DB->get_in_or_equal($editingroles);
                // Find if those users have active enrollments (manager or ediingteacher) in SA courses.
                $sql = 'SELECT sa_c.courseid
                                  FROM {plagiarism_safeassign_course} sa_c
                                  JOIN {course} c ON c.id = sa_c.courseid';
                $courses = $DB->get_fieldset_sql($sql);
                $sql3 = '';
                list($sql3, $courses) = $DB->get_in_or_equal($courses);
                $sql = 'SELECT *
                          FROM {plagiarism_safeassign_instr} sa_instr
                         WHERE sa_instr.instructorid ' . $inuserid .
                             ' AND NOT EXISTS (
                                SELECT 1
                                  FROM {plagiarism_safeassign_course} sa_c
                                  JOIN {context} ctx ON ctx.instanceid = sa_c.courseid
                                   AND ctx.contextlevel = ?
                                  JOIN {role_assignments} ra ON ctx.id = ra.contextid
                                 WHERE ra.userid ' . $inuserid .
                                 ' AND ra.roleid '. $sql2 .
                                 ' AND sa_c.courseid '. $sql3 .
                                 ' AND sa_instr.courseid = sa_c.courseid)';
                $params = array();
                $params = array_merge($params, $userstodelete);
                $params[] = CONTEXT_COURSE;
                $params = array_merge($params, $userstodelete, $editingroles, $courses);
                $notenrollcourses = $DB->get_records_sql($sql, $params);
                if ($notenrollcourses) {
                    foreach ($notenrollcourses as $notenrollcourse) {
                        if ($notenrollcourse->synced == 1) {
                            $notenrollcourse->unenrolled = 1;
                            $DB->update_record('plagiarism_safeassign_instr', $notenrollcourse);
                        } else {
                            $DB->delete_records('plagiarism_safeassign_instr',
                                array('id' => $notenrollcourse->id));
                        }
                    }
                }
            }
        }
        set_config('safeassign_synced_roles', implode(',', $additionalroles), 'plagiarism_safeassign');
    }

    /**
     * Deletes the instructors that have lost a valid enrollment or a instructor role.
     */
    public function delete_instructors() {
        global $DB, $CFG;

        $sql = 'SELECT sa_inst.id, sa_inst.instructorid, sa_c.uuid
                  FROM {plagiarism_safeassign_instr} sa_inst
                  JOIN {plagiarism_safeassign_course} sa_c ON sa_c.courseid = sa_inst.courseid
                 WHERE sa_c.uuid IS NOT NULL
                       AND sa_inst.synced = 1
                       AND sa_inst.unenrolled = 1
                       AND sa_inst.deleted = 0';

        $instructors = $DB->get_records_sql($sql);
        $count = 0;
        $baseurl = get_config('plagiarism_safeassign', 'safeassign_api');
        foreach ($instructors as $instructor) {
            $response = safeassign_api::delete_instructor_from_course($instructor->instructorid, $instructor->uuid);
            if ($response === true) {
                $instructor->deleted = 1;
                $DB->update_record('plagiarism_safeassign_instr', $instructor);
                $count++;
            } else {
                // Log response.
                $params = array();
                if (!empty($CFG->plagiarism_safeassign_debugging)) {
                    $params['Instructor id'] = $instructor->instructorid;
                    $params['Course UUID'] = $instructor->uuid;
                    $params['Url'] = $baseurl . '/api/v1/'. $instructor->uuid .'/members';
                }
                $event = sync_content_log::create_log_message('delete instructor', $instructor->instructorid, true, null, $params);
                $event->trigger();

            }
        }
        if ($count > 0) {
            $event = sync_content_log::create_log_message('delete instructor', $count, false);
            $event->trigger();
        }
        $this->delete_instructors_records();
    }

    /**
     * Deletes the instructors records that have lost a valid enrollment or a instructor role.
     */
    public function delete_instructors_records() {
        global $DB;
        $DB->delete_records('plagiarism_safeassign_instr', ['deleted' => 1]);
    }

    /**
     * Accepts the specific SafeAssign license version stored in DB.
     */
    public function accept_safeassign_license() {

        $storedval = get_config('plagiarism_safeassign');
        $adminaccepted = $storedval->safeassign_license_agreement_readbyadmin;
        $currentlicensestatus = $storedval->safeassign_license_agreement_status;
        if ($adminaccepted && !$currentlicensestatus) {
            $firstname = $storedval->safeassign_license_acceptor_givenname;
            $surname = $storedval->safeassign_license_acceptor_surname;
            $mailaddr = $storedval->safeassign_license_acceptor_email;
            $adminid = $storedval->safeassign_license_agreement_adminid;
            $licenseversion = $storedval->safeassign_latest_license_vers;

            $result = safeassign_api::accept_license($adminid, $firstname, $surname, $mailaddr, $licenseversion);
            if ($result) {
                $event = license_log::create_log_message('license', false);
                $event->trigger();
                set_config('safeassign_license_agreement_status', 1, 'plagiarism_safeassign');
            } else {
                $event = license_log::create_log_message('license', true);
                $event->trigger();
            }
        }
    }

    /**
     * Cleans the data of an out of date license version.
     * This function should be called during the upgrades where a new license is inserted.
     */
    public function clean_safeassign_license_data() {
        set_config('safeassign_license_agreement_status', 0, 'plagiarism_safeassign');
        set_config('safeassign_license_agreement_readbyadmin', 0, 'plagiarism_safeassign');
        set_config('safeassign_license_agreement_readbyadmin_timestamp', '', 'plagiarism_safeassign');
        set_config('safeassign_license_agreement_adminid', '', 'plagiarism_safeassign');
    }
}

/**
 * Adds the list of plagiarism settings to a form.
 *
 * @param object $mform - Moodle form object.
 */
function safeassign_get_form_elements($mform) {
    $mform->addElement('header', 'plagiarismdesc', get_string('safeassign', 'plagiarism_safeassign'));
    $mform->addElement('checkbox', 'safeassign_enabled', get_string('assignment_check_submissions', 'plagiarism_safeassign'));
    $mform->addHelpButton('safeassign_enabled', 'assignment_check_submissions', 'plagiarism_safeassign');
    $mform->addElement('checkbox', 'safeassign_originality_report',
        get_string('students_originality_report', 'plagiarism_safeassign'));
    $mform->addElement('checkbox', 'safeassign_global_reference',
        get_string('submissions_global_reference', 'plagiarism_safeassign'));
    $mform->addHelpButton('safeassign_global_reference', 'submissions_global_reference', 'plagiarism_safeassign');
}

/**
 * Hook called before a course is deleted.
 *
 * @param \stdClass $course The course record.
 */
function plagiarism_safeassign_pre_course_delete($course) {
    global $DB;

    // Find the assignments and submissions for that course.
    if ($DB->record_exists('plagiarism_safeassign_course', array('courseid' => $course->id))) {
        try {
            $transaction = $DB->start_delegated_transaction();
            // Get all context module ids from this course.
            $cmids = $DB->get_fieldset_select("plagiarism_safeassign_mod", "cmid", "courseid=:courseid",
                ["courseid" => $course->id]);
            if (!empty($cmids)) {
                // Get all submission ids from this course.
                list($sqlin, $params) = $DB->get_in_or_equal($cmids);
                $sql = "SELECT submissionid
                          FROM {plagiarism_safeassign_subm}
                         WHERE cmid ";
                $submissions = $DB->get_fieldset_sql($sql . $sqlin, $params);
                if (!empty($submissions)) {
                    // Delete all files for submissions in this course.
                    list($sqlin2, $params2) = $DB->get_in_or_equal($submissions, SQL_PARAMS_NAMED);
                    $sql = "DELETE
                              FROM {plagiarism_safeassign_files}
                             WHERE submissionid ";
                    $DB->execute($sql . $sqlin2, $params2);
                    // Set status deleted to all submissions in this course.
                    $sql = "UPDATE {plagiarism_safeassign_subm}
                               SET status = :status
                             WHERE submissionid ";
                    $params2['status'] = \plagiarism_safeassign\api\safeassign_submission::STATUS_SUBMISSION_DELETED;
                    $DB->execute($sql . $sqlin2, $params2);
                }
                // Delete all modules of this course.
                $DB->delete_records("plagiarism_safeassign_mod", ["courseid" => $course->id]);
            }
            // Finally, delete the course from SafeAssign tables.
            $DB->delete_records('plagiarism_safeassign_course', ['courseid' => $course->id]);
            $transaction->allow_commit();
        } catch (Exception $e) {
            $transaction->rollback($e);
            throw new \dml_exception("Course with id " . $course->id . " could not be deleted in SafeAssign tables");
        }
    }
}

/**
 * Hook called before a course module is deleted.
 *
 * @param \stdClass $cm The course module record.
 */
function plagiarism_safeassign_pre_course_module_delete($cm) {
    global $DB;

    if ($DB->record_exists("plagiarism_safeassign_mod", ["cmid" => $cm->id])) {
        try {
            $transaction = $DB->start_delegated_transaction();
            // Get all submissions of this module.
            $submissions = $DB->get_fieldset_select('plagiarism_safeassign_subm', 'submissionid', 'cmid=:cmid',
                ['cmid' => $cm->id]);
            if (!empty($submissions)) {
                // Delete all files for submissions in this module.
                list($sqlin, $params) = $DB->get_in_or_equal($submissions, SQL_PARAMS_NAMED);
                $sql = "DELETE
                          FROM {plagiarism_safeassign_files}
                         WHERE submissionid ";
                $DB->execute($sql . $sqlin, $params);
                // Set status to deleted to all submissions in this course.
                $sql = "UPDATE {plagiarism_safeassign_subm}
                           SET status = :status
                     WHERE submissionid ";
                $params['status'] = \plagiarism_safeassign\api\safeassign_submission::STATUS_SUBMISSION_DELETED;
                $DB->execute($sql . $sqlin, $params);
            }
            // Finally, delete the module.
            $DB->delete_records("plagiarism_safeassign_mod", ["cmid" => $cm->id]);
            $transaction->allow_commit();
        } catch (Exception $e) {
            $transaction->rollback($e);
            throw new \dml_exception("Module " . $cm->id . " could not be deleted");
        }
    }
}
