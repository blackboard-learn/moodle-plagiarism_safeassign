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

        if (!empty($courseconfiguration['safeassign_enabled']) && $linkarray['userid'] != '0') {
            // The activity has SafeAssign enabled.
            $message = '';
            $file = null;
            $userid = $linkarray['userid'];
            $isonlinesubmission = false;
            $submissionsize = 0;
            if (isset($linkarray['file'])) {
                // This submission has a file associated with it.

                $file = $this->get_file_results($cmid, $userid, $linkarray['file']->get_id());
                $submissionsize = $this->get_total_file_size($linkarray['file']->get_contextid(), $linkarray['file']->get_itemid());
            } else {
                if (!empty($linkarray['content'])) {
                    // This submission has an online text associated with it.
                    $submission = $DB->get_record('assign_submission', array('userid' => $userid,
                        'assignment' => $linkarray['assignment']), 'id');
                    $namefile = 'userid_' . $userid . '_text_submissionid_' . $submission->id . '.html';
                    $filerecord = $DB->get_record('files', array('filename' => $namefile, 'userid' => $userid));
                    // If html file of online text does not exist, try finding a txt file.
                    // Fixing bug of syncing old submissions when they were saved as txt files.
                    if (!$filerecord) {
                        $namefile = 'userid_' . $userid . '_text_submissionid_' . $submission->id . '.txt';
                        $filerecord = $DB->get_record('files', array('filename' => $namefile, 'userid' => $userid));
                    }
                    if (is_object($filerecord)) {
                        $file = $this->get_file_results($cmid, $userid, $filerecord->id);
                        $modcontext = context_module::instance($linkarray['cmid']);
                        $isonlinesubmission = true;
                        $submissionsize = $this->get_total_file_size($modcontext->id, $submission->id);
                    }
                }
            }
            if ($file != null) {
                $message = $this->get_message_result($file, $cm, $courseconfiguration, $userid, $isonlinesubmission,
                    $submissionsize);
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
     * @param int $userid
     * @param boolean $isonlinesubmission
     * @param int $submissionsize
     * @return string
     */
    private function get_message_result($file, $cm, array $courseconfiguration, $userid, $isonlinesubmission,
                                        $submissionsize) {
        global $USER, $OUTPUT, $COURSE, $PAGE, $DB;

        $onlinetextclass = $isonlinesubmission ? 'online-text-div' : '';
        $message = '<div class="plagiarism-inline ' . $onlinetextclass . '">';
        if ($DB->record_exists('plagiarism_safeassign_instr', array('courseid' => $COURSE->id,
                'instructorid' => $userid, 'unenrolled' => 0))) {
            $message .= get_string('safeassign_submission_not_supported', 'plagiarism_safeassign');
            $message .= $OUTPUT->help_icon('safeassign_submission_not_supported', 'plagiarism_safeassign');
            $message .= '</div>';
            return $message;
        }
        if ($file['supported']) {
            if ($file['analyzed']) {
                // We have a valid report for this file.
                $message .= get_string('safeassign_file_similarity_score', 'plagiarism_safeassign', intval($file['score'] * 100));

                // We need to validate that the user can see the link to the similarity report.
                $role = get_user_roles($cm, $USER->id);
                $roleid = key($role);
                $orhtml = "";
                if (empty($role) || $role[$roleid]->shortname != 'student' ||
                    $courseconfiguration['safeassign_originality_report']) {
                    // The report is enabled for this user.
                    $reporturl = new moodle_url('/plagiarism/safeassign/view.php', [
                        'courseid' => $COURSE->id,
                        'uuid' => $file['subuuid'],
                        'fileuuid' => $file['fileuuid']
                    ]);
                    $orhtml = html_writer::link($reporturl,
                        get_string('safeassign_link_originality_report', 'plagiarism_safeassign'),
                        ['target' => '_sa_originality_report']);
                }

                // Print the overall score for this submission.
                $PAGE->requires->js_call_amd('plagiarism_safeassign/score', 'init',
                    array(intval($file['avgscore'] * 100), $userid, $orhtml));
            } else {
                if ($submissionsize > SAFEASSIGN_SUBMISSION_MAX_SIZE) {
                    $message .= get_string('safeassign_file_limit_exceeded', 'plagiarism_safeassign');
                } else if ($file['proceed'] && $file['status'] === ASSIGN_SUBMISSION_STATUS_SUBMITTED) {
                    $message .= get_string('safeassign_file_in_review', 'plagiarism_safeassign');
                }
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
        $subuuid = '';
        $avgscore = -1;
        $proceed = 0;
        $fileuuid = '';
        $status = '';
        $filequery = '
            SELECT fil.id,
                   sub.id as sasubid,
                   sfil.id as safilid,
                   sub.reportgenerated,
                   sfil.similarityscore,
                   sfil.reporturl,
                   sfil.supported,
                   sub.uuid,
                   sub.avgscore,
                   submission.status,
                   sfil.uuid as fileuuid
              FROM {files} fil
         LEFT JOIN {plagiarism_safeassign_subm} sub
                ON fil.itemid = sub.submissionid
               AND sub.deprecated = 0
         LEFT JOIN {assign_submission} submission
                ON sub.submissionid = submission.id
         LEFT JOIN {plagiarism_safeassign_files} sfil
                ON fil.id = sfil.fileid
               AND sfil.cm = :cmid
               AND sfil.userid = :userid
             WHERE fil.id = :fileid
          ORDER BY fil.id';

        $params = [
            'cmid' => $cmid,
            'userid' => $userid,
            'fileid' => $fileid
        ];
        $files = $DB->get_records_sql($filequery, $params);
        $fileinfo = end($files);
        if (!empty($fileinfo)) {
            $proceed = !empty($fileinfo->sasubid) ? 1 : 0;
            $status = $fileinfo->status;
            if (!empty($fileinfo->safilid)) {
                $analyzed = $fileinfo->reportgenerated;
                $score = $fileinfo->similarityscore;
                $reporturl = $fileinfo->reporturl;
                $supported = $fileinfo->supported;
                $subuuid = $fileinfo->uuid;
                $avgscore = $fileinfo->avgscore;
                $fileuuid = $fileinfo->fileuuid;
            }
        }

        return array('analyzed' => $analyzed, 'score' => $score, 'reporturl' => $reporturl, 'supported' => $supported,
            'subuuid' => $subuuid, 'avgscore' => $avgscore, 'proceed' => $proceed, 'fileuuid' => $fileuuid, 'status' => $status);
    }

    /**
     * Retrieve submission information and results.
     * @param int $submid submission ID.
     * @return bool|\stdClass submission object, False if not found.
     */
    public function get_submission_results($submid) {
        global $DB;
        $submquery = "SELECT *
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
            $institutionrelease = get_config('plagiarism_safeassign', 'safeassign_new_student_disclosure');
            if (empty($institutionrelease)) {
                $institutionrelease = get_string('studentdisclosuredefault', 'plagiarism_safeassign');
                $PAGE->requires->js_call_amd('plagiarism_safeassign/disclosure', 'init', array($cmid, $USER->id));
            }
            $institutionrelease .= '<br><br>';
            $institutionrelease .= get_string('files_accepted', 'plagiarism_safeassign');
            if ($siteglobalref == 1) {
                $institutionrelease .= '<br><br>'.$checkbox;
            }
            $col1 = html_writer::tag('div', get_string('plagiarism_tools', 'plagiarism_safeassign'),
                array('class' => 'col-md-3'));
            $col2 = html_writer::tag('div', $institutionrelease, array('class' => 'col-md-9'));
            $output = html_writer::tag('div', $col1.$col2, array('class' => 'row generalbox boxaligncenter intro'));
            $form = html_writer::tag('form', $output);
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
            $assignmentdata->courseid = $eventdata->courseid;
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
            $this->set_course_instructors($courseid);
        }
    }

    /**
     * Checks if submission has a file and creates or updates a record on
     * plagiarism_safeassign_subm if the submission has or not an associated record.
     * @param object $eventdata
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

                $globaldbpref = $this->should_send_to_global_check($config, $eventdata['userid']);
                $params['globalcheck'] = $globaldbpref;
                $this->validate_submission($eventdata, $params);
            }
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
    private function check_assignment_config($eventdata) {
        global $DB;
        $config = $DB->get_records_menu('plagiarism_safeassign_config', array('cm' => $eventdata['contextinstanceid']),
            '', 'name, value');
        return $config;
    }

    /**
     * Creates the submission record on plagiarism_safeassign_subm table.
     * @param stdClass $eventdata
     * @param array $params
     */
    private function create_submission_record($eventdata, $params) {
        global $DB;

        $submissionsize = $this->get_total_file_size($eventdata['contextid'], $eventdata['objectid']);
        $submissionid = $eventdata['objectid'];
        if ($eventdata['objecttable'] === 'assignsubmission_onlinetext') {
            $submissionid = $eventdata['other']['submissionid'];
        }
        $arrayconditions = array('courseid' => $eventdata['courseid'], 'instructorid' => $eventdata['userid'], 'unenrolled' => 0);
        $isinstructor = $DB->record_exists('plagiarism_safeassign_instr', $arrayconditions);

        $submission = new stdClass();
        $submission->submissionid = $submissionid;
        $submission->globalcheck = $params['globalcheck'];
        $submission->groupsubmission = 1;
        $submission->reportgenerated = 0;
        $submission->submitted = 0;
        $submission->highscore = 0.0;
        $submission->avgscore = 0.0;
        // If we mark instructor's submission as deprecated, we avoid that the task tries to sync it continuously.
        $submission->deprecated = $isinstructor || ($submissionsize > SAFEASSIGN_SUBMISSION_MAX_SIZE) ? 1 : 0;

        $submission->hasfile = (isset($params['hasfile'])) ? $params['hasfile'] : 0;
        $submission->hasonlinetext = (isset($params['hasonlinetext'])) ? $params['hasonlinetext'] : 0;
        $submission->timecreated = $eventdata['timecreated'];

        $submissionid = $eventdata['objectid'];
        if ($eventdata["objecttable"] == "assignsubmission_onlinetext") {
            $submissionid = $eventdata["other"]["submissionid"];
        }
        $submission->assignmentid = $DB->get_field('assign_submission', 'assignment', array('id' => $submissionid), MUST_EXIST);

        $DB->insert_record('plagiarism_safeassign_subm', $submission);
    }

    /**
     * Returns the total filesize for a given submission
     * @param $contextid Context module ID
     * @param $submissionid
     */
    public function get_total_file_size($contextid, $submissionid) {
        global $USER;
        $usercontext = context_user::instance($USER->id);
        $total = 0;
        $fs = get_file_storage();
        $files = $fs->get_area_files($contextid, 'assignsubmission_file', 'submission_files', $submissionid);
        foreach ($files as $file) {
            $total += $file->get_filesize();
        }
        $onlinetext = $fs->get_file($usercontext->id, 'assignsubmission_text_as_file', 'submission_text_files', $submissionid,
            '/', 'userid_' . $USER->id . '_text_submissionid_' . $submissionid . '.html');
        if ($onlinetext) {
            $total += $onlinetext->get_filesize();
        }
        return $total;
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
            $totalsize = $this->get_total_file_size($eventdata['contextid'], $submissionid);
            $originalhasfile = $record->hasfile;
            $originalhasonlinesubmission = $record->hasonlinetext;
            if (isset($params['hasfile'])) {
                $record->hasfile = $params['hasfile'];
            }
            if (isset($params['hasonlinetext'])) {
                $record->hasonlinetext = $params['hasonlinetext'];
            }
            if (($record->hasfile == 0 && $record->hasonlinetext == 0) || $totalsize > SAFEASSIGN_SUBMISSION_MAX_SIZE) {
                $record->deprecated = 1;
            }
            if ($originalhasfile != $record->hasfile || $originalhasonlinesubmission != $record->hasonlinetext
                || $record->deprecated) {
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
    public function safeassign_get_scores() {
        global $DB, $CFG;
        $updatedsubmissions = array();
        $gradedsubmissions = array();

        $sql = '
       SELECT DISTINCT plg.id,
                       plg.uuid,
                       plg.globalcheck,
                       plg.groupsubmission,
                       plg.highscore,
                       plg.avgscore,
                       plg.submitted,
                       plg.reportgenerated,
                       plg.submissionid,
                       plg.deprecated,
                       plg.hasfile,
                       plg.hasonlinetext,
                       plg.timecreated,
                       plg.assignmentid,
                       plg.deleted,
		     CASE WHEN asg.userid = 0 AND f.userid <> 0
                  THEN f.userid
                  ELSE asg.userid END as userid
                  FROM {plagiarism_safeassign_subm} plg
                  JOIN {assign_submission} asg ON plg.submissionid = asg.id
                  JOIN {plagiarism_safeassign_files} saf ON asg.id = saf.submissionid
             LEFT JOIN {files} f ON plg.submissionid = f.itemid
                 WHERE plg.deprecated = 0
                   AND f.userid IS NOT NULL
                   AND plg.reportgenerated = 0
                   AND saf.supported = 1
                   AND plg.submitted = 1';

        $submissions = $DB->get_records_sql($sql);
        $count = 0;
        $baseurl = get_config('plagiarism_safeassign', 'safeassign_api');
        foreach ($submissions as $submission) {
            $assignmentid = $submission->assignmentid;
            if (!array_key_exists($assignmentid, $gradedsubmissions)) {
                $gradedsubmissions[$assignmentid] = 0;
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
                $gradedsubmissions[$assignmentid] += 1;
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

        // Send a message to the teachers.
        if (!local::duringphptesting()) {
            foreach ($gradedsubmissions as $assignmentid => $counter) {
                if ($counter > 0) {
                    list($course, $cm) = get_course_and_cm_from_instance($assignmentid, "assign");
                    $courseid = $course->id;
                    $context = context_course::instance($courseid);
                    $teachers = get_enrolled_users($context, 'plagiarism/safeassign:get_messages');

                    // Search for assignment's name.
                    $assignment = $DB->get_record("assign", array('id' => $assignmentid));
                    $assignmentname = $assignment->name;
                    foreach ($teachers as $teacher) {
                        $teacherid = $teacher->id;
                        self::send_notification_to_teacher($teacherid, $courseid, $cm->id, $counter, $assignmentname);
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
       SELECT DISTINCT courseid, MAX(instructorid) as instructorid
                  FROM {plagiarism_safeassign_course}
                 WHERE uuid IS NOT NULL
              GROUP BY courseid';

        return $DB->get_records_sql($sql, array());
    }

    /**
     * Gets the assignments that already have been synced.
     *
     * @return array object
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
     * @param stdClass[] $courses
     */
    public function sync_courses($courses) {
        global $DB, $CFG;
        $count = 0;
        $baseurl = get_config('plagiarism_safeassign', 'safeassign_api');
        foreach ($courses as $course) {
            if ($course->instructorid != 0) {
                $validation = safeassign_api::get_course($course->instructorid, $course->courseid);
                if ($validation === false) {
                    $response = safeassign_api::create_course($course->instructorid, $course->courseid);
                    $params = array();
                    if (!empty($CFG->plagiarism_safeassign_debugging)) {
                        $params['Instructor Id'] = $course->instructorid;
                        $params['Course ID'] = $course->courseid;
                        $params['Url'] = $baseurl . '/api/v1/courses';
                    }
                    if ($response) {
                        $lastresponse = json_decode(rest_provider::instance()->lastresponse());
                        if (isset($lastresponse->uuid)) {
                            $course->uuid = $lastresponse->uuid;
                            $DB->update_record('plagiarism_safeassign_course', $course);
                            safeassign_api::put_instructor_to_course($course->instructorid, $course->uuid);
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
                    safeassign_api::put_instructor_to_course($course->instructorid, $course->uuid);
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
     * Syncs the existing assignments. It is necessary that the course have the corresponding uuid.
     */
    public function sync_course_assignments() {
        global $DB, $CFG;
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
                       AND sa_config.value = '1'
                       AND m.name = 'assign'
                       AND a.course ";

            list($sqlin, $params) = $DB->get_in_or_equal($ids);
            $assignments = $DB->get_records_sql($sql . $sqlin, $params);
            $count = 0;
            $baseurl = get_config('plagiarism_safeassign', 'safeassign_api');
            foreach ($assignments as $assignment) {

                // Check that the assignment does not exits in the SafeAssign database.
                $validation = safeassign_api::check_assignment($courses[$assignment->courseid]->instructorid,
                    $assignment->courseuuid, $assignment->assignmentid);
                $params = array();
                if (!empty($CFG->plagiarism_safeassign_debugging)) {
                    $params['Instructor ID'] = $courses[$assignment->courseid]->instructorid;
                    $params['Course UUID'] = $assignment->courseuuid;
                    $params['Assignment ID'] = $assignment->assignmentid;
                    $params['Assignment Name'] = $assignment->assignmentname;
                    $params['Url'] = $baseurl . '/api/v1/courses/' . $assignment->courseuuid . '/assignments';
                }
                if ($validation === false) {
                    $response = safeassign_api::create_assignment($courses[$assignment->courseid]->instructorid,
                        $assignment->courseuuid, $assignment->assignmentid, $assignment->assignmentname);
                    if ($response) {
                        $lastresponse = json_decode(rest_provider::instance()->lastresponse());
                        if (isset($lastresponse->uuid)) {
                            $DB->set_field('plagiarism_safeassign_assign', 'uuid',
                                $lastresponse->uuid, array('assignmentid' => $assignment->assignmentid));
                            $count ++;
                        }
                    } else if ($response === false) {
                        $event = sync_content_log::create_log_message('Assignment', $assignment->assignmentid, true, null, $params);
                        $event->trigger();
                    }
                } else if ($validation) {
                    $lastresponse = json_decode(rest_provider::instance()->lastresponse());
                    if (isset($lastresponse->uuid)) {
                        $DB->set_field('plagiarism_safeassign_assign', 'uuid',
                            $lastresponse->uuid, array('assignmentid' => $assignment->assignmentid));
                        $count ++;
                    } else {
                        $event = sync_content_log::create_log_message('Assignment', $assignment->assignmentid, true, null, $params);
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
     * Gets the courseuuid and the assignmentuuid for the given assignments.
     * @param array $assignments
     * @return array object
     */
    public function get_course_credentials($assignments) {
        global $DB;

        $sql = "
       SELECT DISTINCT sa_assign.assignmentid,
                       sa_course.uuid AS courseuuid,
                       sa_assign.uuid AS assignuuid,
                       sa_course.courseid
                  FROM {plagiarism_safeassign_assign} sa_assign
                  JOIN {assign} a ON a.id = sa_assign.assignmentid
                  JOIN {plagiarism_safeassign_course} sa_course ON sa_course.courseid = a.course
                 WHERE sa_assign.assignmentid ";
        $params = array();
        list($sqlin, $params) = $DB->get_in_or_equal($assignments);
        return $DB->get_records_sql($sql . $sqlin, $params);
    }

    /**
     * Helper function to check if records on SafeAssign tables are correct.
     * plagiarism_safeassign_subm table should not have any record with both hasfile and hasonlinetext in 0
     */
    private function db_sanitycheck() {
        global $DB;
        if ($DB->count_records("plagiarism_safeassign_subm", array("hasfile" => 0, "hasonlinetext" => 0, "deprecated" => 0)) > 0) {
            $sql = 'UPDATE {plagiarism_safeassign_subm} t
                      JOIN
                           (SELECT s.id as id,
                                   af.id IS NOT NULL as hasfile,
                                   ao.id IS NOT NULL as hasonlinetext
                              FROM {plagiarism_safeassign_subm} s
                         LEFT JOIN {assignsubmission_file} af
                                ON af.submission = s.submissionid
                               AND af.assignment = s.assignmentid
                         LEFT JOIN {assignsubmission_onlinetext} ao
                                ON ao.submission = s.submissionid
                               AND ao.assignment = s.assignmentid
                             WHERE s.hasonlinetext = 0
                               AND s.hasfile = 0
                               AND s.deprecated = 0
                          ) as t1
                       ON t1.id = t.id
                      SET t.hasfile = t1.hasfile, t.hasonlinetext = t1.hasonlinetext';

            $DB->execute($sql);
        }
    }


    /**
     * Returns the submissions that needs to be synced.
     * @return array object
     */
    public function get_unsynced_submissions() {
        global $DB;
        // Check if there are submission without valid assignment ids and update them.
        // A submission can be invalid if its submission id is 0 or it does not correspond to submission tables.
        $sql = 'UPDATE {plagiarism_safeassign_subm} sasub
                   SET assignmentid = (
                SELECT msub.assignment
                  FROM {assign_submission} msub
                 WHERE sasub.submissionid = msub.id)
          WHERE EXISTS (
                SELECT *
                  FROM {assign_submission} msub
                 WHERE sasub.submissionid = msub.id
                   AND sasub.deprecated = 0
                   AND sasub.uuid IS NULL
                   AND sasub.assignmentid <> msub.assignment)';

        if ($DB->get_dbfamily() == 'mssql') {
            $sql = <<<SQL
                 UPDATE sasub
                    SET assignmentid = (
                 SELECT msub.assignment
                   FROM {assign_submission} msub
                  WHERE sasub.submissionid = msub.id)
                   FROM {plagiarism_safeassign_subm} sasub
            WHERE EXISTS (
                  SELECT *
                    FROM {assign_submission} msub
                   WHERE sasub.submissionid = msub.id
                     AND sasub.deprecated = 0
                     AND sasub.uuid IS NULL
                     AND sasub.assignmentid <> msub.assignment)
SQL;
        }

        $DB->execute($sql);

        // Check validity of submissions. Should have at least hasfile or hasonlinetext.
        $this->db_sanitycheck();

        // Get records for when users are in groups, and leave out the ones that are on default group.
        $sql = 'SELECT DISTINCT(s.submissionid),
                       s.hasfile,
                       s.hasonlinetext,
                       s.groupsubmission,
                       s.globalcheck,
                       a.uuid as assignuuid,
                       a.assignmentid,
                       c.uuid as courseuuid,
                       c.courseid,
             CASE WHEN asubm.userid = 0 AND f.userid <> 0
                  THEN f.userid
                  ELSE asubm.userid END as userid,
                       f.userid as fileuserid
                  FROM {plagiarism_safeassign_subm} s
                  JOIN {plagiarism_safeassign_assign} a ON a.assignmentid = s.assignmentid
                  JOIN {plagiarism_safeassign_course} c ON a.courseid = c.courseid
                  JOIN {assign_submission} asubm ON asubm.id = s.submissionid
             LEFT JOIN {files} f ON s.submissionid = f.itemid
                 WHERE s.deprecated = 0
                   AND f.userid IS NOT NULL
                   AND s.uuid IS NULL
                   AND s.submitted = 0
                   AND (s.hasonlinetext = 1 OR s.hasfile = 1)
                   AND asubm.status = ?';

        $records = $DB->get_records_sql($sql, ["submitted"]);

        return $records;
    }

    /**
     * Sync the assignment submissions.
     * @return bool
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
            $unsynced = $this->get_unsynced_submissions();
            if (empty($unsynced)) {
                return false;
            }
            // Check each submission.
            $count = 0;
            foreach ($unsynced as $unsyncsubmission) {
                $cm = $this->get_cmid($unsyncsubmission->assignmentid);
                $assignmentcontext = context_module::instance($cm->id);

                $unsyncsubmission->contextid = $assignmentcontext->id;
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
     * @param int $context Context
     * @return array Array with the files belonging to this submission
     */
    private function get_unsynced_files($submissionid, $contextid) {
        global $DB;
        $files = array();
        $filenames = array();
        $filepaths = array();

        $sql = "SELECT id, filearea, filepath, filename
                  FROM {files} mrfile
                 WHERE itemid = :submissionid
                   AND component = 'assignsubmission_file'
                   AND contextid = :contextid
                   AND filesize > 0";

        $params = array("submissionid" => $submissionid, "contextid" => $contextid);
        $records = $DB->get_records_sql($sql, $params);

        foreach ($records as $file) {
            $fs = get_file_storage();
            $fileobj = $fs->get_file($contextid, 'assignsubmission_file', 'submission_files',
                $submissionid, $file->filepath, $file->filename);
            $gottenfilesystem = $fs->get_file_system();
            $fileisreadable = false;
            if ($gottenfilesystem->is_file_readable_locally_by_storedfile($fileobj)) {
                $fileisreadable = true;
                // If file isn't found locally, then try it remotely.
            } else if ($gottenfilesystem->is_file_readable_remotely_by_storedfile($fileobj)) {
                $fileisreadable = true;
            }

            // If file object does not exist, log an error and skip.
            if (!$fileobj) {
                $errortext = 'File with submission ID: ' . $submissionid .
                    ' and context ID: ' . $contextid . ' could not be found on database';
                $event = sync_content_log::create_log_message('error', null, true, $errortext);
                $event->trigger();
                continue;
            } else if (!$fileisreadable) {
                // If file is not readable, log an error and skip.
                $errortext = 'File with submission ID: ' . $submissionid .
                    ' and context ID: ' . $contextid . ' could not be read. Submission will be marked as deprecated.';
                $event = sync_content_log::create_log_message('error', null, true, $errortext);
                $event->trigger();
                $this->mark_submission_deprecated($submissionid);
                continue;
            }
            $files[] = $fileobj;
            $filenames[] = $file->filename;
        }

        return array("files" => $files, "filenames" => $filenames);
    }

    /**
     * Marks submission as deprecated in plagiarism_safeassign_subm.
     * @param int $submissionid
     */
    public function mark_submission_deprecated(int $submissionid) {
        global $DB;
        if (empty($submissionid)) {
            return;
        }
        $DB->set_field("plagiarism_safeassign_subm", "deprecated", "1", array("submissionid" => $submissionid));
    }

    /**
     * Sends the submission info for the given assignment to the SafeAssign service.
     * @param stdClass $data
     * @return bool
     */
    public function sync_submission(stdClass $data) {
        global $DB, $CFG;

        $userid         = $data->userid;
        $submissionid   = $data->submissionid;
        $courseuuid     = $data->courseuuid;
        $assignuuid     = $data->assignuuid;
        $courseid       = $data->courseid;
        $hasfile        = $data->hasfile;
        $hasonlinetext  = $data->hasonlinetext;
        $globalcheck    = $data->globalcheck;
        $contextid      = $data->contextid;

        // Build the object to pass in to service.
        $wrapper = new stdClass();
        $wrapper->userid = $userid;
        $wrapper->courseuuid = $courseuuid;
        $wrapper->assignuuid = $assignuuid;

        if ($hasfile) {
            $unsyncedfiles = self::get_unsynced_files($submissionid, $contextid);
            // If there are no unsynced files, skip. Errors were thrown on function.
            if (empty($unsyncedfiles)) {
                return false;
            }

            $wrapper->files = $unsyncedfiles['files'];
            $wrapper->filenames = $unsyncedfiles['filenames'];
        }
        if ($hasonlinetext) {
            $fs = get_file_storage();
            $usercontext = context_user::instance($wrapper->userid );
            $textfile = $fs->get_file($usercontext->id, 'assignsubmission_text_as_file', 'submission_text_files', $submissionid,
                '/', 'userid_' . $userid . '_text_submissionid_' . $submissionid . '.html');

            // If html file of online text does not exist, try finding a txt file.
            // Fixing bug of syncing old submissions when they were saved as txt files.
            if (!$textfile) {
                $textfile = $fs->get_file($usercontext->id, 'assignsubmission_text_as_file', 'submission_text_files', $submissionid,
                    '/', 'userid_' . $userid . '_text_submissionid_' . $submissionid . '.txt');
            }
            if ($textfile) {
                $wrapper->files[] = $textfile;
                $wrapper->filenames[] = $textfile->get_filename();
            }
        }
        $wrapper->globalcheck = ($globalcheck) ? true : false;
        $wrapper->grouppermission = true;

        $result = safeassign_api::create_submission($wrapper->userid, $wrapper->courseuuid,
            $wrapper->assignuuid, $wrapper->files, $wrapper->globalcheck, $wrapper->grouppermission);
        $responsedata = json_decode(rest_provider::instance()->lastresponse());
        if ($result === true) {
            $record = $DB->get_record('plagiarism_safeassign_subm', array('submissionid' => $submissionid,
                'uuid' => null, 'submitted' => 0, 'deprecated' => 0));
            $record->submitted = 1;
            $this->sync_submission_files($submissionid, $responsedata, $wrapper->filenames,
                $wrapper->userid, $courseid);
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
     * @param array $filenames
     * @param int $userid
     * @param int $courseid
     */
    public function sync_submission_files($submissionid, stdClass $responsedata, array $filenames, $userid, $courseid) {
        global $DB;
        $sql = "SELECT TRIM(f.filename), f.id AS fileid, cm.id AS cmid
                  FROM {files} f
                  JOIN {assign_submission} sub ON sub.id = f.itemid
                  JOIN {course_modules} cm ON cm.instance = sub.assignment
                  JOIN {modules} m ON m.id = cm.module AND m.name = ?
                 WHERE f.filearea IN (?,?)
                   AND f.itemid = ?
                   AND cm.course = ?
                   AND f.filename ";
        $params = array('assign', 'submission_files', 'submission_text_files', $submissionid, $courseid);
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
                    $filename = trim($file->file_name);
                    if (isset($sentfiles[$filename])) {
                        $record->fileid = $sentfiles[$filename]->fileid;
                        $record->supported = 1;
                        $record->uuid = $file->file_uuid;
                        $record->cm = (int) $sentfiles[$filename]->cmid;
                        $DB->insert_record('plagiarism_safeassign_files', $record);
                    }
                }
            }
            if (!empty($responsedata->unprocessed_file_names)) {
                foreach ($responsedata->unprocessed_file_names as $unsupportedfilename) {
                    if (isset($sentfiles[trim($unsupportedfilename)])) {
                        $record->uuid = null;
                        $record->supported = 0;
                        $record->cm = (int) $sentfiles[trim($unsupportedfilename)]->cmid;
                        $record->fileid = $sentfiles[trim($unsupportedfilename)]->fileid;
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

        $config = $this->check_assignment_config($eventdata);
        if (get_config('plagiarism', 'safeassign_use') & !empty($config) && $config['safeassign_enabled']) {
            $fs = get_file_storage();
            $usercontext = context_user::instance($eventdata['userid']);
            $oldfile = $fs->get_file($usercontext->id, 'assignsubmission_text_as_file', 'submission_text_files',
                $eventdata['other']['submissionid'], '/',
                'userid_' . $eventdata['userid'] . '_text_submissionid_' . $eventdata['other']['submissionid'] .'.html');
            if ($oldfile) {
                $oldfile->delete();
            }

            $assignsubmission = $DB->get_record('assignsubmission_onlinetext', array('id' => $eventdata['objectid']));
            if ($assignsubmission) {
                $filecontent = $assignsubmission->onlinetext;
                $onlinetexttype = $assignsubmission->onlineformat;
                // Markdown is the only format that needs to be converted.
                if ($onlinetexttype == FORMAT_MARKDOWN) {
                    $filecontent = markdown_to_html($filecontent);
                }
                if (is_purify_html_necessary($filecontent)) {
                    $filecontent = purify_html($filecontent);
                }
                // Adds html open and closing tags to be a valid html file.
                $filecontent = "<html>" . $filecontent . "</html>";
                $fs = get_file_storage();
                // Create a new one.
                $filerecord = array(
                    'contextid' => $usercontext->id,
                    'component' => 'assignsubmission_text_as_file',
                    'filearea' => 'submission_text_files',
                    'itemid' => $eventdata['other']['submissionid'],
                    'filepath' => '/',
                    'filename' => 'userid_' . $eventdata['userid'] .
                        '_text_submissionid_' . $eventdata['other']['submissionid'] .'.html',
                    'userid' => $eventdata['userid']
                );
                $fs->create_file_from_string($filerecord, $filecontent);
            }
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
     * Deletes all deprecated submissions from the SafeAssign server.
     */
    public function delete_submissions() {
        global $DB, $CFG;
        $sql = "SELECT sa_subm.id, sa_subm.uuid, sa_course.instructorid
                FROM {plagiarism_safeassign_subm} sa_subm
                JOIN {plagiarism_safeassign_assign} sa_assign ON sa_assign.assignmentid = sa_subm.assignmentid
                JOIN {plagiarism_safeassign_course} sa_course ON sa_assign.courseid = sa_course.courseid
                WHERE sa_subm.uuid IS NOT NULL
                AND sa_subm.deprecated = 1
                AND sa_subm.deleted = 0";
        $submissions = $DB->get_records_sql($sql, array());
        $count = 0;
        $baseurl = get_config('plagiarism_safeassign', 'safeassign_api');
        foreach ($submissions as $submission) {
            $response = safeassign_api::delete_submission($submission->uuid, $submission->instructorid);
            if ($response) {
                $count ++;
                $DB->set_field('plagiarism_safeassign_subm', 'deleted', 1, array('uuid' => $submission->uuid));

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
            $select = "SELECT id
                         FROM {role}
                        WHERE archetype = ? OR archetype = ?";
            $params = ["editingteacher", "manager"];
            $editingroles = $DB->get_fieldset_sql($select, $params);

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
                list($sql5, $params) = $DB->get_in_or_equal(explode(",", $additionalroles));
                $plusroles = $DB->get_records_sql($select . $sql5, $params);
                $context = context_system::instance();
                foreach ($plusroles as $plusrole) {
                    foreach (get_users_from_role_on_context($plusrole, $context) as $user) {
                        $plususers[] = $user;
                    }
                }
            }
            if ($users || $plususers) {
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
                    WHERE archetype = ? OR archetype = ?";
        $params = ['editingteacher', 'manager'];
        $editingroles = $DB->get_fieldset_sql($select, $params);
        $systemcontext = context_system::instance();
        $additionalroles = explode(',', get_config('plagiarism_safeassign', 'safeassign_additional_roles'));
        if (($DB->record_exists('plagiarism_safeassign_course', array('courseid' => $data['courseid'])) &&
            in_array($data['objectid'], $editingroles) && !empty($editingroles)) ||
            ($data['contextid'] == $systemcontext->id) && in_array($data['objectid'], $additionalroles)) {

            if ($eventtype === 'create') {
                if ($systemcontext->id == $data['contextid']) {
                    // Process system level enrollment.
                    $role = $DB->get_record('role', array('id' => $data['objectid']));
                    $sql = 'SELECT sa_course.courseid
                              FROM {plagiarism_safeassign_course} sa_course
                             WHERE sa_course.courseid NOT IN (SELECT courseid
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
                // Update course table if necessary.
                $instructorid = $data['relateduserid'];
                $courseid = $data['courseid'];
                $records = $DB->get_records('plagiarism_safeassign_course',
                    ['courseid' => $courseid, "instructorid" => $instructorid]);
                if ($records) {
                    // Get next instructor of course.
                    $sql = 'SELECT MAX(instructorid) as instructorid
                              FROM {plagiarism_safeassign_instr}
                             WHERE courseid = ?
                               AND unenrolled = 0
                               AND deleted = 0
                          GROUP BY instructorid';
                    $newinstructor = $DB->get_records_sql($sql, [$courseid]);
                    if ($newinstructor) {
                        foreach ($newinstructor as $inst) {
                            $sql = 'UPDATE {plagiarism_safeassign_course}
                                   SET uuid = ?, instructorid = ?
                                 WHERE courseid = ?';
                            $DB->execute($sql, [null, $inst->instructorid, $courseid]);
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
        if (!empty($courses)) {
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
     * @param string $additionalroles - Gotten from the config_plugins table which contains the user roles to sync.
     * @param string $syncedroles - Value from config_plugins that indicates the additional synced roles.
     */
    public function set_additional_role_users($additionalroles, $syncedroles) {
        global $DB;

        $additionalroles = $additionalroles ? explode(',', $additionalroles) : [];
        $syncedroles = $syncedroles ? explode(',', $syncedroles) : [];

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
                    WHERE archetype = ? OR archetype = ?';
                $params = ['editingteacher', 'manager'];
                $editingroles = $DB->get_fieldset_sql($select, $params);
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
     * Removes a submission from a course assignment.
     * @param $data
     */
    public function remove_submission($data) {
        global $DB;
        $assignid = $data['other']['assignid'];
        $userid = $data['relateduserid'];

        // Check if the submission was already being synced with SafeAssign.
        $sql = "SELECT subm.id
                  FROM {assign_submission} as subm
                  JOIN {plagiarism_safeassign_subm} as sasubm ON subm.id = sasubm.submissionid
                 WHERE subm.assignment = $assignid AND subm.userid = $userid";

        $sasubmid = $DB->get_record_sql($sql, ['assignid' => $assignid, 'userid' => $userid]);

        if ($sasubmid) {
            $DB->set_field('plagiarism_safeassign_subm', 'deprecated', 1, ['submissionid' => $sasubmid->id]);
            $this->delete_submissions();
        }
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
        $sql = 'UPDATE {plagiarism_safeassign_subm}
                   SET deprecated = 1
                 WHERE submissionid IN (
                       SELECT asub.id
                         FROM {assign_submission} asub
                         JOIN {assign} a ON a.id = asub.assignment
                        WHERE asub.status = ?
                          AND a.course = ?)';
        $DB->execute($sql, ['submitted', $course->id]);
        // Set instructor records as unenrolled, so scheduled tasks won't bother to send any info.
        $sql = 'UPDATE {plagiarism_safeassign_instr}
                   SET unenrolled = 1
                 WHERE courseid = ?';
        $DB->execute($sql, array($course->id));

        if ($DB->record_exists('plagiarism_safeassign_course', array('courseid' => $course->id, 'uuid' => null))) {
            $DB->delete_records('plagiarism_safeassign_course', array('courseid' => $course->id));
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
    $moduleid = $DB->get_field('modules', 'id', array('name' => 'assign'));
    if ($DB->record_exists('plagiarism_safeassign_assign', array('assignmentid' => $cm->instance)) && $cm->module === $moduleid) {
        $sql = 'UPDATE {plagiarism_safeassign_subm}
                   SET deprecated = 1
                 WHERE submissionid IN (
                       SELECT asub.id
                         FROM {assign_submission} asub
                        WHERE asub.status = ?
                          AND asub.assignment = ?)';
        $DB->execute($sql, ["submitted", $cm->instance]);
    }
}
