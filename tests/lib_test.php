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
 * Test safeassign lib functions interaction with Moodle.
 *
 * @package   plagiarism_safeassign
 * @category  phpunit
 * @copyright Copyright (c) 2017 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $CFG, $DB;

require_once($CFG->dirroot . '/plagiarism/safeassign/lib.php');
require_once($CFG->dirroot . '/plagiarism/safeassign/classes/observer.php');
require_once($CFG->dirroot . '/lib/classes/event/course_module_created.php');
require_once($CFG->dirroot . '/mod/assign/tests/base_test.php');
require_once($CFG->dirroot . '/config.php');

class plagiarism_safeassign_testcase extends advanced_testcase {

    private $user;

    protected function setUp() {
        global $USER;

        $this->setAdminUser();
        $this->user = $USER;
    }

    public function test_assigndbsaver_new_assignment()
    {
        global $DB;

        $this->resetAfterTest(true);

        // Generate course.
        $course1 = $this->getDataGenerator()->create_course();

        // Create an activity.
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $instance = $generator->create_instance(array('course' => $course1->id));
        $cm = get_coursemodule_from_instance('assign', $instance->id);
        $modcontext = context_module::instance($instance->cmid);

        $event = \core\event\course_module_created::create(array(
            'courseid' => $course1->id,
            'context'  => $modcontext,
            'objectid' => $cm->id,
            'other'    => array(
                'modulename' => 'assign',
                'name'       => 'My assignment',
                'instanceid' => $instance->id
            )
        ));

        $sink = $this->redirectEvents();
        $event->trigger();
        $result = $sink->get_events();
        $event = reset($result);
        $sink->close();

        plagiarism_safeassign_observer::course_module_created($event);

        $confirmdb = $DB->get_record('plagiarism_safeassign_assign', array('assignmentid'=>$instance->id));

        $this->assertEquals($instance->id, $confirmdb->assignmentid);
    }

    /**
     * Builds a submitted file object.
     * @param int $userid ID of the user.
     * @param int $cmid course module ID.
     * @param int $submissionid ID of the submission.
     * @return array returns an array with file object, userid and cmid.
     */
    function create_submitted_file_object($userid, $cmid, $submissionid){
        $this->user = $this->getDataGenerator()->create_user();
        $this->course = $this->getDataGenerator()->create_course();
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $params['course'] = $this->course->id;
        $instance = $generator->create_instance($params);
        $this->cm = get_coursemodule_from_instance('assign', $instance->id);
        $this->context = context_module::instance($this->cm->id);
        $this->setUser($this->user->id);
        $fs = get_file_storage();
        $dummy = (object) array(
            'contextid' => $this->context->id,
            'component' => 'assignsubmission_file',
            'filearea' => ASSIGNSUBMISSION_FILE_FILEAREA,
            'itemid' => $submissionid,
            'filepath' => '/',
            'filename' => 'myassignmnent.pdf'
        );
        $this->fi = $fs->create_file_from_string($dummy, 'Content of ' . $dummy->filename);
        $dummy = (object) array(
            'contextid' => $this->context->id,
            'component' => 'assignsubmission_file',
            'filearea' => ASSIGNSUBMISSION_FILE_FILEAREA,
            'itemid' => $submissionid,
            'filepath' => '/',
            'filename' => 'myassignmnent.png'
        );
        $this->fi2 = $fs->create_file_from_string($dummy, 'Content of ' . $dummy->filename);
        $this->files = $fs->get_area_files($this->context->id, 'assignsubmission_file', ASSIGNSUBMISSION_FILE_FILEAREA,
            $submissionid, 'id', false);
        return array('userid' => $userid, 'cmid' => $cmid, 'file' => $this->fi2);
    }

    /**
     * Inserts files in the "mdl_plagiarism_safeassign_files" database table for testing.
     * @param int $cm Course module.
     * @param int $userid User's ID.
     * @param string $reporturl URL of the report provided by SafeAssign.
     * @param number $score Similarity score with two decimal numbers (i.e. 0.75) provided by SafeAssign.
     * @param int $time Timestamp of the file upload.
     * @param int $subid ID of the submission.
     * @param int $fileid ID of the submitted file.
     */
    function insert_files_for_testing($cm, $userid, $reporturl, $score, $time, $subid, $fileid) {
        global $DB;
        $file = new stdClass();
        $file->cm = $cm;
        $file->userid = $userid;
        $file->reporturl = $reporturl;
        $file->similarityscore = $score;
        $file->timesubmitted = $time;
        $file->supported = 1;
        $file->submissionid = $subid;
        $file->fileid = $fileid;
        $DB->insert_record('plagiarism_safeassign_files', $file, true);
    }

    /**
     * Inserts a submission in the "mdl_plagiarism_safeassign_subm" database table for testing.
     * @param int $submitted Flag that indicates if the file was submitted to SafeAssign.
     * @param int $report Flag that indicates if the report file was generated by SafeAssign.
     * @param int $subid  ID of the submission.
     */
    function insert_submission_for_testing($submitted, $report, $subid) {
        global $DB;
        $submission = new stdClass();
        $submission->globalcheck = '1';
        $submission->groupsubmission = '0';
        $submission->highscore = 1.00;
        $submission->avgscore = 0.50;
        $submission->submitted = $submitted;
        $submission->reportgenerated = $report;
        $submission->submissionid = $subid;
        $DB->insert_record('plagiarism_safeassign_subm',$submission, true);
    }

    /**
     * Case 0: Ideal case, file was submitted and analyzed.
     * Tests the get_file_results() and get_links() functions.
     */
    function test_get_file_results()
    {
        $this->resetAfterTest(true);
        $linkarray = $this->create_submitted_file_object(111, 000, 1111111);
        $file = $linkarray['file'];
        $this->insert_submission_for_testing(1, 1, 1111111);
        $this->insert_files_for_testing(000, 111, 'http://fakeurl1.com', 0.99, 1502484564, 1111111, $file->get_id());
        $lib = new plagiarism_plugin_safeassign();
        $results = $lib->get_file_results(000, 111, $file);
        $this->assertequals(1, $results['analyzed']);
        $this->assertequals(0.99, $results['score']);
        $this->assertequals('http://fakeurl1.com', $results['reporturl']);
    }

    /**
     * Case 1: File submitted but not analyzed yet.
     * Tests the get_file_results() and get_links() functions.
     */
    function test_get_results_submitted_not_analyzed() {
        $this->resetAfterTest(true);
        $linkarray = $this->create_submitted_file_object(222, 000, 2222222);
        $file = $linkarray['file'];
        $this->insert_submission_for_testing(1, 0, 2222222);
        $this->insert_files_for_testing(000, 222, '', NULL, 1502484564, 2222222, $file->get_id());
        $lib = new plagiarism_plugin_safeassign();
        $results = $lib->get_file_results(000, 222, $file);
        $this->assertequals(0, $results['analyzed']);
        $this->assertequals('', $results['score']);
        $this->assertequals('', $results['reporturl']);
    }

    /**
     * Case 2: Testing with an unexisting submission.
     * Tests the get_file_results() and get_links() functions.
     */
    function test_get_file_results_no_submission() {
        $this->resetAfterTest(true);
        $linkarray = $this->create_submitted_file_object(333, 000, 3333333);
        $file = $linkarray['file'];
        $lib = new plagiarism_plugin_safeassign();
        $results = $lib->get_file_results(000, 333, $file);
        $this->assertequals(0, $results['analyzed']);
        $this->assertequals('', $results['score']);
        $this->assertequals('', $results['reporturl']);
    }
}