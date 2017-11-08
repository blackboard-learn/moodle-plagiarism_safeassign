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

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__.'/base.php');

use plagiarism_safeassign\api\safeassign_api;
use plagiarism_safeassign\api\testhelper;
use plagiarism_safeassign\api\rest_provider;
use plagiarism_safeassign\api\error_handler;
use plagiarism_safeassign\api\test_safeassign_api_connectors;
/**
 * Class plagiarism_safeassign_safeassign_api_testcase
 *
 * All tests in this class will fail in case there is no appropriate fixture to be loaded.
 *
 * @group plagiarism_safeassign
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
     * @return void
     */
    public function test_login_configured_ok() {
        $this->resetAfterTest(true);
        $result = $this->attempt_login('user-login-final.json');
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function test_login_fail() {
        $this->resetAfterTest(true);
        $result = $this->attempt_login('user-login-fail-final.json');
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function test_login_notconfigured_fail() {
        $this->resetAfterTest(true);
        $this->config_cleanup();
        $result = safeassign_api::login($this->user->id);
        $this->assertFalse( $result );
    }

    /**
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
}
