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
        return array();
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
    }

    /**
     * hook to save plagiarism specific settings on a module settings page
     * @param object $data - data from an mform submission.
     */
    public function save_form_elements($data) {
    }

    /**
     * hook to allow a disclosure to be printed notifying users what will happen with their submission
     * @param int $cmid - course module id
     * @return string
     */
    public function print_disclosure($cmid) {
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

        // Check for safeassign configuration.
        $plagiarismsettings = (array)get_config('plagiarism_safeassign');
        if (!$plagiarismsettings) {
            return false;
        }

        // Call the course saver.
        $this->safeassign_course_dbsaver($eventdata);

        // Let's check that the assignment does not exist previously on db.
        $instanceid = $eventdata['other']['instanceid'];
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
        $courseid = $eventdata['courseid'];
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
    public function create_submission($evendata) {
        if ($this->check_assignment_config($evendata)) {
            $this->update_old_submission_records($evendata);
            $params = array();
            if (!empty($evendata['other']['pathnamehashes'])) {
                $params['hasfile'] = 1;
            }
            if (!empty($evendata['other']['content'])) {
                $params['hasonlinetext'] = 1;
            }
            $this->validate_submission($evendata, $params);
        }
    }

    /**
     * Check that submissions are made for a valid SafeAssign assignment.
     * @param object $eventdata
     * @return bool
     */
    private function check_assignment_config($eventdata) {
        global $DB;

        $sql = "SELECT *
                  FROM {plagiarism_safeassign_assign} safe_assign
                  JOIN {assign_submission} assign_sub ON assign_sub.assignment = safe_assign.assignmentid
                 WHERE assign_sub.id = :submissionid";
        return $DB->record_exists_sql($sql, array('submissionid' => $eventdata['objectid']));
    }

    /**
     * Creates the submission record on plagiarism_safeassign_subm table.
     * @param object $eventdata
     */
    private function create_submission_record($eventdata, $params) {
        global $DB;
        $submission = new stdClass();
        $submission->submissionid = $eventdata['objectid'];
        $submission->globalcheck = 0;
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
