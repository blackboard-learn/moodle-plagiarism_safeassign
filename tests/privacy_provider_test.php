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
 * All tests in this class will fail in case there is no appropriate fixture to be loaded.
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once(__DIR__.'/base.php');
require_once($CFG->dirroot . '/plagiarism/safeassign/tests/base.php');
require_once($CFG->dirroot . '/plagiarism/safeassign/tests/safeassign_api_test.php');
require_once($CFG->dirroot . '/plagiarism/safeassign/classes/observer.php');
require_once($CFG->dirroot . '/mod/assign/tests/base_test.php');

use core_privacy\tests\provider_testcase;
use core_privacy\local\request\writer;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\transform;
use plagiarism_safeassign\privacy\provider;

/**
 * Class plagiarism_safeassign_safeassign_api_testcase
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plagiarism_safeassign_privacy_provider_testcase extends provider_testcase {

    public function setUp() {
        $this->resetAfterTest(true);
        // Enable SafeAssign in the platform.
        set_config('enabled', 1, 'plagiarism_safeassign');
    }

    private function make_teacher_enrolment($teacher, $course) {
        global $DB;

        $editingteacherrole = $DB->get_record('role', array('shortname' => 'editingteacher'));
        $this->getDataGenerator()->enrol_user($teacher->id, $course->id, $editingteacherrole->id);

        return [$course, $teacher];
    }

    private function create_assignment($course) {
        // Create an assignment.
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $instance = $generator->create_instance(array('course' => $course->id));
        $cm = get_coursemodule_from_instance('assign', $instance->id);
        $context = context_module::instance($cm->id);

        $assign = new testable_assign($context, $cm, $course);
        // Create an activity with SafeAssign enabled.
        $data = new stdClass();
        $data->coursemodule = $cm->id;
        $data->safeassign_enabled = 1;
        $data->course = $course->id;
        $data->instance = $instance->id;
        $safeassign = new plagiarism_plugin_safeassign();
        plagiarism_safeassign_coursemodule_edit_post_actions($data);

        return $assign;
    }

    private function make_onlinetext_submission($student, $assign) {
        global $DB;

        $data = new stdClass();
        $data->onlinetext_editor = array(
            'itemid' => file_get_unused_draft_itemid(),
            'text'   => 'Submission text',
            'format' => FORMAT_PLAIN
        );

        $submission = $assign->get_user_submission($student->id, true);
        $context = $assign->get_context();

        $plugin = $assign->get_submission_plugin_by_type('onlinetext');
        $sink = $this->redirectEvents();
        $plugin->save($submission, $data);
        $events = $sink->get_events();
        $sink->clear();
        $event = $events[1];
        // Submission is processed by the event observer class.
        plagiarism_safeassign_observer::assignsubmission_onlinetext_created($event);

        $filename = 'userid_' . $student->id . '_text_submissionid_' . $submission->id . '.html';
        $fi = $DB->get_record('files', array('filename' => $filename));

        $record = new stdClass();
        $record->cm = $context->instanceid;
        $record->userid = $student->id;
        $record->reporturl = '';
        $record->similarityscore = 0.50;
        $record->timesubmitted = time();
        $record->supported = 1;
        $record->submissionid = $submission->id;
        $record->fileid = $fi->id;
        $DB->insert_record('plagiarism_safeassign_files', $record, true);
        return [$record, $submission];
    }

    private function make_file_submission($student, $assign) {
        global $DB;

        $submission = $assign->get_user_submission($student->id, true);

        $context = $assign->get_context();

        $fs = get_file_storage();
        $dummy = (object) array(
            'contextid' => $context->id,
            'component' => 'assignsubmission_file',
            'filearea' => ASSIGNSUBMISSION_FILE_FILEAREA,
            'itemid' => $submission->id,
            'filepath' => '/',
            'filename' => 'myassignmnent.pdf'
        );

        $fi = $fs->create_file_from_string($dummy, 'Content of ' . $dummy->filename);

        $data = new stdClass();
        $plugin = $assign->get_submission_plugin_by_type('file');
        $sink = $this->redirectEvents();
        $plugin->save($submission, $data);
        $events = $sink->get_events();
        $event = reset($events);
        $this->setUser($student->id);
        // Submission is processed by the event observer class.
        plagiarism_safeassign_observer::assignsubmission_file_uploaded($event);

        $record = new stdClass();
        $record->cm = $context->instanceid;
        $record->userid = $student->id;
        $record->reporturl = '';
        $record->similarityscore = 0.50;
        $record->timesubmitted = time();
        $record->supported = 1;
        $record->submissionid = $submission->id;
        $record->fileid = $fi->get_id();
        $DB->insert_record('plagiarism_safeassign_files', $record, true);
        return [$record, $submission];
    }

    private function validate_file($file, $result) {
        $this->assertEquals($file->cm, $result->cm);
        $this->assertEquals($file->userid, $result->userid);
        $this->assertEquals($file->reporturl, $result->reporturl);
        $this->assertEquals($file->timesubmitted, $result->timesubmitted);
        $this->assertEquals($file->supported, $result->supported);
        $this->assertEquals($file->fileid, $result->fileid);
    }

    public function test_get_contexts_for_userid() {

        $teacher = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();

        $this->make_teacher_enrolment($teacher, $course);

        $this->assertEmpty(provider::get_contexts_for_userid($teacher->id));
        $this->create_assignment($course);

        $contextlist = provider::get_contexts_for_userid($teacher->id);
        $this->assertCount(1, $contextlist);

        $coursecontext = \context_course::instance($course->id);
        $this->assertEquals($coursecontext->id, $contextlist->get_contextids()[0]);

    }

    public function test_get_users_in_context() {
        global $DB;

        $teacher1 = $this->getDataGenerator()->create_user();
        $teacher2 = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();
        $context = context_course::instance($course->id);
        $this->make_teacher_enrolment($teacher1, $course);
        $this->make_teacher_enrolment($teacher2, $course);
        $this->create_assignment($course);

        // Admin is included there.
        $this->assertCount(3, $DB->get_records('plagiarism_safeassign_instr'));
        $userlist = new \core_privacy\local\request\userlist($context, 'core_course');
        provider::get_users_in_context($userlist);

        $this->assertCount(3, $userlist->get_userids());

        $course2 = $this->getDataGenerator()->create_course();
        $context2 = context_course::instance($course2->id);

        $userlist2 = new \core_privacy\local\request\userlist($context2, 'core_course');
        provider::get_users_in_context($userlist2);
        $this->assertCount(0, $userlist2->get_userids());
    }

    public function test_export_user_data() {
        global $DB;

        $teacher = $this->getDataGenerator()->create_user();
        $course1 = $this->getDataGenerator()->create_course();

        $this->create_assignment($course1);
        $this->make_teacher_enrolment($teacher, $course1);

        // Create another course with an assignment.
        $course2 = $this->getDataGenerator()->create_course();

        $this->create_assignment($course2);
        $this->make_teacher_enrolment($teacher, $course2);

        $course1context = \context_course::instance($course1->id);
        $course2context = \context_course::instance($course2->id);
        $usercontext = \context_user::instance($teacher->id);
        $writer = writer::with_context($usercontext);
        self::assertFalse($writer->has_any_data());

        $approvedlist = new approved_contextlist($teacher, 'plagiarism_safeassign', [$course1context->id,
            $course2context->id]);
        provider::export_user_data($approvedlist);

        $data = $writer->get_data(['plagiarism_safeassign', 'instructor'])->courses;
        $registers = $DB->get_records('plagiarism_safeassign_instr', array('instructorid' => $teacher->id));

        $i = 0;
        foreach ($registers as $key => $register) {
            $this->assertEquals($teacher->id, $data[$i]->instructorid);
            $this->assertEquals(get_course($register->courseid)->fullname, $data[$i]->course);
            $this->assertEquals(transform::yesno($register->synced), $data[$i]->synced);
            $this->assertEquals(transform::yesno($register->unenrolled), $data[$i]->unenrolled);
            $i++;
        }
    }

    public function test_delete_data_for_users() {
        global $DB;

        $teacher1 = $this->getDataGenerator()->create_user();
        $teacher2 = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();
        $context = context_course::instance($course->id);
        $this->make_teacher_enrolment($teacher1, $course);
        $this->make_teacher_enrolment($teacher2, $course);
        $this->create_assignment($course);

        // Admin is included there.
        $this->assertCount(3, $DB->get_records('plagiarism_safeassign_instr'));
        $approveduserlist = new \core_privacy\local\request\approved_userlist($context, 'core_course',
            [$teacher1->id]);
        provider::delete_data_for_users($approveduserlist);

        $this->assertCount(1, $DB->get_records('plagiarism_safeassign_instr', ['unenrolled' => 1,
            'instructorid' => $teacher1->id]));

    }

    public function test_delete_data_for_all_users_in_context() {
        global $DB;

        $teacher1 = $this->getDataGenerator()->create_user();
        $teacher2 = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();

        $this->make_teacher_enrolment($teacher1, $course);
        $this->make_teacher_enrolment($teacher2, $course);
        $this->create_assignment($course);

        $this->assertEquals(1, $DB->count_records('plagiarism_safeassign_instr', array('instructorid' => $teacher1->id,
            'courseid' => $course->id, 'synced' => 0)));
        $this->assertEquals(1, $DB->count_records('plagiarism_safeassign_instr', array('instructorid' => $teacher2->id,
            'courseid' => $course->id, 'synced' => 0)));
        $record = $DB->get_record('plagiarism_safeassign_instr', array('instructorid' => $teacher1->id,
            'courseid' => $course->id));
        $this->assertEmpty($record->unenrolled);

        // Simulate sync task.
        $DB->set_field('plagiarism_safeassign_instr', 'synced', 1, ['synced' => 0]);

        $coursecontext = \context_course::instance($course->id);
        provider::delete_data_for_all_users_in_context($coursecontext);

        $this->assertEquals(1, $DB->count_records('plagiarism_safeassign_instr', array('instructorid' => $teacher1->id,
            'courseid' => $course->id, 'synced' => 1)));
        $this->assertEquals(1, $DB->count_records('plagiarism_safeassign_instr', array('instructorid' => $teacher2->id,
            'courseid' => $course->id, 'synced' => 1)));

        // Simulate delete_instructors task.
        $DB->set_field('plagiarism_safeassign_instr', 'deleted', 1, ['deleted' => 0]);

        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->delete_instructors_records();

        $this->assertEquals(0, $DB->count_records('plagiarism_safeassign_instr', array('instructorid' => $teacher1->id,
            'courseid' => $course->id)));
        $this->assertEquals(0, $DB->count_records('plagiarism_safeassign_instr', array('instructorid' => $teacher2->id,
            'courseid' => $course->id)));

    }

    public function test_delete_data_for_user() {
        global $DB;

        $teacher = $this->getDataGenerator()->create_user();
        $course1 = $this->getDataGenerator()->create_course();

        $this->create_assignment($course1);
        $this->make_teacher_enrolment($teacher, $course1);

        // Create another course with an assignment.
        $course2 = $this->getDataGenerator()->create_course();

        $this->create_assignment($course2);
        $this->make_teacher_enrolment($teacher, $course2);

        $this->assertEquals(2, $DB->count_records('plagiarism_safeassign_instr', array('instructorid' => $teacher->id,
            'synced' => 0)));

        // Simulate sync task.
        $DB->set_field('plagiarism_safeassign_instr', 'synced', 1, ['synced' => 0]);

        $course1context = \context_course::instance($course1->id);
        $course2context = \context_course::instance($course2->id);
        $approvedlist = new approved_contextlist($teacher, 'plagiarism_safeassign', [$course1context->id,
            $course2context->id]);

        provider::delete_data_for_user($approvedlist);

        // Simulate delete_instructors task.
        $DB->set_field('plagiarism_safeassign_instr', 'deleted', 1, ['deleted' => 0]);

        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->delete_instructors_records();

        $this->assertEquals(0, $DB->count_records('plagiarism_safeassign_instr', array('instructorid' => $teacher->id,
            'courseid' => $course1->id)));
        $this->assertEquals(0, $DB->count_records('plagiarism_safeassign_instr', array('instructorid' => $teacher->id,
            'courseid' => $course2->id)));
    }

    public function test_export_plagiarism_user_data() {
        global $DB;
        $course1 = $this->getDataGenerator()->create_course();
        $course2 = $this->getDataGenerator()->create_course();
        $student = $this->getDataGenerator()->create_user();
        $assignment1 = $this->create_assignment($course1);
        $assignment2 = $this->create_assignment($course2);
        $this->setUser($student);
        // Do a submission with an online text.
        list($file1, $submission1) = $this->make_onlinetext_submission($student, $assignment1);
        // Do a submission with a file.
        list($file2, $submission2)  = $this->make_file_submission($student, $assignment2);

        $this->assertEquals(2, $DB->count_records('plagiarism_safeassign_subm', array()));

        // Retrieve information in course one.
        $course1context = \context_course::instance($course1->id);
        $course2context = \context_course::instance($course2->id);

        $writer = writer::with_context($course1context);
        self::assertFalse($writer->has_any_data());

        provider::export_plagiarism_user_data($student->id, $assignment1->get_context(), ['test'], array());
        $submissions = $writer->get_related_data(['test'], 'safeassign-submissions')->submissions;
        $submission = reset($submissions);
        $this->assertEquals($submission1->id, $submission->submissionid);
        $files = $writer->get_related_data(['test'], 'safeassign-files')->files;
        $this->validate_file($file1, reset($files));

        $writer = writer::with_context($course2context);
        provider::export_plagiarism_user_data($student->id, $assignment2->get_context(), ['test'], array('file' => 'file'));
        $submissions = $writer->get_related_data(['test'], 'safeassign-submissions')->submissions;
        $submission = reset($submissions);
        $this->assertEquals($submission2->id, $submission->submissionid);
        $files = $writer->get_related_data(['test'], 'safeassign-files')->files;
        $this->validate_file($file2, reset($files));
    }

    public function test_delete_plagiarism_for_context() {
        global $DB;
        $course1 = $this->getDataGenerator()->create_course();
        $course2 = $this->getDataGenerator()->create_course();
        $student1 = $this->getDataGenerator()->create_user();
        $student2 = $this->getDataGenerator()->create_user();
        $assignment1 = $this->create_assignment($course1);
        $assignment2 = $this->create_assignment($course2);

        // Do several submissions.
        $this->make_file_submission($student1, $assignment1);
        $this->make_file_submission($student2, $assignment1);
        $this->make_file_submission($student2, $assignment2);

        $this->assertEquals(1, $DB->count_records('plagiarism_safeassign_files', array('userid' => $student1->id)));
        $this->assertEquals(2, $DB->count_records('plagiarism_safeassign_files', array('userid' => $student2->id)));

        // Delete information in assignment one.
        provider::delete_plagiarism_for_context($assignment1->get_context());

        $this->assertEquals(0, $DB->count_records('plagiarism_safeassign_files', array('userid' => $student1->id)));
        $this->assertEquals(1, $DB->count_records('plagiarism_safeassign_files', array('userid' => $student2->id)));
    }

    public function test_delete_plagiarism_for_user() {
        global $DB;
        $course1 = $this->getDataGenerator()->create_course();
        $course2 = $this->getDataGenerator()->create_course();
        $student1 = $this->getDataGenerator()->create_user();
        $student2 = $this->getDataGenerator()->create_user();
        $assignment1 = $this->create_assignment($course1);
        $assignment2 = $this->create_assignment($course2);

        // Do several submissions.
        $this->make_file_submission($student1, $assignment1);
        $this->make_file_submission($student2, $assignment1);
        $this->make_file_submission($student1, $assignment2);

        $this->assertEquals(2, $DB->count_records('plagiarism_safeassign_files', array('userid' => $student1->id)));
        $this->assertEquals(1, $DB->count_records('plagiarism_safeassign_files', array('userid' => $student2->id)));

        // Delete information of assignment one for student one.
        provider::delete_plagiarism_for_user($student1->id, $assignment1->get_context());

        $this->assertEquals(1, $DB->count_records('plagiarism_safeassign_files', array('userid' => $student1->id)));
        $this->assertEquals(1, $DB->count_records('plagiarism_safeassign_files', array('userid' => $student2->id)));

        // Delete information of assignment two for student one.
        provider::delete_plagiarism_for_user($student1->id, $assignment2->get_context());

        $this->assertEquals(0, $DB->count_records('plagiarism_safeassign_files', array('userid' => $student1->id)));
        $this->assertEquals(1, $DB->count_records('plagiarism_safeassign_files', array('userid' => $student2->id)));
    }

}
