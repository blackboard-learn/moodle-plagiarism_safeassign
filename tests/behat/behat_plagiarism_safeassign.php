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
 * @copyright Copyright (c) 2017 Blackboard Inc.
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
 * @copyright Copyright (c) 2017 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_plagiarism_safeassign extends behat_base {

    static private $student;
    static private $teacher;
    static private $course;
    static private $assignment;

    /**
     * @Given /^I am on the course with shortname "(?P<shortname_string>(?:[^"]|\\")*)"$/
     * @param string $shortname
     */
    public function i_am_on_the_course_with_shortname($shortname) {
        global $DB;
        $courseid = $DB->get_field('course', 'id', ['shortname' => $shortname]);
        $this->getSession()->visit($this->locate_path('/course/view.php?id='.$courseid));
    }

    /**
     * @Given /^set test helper student "(?P<username_string>(?:[^"]|\\")*)"$/
     * @param string $username
     */
    public function set_test_helper_student($username) {
        global $DB;
        self::$student = $DB->get_record('user', array('username' => $username));
    }

    /**
     * @Given /^set test helper teacher "(?P<username_string>(?:[^"]|\\")*)"$/
     * @param string $username
     */
    public function set_test_helper_teacher($username) {
        global $DB;
        self::$teacher = $DB->get_record('user', array('username' => $username));
    }

    /**
     * @Given /^set test helper course with shortname "(?P<shortname_string>(?:[^"]|\\")*)"$/
     * @param string $shortname
     */
    public function set_test_helper_course_with_shortname($shortname) {
        global $DB;
        self::$course = $DB->get_record('course', array('shortname' => $shortname));
    }

    /**
     * @Given /^set test helper assignment with name "(?P<name_string>(?:[^"]|\\")*)"$/
     * @param string $name
     */
    public function set_test_helper_assignment_with_name($name) {
        global $DB;
        self::$assignment = $DB->get_record('assign', array('course' => self::$course->id, 'name' => $name));
    }

    /**
     * @Given /^submission with file "(?P<filepath_string>(?:[^"]|\\")*)" is synced$/
     * @param string $filepath
     * @param boolean $globalcheck optional
     * @param boolean $groupsubmission optional
     * @throws exception
     */
    public function submission_with_file_is_synced($filepath, $globalcheck = true, $groupsubmission = false) {
        global $CFG;
        require_once($CFG->dirroot . '/plagiarism/safeassign/lib.php');

        // Given teacher and student log in to SafeAssign.
        test_safeassign_api_connectors::config_set_ok();
        $teacherloginurl = test_safeassign_api_connectors::create_login_url(self::$teacher);
        testhelper::push_pair($teacherloginurl, 'user-login-final.json');
        $studentloginurl = test_safeassign_api_connectors::create_login_url(self::$student);
        testhelper::push_pair($studentloginurl, 'user-login-final.json');

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
        testhelper::push_pair($submissionurl, 'create-submission-one-file-ok.json');
        safeassign_api::create_submission(self::$student->id, $courseuuid,
            $assignmentuuid, array($filepath), $globalcheck, $groupsubmission);
        $safeassignsubmission = json_decode(rest_provider::instance()->lastresponse());
        $submissionuuid = $safeassignsubmission->submissions[0]->submission_uuid;

        // Get the originality report from SafeAssign.
        $getreportbasicdataurl = test_safeassign_api_connectors::create_get_originality_report_basic_data_url($submissionuuid);
        testhelper::push_pair($getreportbasicdataurl, 'get-originality-report-basic-data-ok.json');

        // Sync the assignments' information from SafeAssign.
        $task = new sync_assignments();
        $task->execute();
        $safeassign = new \plagiarism_plugin_safeassign();
        $safeassign->safeassign_get_scores();
    }
}
