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

/**
 * Class plagiarism_safeassign_safeassign_api_testcase
 *
 * All tests in this class will fail in case there is no appropriate fixture to be loaded.
 *
 * @group plagiarism_safeassign
 */
class plagiarism_safeassign_safeassign_api_testcase extends plagiarism_safeassign_base_testcase {

    private $user;

    /**
     * @return void
     */
    public function setUp() {
        $this->reset_ws();
    }

    /**
     * @param $user
     * @return string
     */
    private function create_login_url($user) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $loginurl = '%s/api/v1/tokens?';
        $loginurl .= 'grant_type=client_credentials&user_id=%s&user_firstname=%s&user_lastname=%s';
        $loginurl = sprintf($loginurl, $baseapiurl, $user->id, $user->firstname, $user->lastname);
        return $loginurl;
    }

    /**
     * Pushes a response for the login url for a user
     * @param $user
     * @param $filename
     */
    public function push_login_url($user, $filename) {
        $loginurl = $this->create_login_url($user);
        testhelper::push_pair($loginurl, $filename);
    }

    /**
     * @return string
     */
    public function create_course_url() {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $courseurl = '%s/api/v1/courses';
        $courseurl = sprintf($courseurl, $baseapiurl);
        return $courseurl;
    }

    /**
     * Attempts a login with a specified fixture response file.
     * User attribute is populated with logged in user.
     * @param $filename
     * @return bool
     */
    public function attempt_login($filename) {
        $this->user = $this->getDataGenerator()->create_user([
            'firstname' => 'Teacher',
            'lastname' => 'WhoTeaches'
        ]);

        // Tell the cache to load specific fixture for login url.
        $loginurl = $this->create_login_url($this->user);
        testhelper::push_pair($loginurl, $filename);
        $result = safeassign_api::login($this->user->id);
        return $result;
    }

    /**
     * Creates an url to put/delete an instructor to/from the course with the given uuid.
     * @param $courseuuid
     * @return string
     */
    public function create_put_delete_instructor_url($courseuuid) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $courseurl = '%s/api/v1/courses/%s/members';
        $courseurl = sprintf($courseurl, $baseapiurl, $courseuuid);
        return $courseurl;
    }

    /**
     * Creates an url to create an assignment in a course.
     * @param $courseuuid
     * @return string
     */
    public function create_assignment_url($courseuuid) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $assignmenturl = '%s/api/v1/courses/%s/assignments';
        $assignmenturl = sprintf($assignmenturl, $baseapiurl, $courseuuid);
        return $assignmenturl;
    }

    /**
     * Creates an url to create a submission in an assignment.
     * @param string $courseuuid
     * @param string $assignmentuuid
     * @return string
     */
    public function create_submission_url($courseuuid, $assignmentuuid) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $submissionurl = '%s/api/v1/courses/%s/assignments/%s/submissions';
        $submissionurl = sprintf($submissionurl, $baseapiurl, $courseuuid, $assignmentuuid);
        return $submissionurl;
    }

    /**
     * Creates an url to get the originality report basic data.
     * @param string $submissionuuid
     * @return string
     */
    private function create_get_originality_report_basic_data_url($submissionuuid) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $getreporturl = '%s/api/v1/submissions/%s/report/metadata';
        $getreporturl = sprintf($getreporturl, $baseapiurl, $submissionuuid);
        return $getreporturl;
    }

    /**
     * Creates an url to get the originality report.
     * @param string $submissionuuid
     * @return string
     */
    private function create_get_originality_report_url($submissionuuid) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $getreporturl = '%s/api/v1/submissions/%s/report';
        $getreporturl = sprintf($getreporturl, $baseapiurl, $submissionuuid);
        return $getreporturl;
    }

    /**
     * Creates an url to resubmit a file.
     * @param string $submissionuuid
     * @return string
     */
    private function create_resubmit_file_url($submissionuuid) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $resubmitfileurl = '%s/api/v1/submissions/%s';
        $resubmitfileurl = sprintf($resubmitfileurl, $baseapiurl, $submissionuuid);
        return $resubmitfileurl;
    }

    /**
     * Creates an url to delete a submission.
     * @param string $submissionuuid
     * @return string
     */
    public function create_delete_submission_url($submissionuuid) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $deleteurl = '%s/api/v1/submissions/%s';
        $deleteurl = sprintf($deleteurl, $baseapiurl, $submissionuuid);
        return $deleteurl;

    }

    /**
     * Creates an assignment for testing.
     * @return stdClass
     */
    private function create_assignment() {
        $assignment = new stdClass();
        $assignment->id = "1234567890";
        $assignment->title = "Test assignment for creation";

        return $assignment;
    }

    /**
     * @return void
     */
    public function test_login_configured_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $result = $this->attempt_login('user-login-final.json');
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function test_login_fail() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $result = $this->attempt_login('user-login-fail-final.json');
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function test_login_notconfigured_fail() {
        $this->resetAfterTest(true);
        $this->config_cleanup();

        $user = $this->getDataGenerator()->create_user();
        $this->assertFalse( safeassign_api::login($user->id) );
    }

    /**
     * @return void
     */
    public function test_create_course_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $this->attempt_login('user-login-final.json');
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);

        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        testhelper::push_pair($courseurl.'?id='.$course->id, 'create-course-final.json');

        // Test creating the course.
        $result = safeassign_api::create_course($this->user->id, $course->id);
        $this->assertTrue(!empty($result->uuid));

        // Test verifying the course.
        $result = safeassign_api::get_course($this->user->id, $course->id);
        $this->assertTrue(!empty($result->uuid));
    }

    /**
     * @return void
     */
    public function test_create_course_fail() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $this->attempt_login('user-login-final.json');
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);

        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-fail-final.json');
        testhelper::push_pair($courseurl.'?id='.$course->id, 'create-course-fail-final.json');

        // Test creating the course.
        $result = safeassign_api::create_course($this->user->id, $course->id);
        $this->assertTrue(empty($result->uuid));
        $this->assertTrue(!empty($result->ERROR_ID) && !empty($result->ERROR_CODE));

        // Test verifying the course.
        $result = safeassign_api::get_course($this->user->id, $course->id);
        $this->assertTrue(empty($result->uuid));
        $this->assertTrue(!empty($result->ERROR_ID) && !empty($result->ERROR_CODE));
    }

    /**
     * @return void
     */
    public function test_put_delete_instructor_to_course_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        // Login to Safe Assign.
        $this->attempt_login('user-login-final.json');
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);
        // Create a course.
        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        $result = safeassign_api::create_course($this->user->id, $course->id);
        $this->assertNotEmpty($result->uuid);
        $courseuuid = $result->uuid;

        // Put instructor to created course.
        // Push fixtures for put-delete as cached responses.
        $putdeleteinstructorurl = $this->create_put_delete_instructor_url($courseuuid);
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

        // Login to Safe Assign.
        $this->attempt_login('user-login-final.json');
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);
        // Create a course.
        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        $result = safeassign_api::create_course($this->user->id, $course->id);
        $this->assertNotEmpty($result->uuid);
        $courseuuid = $result->uuid;

        // Put instructor to created course.
        // Push fixtures for put-delete as cached responses.
        $putdeleteinstructorurl = $this->create_put_delete_instructor_url($courseuuid);
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

        // Login to Safe Assign.
        $this->attempt_login('user-login-final.json');
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);
        // Create a course.
        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        $result = safeassign_api::create_course($this->user->id, $course->id);
        $this->assertNotEmpty($result->uuid);
        $courseuuid = $result->uuid;

        // Create an assignment.
        $assignment = self::create_assignment();

        // Push fixture for assignment creation.
        $assignmenturl = $this->create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $assignment->id, $assignment->title);
        $this->assertTrue(!empty($result->id) && !empty($result->uuid) && !empty($result->title));
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);
    }

    /**
     * @return void
     */
    public function test_create_assignment_fail() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        // Login to Safe Assign.
        $this->attempt_login('user-login-final.json');
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);
        // Create a course.
        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        $result = safeassign_api::create_course($this->user->id, $course->id);
        $this->assertNotEmpty($result->uuid);
        $courseuuid = $result->uuid;

        // Create an assignment.
        $assignment = self::create_assignment();

        // Push fixture for assignment creation.
        $assignmenturl = $this->create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-fail.json', 401);
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $assignment->id, $assignment->title);
        $this->assertFalse($result);
        $this->assertTrue(rest_provider::instance()->lasthttpcode() >= 400);
    }


    /**
     * @return void
     */
    public function test_check_assignment_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        // Login to Safe Assign.
        $this->attempt_login('user-login-final.json');
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);
        // Create a course.
        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        $result = safeassign_api::create_course($this->user->id, $course->id);
        $this->assertNotEmpty($result->uuid);
        $courseuuid = $result->uuid;
        // Create an assignment.
        $assignment = self::create_assignment();

        // Push fixture for assignment creation.
        $assignmenturl = $this->create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $assignment->id, $assignment->title);
        $this->assertTrue(!empty($result->id) && !empty($result->uuid) && !empty($result->title));

        // Check created assignment.
        $assignmentid = $result->id;
        $assignurl = $this->create_assignment_url($courseuuid, $assignmentid);
        testhelper::push_pair($assignurl . '?id=' . $assignment->id, "create-assignment-ok.json");
        $result = safeassign_api::check_assignment($this->user->id, $courseuuid, $assignment->id);
        $this->assertTrue(!empty($result->id) && !empty($result->uuid) && !empty($result->title));
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);
    }

    /**
     * @return void
     */
    public function test_check_assignment_fail() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        // Login to Safe Assign.
        $this->attempt_login('user-login-final.json');
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);
        // Create a course.
        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        $result = safeassign_api::create_course($this->user->id, $course->id);
        $this->assertNotEmpty($result->uuid);
        $courseuuid = $result->uuid;

        // Create an assignment.
        $assignment = self::create_assignment();
        // Push fixture for assignment creation.
        $assignmenturl = $this->create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $assignment->id, $assignment->title);
        $this->assertTrue(!empty($result->id) && !empty($result->uuid) && !empty($result->title));

        // Check created assignment.
        $assignmentid = $result->id;
        $assignurl = $this->create_assignment_url($courseuuid);
        testhelper::push_pair($assignurl . '?id=' . $assignment->id, "create-assignment-fail.json", 401);
        $result = safeassign_api::check_assignment($this->user->id, $courseuuid, $assignment->id);
        $this->assertFalse($result);
        $this->assertTrue(rest_provider::instance()->lasthttpcode() >= 400);
    }

    /**
     * @return void
     */
    public function test_create_submission_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        // Login to Safe Assign.
        $this->attempt_login('user-login-final.json');
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);
        // Create a course.
        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        $result = safeassign_api::create_course($this->user->id, $course->id);
        $this->assertNotEmpty($result->uuid);
        $courseuuid = $result->uuid;

        // Create an assignment.
        $assignment = self::create_assignment();
        // Push fixture for assignment creation.
        $assignmenturl = $this->create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $assignment->id, $assignment->title);
        $this->assertNotEmpty($result->uuid);
        $assignmentuuid = $result->uuid;

        // Push fixture for submission creation.
        $submissionurl = $this->create_submission_url($courseuuid, $assignmentuuid);
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

        // Login to Safe Assign.
        $this->attempt_login('user-login-final.json');
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);
        // Create a course.
        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        $result = safeassign_api::create_course($this->user->id, $course->id);
        $this->assertNotEmpty($result->uuid);
        $courseuuid = $result->uuid;

        // Create an assignment.
        $assignment = self::create_assignment();
        // Push fixture for assignment creation.
        $assignmenturl = $this->create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $assignment->id, $assignment->title);
        $this->assertNotEmpty($result->uuid);
        $assignmentuuid = $result->uuid;

        // Push fixture for submission creation.
        $submissionurl = $this->create_submission_url($courseuuid, $assignmentuuid);
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

        // Login to Safe Assign.
        $this->attempt_login('user-login-final.json');
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);
        // Create a course.
        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        $result = safeassign_api::create_course($this->user->id, $course->id);
        $this->assertNotEmpty($result->uuid);
        $courseuuid = $result->uuid;

        // Create an assignment.
        $assignment = self::create_assignment();
        // Push fixture for assignment creation.
        $assignmenturl = $this->create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $assignment->id, $assignment->title);
        $this->assertNotEmpty($result->uuid);
        $assignmentuuid = $result->uuid;

        // Create a submission.
        $submissionuuid = "c93e61c6-be1f-6c49-5c86-76d8f04f3f2f";

        // Get originality report basic data.
        // Push fixtures as cached responses.
        $getreporturl = $this->create_get_originality_report_basic_data_url($submissionuuid);
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

        // Login to Safe Assign.
        $this->attempt_login('user-login-final.json');
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);
        // Create a course.
        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        $result = safeassign_api::create_course($this->user->id, $course->id);
        $this->assertNotEmpty($result->uuid);
        $courseuuid = $result->uuid;

        // Create an assignment.
        $assignment = self::create_assignment();
        // Push fixture for assignment creation.
        $assignmenturl = $this->create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $assignment->id, $assignment->title);
        $this->assertNotEmpty($result->uuid);
        $assignmentuuid = $result->uuid;

        // Create a submission.
        $submissionuuid = "c93e61c6-be1f-6c49-5c86-76d8f04f3f2f";

        // Get originality report basic data.
        // Push fixtures as cached responses.
        $getreporturl = $this->create_get_originality_report_basic_data_url($submissionuuid);
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

        // Login to Safe Assign.
        $this->attempt_login('user-login-final.json');
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);
        // Create a course.
        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        $result = safeassign_api::create_course($this->user->id, $course->id);
        $this->assertNotEmpty($result->uuid);
        $courseuuid = $result->uuid;

        // Create an assignment.
        $assignment = self::create_assignment();
        // Push fixture for assignment creation.
        $assignmenturl = $this->create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $assignment->id, $assignment->title);
        $this->assertNotEmpty($result->uuid);
        $assignmentuuid = $result->uuid;

        // Create a submission.
        $submissionuuid = "c93e61c6-be1f-6c49-5c86-76d8f04f3f2f";

        // Get originality report.
        // Push fixtures as cached responses.
        $getreporturl = $this->create_get_originality_report_url($submissionuuid);
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

        // Login to Safe Assign.
        $this->attempt_login('user-login-final.json');
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);
        // Create a course.
        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        $result = safeassign_api::create_course($this->user->id, $course->id);
        $this->assertNotEmpty($result->uuid);
        $courseuuid = $result->uuid;

        // Create an assignment.
        $assignment = self::create_assignment();
        // Push fixture for assignment creation.
        $assignmenturl = $this->create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $assignment->id, $assignment->title);
        $this->assertNotEmpty($result->uuid);
        $assignmentuuid = $result->uuid;

        // Create a submission.
        $submissionuuid = "c93e61c6-be1f-6c49-5c86-76d8f04f3f2f";

        // Get originality report.
        // Push fixtures as cached responses.
        $getreporturl = $this->create_get_originality_report_url($submissionuuid);
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

        // Login to Safe Assign.
        $this->attempt_login('user-login-final.json');
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);
        // Create a course.
        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        $result = safeassign_api::create_course($this->user->id, $course->id);
        $this->assertNotEmpty($result->uuid);
        $courseuuid = $result->uuid;

        // Create an assignment.
        $assignment = self::create_assignment();
        // Push fixture for assignment creation.
        $assignmenturl = $this->create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $assignment->id, $assignment->title);
        $this->assertNotEmpty($result->uuid);
        $assignmentuuid = $result->uuid;

        // Create a submission.
        $submissionuuid = "c93e61c6-be1f-6c49-5c86-76d8f04f3f2f";
        $resubmitfileurl = $this->create_resubmit_file_url($submissionuuid);
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

        // Login to Safe Assign.
        $this->attempt_login('user-login-final.json');
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);
        // Create a course.
        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-final.json');
        $result = safeassign_api::create_course($this->user->id, $course->id);
        $this->assertNotEmpty($result->uuid);
        $courseuuid = $result->uuid;

        // Create an assignment.
        $assignment = self::create_assignment();
        // Push fixture for assignment creation.
        $assignmenturl = $this->create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        $result = safeassign_api::create_assignment($this->user->id, $courseuuid, $assignment->id, $assignment->title);
        $this->assertNotEmpty($result->uuid);
        $assignmentuuid = $result->uuid;

        // Create a submission.
        $submissionuuid = "c93e61c6-be1f-6c49-5c86-76d8f04f3f2f";
        $resubmitfileurl = $this->create_resubmit_file_url( $submissionuuid);
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
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);

        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
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
        $course = $this->getDataGenerator()->create_course([
            'fullname' => 'AwesomeCourse'
        ]);

        // Push fixtures as cached responses.
        $courseurl = $this->create_course_url();
        testhelper::push_pair($courseurl, 'create-course-fail-final.json', 401);

        // Test creating the course.
        safeassign_api::create_course($this->user->id, $course->id);

        $expected = get_string('error_api_unauthorized', 'plagiarism_safeassign').PHP_EOL;
        $expected .= 'ERROR_ID: b53ad9d2-6e83-4592-8c04-18dada86b14b'.PHP_EOL;
        $expected .= 'ERROR_CODE: WRONG_PARAMETER'.PHP_EOL;

        $this->assertEquals($expected, error_handler::process_last_api_error(false, true));
    }

    public function test_multi_login() {

        $this->resetAfterTest(true);
        $this->config_set_ok();

        $usera = $this->getDataGenerator()->create_user([
            'firstname' => 'TeacherA',
            'lastname' => 'WhoTeachesA'
        ]);

        // Login with User A.
        $loginurla = $this->create_login_url($usera);
        testhelper::push_pair($loginurla, 'user-login-final.json');
        $resulta = safeassign_api::login($usera->id);
        $tokena = rest_provider::instance()->gettoken();

        self::assertTrue($resulta);
        self::assertNotEmpty($tokena);

        $userb = $this->getDataGenerator()->create_user([
            'firstname' => 'TeacherB',
            'lastname' => 'WhoTeachesB'
        ]);

        // Login with User B.
        $loginurlb = $this->create_login_url($userb);
        testhelper::push_pair($loginurlb, 'userb-login-final.json');
        $resultb = safeassign_api::login($userb->id);

        $tokenb = rest_provider::instance()->gettoken();

        self::assertTrue($resultb);
        self::assertNotEmpty($tokenb);

        self::assertNotEquals($tokena, $tokenb);

        $userc = $this->getDataGenerator()->create_user([
            'firstname' => 'TeacherC',
            'lastname' => 'WhoTeachesC'
        ]);

        // Login fails with User C.
        $loginurlc = $this->create_login_url($userc);
        testhelper::push_pair($loginurlc, 'user-login-fail-final.json');
        $resultc = safeassign_api::login($userc->id);

        $tokenc = rest_provider::instance()->gettoken();

        self::assertFalse($resultc);
        self::assertNull($tokenc);
    }

    public function test_login_cache() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        // Create a fake user.
        $this->user = $this->getDataGenerator()->create_user([
            'firstname' => 'Teacher',
            'lastname' => 'WhoTeaches'
        ]);

        // Tell the cache to load specific fixture for login url.
        $loginurl = $this->create_login_url($this->user);
        testhelper::push_pair($loginurl, 'user-login-final.json');
        safeassign_api::login($this->user->id, true); // This will add the token to the cache.

        // Clear the token and get it again. This will make the rest provider look into the cache.
        rest_provider::instance()->cleartoken();
        $cachedtoken = rest_provider::instance()->gettoken($this->user->id);
        $this->assertEquals('4c390fd4-38d7-4675-8677-716d1b8bb12c', $cachedtoken);

        // Pushing fixture with no cache time.
        testhelper::reset_stash();
        rest_provider::instance()->cleartoken();
        testhelper::push_pair($loginurl, 'user-login-notime.json');
        safeassign_api::login($this->user->id, true); // This will add the token to the cache.

        // Clear the token and get it again. This will make the rest provider look into the cache.
        rest_provider::instance()->cleartoken();
        $cachedtoken = rest_provider::instance()->gettoken($this->user->id);
        $this->assertNull($cachedtoken);
    }
}
