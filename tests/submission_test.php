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
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/mod/assign/externallib.php');
require_once($CFG->dirroot . '/mod/assign/tests/base_test.php');
require_once($CFG->dirroot . '/plagiarism/safeassign/classes/observer.php');

/**
 * Class plagiarism_safeassign_submission_test
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
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
    protected function setUp() {
        global $DB;
        $this->user = $this->getDataGenerator()->create_user();
        $this->course = $this->getDataGenerator()->create_course();
        $studentrole = $DB->get_record('role', array('shortname' => 'student'));
        $this->getDataGenerator()->enrol_user($this->user->id,
            $this->course->id,
            $studentrole->id);
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $params['course'] = $this->course->id;
        $instance = $generator->create_instance($params);
        $this->cm = get_coursemodule_from_instance('assign', $instance->id);
        $this->context = context_module::instance($this->cm->id);
        $this->assign = new testable_assign($this->context, $this->cm, $this->course);
        $this->setUser($this->user->id);
        $this->submission = $this->assign->get_user_submission($this->user->id, true);
        // Enable SafeAssign in the platform.
        set_config('safeassign_use', 1, 'plagiarism');
        // Enable SafeAssign in the assignment.
        $record = new stdClass();
        $record->cm = $this->cm->id;
        $record->name = 'safeassign_enabled';
        $record->value = 1;
        $DB->insert_record('plagiarism_safeassign_config', $record);
        $record->name = 'safeassign_global_reference';
        $record->value = self::GLOBALCHECK;
        $DB->insert_record('plagiarism_safeassign_config', $record);
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
        mod_assign_external::submit_for_grading($this->assign->get_instance()->id, false);

        $events = $sink->get_events();
        $this->assertCount(3, $events);
        $event1 = $events[1];
        $event2 = $events[2];

        plagiarism_safeassign_observer::event_triggered($event1);
        plagiarism_safeassign_observer::event_triggered($event2);

        $record = $DB->get_record('plagiarism_safeassign_subm', array());
        $this->evaluate_safeassign_subm_record($record, 0);
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
        mod_assign_external::submit_for_grading($this->assign->get_instance()->id, false);

        $events = $sink->get_events();
        $this->assertCount(3, $events);
        $event0 = $events[0];
        $event1 = $events[1];
        $event2 = $events[2];
        // Submission is processed by the event observer class.
        plagiarism_safeassign_observer::event_triggered($event0);
        plagiarism_safeassign_observer::event_triggered($event1);
        plagiarism_safeassign_observer::event_triggered($event2);

        $record = $DB->get_record('plagiarism_safeassign_subm', array());
        $this->evaluate_safeassign_subm_record($record, 0);
    }

    /**
     * Checks that the values of a record are the expected.
     * @param stdClass $record
     */
    private function evaluate_safeassign_subm_record($record, $globalcheck) {
        $this->assertNull($record->uuid);
        $this->assertEquals($globalcheck, $record->globalcheck);
        $this->assertEquals(1, $record->groupsubmission);
        $this->assertEquals(0, $record->reportgenerated);
        $this->assertEquals(0, $record->submitted);
        $this->assertEquals(0.00, $record->highscore);
        $this->assertEquals(0.00, $record->avgscore);
    }

}