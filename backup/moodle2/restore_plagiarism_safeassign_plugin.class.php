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
 * @copyright Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use plagiarism_safeassign\event\sync_content_log;

/**
 * Class restore_plagiarism_safeassign_plugin
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
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
                'courseid'     => $course->id,
                'instructorid' => $USER->id
            );

            if (!$DB->record_exists('plagiarism_safeassign_course', $params)) {
                // Create the data for the course being restored on SafeAssign table.
                $data = new stdClass();
                $data->courseid = $course->id;
                $data->instructorid = $USER->id;

                $DB->insert_record('plagiarism_safeassign_course', $data);
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

    }

    /**
     * {@inheritdoc}
     * @param stdClass $data
     */
    public function process_safeassign_course($data) {

    }
}