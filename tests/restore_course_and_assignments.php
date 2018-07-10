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
 * Unit tests for restore courses with SafeAssign.
 *
 * @package    plagiarism_safeassign
 * @author     Juan Ibarra
 * @copyright  Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once(__DIR__.'/base.php');
require_once($CFG->dirroot . '/mod/assign/tests/base_test.php');
require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');
require_once($CFG->dirroot . '/plagiarism/safeassign/backup/moodle2/restore_plagiarism_safeassign_plugin.class.php');

/**
 * Test restoring courses with assignments using SafeAssign.
 * @copyright  Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plagiarism_safeassign_restore_course_testcase extends plagiarism_safeassign_base_testcase {

    /** @var stdClass $course New course created to hold the assignment activity. */
    private $course;

    /** @var boolean GLOBALCHECK. */
    const GLOBALCHECK = 1;

    public function test_restore_to_existing_course() {
        global $DB, $USER;
        $this->resetAfterTest(true);
        set_config('safeassign_use', 1, 'plagiarism');
        $this->setAdminUser();
        $this->create_test_data();

        // Check that new course and assignment is in SafeAssign tables.
        $sacourses = $DB->count_records('plagiarism_safeassign_course');
        $saassignments = $DB->count_records('plagiarism_safeassign_assign');
        $this->assertEquals(1, $sacourses);
        $this->assertEquals(1, $saassignments);

        // Backup course.
        $backup = $this->backup_course($this->course->id, $USER->id);

        // Restore course to existing course.
        $this->restore_course($backup['id'], $this->course->id, $USER->id);

        // Simulate restoration of assignment activity.
        $plugintype = 'plagiarism';
        $pluginname = 'safeassign';
        $info = new stdClass();
        $info->modulename = 'assign';
        $info->moduleid = 342001;

        $task = new restore_assign_activity_task('name', $info);
        $task->set_moduleid(342001);

        $step = new restore_module_structure_step('module_info', $backup['destination'], $task);

        $restoreplag = new \restore_plagiarism_safeassign_plugin($plugintype, $pluginname, $step);
        $restoreplag->after_restore_module();

        // Check that the new restored course and assignment is in SafeAssign tables.
        $sacourses = $DB->count_records('plagiarism_safeassign_course');
        $saassignments = $DB->count_records('plagiarism_safeassign_assign');
        $this->assertEquals(1, $sacourses);
        $this->assertEquals(2, $saassignments);
    }

    public function test_restore_to_new_course() {
        global $DB, $USER;
        $this->resetAfterTest(true);
        set_config('safeassign_use', 1, 'plagiarism');
        $this->setAdminUser();
        $this->create_test_data();

        // Check that new course and assignment is in SafeAssign tables.
        $sacourses = $DB->count_records('plagiarism_safeassign_course');
        $saassignments = $DB->count_records('plagiarism_safeassign_assign');
        $this->assertEquals(1, $sacourses);
        $this->assertEquals(1, $saassignments);

        // Backup course.
        $backup = $this->backup_course($this->course->id, $USER->id);

        // Restore course to new course.
        $this->restore_course($backup['id'], 0, $USER->id);

        // Simulate restoration of assignment activity.
        $plugintype = 'plagiarism';
        $pluginname = 'safeassign';
        $info = new stdClass();
        $info->modulename = 'assign';
        $info->moduleid = 342001;

        $task = new restore_assign_activity_task('name', $info);
        $task->set_moduleid(342001);

        $step = new restore_module_structure_step('module_info', $backup['destination'], $task);

        $restoreplag = new \restore_plagiarism_safeassign_plugin($plugintype, $pluginname, $step);
        $restoreplag->after_restore_module();

        // Check that the new restored course and assignment is in SafeAssign tables.
        $sacourses = $DB->count_records('plagiarism_safeassign_course');
        $saassignments = $DB->count_records('plagiarism_safeassign_assign');
        $this->assertEquals(2, $sacourses);
        $this->assertEquals(2, $saassignments);
    }

    /**
     * Backup a course and return its backup ID.
     *
     * @param int $courseid The course ID.
     * @param int $userid The user doing the backup.
     * @return string
     */
    protected function backup_course($courseid, $userid) {
        globaL $CFG;
        $packer = get_file_packer('application/vnd.moodle.backup');

        $bc = new backup_controller(backup::TYPE_1COURSE, $courseid, backup::FORMAT_MOODLE, backup::INTERACTIVE_NO,
            backup::MODE_GENERAL, $userid);
        $bc->execute_plan();

        $results = $bc->get_results();
        $results['backup_destination']->extract_to_pathname($packer, "$CFG->tempdir/backup/core_course_testcase");

        $bc->destroy();
        unset($bc);
        return array('destination' => $results['backup_destination'], 'id' => 'core_course_testcase');
    }

    /**
     * Restore a course.
     *
     * @param int $backupid The backup ID.
     * @param int $courseid The course ID to restore in, or 0.
     * @param int $userid The ID of the user performing the restore.
     * @return stdClass The updated course object.
     */
    protected function restore_course($backupid, $courseid, $userid) {
        global $DB;

        $target = backup::TARGET_CURRENT_ADDING;
        if (!$courseid) {
            $target = backup::TARGET_NEW_COURSE;
            $categoryid = $DB->get_field_sql("SELECT MIN(id) FROM {course_categories}");
            $courseid = restore_dbops::create_new_course('Tmp', 'tmp', $categoryid);
        }

        $rc = new restore_controller($backupid, $courseid, backup::INTERACTIVE_NO, backup::MODE_GENERAL, $userid, $target);
        $target == backup::TARGET_NEW_COURSE ?: $rc->get_plan()->get_setting('overwrite_conf')->set_value(true);
        $this->assertTrue($rc->execute_precheck());
        $rc->execute_plan();

        $course = $DB->get_record('course', array('id' => $rc->get_courseid()));

        $rc->destroy();
        unset($rc);
        return $course;
    }

    protected function create_test_data() {
        global $DB;
        // Generate course.
        $this->course = $this->getDataGenerator()->create_course();
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $params['course'] = $this->course->id;
        $this->instance = $generator->create_instance($params);
        $this->cm = get_coursemodule_from_instance('assign', $this->instance->id);
        $this->context = context_module::instance($this->cm->id);
        $this->assign = new testable_assign($this->context, $this->cm, $this->course);

        // Enable SafeAssign in the assignment.
        $record = new stdClass();
        $record->cm = $this->cm->id;
        $record->name = 'safeassign_enabled';
        $record->value = 1;
        $DB->insert_record('plagiarism_safeassign_config', $record);
        $record->name = 'safeassign_global_reference';
        $record->value = self::GLOBALCHECK;
        $DB->insert_record('plagiarism_safeassign_config', $record);

        $data = new stdClass();
        $data->coursemodule = $this->cm->id;
        $data->safeassign_enabled = 1;
        $data->course = $this->course->id;
        $data->instance = $this->instance->id;
        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->save_form_elements($data);
    }
}