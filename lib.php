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
 * @copyright  Copyright (c) 2017 Blackboard Inc.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page.
}

// Get global class.
global $CFG;
require_once($CFG->dirroot.'/plagiarism/lib.php');
require_once($CFG->dirroot.'/mod/assign/externallib.php');
use plagiarism_safeassign\api\safeassign_api;
use plagiarism_safeassign\api\rest_provider;
use plagiarism_safeassign\event\sync_content_log;
use plagiarism_safeassign\event\score_sync_log;
use plagiarism_safeassign\event\score_sync_fail;

/**
 * Extends plagiarism core base class.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plagiarism_plugin_safeassign extends plagiarism_plugin {

    /**
     * Return the list of form element names.
     *
     * @return array contains the form element names.
     */
    public function get_configs() {
        return array('safeassign_enabled', 'safeassign_originality_report', 'safeassign_global_reference');
    }

    /**
     * This function should be used to initialise settings and check if plagiarism is enabled.
     *
     * @returned array|bool - false if not enabled, or return an array of relevant settings.
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
     * Hook to allow plagiarism specific information to be displayed beside a submission.
     * @return string
     */
    public function get_links($linkarray) {
        global $DB;

        $cmid = $linkarray['cmid'];

        // Check if the user has the right capabilities to see the report.
        $cm = context_module::instance($cmid);
        if (!has_capability('plagiarism/safeassign:viewreport', $cm)) {
            return '';
        }

        // Check if SafeAssign is enabled and configured at global level.
        $plagiarismsettings = $this->get_settings();
        if (!$plagiarismsettings) {
            return '';
        }

        // Check that the activity has SafeAssign enabled.
        $courseconfiguration = $DB->get_records_menu('plagiarism_safeassign_config', array('cm' => $cmid), '', 'name, value');

        if (!empty($courseconfiguration['safeassign_enabled'])) {
            // The activity has SafeAssign enabled.
            $message = '';
            $file = null;
            $userid = $linkarray['userid'];
            if (isset($linkarray['file'])) {
                // This submission has a file associated with it.
                $file = $this->get_file_results($cmid, $userid, $linkarray['file']->get_id());
            } else {
                if (!empty($linkarray['content'])) {
                    // This submission has an online text associated with it.
                    $submission = $DB->get_record('assign_submission', array('userid' => $userid, 'assignment' => $linkarray['assignment']));
                    $namefile = 'userid_' . $userid . '_text_submissionid_' . $submission->id . '.txt';
                    $filerecord = $DB->get_record('files', array('filename' => $namefile));
                    $file = $this->get_file_results($cmid, $userid, $filerecord->id);
                }
            }
            if ($file != null) {
                $message = $this->get_message_result($file, $cm, $courseconfiguration);
            }
            return $message;
        } else {
            // The activity is not configured with SafeAssign.
            return '';
        }

    }

    /**
     * Returns the message to display for a specific file depending the state of that submission.
     * @param array $file
     * @param int $cm
     * @param array $courseconfiguration
     * @return string
     */
    private function get_message_result($file, $cm, array $courseconfiguration) {
        global $USER;
        $message = '';
        if($file['supported']) {
            if ($file['analyzed']) {
                // We have a valid report for this file.
                $message = get_string('safeassign_file_similarity_score', 'plagiarism_safeassign', intval($file['score'] * 100));

                // We need to validate that the user can see the link to the similarity report.
                $role = get_user_roles($cm, $USER->id);
                $roleid = key($role);
                if (empty($role) || $role[$roleid]->shortname != 'student' || $courseconfiguration['safeassign_originality_report']) {
                    // The report is enabled for this user.
                    $message .= html_writer::link($file['reporturl'], get_string('safeassign_link_originality_report', 'plagiarism_safeassign'));
                }
            } else {
                // This file is not supported by SafeAssign.
                $message = get_string('safeassign_file_in_review', 'plagiarism_safeassign');
            }
        } else {
            // This file is not supported by SafeAssign.
            $message = get_string('safeassign_file_not_supported', 'plagiarism_safeassign');
        }
        return $message;
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
        $supported = 1;
        $filequery="SELECT fil.id, sub.reportgenerated, fil.similarityscore, fil.reporturl, fil.supported
                       FROM {plagiarism_safeassign_subm} sub
                       JOIN {plagiarism_safeassign_files} fil ON sub.submissionid = fil.submissionid
                      WHERE fil.cm = ? AND fil.userid = ? AND fil.fileid = ? AND sub.submitted = 1 AND sub.deprecated = 0
                      ORDER BY fil.id";
        $files = $DB->get_records_sql($filequery, array($cmid, $userid, $fileid));
        $fileinfo = end($files);
        if (!empty($fileinfo)) {
            $analyzed = $fileinfo->reportgenerated;
            $score = $fileinfo->similarityscore;
            $reporturl = $fileinfo->reporturl;
            $supported = $fileinfo->supported;
        }
        return array('analyzed' => $analyzed, 'score' => $score, 'reporturl' => $reporturl, 'supported' => $supported);
    }

    /**
     * Retrieve submission information and results.
     * @param int $submid submission ID.
     * @return bool|\stdClass submission object, False if not found.
     */
    public function get_submission_results($submid) {
        global $DB;
        $submquery="SELECT *
                      FROM {plagiarism_safeassign_subm}
                     WHERE submissionid = ? AND deprecated != 1";
        $subminfo = $DB->get_record_sql($submquery, array($submid));
        if (!empty($subminfo)) {
            return $subminfo;
        }
        return false;
    }

    /**
     * hook to add plagiarism specific settings to a module settings page
     * @param object $mform  - Moodle form
     * @param object $context - current context
     * @param string $modulename - Name of the module
     */
    public function get_form_elements_module($mform, $context, $modulename = "") {

        global $DB;

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
        }

    }

    /**
     * hook to save plagiarism specific settings on a module settings page
     * @param object $data - data from an mform submission.
     */
    public function save_form_elements($data) {
        global $DB;

        $existingelements = $DB->get_records_menu('plagiarism_safeassign_config', array('cm' => $data->coursemodule),
            '', 'name, id');

        $plagiarismelements = $this->get_configs();
        foreach ($plagiarismelements as $element) {
            $newelement = new stdClass();
            $newelement->cm = $data->coursemodule;
            $newelement->name = $element;
            $newelement->value = isset($data->$element) && $data->safeassign_enabled ? $data->$element : 0;
            if (isset($existingelements[$element])) {
                $newelement->id = $existingelements[$element];
                $DB->update_record('plagiarism_safeassign_config', $newelement);
            } else {
                $DB->insert_record('plagiarism_safeassign_config', $newelement);
            }
        }
        if (isset($data->safeassign_enabled) && $data->safeassign_enabled) {
            $eventdata = new stdClass();
            $eventdata->courseid = $data->course;
            $eventdata->assignmentid = $data->instance;
            $this->assign_dbsaver($eventdata);
        }

    }

    /**
     * hook to allow a disclosure to be printed notifying users what will happen with their submission.
     * @param int $cmid - course module id.
     * @return string $output - HTMl to be rendered.
     */
    public function print_disclosure($cmid) {
        global $USER, $PAGE, $DB;
        $checked = false;
        $value = 0;
        $cmenabled = $DB->get_record('plagiarism_safeassign_config', array('cm' => $cmid, 'name' => 'safeassign_enabled'));
        $cmglobalref = $DB->get_record('plagiarism_safeassign_config', array('cm' => $cmid, 'name' => 'safeassign_global_reference'));
        if ($cmenabled->value == 0) {
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
        $col1 = html_writer::tag('div', get_string('plagiarism_tools', 'plagiarism_safeassign'), array('class' => 'col-md-3'));
        $col2 = html_writer::tag('div', get_string('files_accepted', 'plagiarism_safeassign').'<br><br>'.$checkbox, array('class' => 'col-md-9'));
        $output = html_writer::tag('div', $col1.$col2, array('class' => 'row generalbox boxaligncenter intro'));
        $form = html_writer::tag('form', $output);
        $PAGE->requires->js_call_amd('plagiarism_safeassign/disclosure', 'init', array($cmid, $USER->id));
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
     * Adds assignments to plagiarism_safeassign_assign table when an assignment is created on a course.
     *
     * @param object $eventdata
     * @return boolean
     */
    public function assign_dbsaver($eventdata) {
        global $DB;

        // Call the course saver.
        $this->safeassign_course_dbsaver($eventdata);

        // Let's check that the assignment does not exist previously on db.
        $instanceid = $eventdata->assignmentid;
        if (!$DB->record_exists('plagiarism_safeassign_assign', ['assignmentid' => $instanceid])) {
            // We have to set the object in safeassign_assign table.
            $assignmentdata = new stdClass();
            $assignmentdata->uuid = null;
            $assignmentdata->assignmentid = $instanceid;
            $DB->insert_record('plagiarism_safeassign_assign', $assignmentdata);
        }
        return true;
    }

    /**
     * Adds courses to plagiarism_safeassign_course table when a cm is created in that course.
     *
     * @param object $eventdata
     */
    private function safeassign_course_dbsaver($eventdata) {
        global $DB, $USER;

        // Let's check that the course does not exist previously on db.
        $courseid = $eventdata->courseid;
        if (!$DB->record_exists('plagiarism_safeassign_course', ['courseid' => $courseid])) {
            // We have to set the object in safeassign_course table.
            $coursedata = new stdClass();
            $coursedata->uuid = null;
            $coursedata->courseid = $courseid;
            $coursedata->instructorid = $USER->id;
            $DB->insert_record('plagiarism_safeassign_course', $coursedata);
        }
    }

    /**
     * Checks if submission has a file and creates or updates a record on
     * plagiarism_safeassign_subm if the submission has or not an associated record.
     * @param object $evendata
     */
    public function create_submission($eventdata) {
        // Check if SafeAssign is enable at site level.
        if (get_config('plagiarism', 'safeassign_use')) {
            // Get SafeAssign assignment configuration.
            $config = $this->check_assignment_config($eventdata);
            if (!empty($config) && $config['safeassign_enabled']) {
                $this->update_old_submission_records($eventdata);
                $params = array();
                if (!empty($eventdata['other']['pathnamehashes'])) {
                    $params['hasfile'] = 1;
                }
                if ($eventdata['objecttable'] === 'assignsubmission_onlinetext') {
                    if (isset($eventdata['other']['onlinetextwordcount']) && $eventdata['other']['onlinetextwordcount'] > 0) {
                        $params['hasonlinetext'] = 1;
                    }
                }
                $params['globalcheck'] = $config['safeassign_global_reference'];
                $this->validate_submission($eventdata, $params);
            }
        }
    }

    /**
     * Returns the SafeAssign configuration for the assignment.
     * @param object $eventdata
     * @return array $config
     */
    private function check_assignment_config($eventdata) {
        global $DB;
        $config = $DB->get_records_menu('plagiarism_safeassign_config', array('cm' => $eventdata['contextinstanceid']),
            '', 'name, value');
        return $config;
    }

    /**
     * Returns the stored state of the globalcheck flag.
     * @param object $eventdata
     * @return int $globalcheck state of the flag, 1 or 0.
     */
    private function get_global_check_flag($eventdata) {
        global $DB, $USER;
        $query = "SELECT saconf.value
                    FROM {course_modules} cm
                    JOIN {assign_submission} asub
                      ON cm.instance = asub.assignment
                    JOIN {plagiarism_safeassign_config} saconf
                      ON saconf.cm = cm.id
                   WHERE asub.id = ? AND cm.course = ? AND saconf.name = ?";
        $globalcheck = $DB->get_record_sql($query, array($eventdata['objectid'], $eventdata['courseid'], $USER->id));
        if(!empty($globalcheck->value)) {
            return $globalcheck->value;
        }
        return 0;
    }

    /**
     * Creates the submission record on plagiarism_safeassign_subm table.
     * @param object $eventdata
     */
    private function create_submission_record($eventdata, $params) {
        global $DB;

        $submissionid = $eventdata['objectid'];
        if ($eventdata['objecttable'] === 'assignsubmission_onlinetext') {
            $submissionid = $eventdata['other']['submissionid'];
        }

        $submission = new stdClass();
        $submission->submissionid = $submissionid;
        $submission->globalcheck = $this->get_global_check_flag($eventdata);
        $submission->groupsubmission = 1;
        $submission->reportgenerated = 0;
        $submission->submitted = 0;
        $submission->highscore = 0.0;
        $submission->avgscore = 0.0;
        $submission->deprecated = 0;
        $submission->hasfile = (isset($params['hasfile']))? $params['hasfile']: 0;
        $submission->hasonlinetext =(isset($params['hasonlinetext']))? $params['hasonlinetext']: 0;
        $submission->timecreated = $eventdata['timecreated'];
        $DB->insert_record('plagiarism_safeassign_subm', $submission);
    }

    /**
     * Search for old records on plagiarism_safeassign_subm table and updates their
     * deprecated field. This way, the sync task should know which submissions are valid.
     * @param object $eventdata
     */
    public function update_old_submission_records($eventdata) {
        global $DB;
        $submissionid = $eventdata['objectid'];
        if ($eventdata['objecttable'] === 'assignsubmission_onlinetext') {
            $submissionid = $eventdata['other']['submissionid'];
        }
        // Search the submission that are already in plagiarism_safeassign_subm.
        $sql = "UPDATE {plagiarism_safeassign_subm}
                   SET deprecated = 1
                 WHERE submissionid = :submissionid AND timecreated <> :timecreated";
        $DB->execute($sql, array('submissionid' => $submissionid, 'timecreated' => $eventdata['timecreated']));
    }

    /**
     * Checks if submission already has a record on plagiarism_safeassign_subm for
     * not creating a duplicate record.
     * @param object $eventdata
     * @param array $params
     */
    private function validate_submission($eventdata, $params) {
        global $DB;
        $submissionid = $eventdata['objectid'];
        if ($eventdata['objecttable'] === 'assignsubmission_onlinetext') {
            $submissionid = $eventdata['other']['submissionid'];
        }
        $record = $DB->get_record('plagiarism_safeassign_subm', array('submissionid' => $submissionid,
            'timecreated' => $eventdata['timecreated']));
        if (empty($record)) {
            $this->create_submission_record($eventdata, $params);
        } else {
            $originalhasfile = $record->hasfile;
            $originalhasonlinesubmission = $record->hasonlinetext;
            if (isset($params['hasfile'])) {
                $record->hasfile = $params['hasfile'];
            }
            if (isset($params['hasonlinetext'])) {
                $record->hasonlinetext = $params['hasonlinetext'];
            }
            if ($originalhasfile != $record->hasfile || $originalhasonlinesubmission != $record->hasonlinetext) {
                $this->update_submission($record);
            }
        }
    }

    /**
     * Updates a submission record on plagiarism_safeassign_subm table.
     * @param object $submission
     */
    private function update_submission($submission) {
        global $DB;
        $DB->update_record('plagiarism_safeassign_subm', $submission);
    }

    /**
     * Function to be run periodically according to the scheduled task.
     * Checks if the submissions already have a report generated on SafeAssign side and mark the flag.
     */
    function safeassign_get_scores() {
        global $DB;

        $submissions = $DB->get_records_sql("SELECT plg.*, asg.userid
        FROM {plagiarism_safeassign_subm} plg, {assign_submission} asg
        WHERE plg.deprecated = 0 AND plg.reportgenerated = 0 AND plg.submitted = 1 AND plg.submissionid = asg.id;");
        foreach ($submissions as $submission) {
            $userid = $submission->userid;
            $submissionuuid = $submission->uuid;
            $result = '';
            if ($submissionuuid) {
                $result = safeassign_api::get_originality_report_basic_data($userid, $submissionuuid);
            } else {
                continue;
            }

            if ($result) {
                $convhighscore = floatval($result->highest_score / 100);
                $convavgscore = floatval($result->average_score / 100);
                $submission->highscore = $convhighscore;
                $submission->avgscore = $convavgscore;
                $submission->reportgenerated = 1;
                if (isset($result->submission_files)) {
                    foreach ($result->submission_files as $fileuuid => $score) {
                        $DB->set_field('plagiarism_safeassign_files', 'similarityscore', floatval($score/100), array('uuid' => $fileuuid));
                    }
                }
                unset($submission->userid);
                $DB->update_record('plagiarism_safeassign_subm', $submission);
            } else {
                $event = score_sync_fail::create_from_error_handler($submission->id);
                $event->trigger();
            }
        }
        $event = score_sync_log::create();
        $event->trigger();
    }

    /**
     * Gets the courses that already have been synced.
     *
     * @returns @array object
     */
    public function get_valid_courses() {
        global $DB;

        $sql = 'SELECT courseid, instructorid
                  FROM {plagiarism_safeassign_course}
                 WHERE uuid IS NOT NULL';

        return $DB->get_records_sql($sql, array());
    }

    /**
     * Gets the assignments that already have been synced.
     *
     * @returns array object
     */
    public function get_valid_assignments() {
        global $DB;

        $sql = 'SELECT *
                  FROM {plagiarism_safeassign_assign}
                 WHERE uuid IS NOT NULL';

        return $DB->get_records_sql($sql, array());
    }

    /**
     * Syncs the local SafeAssign course table with the identifier from the SafeAssign service.
     *
     * @param object array $courses
     */
    public function sync_courses($courses) {
        global $DB;
        foreach ($courses as $course) {
            if ($course->instructorid != 0) {
                $validation = safeassign_api::get_course($course->instructorid, $course->courseid);
                if ($validation === false) {
                    $response = safeassign_api::create_course($course->instructorid, $course->courseid);
                    if ($response) {
                        $lastresponse = json_decode(rest_provider::instance()->lastresponse());
                        if (isset($lastresponse->uuid)) {
                            $course->uuid = $lastresponse->uuid;
                            $DB->update_record('plagiarism_safeassign_course', $course);
                            safeassign_api::put_instructor_to_course($course->instructorid, $course->uuid);
                            continue;
                        } else {
                            $event = sync_content_log::create_log_message('Course', $course->courseid);
                            $event->trigger();
                        }
                    } else {
                        $event = sync_content_log::create_log_message('Course', $course->courseid);
                        $event->trigger();
                    }
                } else if (isset($validation->uuid)) {
                    $course->uuid = $validation->uuid;
                    $DB->update_record('plagiarism_safeassign_course', $course);
                    safeassign_api::put_instructor_to_course($course->instructorid, $course->uuid);
                }
            }
        }
    }

    /**
     * Syncs the existing assignments. It is necessary that the course have the corresponding uuid.
     */
    public function sync_course_assignments() {
        global $DB;
        $courses = $this->get_valid_courses();
        if (!empty($courses)) {
            foreach ($courses as $course) {
                $ids[] = $course->courseid;
            }
            $sql = "SELECT sa_assign.assignmentid, sa_course.courseid, sa_course.uuid AS courseuuid, a.name AS assignmentname
                      FROM {assign} a
                      JOIN {plagiarism_safeassign_assign} sa_assign ON sa_assign.assignmentid = a.id 
                      JOIN {plagiarism_safeassign_course} sa_course ON sa_course.courseid = a.course 
                      JOIN {course_modules} cm ON cm.course = a.course
                      JOIN {modules} m ON m.id = cm.module
                      JOIN {plagiarism_safeassign_config} sa_config ON sa_config.cm = cm.id 
                     WHERE sa_assign.uuid IS NULL
                       AND cm.instance=a.id
                       AND sa_course.uuid IS NOT NULL
                       AND sa_config.name = 'safeassign_enabled'
                       AND sa_config.value = 1
                       AND m.name = 'assign'
                       AND a.course ";

            list($sqlin, $params) = $DB->get_in_or_equal($ids);
            $assignments = $DB->get_records_sql($sql . $sqlin, $params);
            foreach ($assignments as $assignment) {

                // Check that the assignment does not exits in the SafeAssign database.
                $validation = safeassign_api::check_assignment($courses[$assignment->courseid]->instructorid, $assignment->courseuuid, $assignment->assignmentid);
                if ($validation === false) {
                    $response = safeassign_api::create_assignment($courses[$assignment->courseid]->instructorid,
                        $assignment->courseuuid, $assignment->assignmentid, $assignment->assignmentname);
                    if ($response) {
                        $lastresponse = json_decode(rest_provider::instance()->lastresponse());
                        if (isset($lastresponse->uuid)) {
                            $DB->set_field('plagiarism_safeassign_assign', 'uuid', $lastresponse->uuid, array('assignmentid' => $assignment->assignmentid));
                        }
                    } else if ($response === false) {
                        $event = sync_content_log::create_log_message('Assignment', $assignment->assignmentid);
                        $event->trigger();
                    }
                } else if ($validation) {
                    $lastresponse = json_decode(rest_provider::instance()->lastresponse());
                    if (isset($lastresponse->uuid)) {
                        $DB->set_field('plagiarism_safeassign_assign', 'uuid', $lastresponse->uuid, array('assignmentid' => $assignment->assignmentid));
                    } else {
                        $event = sync_content_log::create_log_message('Assignment', $assignment->assignmentid);
                        $event->trigger();
                    }
                }
            }
            $event = sync_content_log::create_log_message('Assignments', null, false);
            $event->trigger();
        }
    }

    /**
     * Gets the courseuuid and the assignmentuuid for the given assignments.
     * @param array $assignments
     * @return array object
     */
    public function get_course_credentials($assignments) {
        global $DB;

        $sql = "SELECT sa_assign.assignmentid, sa_course.uuid AS courseuuid, sa_assign.uuid AS assignuuid, sa_course.courseid
                  FROM {plagiarism_safeassign_assign} sa_assign
                  JOIN {assign} a ON a.id = sa_assign.assignmentid 
                  JOIN {plagiarism_safeassign_course} sa_course ON sa_course.courseid = a.course
                 WHERE sa_assign.assignmentid ";
        $params = array();
        list($sqlin, $params) = $DB->get_in_or_equal($assignments);
        return $DB->get_records_sql($sql . $sqlin, $params);
    }

    /**
     * Returns the submissions that needs to be synced.
     * @return array object
     */
    public function get_unsynced_submissions() {
        global $DB;

        $sql = "SELECT s.submissionid, s.hasfile, s.hasonlinetext, s.groupsubmission, s.globalcheck
                  FROM {plagiarism_safeassign_subm} s
                 WHERE s.deprecated = 0
                   AND s.uuid IS NULL
                   AND s.submitted = 0";
        return $DB->get_records_sql($sql, array());
    }

    /**
     *  Sync the assignment submissions.
     *  @return bool
     */
    public function sync_assign_submissions() {
        $assignments = $this->get_valid_assignments();
        if (empty($assignments)) {
            return false;
        }
        $ids = [];
        foreach ($assignments as $assignment) {
            $ids[] = $assignment->assignmentid;
        }
        $credentials = $this->get_course_credentials($ids);
        // Can't sync anything without credentials for courses and assignments.
        if (!empty($credentials)) {
            $submissions = mod_assign_external::get_submissions($ids, 'submitted');
            $unsynced = $this->get_unsynced_submissions();
            if (empty($unsynced)) {
                return false;
            }
            if (isset($submissions['assignments'])) {
                // Go on each assign submissions.
                foreach ($submissions['assignments'] as $submission) {
                    if (isset($submission['submissions'][0]) && (count($submission['submissions'][0]) > 0 )) {
                        $arraydata = $submission['submissions'];
                        // Go on each submission.
                        foreach ($arraydata as $data) {
                            if (isset($unsynced[$data['id']])) {
                                $cm = $this->get_cmid($submission['assignmentid']);
                                $assignmentcontext = context_module::instance($cm->id);
                                $this->sync_submission($data, $credentials, $submission, $unsynced[$data['id']],
                                    $assignmentcontext);
                            }
                        }
                    }
                }
                $event = sync_content_log::create_log_message('Submissions', null, false);
                $event->trigger();
            }
        }
    }

    /**
     * Sends the submission info for the given assignment to the SafeAssign service.
     * @param stdClass[] $data
     * @param stdClass[] $credentials
     * @param object $submission
     * @param stdClass $unsynced
     * @param context $context
     */
    public function sync_submission(array $data, array $credentials, $submission, stdClass $unsynced, context $context) {
        global $DB, $CFG;
        // Build the object to pass in to service.
        $wrapper = new stdClass();
        $wrapper->userid = (int) $data['userid'];
        $wrapper->courseuuid = $credentials[$submission['assignmentid']]->courseuuid;
        $wrapper->assignuuid = $credentials[$submission['assignmentid']]->assignuuid;
        $wrapper->filepaths = array();

        if ($unsynced->hasfile) {
            $files = $data['plugins'][0]['fileareas'][0]['files'];
            foreach ($files as $file) {
                $fs = get_file_storage();
                $wrapper->files[] = $fs->get_file($context->id, 'assignsubmission_file', 'submission_files',
                    $data['id'], '/', $file['filename']);
                $wrapper->filepaths[] = $file['fileurl'];
                $wrapper->filenames[] = $file['filename'];
            }
        }
        if ($unsynced->hasonlinetext) {
            $fs = get_file_storage();
            $usercontext = context_user::instance($wrapper->userid );
            $textfile = $fs->get_file($usercontext->id, 'assignsubmission_text_as_file', 'submission_text_files', $data['id'],
                '/', 'userid_' . $data['userid'] . '_text_submissionid_' . $data['id'] . '.txt');
            if ($textfile) {
                $wrapper->files[] = $textfile;
                $wrapper->filepaths[] = moodle_url::make_webservice_pluginfile_url($usercontext->id, 'assignsubmission_text_as_file',
                    'submission_text_files', $data['id'], $textfile->get_filepath(), $textfile->get_filename())->out(false);
                $wrapper->filenames[] = $textfile->get_filename();
            }
        }
        $wrapper->globalcheck = ($unsynced->globalcheck) ? true : false;;
        $wrapper->grouppermission = true;
        $result = safeassign_api::create_submission($wrapper->userid, $wrapper->courseuuid,
            $wrapper->assignuuid, $wrapper->files, $wrapper->globalcheck, $wrapper->grouppermission);
        $responsedata =json_decode(rest_provider::instance()->lastresponse());
        if ($result === true) {
            $record = $DB->get_record('plagiarism_safeassign_subm', array('submissionid' => $data['id'],
                'uuid' => null, 'submitted' => 0, 'deprecated' => 0));
            $record->submitted = 1;
            $this->sync_submission_files($data['id'], $responsedata, $wrapper->filenames,
                $wrapper->userid, $credentials[$submission['assignmentid']]->courseid);
            if (!empty($responsedata->submissions[0])) {
                $record->uuid = $responsedata->submissions[0]->submission_uuid;
            }
            $DB->update_record('plagiarism_safeassign_subm', $record);
        } else {
            $event = sync_content_log::create_log_message('Submissions', $data['id']);
            $event->trigger();
        }

    }

    /**
     *  Fill the safeassign_files datatable with the server response.
     *  @param int $submissionid
     *  @param stdClass $responsedata
     *  @param array $filenames
     *  @param int $userid
     *  @param int $courseid
     */
    public function sync_submission_files($submissionid, stdClass $responsedata, array $filenames, $userid, $courseid) {
        global $DB;
        $sql = "SELECT f.filename, f.id AS fileid, cm.id AS cmid
                  FROM {files} f
                  JOIN {assign_submission} sub ON sub.id = f.itemid
                  JOIN {course_modules} cm ON cm.instance = sub.assignment 
                 WHERE f.filearea IN (?,?)
                   AND f.itemid = ?
                   AND cm.course = ?
                   AND f.filename ";
        $params = array('submission_files', 'submission_text_files', $submissionid, $courseid);
        list($sqlin, $params2) = $DB->get_in_or_equal($filenames);
        $sentfiles = $DB->get_records_sql($sql . $sqlin, array_merge($params, $params2));
        if ($sentfiles) {
            $record = new stdClass();
            $record->userid = $userid;
            $record->timesubmitted = time();
            $record->submissionid = (int) $submissionid;
            if (!empty($responsedata->submissions[0]->submission_files)) {
                foreach ($responsedata->submissions[0]->submission_files as $file) {
                    $record->uuid = null;
                    if (isset($sentfiles[urldecode($file->file_name)])) {
                        $record->fileid = $sentfiles[urldecode($file->file_name)]->fileid;
                        $record->supported = 1;
                        $record->uuid = $file->file_uuid;
                        $record->cm = (int) $sentfiles[urldecode($file->file_name)]->cmid;
                        $DB->insert_record('plagiarism_safeassign_files', $record);
                    }
                }
            }
           if (!empty($responsedata->unprocessed_file_names)) {
               foreach ($responsedata->unprocessed_file_names as $unsupportedfilename) {
                   if (isset($sentfiles[urldecode($unsupportedfilename)])) {
                       $record->uuid = null;
                       $record->supported = 0;
                       $record->cm = (int) $sentfiles[urldecode($unsupportedfilename)]->cmid;
                       $record->fileid = $sentfiles[urldecode($unsupportedfilename)]->fileid;
                       $DB->insert_record('plagiarism_safeassign_files', $record);
                   }
               }
           }
        }
    }

    /**
     * Converts the submission text into a fie to be avaliable for the sync task.
     * @param object $eventdata
     */
    public function make_file_from_text_submission($eventdata) {
        global $DB;

        if (get_config('plagiarism', 'safeassign_use')) {
            $fs = get_file_storage();
            $usercontext = context_user::instance($eventdata['userid']);
            $oldfile = $fs->get_file($usercontext->id, 'assignsubmission_text_as_file', 'submission_text_files',
                $eventdata['other']['submissionid'], '/', 'userid_' . $eventdata['userid'] . '_text_submissionid_' . $eventdata['other']['submissionid'] .'.txt');
            if ($oldfile) {
                $oldfile->delete();
            }

            $filecontent = $DB->get_field('assignsubmission_onlinetext', 'onlinetext',  array('id' => $eventdata['objectid']));
            if ($filecontent) {
                $fs = get_file_storage();
                // Create a new one.
                $filerecord = array(
                    'contextid' => $usercontext->id,
                    'component' => 'assignsubmission_text_as_file',
                    'filearea' => 'submission_text_files',
                    'itemid' => $eventdata['other']['submissionid'],
                    'filepath' => '/',
                    'filename' => 'userid_' . $eventdata['userid'] . '_text_submissionid_' . $eventdata['other']['submissionid'] .'.txt',
                    'userid' => $eventdata['userid']
                );
                $fs->create_file_from_string($filerecord, $filecontent);
            }
        }
    }

    /**
     * Gets the course module ID for the given assignment id.
     * @param $assignmentid
     * @return mixed
     */
    public function get_cmid($assignmentid) {
        list ($course, $cm) = get_course_and_cm_from_instance($assignmentid, 'assign');
        return $cm;

    }

}

/**
 * Adds the list of plagiarism settings to a form.
 *
 * @param object $mform - Moodle form object.
 */
function safeassign_get_form_elements($mform) {
    $mform->addElement('header', 'plagiarismdesc', get_string('safeassign', 'plagiarism_safeassign'));
    $mform->addElement('checkbox' ,'safeassign_enabled', get_string('assignment_check_submissions', 'plagiarism_safeassign'));
    $mform->addHelpButton('safeassign_enabled', 'assignment_check_submissions', 'plagiarism_safeassign');
    $mform->addElement('checkbox' ,'safeassign_originality_report', get_string('students_originality_report', 'plagiarism_safeassign'));
    $mform->addElement('checkbox' ,'safeassign_global_reference', get_string('submissions_global_reference', 'plagiarism_safeassign'));
    $mform->addHelpButton('safeassign_global_reference', 'submissions_global_reference', 'plagiarism_safeassign');
}