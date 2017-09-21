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
     * Hook to allow plagiarism specific information to be displayed beside a submission.
     * @return string
     */
    public function get_links($linkarray) {
        return '';
    }

    /**
     * Hook to allow plagiarism specific information to be returned unformatted.
     * @param int $cmid
     * @param int $userid
     * @param $file file object
     * @return array containing at least:
     *   - 'analyzed' - whether the file has been successfully analyzed
     *   - 'score' - similarity score - ('' if not known)
     *   - 'reporturl' - url of originality report - '' if unavailable
     */
    public function get_file_results($cmid, $userid, $file) {
        global $DB;
        $analyzed = 0;
        $score = '';
        $reporturl = '';
        $filequery="SELECT sub.reportgenerated, fil.similarityscore, fil.reporturl
                       FROM {plagiarism_safeassign_subm} sub
                       JOIN {plagiarism_safeassign_files} fil ON sub.submissionid = fil.submissionid
                      WHERE fil.cm = ? AND fil.userid = ? AND fil.fileid = ?";
        $fileinfo = $DB->get_record_sql($filequery, array($cmid, $userid, $file->get_id()));
        if (!empty($fileinfo)) {
            $analyzed = $fileinfo->reportgenerated;
            $score = $fileinfo->similarityscore;
            $reporturl = $fileinfo->reporturl;
        }
        return array('analyzed' => $analyzed, 'score' => $score, 'reporturl' => $reporturl);
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
        if ($cmglobalref->value == 1) {
            return '';
        }
        $info = $DB->get_record('plagiarism_safeassign_config', array('cm' => $cmid, 'name' => $USER->id));
        if (!empty($info->value)) {
            $checked = true;
            $value = $info->value;
        }
        $col1 = html_writer::tag('div', get_string('plagiarism_tools', 'plagiarism_safeassign'), array('class' => 'col-md-2'));
        $checkbox = html_writer::checkbox('agreement', $value, $checked, get_string('agreement', 'plagiarism_safeassign'));
        $col2 = html_writer::tag('div', get_string('files_accepted', 'plagiarism_safeassign').'<br><br>'.$checkbox, array('class' => 'col-md-9'));
        $output = html_writer::tag('div', $col1.$col2, array('class' => 'row generalbox boxaligncenter intro'));
        $form = html_writer::tag('form',$output);
        $PAGE->requires->js_call_amd('plagiarism_safeassign/disclosure', 'init', array($cmid,$USER->id));
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
        global $DB;

        // Let's check that the course does not exist previously on db.
        $courseid = $eventdata->courseid;
        if (!$DB->record_exists('plagiarism_safeassign_course', ['courseid' => $courseid])) {
            // We have to set the object in safeassign_course table.
            $coursedata = new stdClass();
            $coursedata->uuid = null;
            $coursedata->courseid = $courseid;
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
                if (!empty($eventdata['other']['content'])) {
                    $params['hasonlinetext'] = 1;
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
        $submission = new stdClass();
        $submission->submissionid = $eventdata['objectid'];
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
        // Search the submission that are already in plagiarism_safeassign_subm.
        $sql = "UPDATE {plagiarism_safeassign_subm}
                   SET deprecated = 1
                 WHERE submissionid = :submissionid AND timecreated <> :timecreated";
        $DB->execute($sql, array('submissionid' => $eventdata['objectid'], 'timecreated' => $eventdata['timecreated']));
    }

    /**
     * Checks if submission already has a record on plagiarism_safeassign_subm for
     * not creating a duplicate record.
     * @param object $eventdata
     * @param array $params
     */
    private function validate_submission($eventdata, $params) {
        global $DB;
        $record = $DB->get_record('plagiarism_safeassign_subm', array('submissionid' => $eventdata['objectid'],
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
}