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
 * Test SafeAssign events and how the records are stored when a submission is made.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/mod/assign/tests/base_test.php');
require_once($CFG->dirroot . '/plagiarism/safeassign/classes/observer.php');

/**
 * Class plagiarism_safeassign_submission_test
 * @copyright Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plagiarism_safeassign_submission_test extends advanced_testcase  {


    /** @var stdClass $user A user to submit an assignment. */
    protected $user;

    /** @var stdClass $course New course created to hold the assignment activity. */
    protected $course;

    /** @var stdClass $cm A context module object. */
    protected $cm;

    /** @var stdClass $context Context of the assignment activity. */
    protected $context;

    /** @var stdClass $assign The assignment object. */
    protected $assign;

    /** @var stdClass $submission Submission information. */
    protected $submission;

    /** @var stdClass $data General data for the assignment submission. */
    protected $data;

    /**
     * @var boolean GLOBALCHECK.
     */
    const GLOBALCHECK = 1;

    /**
     * Setup all the various parts of an assignment activity including creating an onlinetext submission.
     */
    protected function setUp():void {
        global $DB;
        $this->user = $this->getDataGenerator()->create_user();
        $this->course = $this->getDataGenerator()->create_course();
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $params['course'] = $this->course->id;
        $instance = $generator->create_instance($params);
        $this->cm = get_coursemodule_from_instance('assign', $instance->id);
        $this->context = context_module::instance($this->cm->id);
        $this->assign = new testable_assign($this->context, $this->cm, $this->course);
        $this->setUser($this->user->id);
        $this->submission = $this->assign->get_user_submission($this->user->id, true);
        // Enable SafeAssign in the platform.
        set_config('enabled', 1, 'plagiarism_safeassign');
        // Enable SafeAssign in the assignment.
        $record = new stdClass();
        $record->course = $this->course->id;
        $record->instance = $instance->id;
        $record->coursemodule = $this->cm->id;
        $record->safeassign_enabled = 1;
        $record->name = 'safeassign_enabled';
        $record->value = 1;
        plagiarism_safeassign_coursemodule_edit_post_actions($record);
        $record->name = 'safeassign_global_reference';
        $record->value = self::GLOBALCHECK;
        plagiarism_safeassign_coursemodule_edit_post_actions($record);
    }

    /**
     * Test that the assessable_uploaded event is fired when an online text submission is saved.
     */
    public function test_onlinetext_assessable_uploaded() {
        global $DB;

        $this->resetAfterTest();

        $this->data = new stdClass();
        $this->data->onlinetext_editor = array(
            'itemid' => file_get_unused_draft_itemid(),
            'text'   => 'Submission text',
            'format' => FORMAT_PLAIN
        );

        $plugin = $this->assign->get_submission_plugin_by_type('onlinetext');
        $sink = $this->redirectEvents();
        $plugin->save($this->submission, $this->data);
        $events = $sink->get_events();
        $this->assertCount(2, $events);
        $event = $events[1];
        // Submission is processed by the event observer class.
        plagiarism_safeassign_observer::assignsubmission_onlinetext_created($event);
        $record = $DB->get_record('plagiarism_safeassign_subm', array());
        $this->evaluate_safeassign_subm_recor($record, 0);
        // Simulate a resubmission.
        $reflection = new \ReflectionProperty(get_class($event), 'data');
        $reflection->setAccessible(true);
        $temporal = $reflection->getValue($event);
        $temporal['timecreated'] = $temporal['timecreated'] + 1;
        $reflection->setValue($event, $temporal);
        $reflection->setAccessible(false);
        plagiarism_safeassign_observer::assignsubmission_onlinetext_created($event);
        // Test file creation for text submission.
        $fs = get_file_storage();
        $usercontext = context_user::instance($this->user->id);
        $file = $fs->get_file($usercontext->id, 'assignsubmission_text_as_file', 'submission_text_files',
            $this->submission->id, '/', 'userid_' . $this->user->id . '_text_submissionid_' . $this->submission->id .'.txt');
        $this->assertNotNull($file);
        $records = $DB->get_records('plagiarism_safeassign_subm');
        $this->assertEquals(2, count($records));
        // The record with the highest id should be not deleted.
        $maxkey = max(array_keys($records));
        foreach ($records as $id => $record) {
            $deprecated = 1;
            if ($record->id == $maxkey) {
                $deprecated = 0;
            }
            $this->evaluate_safeassign_subm_recor($record, $deprecated);
        }
        // Extending this scenario to test that online text submissions done by instructors are marked as deprecated.
        // Insert user data in SafeAssign instructors table.
        $instructorrecord = new stdClass();
        $instructorrecord->instructorid = $this->user->id;
        $instructorrecord->courseid = $this->course->id;
        $instructorrecord->synced = 0;
        $instructorrecord->unenrrolled = 0;
        $instructorrecord->deleted = 0;
        $DB->insert_record('plagiarism_safeassign_instr', $instructorrecord, true);

        // Simulate a new resubmission.
        $reflection->setAccessible(true);
        $temporal2 = $reflection->getValue($event);
        $temporal2['timecreated'] = $temporal['timecreated'] + 2;
        $reflection->setValue($event, $temporal2);
        $reflection->setAccessible(false);
        plagiarism_safeassign_observer::assignsubmission_onlinetext_created($event);

        $records = $DB->get_records('plagiarism_safeassign_subm');
        $this->assertEquals(3, count($records));
        foreach ($records as $id => $record) {
            // All of 3 online text submissions must be marked as deprecated. The #1 and #2 because of the resubmission events.
            // The third one because it was done after setting the user as instructor in safeassign.
            $this->evaluate_safeassign_subm_recor($record, 1);
        }
    }

    /**
     * Test that the assessable_uploaded event is fired when a file submission has been made.
     */
    public function test_file_assessable_uploaded() {
        global $DB;

        $this->resetAfterTest();

        $fs = get_file_storage();
        $dummy = (object) array(
            'contextid' => $this->context->id,
            'component' => 'assignsubmission_file',
            'filearea' => ASSIGNSUBMISSION_FILE_FILEAREA,
            'itemid' => $this->submission->id,
            'filepath' => '/',
            'filename' => 'myassignmnent.pdf'
        );
        $this->fi = $fs->create_file_from_string($dummy, 'Content of ' . $dummy->filename);
        $dummy = (object) array(
            'contextid' => $this->context->id,
            'component' => 'assignsubmission_file',
            'filearea' => ASSIGNSUBMISSION_FILE_FILEAREA,
            'itemid' => $this->submission->id,
            'filepath' => '/',
            'filename' => 'myassignmnent.png'
        );
        $this->fi2 = $fs->create_file_from_string($dummy, 'Content of ' . $dummy->filename);
        $this->files = $fs->get_area_files($this->context->id, 'assignsubmission_file', ASSIGNSUBMISSION_FILE_FILEAREA,
            $this->submission->id, 'id', false);

        $data = new stdClass();
        $plugin = $this->assign->get_submission_plugin_by_type('file');
        $sink = $this->redirectEvents();
        $plugin->save($this->submission, $data);
        $events = $sink->get_events();
        $event = reset($events);

        // Submission is processed by the event observer class.
        plagiarism_safeassign_observer::assignsubmission_file_uploaded($event);
        $record = $DB->get_record('plagiarism_safeassign_subm', array());
        $this->evaluate_safeassign_subm_recor($record, 0);
        // Simulate a resubmission.
        $reflection = new \ReflectionProperty(get_class($event), 'data');
        $reflection->setAccessible(true);
        $temporal = $reflection->getValue($event);
        $temporal['timecreated'] = $temporal['timecreated'] + 1;
        $reflection->setValue($event, $temporal);
        $reflection->setAccessible(false);
        plagiarism_safeassign_observer::assignsubmission_file_uploaded($event);
        $records = $DB->get_records('plagiarism_safeassign_subm');
        $this->assertEquals(2, count($records));
        // The record with the highest id should be not deleted.
        $maxkey = max(array_keys($records));
        foreach ($records as $id => $record) {
            $deprecated = 1;
            if ($record->id == $maxkey) {
                $deprecated = 0;
            }
            $this->evaluate_safeassign_subm_recor($record, $deprecated);
        }

        // Extending this scenario to test that submissions done by instructors are marked as deprecated.
        // Insert user data in SafeAssign instructors table.
        $instructorrecord = new stdClass();
        $instructorrecord->instructorid = $this->user->id;
        $instructorrecord->courseid = $this->course->id;
        $instructorrecord->synced = 0;
        $instructorrecord->unenrrolled = 0;
        $instructorrecord->deleted = 0;
        $DB->insert_record('plagiarism_safeassign_instr', $instructorrecord, true);

        // Simulate a new resubmission.
        $reflection->setAccessible(true);
        $temporal2 = $reflection->getValue($event);
        $temporal2['timecreated'] = $temporal['timecreated'] + 2;
        $reflection->setValue($event, $temporal2);
        $reflection->setAccessible(false);
        plagiarism_safeassign_observer::assignsubmission_file_uploaded($event);

        $records = $DB->get_records('plagiarism_safeassign_subm');
        $this->assertEquals(3, count($records));
        foreach ($records as $id => $record) {
            // All of 3 submissions must be marked as deprecated. The #1 and #2 because of the resubmission events.
            // The third one because it was done after setting the user as instructor in safeassign.
            $this->evaluate_safeassign_subm_recor($record, 1);
        }
    }

    /**
     * Test to remove a synced submission from the grader.
     */
    public function test_remove_submission_from_viewed() {
        global $DB, $PAGE;
        $this->markTestSkipped('To be fixed by INT-17821');
        $this->resetAfterTest();

        $fs = get_file_storage();
        $dummy = (object) array(
            'contextid' => $this->context->id,
            'component' => 'assignsubmission_file',
            'filearea' => ASSIGNSUBMISSION_FILE_FILEAREA,
            'itemid' => $this->submission->id,
            'filepath' => '/',
            'filename' => 'myassignmnent.pdf'
        );
        $this->fi = $fs->create_file_from_string($dummy, 'Content of ' . $dummy->filename);
        $dummy = (object) array(
            'contextid' => $this->context->id,
            'component' => 'assignsubmission_file',
            'filearea' => ASSIGNSUBMISSION_FILE_FILEAREA,
            'itemid' => $this->submission->id,
            'filepath' => '/',
            'filename' => 'myassignmnent.png'
        );
        $this->fi2 = $fs->create_file_from_string($dummy, 'Content of ' . $dummy->filename);
        $this->files = $fs->get_area_files($this->context->id, 'assignsubmission_file', ASSIGNSUBMISSION_FILE_FILEAREA,
            $this->submission->id, 'id', false);

        $data = new stdClass();
        $plugin = $this->assign->get_submission_plugin_by_type('file');
        $sink = $this->redirectEvents();
        $plugin->save($this->submission, $data);
        $events = $sink->get_events();
        $event = reset($events);

        // Submission is processed by the event observer class.
        plagiarism_safeassign_observer::assignsubmission_file_uploaded($event);
        $teacher = $this->getDataGenerator()->create_and_enrol($this->course, 'editingteacher');
        $roleid = $DB->get_field('role', 'id', ['shortname' => 'editingteacher'], MUST_EXIST);

        assign_capability('mod/assign:editothersubmission', CAP_ALLOW,
            $roleid, $this->assign->get_context()->id, true);
        assign_capability('mod/assign:submit', CAP_ALLOW,
            $roleid, $this->assign->get_context()->id, true);

        $this->getDataGenerator()->enrol_user($teacher->id,
            $this->course->id, $roleid);

        $this->setUser($teacher->id);
        // Check submission status different from submitted. Should not update on SafeAssign.
        $this->submission->status = ASSIGN_SUBMISSION_STATUS_DRAFT;
        $this->assign->testable_update_submission($this->submission, $this->user, true, false);

        // Trigger and capture the event.
        $sink = $this->redirectEvents();
        $this->assign->remove_submission($this->user->id);
        $events = $sink->get_events();
        $event = reset($events);

        // Simulate that the submission has been synced with SafeAssign.
        $DB->set_field("plagiarism_safeassign_subm", 'uuid', '1234567890', ['submissionid' => $this->submission->id]);
        $record = $DB->get_record('plagiarism_safeassign_subm', ['submissionid' => $this->submission->id]);
        $this->assertEquals(0, $record->deprecated);
        plagiarism_safeassign_observer::submission_removed($event);
        $record = $DB->get_record('plagiarism_safeassign_subm', ['submissionid' => $this->submission->id]);
        // The submission is not altered.
        $this->assertEquals(0, $record->deprecated);

        // Set submission as submitted to capture event and update submission.
        $this->submission->status = ASSIGN_SUBMISSION_STATUS_SUBMITTED;
        $this->assign->testable_update_submission($this->submission, $this->user, true, false);

        // Trigger and capture the event.
        $sink = $this->redirectEvents();
        $this->assign->remove_submission($this->user->id);
        $events = $sink->get_events();
        $event = reset($events);

        // Simulate that the submission has been synced with SafeAssign.
        $DB->set_field("plagiarism_safeassign_subm", 'uuid', '1234567890', ['submissionid' => $this->submission->id]);
        $record = $DB->get_record('plagiarism_safeassign_subm', ['submissionid' => $this->submission->id]);
        $this->assertEquals(0, $record->deprecated);
        plagiarism_safeassign_observer::submission_removed($event);
        $record = $DB->get_record('plagiarism_safeassign_subm', ['submissionid' => $this->submission->id]);
        // The submission is deprecated.
        $this->assertEquals(1, $record->deprecated);
    }

    /**
     * Test to remove an unsynced submission from the grader.
     */
    public function test_remove_unsynced_submission_from_viewed() {
        global $DB;
        $this->markTestSkipped('To be fixed by INT-17821');
        $this->resetAfterTest();

        $fs = get_file_storage();
        $dummy = (object) array(
            'contextid' => $this->context->id,
            'component' => 'assignsubmission_file',
            'filearea' => ASSIGNSUBMISSION_FILE_FILEAREA,
            'itemid' => $this->submission->id,
            'filepath' => '/',
            'filename' => 'myassignmnent.pdf'
        );
        $this->fi = $fs->create_file_from_string($dummy, 'Content of ' . $dummy->filename);
        $dummy = (object) array(
            'contextid' => $this->context->id,
            'component' => 'assignsubmission_file',
            'filearea' => ASSIGNSUBMISSION_FILE_FILEAREA,
            'itemid' => $this->submission->id,
            'filepath' => '/',
            'filename' => 'myassignmnent.png'
        );
        $this->fi2 = $fs->create_file_from_string($dummy, 'Content of ' . $dummy->filename);
        $this->files = $fs->get_area_files($this->context->id, 'assignsubmission_file', ASSIGNSUBMISSION_FILE_FILEAREA,
            $this->submission->id, 'id', false);

        $data = new stdClass();
        $plugin = $this->assign->get_submission_plugin_by_type('file');
        $sink = $this->redirectEvents();
        $plugin->save($this->submission, $data);
        $events = $sink->get_events();
        $event = reset($events);

        // Submission is processed by the event observer class.
        plagiarism_safeassign_observer::assignsubmission_file_uploaded($event);
        $teacher = $this->getDataGenerator()->create_and_enrol($this->course, 'editingteacher');
        $roleid = $DB->get_field('role', 'id', ['shortname' => 'editingteacher'], MUST_EXIST);

        assign_capability('mod/assign:editothersubmission', CAP_ALLOW,
            $roleid, $this->assign->get_context()->id, true);
        assign_capability('mod/assign:submit', CAP_ALLOW,
            $roleid, $this->assign->get_context()->id, true);

        $this->getDataGenerator()->enrol_user($teacher->id,
            $this->course->id, $roleid);

        $this->setUser($teacher->id);
        $this->submission->status = ASSIGN_SUBMISSION_STATUS_SUBMITTED;
        $this->assign->testable_update_submission($this->submission, $this->user, true, false);
        // Trigger and capture the event.
        $sink = $this->redirectEvents();
        $this->assign->remove_submission($this->user->id);
        $events = $sink->get_events();
        $event = reset($events);

        $record = $DB->get_record('plagiarism_safeassign_subm', ['submissionid' => $this->submission->id]);
        // Make sure that the submission has not been synced.
        $this->assertEquals("", $record->uuid);
        $this->assertEquals(0, $record->deprecated);
        plagiarism_safeassign_observer::submission_removed($event);
        $record = $DB->get_record('plagiarism_safeassign_subm', ['submissionid' => $this->submission->id]);
        $this->assertEquals(1, $record->deprecated);
    }

    /**
     * Checks that the values of a record are the expected.
     * @param stdClass $record
     * @param boolean $deprecated
     */
    private function evaluate_safeassign_subm_recor($record, $deprecated) {
        $this->assertNull($record->uuid);
        $this->assertEquals(0, $record->globalcheck);
        $this->assertEquals(1, $record->groupsubmission);
        $this->assertEquals(0, $record->reportgenerated);
        $this->assertEquals(0, $record->submitted);
        $this->assertEquals(0.00, $record->highscore);
        $this->assertEquals(0.00, $record->avgscore);
        $this->assertEquals($deprecated, $record->deprecated);
    }

}