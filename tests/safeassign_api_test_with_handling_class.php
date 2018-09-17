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

require_once(__DIR__.'/base.php');
global $CFG;
require_once($CFG->dirroot.'/plagiarism/safeassign/lib.php');
use plagiarism_safeassign\api\safeassign_api;
use plagiarism_safeassign\api\testhelper;
use plagiarism_safeassign\api\rest_provider;
use plagiarism_safeassign\api\error_handler;
use plagiarism_safeassign\api\test_safeassign_api_connectors;

/**
 * Class plagiarism_safeassign_safeassign_api_testcase_with_handling_class
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plagiarism_safeassign_safeassign_api_testcase_with_handling_class extends plagiarism_safeassign_base_testcase {
    /**
     * @var stdClass the user to be used to login to SafeAssign
     */
    private $user;
    /**
     * @var stdClass the course to be used in SafeAssign
     */
    private $course;
    /**
     * @var stdClass the assignment inside the course to be used in SafeAssign
     */
    private $assignment;


    /**
     * Set up test.
     */
    public function setUp() {
        $this->reset_ws();
        $this->user = test_safeassign_api_connectors::create_user();
        $this->course = test_safeassign_api_connectors::create_course();
        $this->assignment = test_safeassign_api_connectors::create_assignment("123456789", "Test assignment");
    }

    /**
     * Attempts a login with a specified fixture response file.
     * @param string $fixturefilename
     * @return bool
     */
    public function attempt_login($fixturefilename) {

        test_safeassign_api_connectors::config_set_ok();
        $loginurl = test_safeassign_api_connectors::create_login_url($this->user);
        testhelper::push_pair($loginurl, $fixturefilename);
        $result = safeassign_api::login($this->user->id);
        return $result;
    }

    /**
     * Attempts to create a uuid for a course.
     * @return string
     */
    public function create_course_uuid() {
        // Login to Safe Assign.
        $this->attempt_login('user-login-final.json');
        // Push fixtures as cached responses.
        $courseurl = test_safeassign_api_connectors::create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        $result = safeassign_api::create_course($this->user->id, $this->course->id);
        $this->assertNotEmpty($result->uuid);
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);
        return $result->uuid;
    }

    /**
     * Attempts to create a uuid for an assignment in a course.
     * @param string $courseuuid uuid of the course that will have the assignment
     * @return string
     */
    public function create_assignment_uuid($courseuuid) {
        // Push fixture for assignment creation.
        $assignmenturl = test_safeassign_api_connectors::create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $this->assignment->id, $this->assignment->title);
        $this->assertNotEmpty($result->uuid);
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);
        return $result->uuid;
    }

    /**
     * Attempts to create a uuid for a submission.
     * @param string $courseuuid course that have an assignment
     * @param string $assignmentuuid assignment that will have the submission
     * @return string
     */
    public function create_submission_uuid($courseuuid, $assignmentuuid) {
        $submissionurl = test_safeassign_api_connectors::create_submission_url($courseuuid, $assignmentuuid);
        testhelper::push_pair($submissionurl, 'create-submission-ok.json');
        // Create a submission with an array of files for an assignment.
        $filepaths = array('/path/file1.txt', '/path/file2.txt', '/path/file3.json', '/path/file4.zip', '/path/file5.csv');
        $result = safeassign_api::create_submission($this->user->id, $courseuuid, $assignmentuuid, $filepaths, false, false);
        $this->assertTrue($result);
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);
        $result = json_decode(rest_provider::instance()->lastresponse());
        return $result->submissions[0]->submission_uuid;
    }

    /**
     * Attempts to login with a correct configuration.
     * @return void
     */
    public function test_login_configured_ok() {
        $this->resetAfterTest(true);
        $result = $this->attempt_login('user-login-final.json');
        $this->assertTrue($result);
    }

    /**
     * Simulates a failure login.
     * @return void
     */
    public function test_login_fail() {
        $this->resetAfterTest(true);
        $result = $this->attempt_login('user-login-fail-final.json');
        $this->assertFalse($result);
    }

    /**
     * Attempts to login with an empty configuration.
     * @return void
     */
    public function test_login_notconfigured_fail() {
        $this->resetAfterTest(true);
        $this->config_cleanup();
        $result = safeassign_api::login($this->user->id);
        $this->assertFalse( $result );
    }

    /**
     * Attempts to create a course with a valid response.
     * @return void
     */
    public function test_create_course_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();
        $this->attempt_login('user-login-final.json');
        $courseurl = test_safeassign_api_connectors::create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        testhelper::push_pair($courseurl.'?id='.$this->course->id, 'create-course-final.json');

        // Test creating the course.
        $result = safeassign_api::create_course($this->user->id, $this->course->id);
        $this->assertTrue(!empty($result->uuid));

        // Test verifying the course.
        $result = safeassign_api::get_course($this->user->id, $this->course->id);
        $this->assertTrue(!empty($result->uuid));
    }

    /**
     * Simulates a failure creating a course.
     * @return void
     */
    public function test_create_course_fail() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $this->attempt_login('user-login-final.json');
        // Push fixtures as cached responses.
        $courseurl = test_safeassign_api_connectors::create_course_url();
        testhelper::push_pair($courseurl, 'create-course-fail-final.json');
        testhelper::push_pair($courseurl.'?id='.$this->course->id, 'create-course-fail-final.json');

        // Test creating the course.
        $result = safeassign_api::create_course($this->user->id, $this->course->id);
        $this->assertTrue(empty($result->uuid));
        $this->assertTrue(!empty($result->ERROR_ID) && !empty($result->ERROR_CODE));

        // Test verifying the course.
        $result = safeassign_api::get_course($this->user->id, $this->course->id);
        $this->assertTrue(empty($result->uuid));
        $this->assertTrue(!empty($result->ERROR_ID) && !empty($result->ERROR_CODE));
    }

    /**
     * Attempts to delete an instructor from a course with a valid response.
     * @return void
     */
    public function test_put_delete_instructor_to_course_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $courseuuid = $this->create_course_uuid();
        // Put instructor to created course.
        // Push fixtures for put-delete as cached responses.
        $putdeleteinstructorurl = test_safeassign_api_connectors::create_put_delete_instructor_url($courseuuid);
        testhelper::push_pair($putdeleteinstructorurl, 'put-delete-instructor-ok.json');

        // Test for adding instructor to course.
        $result = safeassign_api::put_instructor_to_course($this->user->id, $courseuuid);
        $this->assertTrue($result);
        $httpcode = rest_provider::instance()->lasthttpcode();
        $this->assertEquals($httpcode, 200);

        // Test for deleting instructor from course.
        $result = safeassign_api::delete_instructor_from_course($this->user->id, $courseuuid);
        $this->assertTrue($result);
        $httpcode = rest_provider::instance()->lasthttpcode();
        $this->assertEquals($httpcode, 200);
    }

    /**
     * Simulates a failure deleting an instructor from a course.
     * @return void
     */
    public function test_put_instructor_to_course_fail_wrong_token() {
        $this->resetAfterTest(true);
        $this->config_set_ok();
        $courseuuid = $this->create_course_uuid();

        // Put instructor to created course.
        // Push fixtures for put-delete as cached responses.
        $putdeleteinstructorurl = test_safeassign_api_connectors::create_put_delete_instructor_url($courseuuid);
        testhelper::push_pair($putdeleteinstructorurl, 'put-delete-instructor-fail-wrong-token.json', 401);

        // Test for put intructor to course.
        $result = safeassign_api::put_instructor_to_course($this->user->id, $courseuuid);
        $this->assertFalse($result);
        $httpcode = rest_provider::instance()->lasthttpcode();
        $this->assertTrue($httpcode >= 400);

        // Test for deleting instructor from course.
        $result = safeassign_api::delete_instructor_from_course($this->user->id, $courseuuid);
        $this->assertFalse($result);
        $httpcode = rest_provider::instance()->lasthttpcode();
        $this->assertTrue($httpcode >= 400);
    }

    /**
     * Attempts to create an assignment.
     * @return void
     */
    public function test_create_assignment_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();
        $courseuuid = $this->create_course_uuid();

        // Push fixture for assignment creation.
        $assignmenturl = test_safeassign_api_connectors::create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $this->assignment->id, $this->assignment->title);
        $this->assertTrue(!empty($result->id) && !empty($result->uuid) && !empty($result->title));
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);
    }

    /**
     * Simulates a failure creating an assignment.
     * @return void
     */
    public function test_create_assignment_fail() {
        $this->resetAfterTest(true);
        $this->config_set_ok();
        $courseuuid = $this->create_course_uuid();
        // Push fixture for assignment creation.
        $assignmenturl = test_safeassign_api_connectors::create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-fail.json', 401);
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $this->assignment->id, $this->assignment->title);
        $this->assertFalse($result);
        $this->assertTrue(rest_provider::instance()->lasthttpcode() >= 400);
    }


    /**
     * Attempts to check an assignment.
     * @return void
     */
    public function test_check_assignment_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();
        $courseuuid = $this->create_course_uuid();

        // Push fixture for assignment creation.
        $assignmenturl = test_safeassign_api_connectors::create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $this->assignment->id, $this->assignment->title);
        $this->assertTrue(!empty($result->id) && !empty($result->uuid) && !empty($result->title));

        // Check created assignment.
        $assignmentid = $result->id;
        $assignurl = test_safeassign_api_connectors::create_assignment_url($courseuuid, $assignmentid);
        testhelper::push_pair($assignurl . '?id=' . $this->assignment->id, "create-assignment-ok.json");
        $result = safeassign_api::check_assignment($this->user->id, $courseuuid, $this->assignment->id);
        $this->assertTrue(!empty($result->id) && !empty($result->uuid) && !empty($result->title));
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);
    }

    /**
     * Simulates a failure checking an assignment.
     * @return void
     */
    public function test_check_assignment_fail() {
        $this->resetAfterTest(true);
        $this->config_set_ok();
        $courseuuid = $this->create_course_uuid();
        // Push fixture for assignment creation.
        $assignmenturl = test_safeassign_api_connectors::create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $this->assignment->id, $this->assignment->title);
        $this->assertTrue(!empty($result->id) && !empty($result->uuid) && !empty($result->title));

        // Check created assignment.
        $assignmentid = $result->id;
        $assignurl = test_safeassign_api_connectors::create_assignment_url($courseuuid);
        testhelper::push_pair($assignurl . '?id=' . $this->assignment->id, "create-assignment-fail.json", 401);
        $result = safeassign_api::check_assignment($this->user->id, $courseuuid, $this->assignment->id);
        $this->assertFalse($result);
        $this->assertTrue(rest_provider::instance()->lasthttpcode() >= 400);
    }

    /**
     * Attempts to create a submission.
     * @return void
     */
    public function test_create_submission_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();
        $courseuuid = $this->create_course_uuid();
        $assignmentuuid = $this->create_assignment_uuid($courseuuid);

        // Push fixture for submission creation.
        $submissionurl = test_safeassign_api_connectors::create_submission_url($courseuuid, $assignmentuuid);
        testhelper::push_pair($submissionurl, 'create-submission-ok.json');
        // Create a submission with an array of files for an assignment.
        $filepaths = array('/path/file1.txt', '/path/file2.txt', '/path/file3.json', '/path/file4.zip', '/path/file5.csv');
        $result = safeassign_api::create_submission($this->user->id, $courseuuid, $assignmentuuid, $filepaths, false, false);
        $this->assertTrue($result);
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);
    }

    /**
     * Simulates a failure creating a submission.
     * @return void
     */
    public function test_create_submission_fail() {
        $this->resetAfterTest(true);
        $this->config_set_ok();
        $courseuuid = $this->create_course_uuid();
        $assignmentuuid = $this->create_assignment_uuid($courseuuid);

        // Push fixture for submission creation.
        $submissionurl = test_safeassign_api_connectors::create_submission_url($courseuuid, $assignmentuuid);
        testhelper::push_pair($submissionurl, 'create-submission-fail.json', 400);
        // Create a submission with an array of files for an assignment.
        $filepaths = array('/path/file1.txt', '/path/file2.txt', '/path/file3.json', '/path/file4.zip', '/path/file5.csv');
        $result = safeassign_api::create_submission($this->user->id, $courseuuid, $assignmentuuid, $filepaths, false, false);

        $this->assertFalse($result);
        $this->assertTrue(rest_provider::instance()->lasthttpcode() >= 400);
    }

    /**
     * Attempts to get the basic originality report.
     * @return void
     */
    public function test_get_originality_report_basic_data_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();
        $courseuuid = $this->create_course_uuid();
        $assignmentuuid = $this->create_assignment_uuid($courseuuid);
        $submissionuuid = $this->create_submission_uuid($courseuuid, $assignmentuuid);

        // Get originality report basic data.
        // Push fixtures as cached responses.
        $getreporturl = test_safeassign_api_connectors::create_get_originality_report_basic_data_url($submissionuuid);
        testhelper::push_pair($getreporturl, 'get-originality-report-basic-data-ok.json');
        $result = safeassign_api::get_originality_report_basic_data($this->user->id, $submissionuuid);
        $this->assertTrue(!empty($result->highest_score) && !empty($result->average_score) && !empty($result->submission_files));
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);
    }

    /**
     * Simulates a failure getting the basic originality report.
     * @return void
     */
    public function test_get_originality_report_basic_data_fail() {
        $this->resetAfterTest(true);
        $this->config_set_ok();
        $courseuuid = $this->create_course_uuid();
        $assignmentuuid = $this->create_assignment_uuid($courseuuid);
        $submissionuuid = $this->create_submission_uuid($courseuuid, $assignmentuuid);

        // Get originality report basic data.
        // Push fixtures as cached responses.
        $getreporturl = test_safeassign_api_connectors::create_get_originality_report_basic_data_url($submissionuuid);
        testhelper::push_pair($getreporturl, 'get-originality-report-basic-data-fail.json', 404);
        $result = safeassign_api::get_originality_report_basic_data($this->user->id, $submissionuuid);
        $this->assertFalse($result);
        $this->assertTrue(rest_provider::instance()->lasthttpcode() >= 400);
    }

    /**
     * Attempts to get the originality report.
     * @return void
     */
    public function test_get_originality_report_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();
        $courseuuid = $this->create_course_uuid();
        $assignmentuuid = $this->create_assignment_uuid($courseuuid);
        $submissionuuid = $this->create_submission_uuid($courseuuid, $assignmentuuid);

        // Get originality report.
        // Push fixtures as cached responses.
        $getreporturl = test_safeassign_api_connectors::create_get_originality_report_url($submissionuuid);
        testhelper::push_pair($getreporturl, 'get-originality-report-ok.html');
        $result = safeassign_api::get_originality_report($this->user->id, $submissionuuid);
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);

        // Result should have html tags.
        $this->assertNotEquals(strip_tags($result), $result);
    }

    /**
     * Simulates a failure getting the originality report.
     * @return void
     */
    public function test_get_originality_report_fail() {
        $this->resetAfterTest(true);
        $this->config_set_ok();
        $courseuuid = $this->create_course_uuid();
        $assignmentuuid = $this->create_assignment_uuid($courseuuid);
        $submissionuuid = $this->create_submission_uuid($courseuuid, $assignmentuuid);

        // Get originality report.
        // Push fixtures as cached responses.
        $getreporturl = test_safeassign_api_connectors::create_get_originality_report_url($submissionuuid);
        testhelper::push_pair($getreporturl, 'get-originality-report-basic-data-fail.json', 404);
        $result = safeassign_api::get_originality_report($this->user->id, $submissionuuid);
        $domdoc = new DOMDocument();
        $domdoc->loadHTML(rest_provider::instance()->lastresponse(), LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
        $this->assertFalse($result);

        $lastresponse = rest_provider::instance()->lastresponse();
        // Result should not have html tags.
        $this->assertEquals(strip_tags($lastresponse), $lastresponse);
    }

    /**
     * Attempt to resubmit a file.
     * @return void
     */
    public function test_resubmit_file_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();
        $courseuuid = $this->create_course_uuid();
        $assignmentuuid = $this->create_assignment_uuid($courseuuid);
        $submissionuuid = $this->create_submission_uuid($courseuuid, $assignmentuuid);

        $resubmitfileurl = test_safeassign_api_connectors::create_resubmit_file_url($submissionuuid);
        testhelper::push_pair($resubmitfileurl, 'resubmit-file-ok.json');
        $fileuuid = "4db799a8-418b-7315-0323-75ab5f4e30cd";
        $urls = array("url_1", "url_2");
        $engines = array("engine_name_1", "engine_name_2");
        $result = safeassign_api::resubmit_file($this->user->id, $submissionuuid, $fileuuid, $urls, $engines);
        $this->assertTrue(!empty($result->status) && $result->status == "SUCCESS");
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);
    }

    /**
     * Simulates a failure resubmitting a file.
     * @return void
     */
    public function test_resubmit_file_fail() {
        $this->resetAfterTest(true);
        $this->config_set_ok();
        $courseuuid = $this->create_course_uuid();
        $assignmentuuid = $this->create_assignment_uuid($courseuuid);
        $submissionuuid = $this->create_submission_uuid($courseuuid, $assignmentuuid);

        $resubmitfileurl = test_safeassign_api_connectors::create_resubmit_file_url( $submissionuuid);
        testhelper::push_pair($resubmitfileurl, 'resubmit-file-fail.json', 400);
        $fileuuid = "4db799a8-418b-7315-0323-75ab5f4e30cd";
        $urls = array("url_1", "url_2");
        $engines = array("engine_name_1", "engine_name_2");
        $result = safeassign_api::resubmit_file($this->user->id, $submissionuuid, $fileuuid, $urls, $engines);
        $this->assertFalse($result);
        $this->assertTrue(rest_provider::instance()->lasthttpcode() >= 400);
    }

    /**
     * Skips last API error.
     * @return void
     */
    public function test_process_last_api_error_skip() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $this->attempt_login('user-login-final.json');
        $course = test_safeassign_api_connectors::create_course();

        // Push fixtures as cached responses.
        $courseurl = test_safeassign_api_connectors::create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        testhelper::push_pair($courseurl.'?id='.$course->id, 'create-course-final.json');

        // Test creating the course.
        safeassign_api::create_course($this->user->id, $course->id);
        $this->assertNull(error_handler::process_last_api_error());
    }

    /**
     * Process last API error.
     * @return void
     */
    public function test_process_last_api_error_process() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $this->attempt_login('user-login-final.json');
        $course = test_safeassign_api_connectors::create_course();

        // Push fixtures as cached responses.
        $courseurl = test_safeassign_api_connectors::create_course_url();
        testhelper::push_pair($courseurl, 'create-course-fail-final.json', 401);

        // Test creating the course.
        safeassign_api::create_course($this->user->id, $course->id);

        $expected = get_string('error_api_unauthorized', 'plagiarism_safeassign').PHP_EOL;
        $expected .= 'ERROR_ID: b53ad9d2-6e83-4592-8c04-18dada86b14b'.PHP_EOL;
        $expected .= 'ERROR_CODE: WRONG_PARAMETER'.PHP_EOL;

        $this->assertEquals($expected, error_handler::process_last_api_error(false, true));
    }

    /**
     * Multiple login test.
     * @return void
     */
    public function test_multi_login() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $usera = test_safeassign_api_connectors::create_user("TeacherA", "WhoTeachesA");

        // Login with User A.
        $loginurla = test_safeassign_api_connectors::create_login_url($usera);
        testhelper::push_pair($loginurla, 'user-login-final.json');
        $resulta = safeassign_api::login($usera->id);
        $tokena = rest_provider::instance()->gettoken();

        self::assertTrue($resulta);
        self::assertNotEmpty($tokena);

        $userb = test_safeassign_api_connectors::create_user("TeacherB", "WhoTeachesB");

        // Login with User B.
        $loginurlb = test_safeassign_api_connectors::create_login_url($userb);
        testhelper::push_pair($loginurlb, 'userb-login-final.json');
        $resultb = safeassign_api::login($userb->id);

        $tokenb = rest_provider::instance()->gettoken();

        self::assertTrue($resultb);
        self::assertNotEmpty($tokenb);

        self::assertNotEquals($tokena, $tokenb);

        $userc = test_safeassign_api_connectors::create_user("TeacherC", "WhoTeachesC");

        // Login fails with User C.
        $loginurlc = test_safeassign_api_connectors::create_login_url($userc);
        testhelper::push_pair($loginurlc, 'user-login-fail-final.json');
        $resultc = safeassign_api::login($userc->id);

        $tokenc = rest_provider::instance()->gettoken();

        self::assertFalse($resultc);
        self::assertNull($tokenc);
    }

    /**
     * Get all licenses test ok.
     * @return void
     */
    public function test_get_licenses_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $this->attempt_login('user-login-final.json');

        $getlicenseurl = test_safeassign_api_connectors::create_get_licenses_url();
        testhelper::push_pair($getlicenseurl, 'get-licenses-ok.json');
        $result = safeassign_api::get_licenses($this->user->id);

        $this->assertTrue(!empty($result));
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);
    }

    /**
     * Get all licenses test fail.
     * @return void
     */
    public function test_get_licenses_fail() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $this->attempt_login('user-login-final.json');

        $getlicenseurl = test_safeassign_api_connectors::create_get_licenses_url();
        testhelper::push_pair($getlicenseurl, 'default-fail.json', 400);
        $result = safeassign_api::get_licenses($this->user->id);

        $this->assertFalse($result);
        $this->assertTrue(rest_provider::instance()->lasthttpcode() >= 400);
    }

    /**
     * Get accepted licenses test ok.
     * @return void
     */
    public function test_get_accepted_licenses_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $this->attempt_login('user-login-final.json');

        $getlicenseurl = test_safeassign_api_connectors::create_get_accepted_licenses_url();
        testhelper::push_pair($getlicenseurl, 'get-accepted-licenses-ok.json');
        $result = safeassign_api::get_accepted_licenses($this->user->id);

        $this->assertTrue(!empty($result));
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);
    }

    /**
     * Get accepted licenses test fail.
     * @return void
     */
    public function test_get_accepted_licenses_fail() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $this->attempt_login('user-login-final.json');

        $getlicenseurl = test_safeassign_api_connectors::create_get_accepted_licenses_url();
        testhelper::push_pair($getlicenseurl, 'default-fail.json', 400);
        $result = safeassign_api::get_accepted_licenses($this->user->id);

        $this->assertFalse($result);
        $this->assertTrue(rest_provider::instance()->lasthttpcode() >= 400);
    }

    /**
     * Accept license test ok.
     * @return void
     */
    public function test_accept_license_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $this->attempt_login('user-login-final.json');

        $acceptlicenseurl = test_safeassign_api_connectors::create_accept_license_url();
        testhelper::push_pair($acceptlicenseurl, 'empty-file.json');
        $result = safeassign_api::accept_license($this->user->id, 'John', 'Doe', 'john.doe@mail.com');

        $this->assertTrue($result);
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);

        // Test using the license version.
        $result = safeassign_api::accept_license($this->user->id, 'John', 'Doe', 'john.doe@mail.com', '123456');

        $this->assertTrue($result);
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);
    }

    /**
     * Accept license test fail.
     * @return void
     */
    public function test_accept_license_fail() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $this->attempt_login('user-login-final.json');

        $acceptlicenseurl = test_safeassign_api_connectors::create_accept_license_url();
        testhelper::push_pair($acceptlicenseurl, 'default-fail.json', 400);
        $result = safeassign_api::accept_license($this->user->id, 'John', 'Doe', 'john.doe@mail.com');

        $this->assertFalse($result);
        $this->assertTrue(rest_provider::instance()->lasthttpcode() >= 400);

        // Test using the license version.
        $result = safeassign_api::accept_license($this->user->id, 'John', 'Doe', 'john.doe@mail.com', '123456');

        $this->assertFalse($result);
        $this->assertTrue(rest_provider::instance()->lasthttpcode() >= 400);
    }

    /**
     * Revoke license test ok.
     * @return void
     */
    public function test_revoke_license_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $this->attempt_login('user-login-final.json');

        $revokelicenseurl = test_safeassign_api_connectors::create_revoke_license_url();
        testhelper::push_pair($revokelicenseurl, 'empty-file.json');
        $result = safeassign_api::revoke_license($this->user->id);

        $this->assertTrue($result);
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);

        // Test using license version.
        $licenseversion = '123456';
        $revokelicenseurl = test_safeassign_api_connectors::create_revoke_license_url($licenseversion);
        testhelper::push_pair($revokelicenseurl, 'empty-file.json');
        $result = safeassign_api::revoke_license($this->user->id, $licenseversion);

        $this->assertTrue($result);
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);
    }

    /**
     * Revoke license test fail.
     * @return void
     */
    public function test_revoke_license_fail() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $this->attempt_login('user-login-final.json');

        $revokelicenseurl = test_safeassign_api_connectors::create_revoke_license_url();
        testhelper::push_pair($revokelicenseurl, 'default-fail.json', 400);
        $result = safeassign_api::revoke_license($this->user->id);

        $this->assertFalse($result);
        $this->assertTrue(rest_provider::instance()->lasthttpcode() >= 400);

        // Test using license version.
        $licenseversion = '123456';
        $revokelicenseurl = test_safeassign_api_connectors::create_revoke_license_url($licenseversion);
        testhelper::push_pair($revokelicenseurl, 'default-fail.json', 400);
        $result = safeassign_api::revoke_license($this->user->id, $licenseversion);

        $this->assertFalse($result);
        $this->assertTrue(rest_provider::instance()->lasthttpcode() >= 400);
    }

    /**
     * Process last API error for originality report.
     * @return void
     */
    public function test_process_last_api_originality_error_process() {
        $this->resetAfterTest(true);
        $this->config_set_ok();
        $courseuuid = $this->create_course_uuid();
        $assignmentuuid = $this->create_assignment_uuid($courseuuid);
        $submissionuuid = $this->create_submission_uuid($courseuuid, $assignmentuuid);

        // Get originality report.
        // Push fixtures as cached responses.
        $getreporturl = test_safeassign_api_connectors::create_get_originality_report_url($submissionuuid);
        testhelper::push_pair($getreporturl, 'forbidden_error.json', 403);
        $result = safeassign_api::get_originality_report($this->user->id, $submissionuuid);
        $this->assertFalse($result);

        $expected = get_string('error_api_forbidden', 'plagiarism_safeassign').PHP_EOL;
        $expected .= 'ERROR_ID: b53ad9d2-6e83-4592-8c04-18dada86b14b'.PHP_EOL;
        $expected .= 'ERROR_CODE: FORBIDDEN'.PHP_EOL;
        $this->assertEquals($expected, error_handler::process_last_api_error(false, true));

        // Get originality report.
        // Push fixtures as cached responses.
        $getreporturl = test_safeassign_api_connectors::create_get_originality_report_url($submissionuuid);
        testhelper::push_pair($getreporturl, 'not_found_error.json', 404);
        $result = safeassign_api::get_originality_report($this->user->id, $submissionuuid);
        $this->assertFalse($result);

        $expected = get_string('error_api_not_found', 'plagiarism_safeassign').PHP_EOL;
        $expected .= 'ERROR_ID: b53ad9d2-6e83-4592-8c04-18dada86b14b'.PHP_EOL;
        $expected .= 'ERROR_CODE: NOT_FOUND'.PHP_EOL;
        $this->assertEquals($expected, error_handler::process_last_api_error(false, true));

        // Get originality report.
        // Push fixtures as cached responses.
        $getreporturl = test_safeassign_api_connectors::create_get_originality_report_url($submissionuuid);
        testhelper::push_pair($getreporturl, 'unauthorized_error.json', 401);
        $result = safeassign_api::get_originality_report($this->user->id, $submissionuuid);
        $this->assertFalse($result);

        $expected = get_string('error_api_unauthorized', 'plagiarism_safeassign').PHP_EOL;
        $expected .= 'ERROR_ID: b53ad9d2-6e83-4592-8c04-18dada86b14b'.PHP_EOL;
        $expected .= 'ERROR_CODE: UNAUTHORIZED'.PHP_EOL;
        $this->assertEquals($expected, error_handler::process_last_api_error(false, true));
    }

    /**
     * If there are non valid submissions, fix them with info from mr tables.
     * @return void
     */
    public function test_get_unsynced_submissions() {
        global $DB;
        $this->resetAfterTest(true);

        // Simulating submissions on mr tables.
        $countersubm = 10;
        $assignsubmissions = [];
        for ($i = 0; $i < $countersubm; $i++) {
            $assignid = 100 + $i;
            $submid = $DB->insert_record("assign_submission", (object) array(
                "assignment" => $assignid,
                "userid" => 1234
            ), true);

            $assignsubmissions[] = array("assign" => $assignid, "subm" => $submid);
        }

        // Simulating non valid records on SafeAssign tables.
        // Non valid could be assignment id 0 or different from submission Tables.
        foreach ($assignsubmissions as $submission) {
            $DB->insert_record("plagiarism_safeassign_subm", (object)array(
                "submissionid" => $submission['subm'],
                "assignmentid" => rand(0, 1) == 1 ? 0 : 1234567890,
                "deprecated" => 0
            ));
        }

        // Count non valid records.
        $nonvalid = 0;
        foreach ($assignsubmissions as $submission) {
            $record = $DB->get_record("plagiarism_safeassign_subm", array(
                "submissionid" => $submission['subm']), "assignmentid");
            $nonvalid += $submission["assign"] != $record->assignmentid;
        }
        $this->assertEquals($countersubm, $nonvalid);

        // Getting unsynced submissions.
        $plagiarismplugin = new plagiarism_plugin_safeassign();
        $plagiarismplugin->get_unsynced_submissions();

        // Check that all submissions have valid assignment id.
        foreach ($assignsubmissions as $submission) {
            $record = $DB->get_record("plagiarism_safeassign_subm", array(
                "submissionid" => $submission['subm']), "assignmentid");
            $this->assertEquals($submission["assign"], $record->assignmentid);
        }
    }
}
