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
 * Test functions to create data for SafeAssign tests.
 *
 * @package    plagiarism_safeassign
 * @author     Jonathan Garcia
 * @copyright  Copyright (c) 2017 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\api;
use stdClass;
use testing_data_generator;

/**
 * Class test_safeassign_api_connectors
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2018 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class test_safeassign_api_connectors {

    /**
     * @var string PLUGIN
     */
    const PLUGIN = 'plagiarism_safeassign';

    /**
     * @var testing_data_generator $datagenerator
     */
    protected static $datagenerator;

    /**
     * Gets the singleton instance of testing_data_generator class.
     * @return testing_data_generator
     */
    protected static function get_data_generator() {
        if (self::$datagenerator == null) {
            self::$datagenerator = new testing_data_generator();
        }
        return self::$datagenerator;
    }
    /**
     * Function to clear opts, token and reset test helper stash.
     * @return void
     */
    public static function reset_ws() {
        // Reset some internals.
        rest_provider::instance()->getopts();
        rest_provider::instance()->cleartoken();
        testhelper::reset_stash();
    }

    /**
     * Set configuration for SafeAssign testing.
     * @return void
     */
    public static function config_set_ok() {
        set_config('safeassign_instructor_username', 'someuser', self::PLUGIN);
        set_config('safeassign_instructor_password', 'somepass', self::PLUGIN);
        set_config('safeassign_student_username', 'someuser', self::PLUGIN);
        set_config('safeassign_student_password', 'somepass', self::PLUGIN);
        set_config('safeassign_api', 'http://safeassign.foo.com', self::PLUGIN);
        set_config('safeassign_curlcache', '3600', self::PLUGIN);
    }

    /**
     * Cleans configuration for SafeAssign testing.
     * @return void
     */
    public static function config_cleanup() {
        unset_config('safeassign_instructor_username', self::PLUGIN);
        unset_config('safeassign_instructor_password', self::PLUGIN);
        unset_config('safeassign_student_username', self::PLUGIN);
        unset_config('safeassign_student_password', self::PLUGIN);
        unset_config('safeassign_api', self::PLUGIN);
        unset_config('safeassign_curlcache', self::PLUGIN);
    }

    /**
     * Creates a user using the data generator.
     * @param string $firstname
     * @param string $lastname
     * @return stdClass
     */
    public static function create_user($firstname = 'john', $lastname = 'doe') {
        return \testing_util::get_data_generator()->create_user(array('firstname' => $firstname, 'lastname' => $lastname));
    }

    /**
     * Creates a course using the data generator.
     * @return stdClass
     */
    public static function create_course() {
        return self::get_data_generator()->create_course();
    }

    /**
     * Creates an assignment for testing.
     * @param string $id
     * @param string $title
     * @return stdClass
     */
    public static function create_assignment($id, $title) {
        $assignment = new stdClass();
        $assignment->id = $id;
        $assignment->title = $title;
        return $assignment;
    }

    /**
     * Creates the url for SafeAssign login
     * @param stdClass $user
     * @return string
     */
    public static function create_login_url($user) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $loginurl = '%s' . safeassign_api::APIVER . 'tokens?';
        $loginurl .= 'grant_type=client_credentials&user_id=%s&user_firstname=%s&user_lastname=%s';
        $loginurl = sprintf($loginurl, $baseapiurl, $user->id, $user->firstname, $user->lastname);
        return $loginurl;
    }

    /**
     * Creates the url to create a course in SafeAssign
     * @return string
     */
    public static function create_course_url() {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $courseurl = '%s' . safeassign_api::APIVER . 'courses';
        $courseurl = sprintf($courseurl, $baseapiurl);
        return $courseurl;
    }

    /**
     * Creates an url to put/delete an instructor to/from the course with the given uuid.
     * @param string $courseuuid
     * @return string
     */
    public static function create_put_delete_instructor_url($courseuuid) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $courseurl = '%s' . safeassign_api::APIVER . 'courses/%s/members';
        $courseurl = sprintf($courseurl, $baseapiurl, $courseuuid);
        return $courseurl;
    }

    /**
     * Creates an url to create an assignment in a SafeAssign course.
     * @param string $courseuuid
     * @return string
     */
    public static function create_assignment_url($courseuuid) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $assignmenturl = '%s' . safeassign_api::APIVER . 'courses/%s/assignments';
        $assignmenturl = sprintf($assignmenturl, $baseapiurl, $courseuuid);
        return $assignmenturl;
    }

    /**
     * Creates an url to check an assignment in a SafeAssign course.
     * @param string $courseuuid
     * @param string $assignmentid
     * @return string
     */
    public static function create_check_assignment_url($courseuuid, $assignmentid) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $checkassignmenturl = '%s' . safeassign_api::APIVER . 'courses/%s/assignments?id=%s';
        $checkassignmenturl = sprintf($checkassignmenturl, $baseapiurl, $courseuuid, $assignmentid);
        return $checkassignmenturl;
    }

    /**
     * Creates an url to create a submission in an assignment.
     * @param string $courseuuid
     * @param string $assignmentuuid
     * @return string
     */
    public static function create_submission_url($courseuuid, $assignmentuuid) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $submissionurl = '%s' . safeassign_api::APIVER . 'courses/%s/assignments/%s/submissions';
        $submissionurl = sprintf($submissionurl, $baseapiurl, $courseuuid, $assignmentuuid);
        return $submissionurl;
    }

    /**
     * Creates an url to get the originality report basic data.
     * @param string $submissionuuid
     * @return string
     */
    public static function create_get_originality_report_basic_data_url($submissionuuid) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $getreporturl = '%s' . safeassign_api::APIVER . 'submissions/%s/report/metadata';
        $getreporturl = sprintf($getreporturl, $baseapiurl, $submissionuuid);
        return $getreporturl;
    }

    /**
     * Creates an url to get the originality report.
     * @param string $submissionuuid
     * @return string
     */
    public static function create_get_originality_report_url($submissionuuid) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $getreporturl = '%s' . safeassign_api::APIVER . 'submissions/%s/report';
        $getreporturl = sprintf($getreporturl, $baseapiurl, $submissionuuid);
        return $getreporturl;
    }

    /**
     * Creates an url to get the originality report.
     * @param string $submissionuuid
     * @param string $fileuuid
     * @param boolean $force
     * @return string
     */
    public static function create_get_originality_report_with_file_url($submissionuuid, $fileuuid, $force = false) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $getreporturl = '%s' . safeassign_api::APIVER . 'submissions/%s/report?file_uuid=%s';
        $getreporturl = sprintf($getreporturl, $baseapiurl, $submissionuuid, $fileuuid);
        if ($force) {
            $getreporturl .= '&force=true';
        }
        return $getreporturl;
    }

    /**
     * Creates an url to resubmit a file.
     * @param string $submissionuuid
     * @return string
     */
    public static function create_resubmit_file_url($submissionuuid) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $resubmitfileurl = '%s' . safeassign_api::APIVER . 'submissions/%s';
        $resubmitfileurl = sprintf($resubmitfileurl, $baseapiurl, $submissionuuid);
        return $resubmitfileurl;
    }

    /**
     * Creates an url to get all licenses
     * @return string
     */
    public static function create_get_licenses_url() {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $getlicensesurl = '%s' . safeassign_api::APIVER . 'licenses/all';
        $getlicensesurl = sprintf($getlicensesurl, $baseapiurl);
        return $getlicensesurl;
    }

    /**
     * Creates an url to get accepted licenses
     * @return string
     */
    public static function create_get_accepted_licenses_url() {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $getlicensesurl = '%s' . safeassign_api::APIVER . 'licenses/accepted';
        $getlicensesurl = sprintf($getlicensesurl, $baseapiurl);
        return $getlicensesurl;
    }

    /**
     * Creates an url to accept license
     * @return string
     */
    public static function create_accept_license_url() {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $getlicensesurl = '%s' . safeassign_api::APIVER . 'licenses';
        $getlicensesurl = sprintf($getlicensesurl, $baseapiurl);
        return $getlicensesurl;
    }

    /**
     * Creates an url to revoke license
     * @param string $licenseversion
     * @return string
     */
    public static function create_revoke_license_url($licenseversion = '') {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $getlicensesurl = '%s' . safeassign_api::APIVER . 'licenses?license_version' .
            (empty($licenseversion) ? '' : ('=' . $licenseversion));
        $getlicensesurl = sprintf($getlicensesurl, $baseapiurl);
        return $getlicensesurl;
    }
}
