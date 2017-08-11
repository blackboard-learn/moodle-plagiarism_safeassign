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
     * @return string
     */
    private function create_course_url() {
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
    private function attempt_login($filename) {
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

}
