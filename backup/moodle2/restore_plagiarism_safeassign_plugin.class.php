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
 * Restore class for the SafeAssign plugin.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use plagiarism_safeassign\event\sync_content_log;

/**
 * Class restore_plagiarism_safeassign_plugin
 * @copyright Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_plagiarism_safeassign_plugin extends restore_plagiarism_plugin {

    /**
     * {@inheritdoc}
     * Return the paths of the course data along with the function used for restoring that data.
     */
    protected function define_course_plugin_structure() {
        $paths = array();
        $paths[] = new restore_path_element('safeassign_course', $this->get_pathfor('safeassign_courses/safeassign_course'));

        return $paths;
    }

    /**
     * {@inheritdoc}
     * Return the paths of the module data along with the function used for restoring that data.
     */
    protected function define_module_plugin_structure() {
        $paths = array();
        $paths[] = new restore_path_element('safeassign_config', $this->get_pathfor('/safeassign_configs/safeassign_config'));
        $paths[] = new restore_path_element('safeassign_files', $this->get_pathfor('/safeassign_files/safeassign_file'));

        return $paths;
    }

    /**
     * Restore the SafeAssign assignment id for this module
     * This will only be done this if the module is from the same site it was backed up from
     * and if the SafeAssign assignment id does not currently exist in the database.
     * @param stdClass $data
     */
    public function process_safeassign_config($data) {
        global $DB;
        if ($this->task->is_samesite()) {
            $data = (object)$data;
            $cmid = $this->task->get_moduleid();
            $data->cm = $cmid;
            $DB->insert_record('plagiarism_safeassign_config', $data);
        }
    }

    /**
     * {@inheritdoc}
     * Restore the SafeAssignment assignment record on table plagiarism_safeassign_assign after being restored
     * @throws moodle_exception
     */
    public function after_restore_module() {
        global $DB, $USER;

        $supportedmodules = ['assign'];
        $modulename = $this->task->get_modulename();
        if (in_array($modulename, $supportedmodules)) {
            $cmid = $this->task->get_moduleid();
            list($course, $cm) = get_course_and_cm_from_cmid($cmid, $modulename);
            // Create the data being restored from the course and cm from SafeAssign assign.
            $data = new stdClass();
            $data->assignmentid = $cm->instance;
            $data->courseid = $course->id;
            $DB->insert_record('plagiarism_safeassign_assign', $data);

            $params = array(
                'courseid'     => $course->id
            );

            if (!$DB->record_exists('plagiarism_safeassign_course', $params)) {
                // Create the data for the course being restored on SafeAssign table.
                $data = new stdClass();
                $data->courseid = $course->id;
                $data->instructorid = $USER->id;

                $DB->insert_record('plagiarism_safeassign_course', $data);

                $safeassign = new \plagiarism_plugin_safeassign();
                $safeassign->set_course_instructors($course->id);
            }

            // We check for records created in restoring step.
            $files = $DB->get_records('plagiarism_safeassign_files', ['reporturl' => 'restoring']);
            foreach ($files as $file) {
                // We need to search for the submission id created for the new/restored course.
                $submission = $this->get_proper_submission($cm->instance, $file->userid);
                $submissionid = !empty($submission) ? $submission : $file->submissionid;
                if (!empty($submissionid)) {
                    // Find ID of the copied file.
                    $fileid = $this->get_proper_file($submissionid, $file->userid);
                    $fileid = !empty($fileid) ? $fileid : $file->fileid;
                    $changes = [
                        'id' => $file->id,
                        'reporturl' => null,
                        'submissionid' => $submissionid,
                        'fileid' => $fileid
                    ];
                    // Update plagiarism_safeassign_files table.
                    $DB->update_record('plagiarism_safeassign_files', $changes);

                    // We search for old safe assign submissions.
                    $submissions = $DB->get_records('plagiarism_safeassign_subm', [
                        'submissionid' => $file->submissionid
                    ]);
                    foreach ($submissions as $submission) {
                        $submission->submissionid = $submissionid;
                        $submission->assignmentid = $cm->instance;
                        // A new record in plagiarism_safeassign_subm table is created.
                        $DB->insert_record('plagiarism_safeassign_subm', $submission);
                    }

                }
            }
        }
    }

    /**
     * {@inheritdoc}
     * Restore the links to SafeAssign files.
     * This will only be done this if the module is from the same site it was backed up from
     * and if the SafeAssign submission does not currently exist in the database.
     * @param stdClass $data
     */
    public function process_safeassign_files($data) {
        global $DB;
        $runningphpunittest = defined('PHPUNIT_TEST') && PHPUNIT_TEST;
        $runningbehattest = defined('BEHAT_SITE_RUNNING') && BEHAT_SITE_RUNNING;
        $duringtesting = $runningphpunittest || $runningbehattest;

        if (!empty($duringtesting) || $this->task->is_samesite()) {
            $data = (object)$data;
            $cmid = $this->task->get_moduleid();
            $data->cm = $cmid;
            $data->reporturl = 'restoring';

            $DB->insert_record('plagiarism_safeassign_files', $data);
        }
    }

    /**
     * {@inheritdoc}
     * @param stdClass $data
     */
    public function process_safeassign_course($data) {

    }

    /**
     * @param $assignment
     * @param $userid
     * @return false|integer
     * @throws dml_exception
     */
    public function get_proper_submission($assignment, $userid) {
        global $DB;

        if (empty($assignment) || empty($userid)) {
            return false;
        }

        $params = [
            'assignment' => $assignment,
            'userid' => $userid
        ];
        $sql = "SELECT id
                  FROM {assign_submission}
                 WHERE assignment = :assignment
                   AND userid = :userid
              ORDER BY id DESC";
        $record = $DB->get_record_sql($sql, $params, IGNORE_MULTIPLE);

        return $record->id ?? false;
    }

    /**
     * @param $submissionid
     * @param $userid
     * @return false|integer
     * @throws dml_exception
     */
    public function get_proper_file($submissionid, $userid) {
        global $DB;

        if (empty($submissionid) || empty($userid)) {
            return false;
        }

        $params = [
            'itemid' => $submissionid,
            'userid' => $userid
        ];
        $sql = "SELECT id
                  FROM {files}
                 WHERE itemid = :itemid
                   AND userid = :userid
                   AND filesize > 0
              ORDER BY id";
        $record = $DB->get_record_sql($sql, $params, IGNORE_MULTIPLE);

        return $record->id ?? false;
    }
}