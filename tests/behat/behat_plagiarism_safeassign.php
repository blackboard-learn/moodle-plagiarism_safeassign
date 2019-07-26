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
 * Steps definitions for behat.
 *
 * @package   plagiarism_safeassign
 * @category  test
 * @copyright Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');
use plagiarism_safeassign\task\sync_assignments;
use plagiarism_safeassign\api\testhelper;
use plagiarism_safeassign\api\test_safeassign_api_connectors;
use plagiarism_safeassign\api\safeassign_api;
use plagiarism_safeassign\api\rest_provider;

/**
 * Class behat_plagiarism_safeassign.
 *
 * @package   plagiarism_safeassign
 * @category  test
 * @copyright Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_plagiarism_safeassign extends behat_base {

    /**
     * @var $student
     */
    static private $student;

    /**
     * @var $teacher
     */
    static private $teacher;

    /**
     * @var $course
     */
    static private $course;

    /**
     * @var $assignment
     */
    static private $assignment;

    /**
     * Redirects to a course with an specific shortname.
     * @Given /^I am on the course with shortname "(?P<shortname_string>(?:[^"]|\\")*)"$/
     * @param string $shortname
     */
    public function i_am_on_the_course_with_shortname($shortname) {
        global $DB;
        $courseid = $DB->get_field('course', 'id', ['shortname' => $shortname]);
        $this->getSession()->visit($this->locate_path('/course/view.php?id='.$courseid));
    }

    /**
     * Sets a student.
     * @Given /^set test helper student "(?P<username_string>(?:[^"]|\\")*)"$/
     * @param string $username
     */
    public function set_test_helper_student($username) {
        global $DB;
        self::$student = $DB->get_record('user', array('username' => $username));
    }

    /**
     * Sets a teacher.
     * @Given /^set test helper teacher "(?P<username_string>(?:[^"]|\\")*)"$/
     * @param string $username
     */
    public function set_test_helper_teacher($username) {
        global $DB;
        self::$teacher = $DB->get_record('user', array('username' => $username));
    }

    /**
     * Sets a course.
     * @Given /^set test helper course with shortname "(?P<shortname_string>(?:[^"]|\\")*)"$/
     * @param string $shortname
     */
    public function set_test_helper_course_with_shortname($shortname) {
        global $DB;
        self::$course = $DB->get_record('course', array('shortname' => $shortname));
    }

    /**
     * Sets an assignment.
     * @Given /^set test helper assignment with name "(?P<name_string>(?:[^"]|\\")*)"$/
     * @param string $name
     */
    public function set_test_helper_assignment_with_name($name) {
        global $DB;
        self::$assignment = $DB->get_record('assign', array('course' => self::$course->id, 'name' => $name));
    }

    /**
     * Sends a submission to SafeAssign mock service.
     * @Given /^I send a submission with file "(?P<filepath_string>(?:[^"]|\\")*)"$/
     * @param string $filepath
     * @param boolean $globalcheck optional
     * @param boolean $groupsubmission optional
     * @param boolean $onlinesubmission optional
     * @throws exception
     */
    public function i_send_a_submission_with_file($filepath, $globalcheck = true, $groupsubmission = false,
                                                  $onlinesubmission = false) {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/plagiarism/safeassign/lib.php');

        // Given teacher and student log in to SafeAssign.
        test_safeassign_api_connectors::config_set_ok();
        $teacherloginurl = test_safeassign_api_connectors::create_login_url(self::$teacher);
        testhelper::push_pair($teacherloginurl, 'user-login-final.json');
        $studentloginurl = test_safeassign_api_connectors::create_login_url(self::$student);
        testhelper::push_pair($studentloginurl, 'user-login-final.json');
        $administ = $DB->get_record('user', array('id' => 2));
        $adminloginurl = test_safeassign_api_connectors::create_login_url($administ);
        testhelper::push_pair($adminloginurl, 'user-login-final.json');

        // Create the given course inside SafeAssign.
        $courseurl = test_safeassign_api_connectors::create_course_url();
        $loginurl = test_safeassign_api_connectors::create_login_url(self::$teacher);
        testhelper::push_pair($loginurl, 'user-login-final.json');
        testhelper::push_pair($courseurl, 'create-course-final.json');
        testhelper::push_pair($courseurl.'?id='. self::$course->id, 'create-course-final.json');
        $safeassigncourse = safeassign_api::create_course(self::$teacher->id, self::$course->id);
        $courseuuid = $safeassigncourse->uuid;

        // Put the instructor in the created course.
        $putdeleteinstructorurl = test_safeassign_api_connectors::create_put_delete_instructor_url($courseuuid);
        testhelper::push_pair($putdeleteinstructorurl, 'put-delete-instructor-ok.json');

        // Create an assignment in the given course.
        $assignmenturl = test_safeassign_api_connectors::create_assignment_url($courseuuid);
        testhelper::push_pair($assignmenturl, 'create-assignment-ok.json');
        $checkassignmenturl = test_safeassign_api_connectors::create_check_assignment_url($courseuuid, self::$assignment->id);
        testhelper::push_pair($checkassignmenturl, 'create-assignment-ok.json');
        $safeassignassignment = safeassign_api::create_assignment(self::$teacher->id,
            $courseuuid, self::$assignment->id, self::$assignment->name);
        $assignmentuuid = $safeassignassignment->uuid;

        // Create a submission for the given assignment.
        $submissionurl = test_safeassign_api_connectors::create_submission_url($courseuuid, $assignmentuuid);
        if ($onlinesubmission) {
            testhelper::push_pair($submissionurl, 'create-online-submission-ok.json');
        } else {
            testhelper::push_pair($submissionurl, 'create-submission-one-file-ok.json');
        }
        safeassign_api::create_submission(self::$student->id, $courseuuid,
            $assignmentuuid, array($filepath), $globalcheck, $groupsubmission);
        $safeassignsubmission = json_decode(rest_provider::instance()->lastresponse());
        $submissionuuid = $safeassignsubmission->submissions[0]->submission_uuid;

        // Get the originality report from SafeAssign.
        $getreportbasicdataurl = test_safeassign_api_connectors::create_get_originality_report_basic_data_url($submissionuuid);
        testhelper::push_pair($getreportbasicdataurl, 'get-originality-report-basic-data-ok.json');
    }

    /**
     * Sync a submission with SafeAssign mock server.
     * @Given /^I sync submissions$/
     * @throws exception
     */
    public function i_sync_submissions() {
        $task = new sync_assignments();
        $task->execute();
        $safeassign = new \plagiarism_plugin_safeassign();
        $safeassign->safeassign_get_scores();
    }

    /**
     * Change the notifications settings for some user.
     * @Given /^I change notifications setup for user "(?P<name_string>(?:[^"]|\\")*)" with "(?P<prefs_string>(?:[^"]|\\")*)"$/
     * @param string $username
     * @param string $preferences
     * @throws exception
     */
    public function i_change_safeassign_notification_setup_for_user($username, $preferences) {
        global $DB;
        $user = $DB->get_record('user', array('username' => $username));
        $DB->insert_record('user_preferences', array('userid' => $user->id,
            'name' => 'message_provider_plagiarism_safeassign_safeassign_graded_loggedin',
            'value' => $preferences));
        $DB->insert_record('user_preferences', array('userid' => $user->id,
            'name' => 'message_provider_plagiarism_safeassign_safeassign_graded_loggedoff',
            'value' => $preferences));
    }

    /**
     * Sync a submission with an online text.
     * @Given /^submission with online text is synced$/
     * @throws exception
     */
    public function submission_with_online_text_is_synced() {
        global $CFG;
        require_once($CFG->dirroot . '/plagiarism/safeassign/lib.php');
        $fs = get_file_storage();
        $usercontext = context_user::instance(self::$student->id);
        $safeassign = new \plagiarism_plugin_safeassign();
        $unsynced = $safeassign->get_unsynced_submissions();
        $submission = reset($unsynced);

        $filename = 'userid_' . self::$student->id . '_text_submissionid_' . $submission->submissionid . '.html';
        $textfile = $fs->get_file($usercontext->id, 'assignsubmission_text_as_file', 'submission_text_files',
            $submission->submissionid , '/', $filename);

        $this->create_onlinetext_json_file($filename);
        $this->i_send_a_submission_with_file($textfile, true, false, true);
        $this->i_sync_submissions();
        $this->delete_onlinetext_json_file();
    }

    /**
     * Creates a file that simulates the JSON response from SafeAssign server.
     * @param string $filename
     */
    private function create_onlinetext_json_file($filename) {
        global $CFG;
        $file = new stdClass();
        $file->file_name = $filename;
        $file->file_uuid = '5140a223-8cbc-7a85-3cb4-f52d959ee067';
        $submission = new stdClass();
        $submission->submission_uuid = '5140a223-8cbc-7a85-3cb4-f52d959ee06';
        $submission->submission_files = array($file);
        $jsonobject = new stdClass();
        $jsonobject->submissions = array($submission);
        $jsonobject->unprocessed_file_names = array();
        // We should create a file to simulate the json response from SafeAssign server.
        // It should be created in every test because te name of the file depends of several IDs records.
        make_upload_directory('safeassign');
        $file = fopen($CFG->dataroot . '/safeassign/create-online-submission-ok.json', 'w');
        fwrite($file, json_encode($jsonobject));
        fclose($file);
    }

    /**
     * Deletes the file with the JSON response.
     */
    private function delete_onlinetext_json_file() {
        global $CFG;
        $filename = $CFG->dataroot . '/safeassign/create-online-submission-ok.json';
        unlink($filename);
    }

    /**
     * Follows the duplicate of one assignment.
     * @Given /^I follow duplicate "(?P<duplicate_number_int>(?:[^"]|\\")*)" of assignment "(?P<assignment_string>(?:[^"]|\\")*)"$/
     * @param int $duplicatenumber
     * @param string $assignmentname
     * @throws exception
     */
    public function i_follow_duplicate($duplicatenumber, $assignmentname) {
        $xpath = '//div[contains(@class,"activityinstance")]//a';
        $linknodes = $this->find_all('xpath', $xpath);
        $targetnode = $linknodes[$duplicatenumber];
        $targetnode->click();
    }

    /**
     * Reviews if SafeAssign file links have been updated.
     * @Given /^I should see the SafeAssign links updated$/
     */
    public function safeassign_links_updated() {
        global $CFG;
        $wwwroot = $CFG->wwwroot;
        $evaljs = 'return document.querySelectorAll(".attachments-list a[href*=' .
            "'" . $wwwroot . "']" . '").length > 0;';
        if (!$this->getSession()->evaluateScript($evaljs)) {
            throw new Exception("No SafeAssign file links were updated.");
        }
    }

    /**
     * Changes the submission file size
     * @Given /^The submission for assignment exceeds the file size limit$/
     *
     */
    public function submission_size_exceeded() {
        global $DB;

        $module = $DB->get_record('modules', ['name' => 'assign']);
        $cm = $DB->get_record('course_modules', ['course' => self::$course->id, 'instance' => self::$assignment->id,
            'module' => $module->id]);
        $submission = $DB->get_record('assign_submission', ['assignment' => self::$assignment->id,
            'userid' => self::$student->id]);
        $context = context_module::instance($cm->id);
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'assignsubmission_file', 'submission_files', $submission->id);
        foreach ($files as $file) {
            $DB->set_field('files', 'filesize', 10000111, ['id' => $file->get_id()]);
        }
    }

    /**
     * Reviews if SafeAssign old report link has been updated.
     * @Given /^I should see the SafeAssign old report link updated$/
     */
    public function safeassign_old_report_link_updated() {
        global $CFG;
        $wwwroot = $CFG->wwwroot;
        $evaljs = 'return document.querySelector("a.report-button[href*=' .
            "'" . $wwwroot . "']" . '") != null;';
        if (!$this->getSession()->evaluateScript($evaljs)) {
            throw new Exception("SafeAssign old report link not found or not updated.");
        }
    }

    /**
     * @Given /^I click on file originality report for student "(?P<username_string>(?:[^"]|\\")*)"$/
     * @param string $username
     * @return Given
     */
    public function i_click_on_file_or_for_student($username) {
        global $DB;
        $student = $DB->get_record('user', array('username' => $username));
        $csspath = "#safeassign_or_" . $student->id . " a";
        $this->execute("behat_general::i_click_on", array($this->escape($csspath), "css_element"));
    }
}
