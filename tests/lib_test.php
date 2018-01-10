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

class plagiarism_safeassign_testcase extends advanced_testcase {

    private $user;

    protected function setUp() {
        global $USER;

        $this->setAdminUser();
        $this->user = $USER;
        // Enable SafeAssign in the platform.
        set_config('safeassign_use', 1, 'plagiarism');
    }

    public function test_assigndbsaver_assignments() {
        global $DB;

        $this->resetAfterTest(true);

        // Generate course.
        $course1 = $this->getDataGenerator()->create_course();

        // Create an activity.
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $instance = $generator->create_instance(array('course' => $course1->id));
        $cm = get_coursemodule_from_instance('assign', $instance->id);

        // Create an activity with SafeAssign enabled.
        $data = new stdClass();
        $data->coursemodule = $cm->id;
        $data->safeassign_enabled = 1;
        $data->course = $course1->id;
        $data->instance = $instance->id;
        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->save_form_elements($data);
        $confirmdbassign = $DB->get_record('plagiarism_safeassign_assign', array('assignmentid' => $instance->id));
        $confirmdbcourse = $DB->get_record('plagiarism_safeassign_course', array('courseid' => $course1->id));

        $this->assertEquals($instance->id, $confirmdbassign->assignmentid);
        $this->assertEquals($course1->id, $confirmdbcourse->courseid);

        // Now let's add a second assign on the same course without SafeAssign enabled
        // and see that course records are not being duplicated.
        $instance2 = $generator->create_instance(array('course' => $course1->id));
        $cm2 = get_coursemodule_from_instance('assign', $instance2->id);

        $data = new stdClass();
        $data->coursemodule = $cm2->id;
        $data->safeassign_enabled = 0;
        $data->course = $course1->id;
        $data->instance = $instance2->id;

        $safeassign->save_form_elements($data);
        $confirmdbassign2 = $DB->get_record('plagiarism_safeassign_assign', array('assignmentid' => $instance2->id));
        $confirmdbcourse2 = $DB->count_records('plagiarism_safeassign_course', array('courseid' => $course1->id));

        $this->assertEmpty($confirmdbassign2);
        $this->assertEquals(1, $confirmdbcourse2);

        // Now let's add a third assign on a different course and check that course records are being saved.

        $course2 = $this->getDataGenerator()->create_course();

        $instance3 = $generator->create_instance(array('course' => $course2->id));
        $cm3 = get_coursemodule_from_instance('assign', $instance3->id);

        $data = new stdClass();
        $data->coursemodule = $cm3->id;
        $data->safeassign_enabled = 1;
        $data->course = $course2->id;
        $data->instance = $instance3->id;

        $safeassign->save_form_elements($data);
        $confirmdbassign3 = $DB->get_record('plagiarism_safeassign_assign', array('assignmentid' => $instance3->id));
        $confirmdbcourse3 = $DB->get_record('plagiarism_safeassign_course', array('courseid' => $course2->id));

        $this->assertEquals($instance3->id, $confirmdbassign3->assignmentid);
        $this->assertEquals($course2->id, $confirmdbcourse3->courseid);
        $this->assertEquals(2, $DB->count_records('plagiarism_safeassign_assign'));
        $this->assertEquals(2, $DB->count_records('plagiarism_safeassign_course'));
    }

    /**
     * Builds a submitted file object.
     * @param int $userid ID of the user.
     * @param int $cmid course module ID.
     * @param int $submissionid ID of the submission.
     * @return array returns an array with file object, userid and cmid.
     */
    public function create_submitted_file_object($userid, $cmid, $submissionid) {
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
    public function insert_files_for_testing($cm, $userid, $reporturl, $score, $time, $subid, $fileid) {
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
     * @param int $deprecated Flag that indicates if the submission was updated.
     * @return stdClass $submission submission object.
     */
    public function insert_submission_for_testing($submitted, $report, $subid, $deprecated) {
        global $DB;
        $submission = new stdClass();
        $submission->globalcheck = '1';
        $submission->groupsubmission = '0';
        $submission->highscore = 1.00;
        $submission->avgscore = 0.50;
        $submission->submitted = $submitted;
        $submission->reportgenerated = $report;
        $submission->submissionid = $subid;
        $submission->deprecated = $deprecated;
        $submission->uuid = uniqid();
        $DB->insert_record('plagiarism_safeassign_subm', $submission, true);
        return $submission;
    }

    /**
     * Case 0: Ideal case, file was submitted and analyzed.
     * Tests the get_file_results() and get_links() functions.
     */
    public function test_get_file_results() {
        $this->resetAfterTest(true);
        $linkarray = $this->create_submitted_file_object(111, 000, 1111111);
        $file = $linkarray['file'];
        $this->insert_submission_for_testing(1, 1, 1111111, 0);
        $this->insert_files_for_testing(000, 111, 'http://fakeurl1.com', 0.99, 1502484564, 1111111, $file->get_id());
        $lib = new plagiarism_plugin_safeassign();
        $results = $lib->get_file_results(000, 111, $file->get_id());
        $this->assertequals(1, $results['analyzed']);
        $this->assertequals(0.99, $results['score']);
        $this->assertequals('http://fakeurl1.com', $results['reporturl']);
    }

    /**
     * Case 1: File submitted but not analyzed yet.
     * Tests the get_file_results() and get_links() functions.
     */
    public function test_get_results_submitted_not_analyzed() {
        $this->resetAfterTest(true);
        $linkarray = $this->create_submitted_file_object(222, 000, 2222222);
        $file = $linkarray['file'];
        $this->insert_submission_for_testing(1, 0, 2222222, 0);
        $this->insert_files_for_testing(000, 222, '', null, 1502484564, 2222222, $file->get_id());
        $lib = new plagiarism_plugin_safeassign();
        $results = $lib->get_file_results(000, 222, $file->get_id());
        $this->assertequals(0, $results['analyzed']);
        $this->assertequals('', $results['score']);
        $this->assertequals('', $results['reporturl']);
    }

    /**
     * Case 2: Testing with an unexisting submission.
     * Tests the get_file_results() and get_links() functions.
     */
    public function test_get_file_results_no_submission() {
        $this->resetAfterTest(true);
        $linkarray = $this->create_submitted_file_object(333, 000, 3333333);
        $file = $linkarray['file'];
        $lib = new plagiarism_plugin_safeassign();
        $results = $lib->get_file_results(000, 333, $file->get_id());
        $this->assertequals(0, $results['analyzed']);
        $this->assertequals('', $results['score']);
        $this->assertequals('', $results['reporturl']);
    }

    /**
     * Tests the function get_submission_results().
     * The function should retrieve the object correctly.
     */
    public function test_get_submission_results() {
        $this->resetAfterTest(true);
        $testobject = $this->insert_submission_for_testing(1, 1, 11111, 0);
        $lib = new plagiarism_plugin_safeassign();
        $result = $lib->get_submission_results(11111);
        $this->assertEquals($testobject->globalcheck, $result->globalcheck);
        $this->assertEquals($testobject->groupsubmission, $result->groupsubmission);
        $this->assertEquals($testobject->highscore, $result->highscore);
        $this->assertEquals($testobject->avgscore, $result->avgscore);
        $this->assertEquals($testobject->submitted, $result->submitted);
        $this->assertEquals($testobject->reportgenerated, $result->reportgenerated);
        $this->assertEquals($testobject->submissionid, $result->submissionid);
        $this->assertEquals($testobject->deprecated, $result->deprecated);
    }

    /**
     * Tests the function get_submission_results().
     * The function should return an empty string.
     */
    public function test_get_submission_results_nosubmission() {
        $this->resetAfterTest(true);
        $lib = new plagiarism_plugin_safeassign();
        $result = $lib->get_submission_results(22222);
        $this->assertFalse($result);
    }

    /**
     * Tests if resubmit ack marks submission as having no report generated.
     */
    public function test_resubmit_ack() {
        $this->resetAfterTest(true);

        $testobject = $this->insert_submission_for_testing(1, 1, 11111, 0);
        $uuid = $testobject->uuid;

        $lib = new plagiarism_plugin_safeassign();
        $lib->resubmit_acknowlegment($uuid);

        $result = $lib->get_submission_results(11111);
        $this->assertEquals($result->reportgenerated, 0);
    }
}