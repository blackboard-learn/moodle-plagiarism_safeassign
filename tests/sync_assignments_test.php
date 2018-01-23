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
 * Unit tests for sync functions.
 *
 * @package    plagiarism_safeassign
 * @author     Jonathan Garcia
 * @copyright  Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once(__DIR__.'/base.php');
require_once($CFG->dirroot . '/mod/assign/tests/base_test.php');
require_once($CFG->dirroot . '/plagiarism/safeassign/tests/base.php');
require_once($CFG->dirroot . '/plagiarism/safeassign/tests/safeassign_api_test.php');
use plagiarism_safeassign\api\testhelper;
use plagiarism_safeassign\task\sync_assignments;


/**
 * Test the sync functions.
 * @copyright  Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plagiarism_safeassign_sync_assignments_testcase extends plagiarism_safeassign_base_testcase {

    public function setUp() {
        global $DB, $USER;
        set_config('safeassign_use', 1, 'plagiarism');
        // Create a course and assignment and users.
        $this->course = self::getDataGenerator()->create_course();

        $this->teacher = self::getDataGenerator()->create_user([
            'firstname' => 'Teacher',
            'lastname' => 'WhoTeaches'
        ]);
        $teacherrole = $DB->get_record('role', array('shortname' => 'teacher'));
        $this->getDataGenerator()->enrol_user($this->teacher->id,
            $this->course->id,
            $teacherrole->id);
        $this->setUser($this->teacher);

        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $params['course'] = $this->course->id;
        $params['assignsubmission_onlinetext_enabled'] = 1;
        $params['assignsubmission_file_enabled'] = 1;
        $params['assignsubmission_file_maxfiles'] = 5;
        $params['assignsubmission_file_maxsizebytes'] = 1024 * 1024;
        $instance = $generator->create_instance($params);
        $this->assigninstance = $instance;
        $this->cm = get_coursemodule_from_instance('assign', $instance->id);
        $context = context_module::instance($this->cm->id);

        $assign = new assign($context, $this->cm, $this->course);
        $this->student1 = self::getDataGenerator()->create_user([
            'firstname' => 'Student1',
            'lastname' => 'WhoStudies'
        ]);
        $this->student2 = self::getDataGenerator()->create_user([
            'firstname' => 'Student2',
            'lastname' => 'WhoStudies'
        ]);
        $studentrole = $DB->get_record('role', array('shortname' => 'student'));
        $this->getDataGenerator()->enrol_user($this->student1->id,
            $this->course->id,
            $studentrole->id);
        $this->getDataGenerator()->enrol_user($this->student2->id,
            $this->course->id,
            $studentrole->id);
        // Create a student1 with an online text submission.
        // Simulate a submission.
        $this->setUser($this->student1);

        // Create a file in a draft area.
        $draftidfile = file_get_unused_draft_itemid();

        $usercontext = context_user::instance($this->student1->id);
        $filerecord = array(
            'contextid' => $usercontext->id,
            'component' => 'user',
            'filearea' => 'draft',
            'itemid' => $draftidfile,
            'filepath' => '/',
            'filename' => 'file1.txt',
            'userid' => $this->student1->id
        );

        $fs = get_file_storage();
        $fs->create_file_from_string($filerecord, 'text contents');

        $filerecord['filename'] = 'file2.txt';

        $fs = get_file_storage();
        $fs->create_file_from_string($filerecord, 'text contents');

        $filerecord['filename'] = 'file3.json';

        $fs = get_file_storage();
        $fs->create_file_from_string($filerecord, 'text contents');

        $filerecord['filename'] = 'file4.zip';

        $fs = get_file_storage();
        $fs->create_file_from_string($filerecord, 'text contents');

        $filerecord['filename'] = 'file5.csv';

        $fs = get_file_storage();
        $fs->create_file_from_string($filerecord, 'text contents');

        // Create another file in a different draft area.
        $draftidonlinetext = file_get_unused_draft_itemid();

        $filerecord = array(
            'contextid' => $usercontext->id,
            'component' => 'user',
            'filearea' => 'draft',
            'itemid' => $draftidonlinetext,
            'filepath' => '/',
            'filename' => 'shouldbeanimage.txt',
            'userid' => $this->student1->id
        );

        $fs->create_file_from_string($filerecord, 'image contents (not really)');

        // Now try a submission.
        $submissionpluginparams = array();
        $submissionpluginparams['files_filemanager'] = $draftidfile;
        $onlinetexteditorparams = array('text' => '<p>Yeeha!</p>',
            'format' => 1,
            'itemid' => $draftidonlinetext);
        $submissionpluginparams['onlinetext_editor'] = $onlinetexteditorparams;
        mod_assign_external::save_submission($instance->id, $submissionpluginparams);
        $this->student1submission = $DB->get_record('assign_submission', array('userid' => $this->student1->id));
        $DB->set_field('assign_submission', 'status', 'submitted', array('id' => $this->student1submission->id));
    }

    /**
     * Add login responses for several users.
     */
    private function push_login_urls() {
        global $DB;

        // Add successful login responses for all users, including the admin.
        $testhelper = new plagiarism_safeassign_safeassign_api_testcase();
        $testhelper->push_login_url($this->teacher, 'user-login-final.json');
        $testhelper->push_login_url($this->student1, 'user-login-final.json');
        $testhelper->push_login_url($this->student2, 'user-login-final.json');
        $administ = $DB->get_record('user', array('id' => 2));
        $testhelper->push_login_url($administ, 'user-login-final.json');
    }

    public function test_sync_just_course_ok() {
        global $DB;

        $this->resetAfterTest(true);
        $this->set_safeassign_records();

        $this->setAdminUser();
        $testhelper = new plagiarism_safeassign_safeassign_api_testcase();
        $this->config_set_ok();
        $this->push_login_urls();

        $task = new sync_assignments();
        $courseurl = $testhelper->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-fail-final.json');
        testhelper::push_pair($courseurl.'?id='.$this->course->id, 'create-course-fail-final.json');
        $task->execute();
        $course = $DB->get_record('plagiarism_safeassign_course', array('courseid' => $this->course->id));
        $this->assertNull($course->uuid);
        $assignment = $DB->get_record('plagiarism_safeassign_assign', array('assignmentid' => $this->assigninstance->id));
        $this->assertNull($assignment->uuid);
        $submission = $DB->get_record('assign_submission', array('userid' => $this->student1->id,
            'assignment' => $this->assigninstance->id));
        $syncedsubmission = $DB->get_record('plagiarism_safeassign_subm', array('submissionid' => $submission->id));
        $this->assertNull($syncedsubmission->uuid);
        $supportedfiles = $DB->get_records('plagiarism_safeassign_files', array('supported' => 1));
        $unsupportedfiles = $DB->get_records('plagiarism_safeassign_files', array('supported' => 0));
        $this->assertCount(0, $supportedfiles);
        $this->assertCount(0, $unsupportedfiles);

    }

    public function test_sync_course_ok_assignment_fail() {
        global $DB;
        $this->resetAfterTest(true);
        $this->set_safeassign_records();

        $this->setAdminUser();
        $testhelper = new plagiarism_safeassign_safeassign_api_testcase();
        $this->config_set_ok();
        $this->push_login_urls();

        $task = new sync_assignments();
        $courseurl = $testhelper->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        testhelper::push_pair($courseurl.'?id='.$this->course->id, 'create-course-final.json');
        $putdeleteinstructorurl = $testhelper->create_put_delete_instructor_url('123e4567-e89b-12d3-a456-426655440000');
        testhelper::push_pair($putdeleteinstructorurl, 'put-delete-instructor-ok.json');
        $assignmenturl = $testhelper->create_assignment_url('123e4567-e89b-12d3-a456-426655440000');
        testhelper::push_pair($assignmenturl, 'create-assignment-fail.json');
        $assignurl = $testhelper->create_assignment_url('123e4567-e89b-12d3-a456-426655440000', $this->assigninstance->id);
        testhelper::push_pair($assignurl . '?id=' . $this->assigninstance->id, "create-assignment-fail.json");
        $task->execute();

        $course = $DB->get_record('plagiarism_safeassign_course', array('courseid' => $this->course->id));
        $this->assertNotNull($course->uuid);
        $assignment = $DB->get_record('plagiarism_safeassign_assign', array('assignmentid' => $this->assigninstance->id));
        $this->assertNull($assignment->uuid);
        $submission = $DB->get_record('assign_submission', array('userid' => $this->student1->id,
            'assignment' => $this->assigninstance->id));
        $syncedsubmission = $DB->get_record('plagiarism_safeassign_subm', array('submissionid' => $submission->id));
        $this->assertNull($syncedsubmission->uuid);
        $supportedfiles = $DB->get_records('plagiarism_safeassign_files', array('supported' => 1));
        $unsupportedfiles = $DB->get_records('plagiarism_safeassign_files', array('supported' => 0));
        $this->assertCount(0, $supportedfiles);
        $this->assertCount(0, $unsupportedfiles);
    }

    public function test_sync_course_ok_assignment_ok_submission_fail() {
        global $DB;

        $this->resetAfterTest(true);
        $this->set_safeassign_records();

        $this->setAdminUser();
        $testhelper = new plagiarism_safeassign_safeassign_api_testcase();
        $this->config_set_ok();
        $this->push_login_urls();

        $task = new sync_assignments();
        $courseurl = $testhelper->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        testhelper::push_pair($courseurl.'?id='.$this->course->id, 'create-course-final.json');
        $putdeleteinstructorurl = $testhelper->create_put_delete_instructor_url('123e4567-e89b-12d3-a456-426655440000');
        testhelper::push_pair($putdeleteinstructorurl, 'put-delete-instructor-ok.json');
        $assignmenturl = $testhelper->create_assignment_url('123e4567-e89b-12d3-a456-426655440000');
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        // Make avaliable the check assignment url.
        $assignurl = $testhelper->create_assignment_url('123e4567-e89b-12d3-a456-426655440000', $this->assigninstance->id);
        testhelper::push_pair($assignurl . '?id=' . $this->assigninstance->id, "create-assignment-ok.json");
        // Make the submission url avaliable.
        $submissionurl = $testhelper->create_submission_url('123e4567-e89b-12d3-a456-426655440000',
            'c93e61c6-be1f-6c49-5c86-76d8f04f3f2f');
        testhelper::push_pair($submissionurl, 'create-submission-fail.json', 400);
        $task->execute();

        $course = $DB->get_record('plagiarism_safeassign_course', array('courseid' => $this->course->id));
        $this->assertNotNull($course->uuid);
        $assignment = $DB->get_record('plagiarism_safeassign_assign', array('assignmentid' => $this->assigninstance->id));
        $this->assertNotNull($assignment->uuid);
        $submission = $DB->get_record('assign_submission', array('userid' => $this->student1->id,
            'assignment' => $this->assigninstance->id));
        $syncedsubmission = $DB->get_record('plagiarism_safeassign_subm', array('submissionid' => $submission->id));
        $this->assertNull($syncedsubmission->uuid);
        $supportedfiles = $DB->get_records('plagiarism_safeassign_files', array('supported' => 1));
        $unsupportedfiles = $DB->get_records('plagiarism_safeassign_files', array('supported' => 0));
        $this->assertCount(0, $supportedfiles);
        $this->assertCount(0, $unsupportedfiles);
    }

    public function test_sync_course_assignment_submission_ok() {
        global $DB;

        $this->resetAfterTest(true);
        $this->set_safeassign_records();

        $this->setAdminUser();
        $testhelper = new plagiarism_safeassign_safeassign_api_testcase();
        $this->config_set_ok();
        $this->push_login_urls();

        $task = new sync_assignments();
        $courseurl = $testhelper->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        testhelper::push_pair($courseurl.'?id='.$this->course->id, 'create-course-final.json');
        $putdeleteinstructorurl = $testhelper->create_put_delete_instructor_url('123e4567-e89b-12d3-a456-426655440000');
        testhelper::push_pair($putdeleteinstructorurl, 'put-delete-instructor-ok.json');
        $assignmenturl = $testhelper->create_assignment_url('123e4567-e89b-12d3-a456-426655440000');
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        // Make avaliable the check assignment url.
        $assignurl = $testhelper->create_assignment_url('123e4567-e89b-12d3-a456-426655440000', $this->assigninstance->id);
        testhelper::push_pair($assignurl . '?id=' . $this->assigninstance->id, "create-assignment-ok.json");
        // Make the submission url avaliable.
        $submissionurl = $testhelper->create_submission_url('123e4567-e89b-12d3-a456-426655440000',
            'c93e61c6-be1f-6c49-5c86-76d8f04f3f2f');
        testhelper::push_pair($submissionurl, 'create-submission-ok.json');
        $task->execute();

        $course = $DB->get_record('plagiarism_safeassign_course', array('courseid' => $this->course->id));
        $this->assertNotNull($course->uuid);
        $assignment = $DB->get_record('plagiarism_safeassign_assign', array('assignmentid' => $this->assigninstance->id));
        $this->assertNotNull($assignment->uuid);
        $submission = $DB->get_record('assign_submission', array('userid' => $this->student1->id,
            'assignment' => $this->assigninstance->id));
        $syncedsubmission = $DB->get_record('plagiarism_safeassign_subm', array('submissionid' => $submission->id));
        $this->assertNotNull($syncedsubmission->uuid);
        $supportedfiles = $DB->get_records('plagiarism_safeassign_files', array('supported' => 1));
        $unsupportedfiles = $DB->get_records('plagiarism_safeassign_files', array('supported' => 0));
        $this->assertCount(2, $supportedfiles);
        $this->assertCount(3, $unsupportedfiles);

        // Now we want to extend this test and delete the previous submission since it will be deprecated.
        $DB->set_field('plagiarism_safeassign_subm', 'deprecated', 1, array('submissionid' => $this->student1submission->id));
        // New submission.
        $record = new stdClass();
        $record->uuid = null;
        $record->globalcheck = 1;
        $record->groupsubmission = 1;
        $record->submitted = 0;
        $record->submissionid = $this->student1submission->id;
        $record->deprecated = 0;
        $record->hasfile = 1;
        $record->hasonlinetext = 1;
        $record->timecreated = time();
        $DB->insert_record('plagiarism_safeassign_subm', $record);
        $deletesubmissionurl = $testhelper->create_delete_submission_url('5140a223-8cbc-7a85-3cb4-f52d959ee067');
        // First test is expected to fail, so the deleted field should stay as '0'.
        testhelper::push_pair($deletesubmissionurl, 'delete-submission-fail.json', 400);
        $task->execute();
        $deprecatedsubmission = $DB->get_record('plagiarism_safeassign_subm', array('deprecated' => '1'));
        $this->assertEquals('0', $deprecatedsubmission->deleted);
        // Second test should be success, so now the deleted files should be marked as '1'.
        testhelper::push_pair($deletesubmissionurl, 'delete-submission-ok.json', 200);
        $task->execute();
        $deprecatedsubmission = $DB->get_record('plagiarism_safeassign_subm', array('deprecated' => '1'));
        $this->assertEquals('1', $deprecatedsubmission->deleted);

    }

    /**
     * Test the deletion course hook
     */
    public function test_deletion_course_hook() {
        global $DB;
        $this->resetAfterTest(true);
        $this->set_safeassign_records();
        $submission = $DB->get_record('plagiarism_safeassign_subm', array('submissionid' => $this->student1submission->id));
        $this->assertEquals('0', $submission->deprecated);
        delete_course($this->course->id, false);
        $submission = $DB->get_record('plagiarism_safeassign_subm', array('submissionid' => $this->student1submission->id));
        $this->assertEquals('1', $submission->deprecated);
    }

    /**
     * Test the deletion module hook
     */
    public function test_deletion_course_module_hook() {
        global $DB;
        $this->resetAfterTest(true);
        $this->set_safeassign_records();
        $submission = $DB->get_record('plagiarism_safeassign_subm', array('submissionid' => $this->student1submission->id));
        $this->assertEquals('0', $submission->deprecated);
        // Delete the course module.
        course_delete_module($this->cm->id);
        // Now, run the course module deletion adhoc task.
        phpunit_util::run_all_adhoc_tasks();
        $submission = $DB->get_record('plagiarism_safeassign_subm', array('submissionid' => $this->student1submission->id));
        $this->assertEquals('1', $submission->deprecated);
    }

    /**
     * Insert some SafeAssign records directly on the database.
     */
    public function set_safeassign_records() {
        global $DB;
        $record = new stdClass();
        $record->uuid = null;
        $record->courseid = $this->course->id;
        $record->instructorid = $this->teacher->id;
        $DB->insert_record('plagiarism_safeassign_course', $record);

        $record2 = new stdClass();
        $record2->uuid = null;
        $record2->assignmentid = $this->assigninstance->id;
        $record2->courseid = $this->course->id;
        $DB->insert_record('plagiarism_safeassign_assign', $record2);

        $record3 = new stdClass();
        $record3->uuid = null;
        $record3->globalcheck = 1;
        $record3->groupsubmission = 1;
        $record3->submitted = 0;
        $record3->submissionid = $this->student1submission->id;
        $record3->deprecated = 0;
        $record3->hasfile = 1;
        $record3->hasonlinetext = 1;
        $record3->timecreated = time();
        $record3->assignmentid = $this->assigninstance->id;
        $DB->insert_record('plagiarism_safeassign_subm', $record3);

        // Turn on SafeAssign for the test assignment.
        $enablesafeassign = new stdClass();
        $enablesafeassign->cm = $this->cm->id;
        $enablesafeassign->name = 'safeassign_enabled';
        $enablesafeassign->value = 1;
        $DB->insert_record('plagiarism_safeassign_config', $enablesafeassign);
    }
}