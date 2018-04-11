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
 * Convenient wrappers and helper for using the SafeAssign web service API.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\api;

defined('MOODLE_INTERNAL') || die();

/**
 * Class safeassign_api
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class safeassign_api {

    /**
     * @var string PLUGIN
     */
    const PLUGIN = 'plagiarism_safeassign';

    /**
     * Logins as a teacher or a student into SafeAssign REST API.
     * @param int $userid
     * @param bool $isinstructor
     * @return bool
     * @throws \Exception
     * @throws \dml_exception
     * @throws jsonerror_exception
     */
    public static function login($userid, $isinstructor = false) {
        if (rest_provider::instance()->hastoken($userid)) {
            return true;
        }
        // DB is declared here for efficiency, if token exists, it should not be declared.
        global $DB;

        list($username, $password) = self::get_login_credentials($isinstructor);
        $baseurl = get_config(self::PLUGIN, 'safeassign_api');
        if (($username === false) || ($password === false) || ($baseurl === false)) {
            return false;
        }

        $firstname = $DB->get_field('user', 'firstname', array('id' => $userid));
        $lastname = $DB->get_field('user', 'lastname', array('id' => $userid));

        $url = new \moodle_url($baseurl . '/api/v1/tokens', array(
            'grant_type' => 'client_credentials',
            'user_id' => urlencode($userid),
            'user_firstname' => urlencode($firstname),
            'user_lastname' => urlencode($lastname)
        ));

        $result = rest_provider::instance()->post_withauth($url->out(false), $username, $password, array(), array());
        if ($result) {
            $data = json_decode(rest_provider::instance()->lastresponse());
            $result = (!is_null($data) && isset($data->access_token));
            if (!$result) {
                // Should check this some more?
                rest_provider::instance()->resettoken($userid);
            } else {
                rest_provider::instance()->settoken($userid, $data->access_token, $data->expires_in);
            }
        }

        return $result;
    }

    /**
     * Gets the credentials for logging into the SafeAssign API.
     * @param bool $isinstructor
     * @return array
     */
    private static function get_login_credentials($isinstructor = false) {
        $type = 'student';
        if ($isinstructor) {
            $type = 'instructor';
        }
        $username = get_config(self::PLUGIN, 'safeassign_' . $type . '_username');
        $password = get_config(self::PLUGIN, 'safeassign_' . $type . '_password');
        return array($username, $password);
    }

    /**
     * Generic http get call.
     * @param string $url
     * @param int $userid
     * @param bool $isinstructor
     * @return bool|mixed
     */
    protected static function generic_getcall($url, $userid, $isinstructor = false) {
        if (empty($url)) {
            return false;
        }

        if (!rest_provider::instance()->hastoken($userid)) {
            if (!self::login($userid, $isinstructor)) {
                return false;
            }
        }
        $result = rest_provider::instance()->get_withtoken($url, $userid);
        if ($result) {
            $data = json_decode(rest_provider::instance()->lastresponse());
            if ($data === false) {
                return false;
            }
            $result = $data;
        }

        return $result;
    }

    /**
     * Generic http get call. This get call does not parse json reponses.
     * @param string $url
     * @param int $userid
     * @param bool $isinstructor
     * @param array $headers
     * @return bool|mixed
     */
    protected static function generic_getcall_raw($url, $userid, $isinstructor = false, $headers = []) {
        if (empty($url)) {
            return false;
        }

        if (!rest_provider::instance()->hastoken($userid)) {
            if (!self::login($userid, $isinstructor)) {
                return false;
            }
        }
        $result = rest_provider::instance()->get_withtoken($url, $userid, $headers);
        if ($result) {
            $result = rest_provider::instance()->lastresponse();
        }

        return $result;
    }

    /**
     * Generic http post call.
     * @param string $url
     * @param int $userid
     * @param string $postdata
     * @param bool $isinstructor
     * @return bool|mixed
     */
    protected static function generic_postcall($url, $userid, $postdata, $isinstructor = false) {
        if (empty($url)) {
            return false;
        }

        if (!rest_provider::instance()->hastoken($userid)) {
            if (!self::login($userid, $isinstructor)) {
                return false;
            }
        }

        $result = rest_provider::instance()->post_withtoken($url, $userid, [], [], $postdata);
        if ($result) {
            $data = json_decode(rest_provider::instance()->lastresponse());
            if ($data === false) {
                return false;
            }
            $result = $data;
        }

        return $result;
    }


    /**
     * Generic http put call.
     * @param string $url
     * @param int $userid
     * @param bool $isinstructor
     * @param array $postdata
     * @return bool|mixed
     */
    protected static function generic_putcall($url, $userid, $isinstructor = false, array $postdata = array()) {
        if (empty($url)) {
            return false;
        }
        if (!rest_provider::instance()->hastoken($userid)) {
            if (!self::login($userid, $isinstructor)) {
                return false;
            }
        }
        $result = rest_provider::instance()->put_withtoken($url, $userid, [], $postdata);
        if ($result) {
            $data = json_decode(rest_provider::instance()->lastresponse());
            if ($data === false) {
                return false;
            } else if (empty($data) && rest_provider::instance()->lasthttpcode() < 400) {
                return true;
            }
            $result = $data;
        }

        return $result;
    }

    /**
     * Generic http delete call.
     * @param string $url
     * @param int $userid
     * @param bool $isinstructor
     * @return bool|mixed
     */
    protected static function generic_deletecall($url, $userid, $isinstructor = false) {
        if (empty($url)) {
            return false;
        }

        if (!rest_provider::instance()->hastoken($userid)) {
            if (!self::login($userid, $isinstructor)) {
                return false;
            }
        }
        $result = rest_provider::instance()->delete_withtoken($url, $userid);
        if ($result) {
            $data = json_decode(rest_provider::instance()->lastresponse());
            if ($data === false) {
                return false;
            } else if (empty($data) && rest_provider::instance()->lasthttpcode() < 400) {
                return true;
            }
            $result = $data;
        }

        return $result;
    }

    /**
     * Puts an instructor to a course inside SafeAssign.
     * @param int $userid
     * @param int $courseuuid
     * @return bool|mixed|null
     */
    public static function put_instructor_to_course($userid, $courseuuid) {

        $baseurl = get_config(self::PLUGIN, 'safeassign_api');

        if (empty($baseurl)) {
            return false;
        }
        $url = new \moodle_url($baseurl . '/api/v1/courses/' . $courseuuid . '/members');
        return self::generic_putcall($url->out(false), $userid, true);
    }

    /**
     * Deletes an instructor from a course inside SafeAssign.
     * @param int $userid
     * @param int $courseuuid
     * @return bool|mixed|null
     */
    public static function delete_instructor_from_course($userid, $courseuuid) {
        $baseurl = get_config(self::PLUGIN, 'safeassign_api');
        if (empty($baseurl)) {
            return false;
        }
        $url = new \moodle_url($baseurl . '/api/v1/courses/' . $courseuuid . '/members');
        return self::generic_deletecall($url->out(false), $userid, true);
    }

    /**
     * Creates a course in SafeAssign.
     *
     * @param int $userid User id of an instructor of this course.
     * @param int $courseid
     * @return bool|mixed
     */
    public static function create_course($userid, $courseid) {
        $course = get_course($courseid);
        $baseurl = get_config(self::PLUGIN, 'safeassign_api');
        if (empty($baseurl)) {
            return false;
        }
        $url = new \moodle_url($baseurl . '/api/v1/courses');
        $postparams = array(
            'id' => $courseid,
            'title' => $course->fullname
        );
        return self::generic_postcall($url->out(false), $userid, json_encode($postparams), true);
    }

    /**
     * Retrieves a course from SafeAssign.
     *
     * @param int $userid User id of an instructor of this course.
     * @param int $courseid
     * @return bool|mixed
     */
    public static function get_course($userid, $courseid) {
        $baseurl = get_config(self::PLUGIN, 'safeassign_api');
        if (empty($baseurl)) {
            return false;
        }
        $url = new \moodle_url($baseurl . '/api/v1/courses', array('id' => $courseid));
        return self::generic_getcall($url->out(false), $userid, true);
    }


    /**
     * Test the given credentials.
     * @param int $userid
     * @param string $username
     * @param string $password
     * @param string $baseurl
     * @return bool
     */
    public static function test_credentials($userid, $username, $password, $baseurl) {

        global $DB;
        if (!defined('SAFEASSIGN_OMIT_CACHE')) {
            define('SAFEASSIGN_OMIT_CACHE', true);
        }
        $firstname = $DB->get_field('user', 'firstname', array('id' => $userid));
        $lastname = $DB->get_field('user', 'lastname', array('id' => $userid));
        $url = new \moodle_url($baseurl . '/api/v1/tokens?grant_type=client_credentials', array('user_id' => $userid,
            'user_firstname' => $firstname, 'user_lastname' => $lastname));
        $result = rest_provider::instance()->post_withauth($url->out(false), $username, $password, array(), array());
        return $result;

    }

    /**
     * Creates an assignment inside SafeAssign.
     * @param int $userid
     * @param string $courseuuid
     * @param int $assignmentid
     * @param string $assignmenttitle
     * @return bool|mixed
     */
    public static function create_assignment($userid, $courseuuid, $assignmentid, $assignmenttitle) {
        $baseurl = get_config(self::PLUGIN, 'safeassign_api');
        if (empty($baseurl)) {
            return false;
        }
        $url = new \moodle_url($baseurl . '/api/v1/courses/' . $courseuuid . '/assignments');
        $postparams = array(
            'id' => $assignmentid,
            'title' => $assignmenttitle
        );
        $postdata = json_encode($postparams);
        return self::generic_postcall($url->out(false), $userid, $postdata, true);
    }

    /**
     * Check if the assignment exists inside SafeAssign.
     * @param int $userid
     * @param string $courseuuid
     * @param string $assignmentid
     * @return bool|mixed
     */
    public static function check_assignment($userid, $courseuuid, $assignmentid) {
        $baseurl = get_config(self::PLUGIN, 'safeassign_api');
        if (empty($baseurl)) {
            return false;
        }
        $url = new \moodle_url($baseurl . '/api/v1/courses/' . $courseuuid . '/assignments', array('id' => $assignmentid));
        $result = self::generic_getcall($url->out(false), $userid, true);
        return $result;
    }

    /**
     * Creates a submission inside SafeAssign
     * @param int $userid
     * @param string $courseuuid
     * @param string $assignmentuuid
     * @param \stored_file[] $files
     * @param bool $globalcheck
     * @param bool $groupsubmission
     * @return bool
     */
    public static function create_submission($userid, $courseuuid, $assignmentuuid,
                                             array $files, $globalcheck = false, $groupsubmission = false) {
        $baseurl = get_config(self::PLUGIN, 'safeassign_api');
        if (empty($baseurl)) {
            return false;
        }
        $url = new \moodle_url($baseurl . '/api/v1/courses/' . $courseuuid . '/assignments/' . $assignmentuuid . '/submissions');

        if (!rest_provider::instance()->hastoken($userid)) {
            if (!self::login($userid, false)) {
                return false;
            }
        }
        $result = rest_provider::instance()->post_submission_to_safeassign($userid, $url->out(false),
            $files, $globalcheck, $groupsubmission);
        return $result;
    }

    /**
     * Get the originality report basic data from SafeAssign.
     * @param int $userid
     * @param string $submissionuuid
     * @return bool|mixed
     */
    public static function get_originality_report_basic_data($userid, $submissionuuid) {
        $baseurl = get_config(self::PLUGIN, 'safeassign_api');
        if (empty($baseurl)) {
            return false;
        }
        $url = new \moodle_url($baseurl . '/api/v1/submissions/' . $submissionuuid . '/report/metadata');

        $result = self::generic_getcall($url->out(false), $userid, true);
        return $result;
    }

    /**
     * Get the originality report from SafeAssign.
     * @param int $userid
     * @param string $submissionuuid
     * @param bool $isinstructor
     * @param bool|string $fileuuid File uuid
     * @param bool $print For getting a print version of the report
     * @param bool|string $cssurl to add some custom report styling
     * @param bool|string $logourl to use custom logo
     * @return bool|mixed
     */
    public static function get_originality_report($userid, $submissionuuid, $isinstructor = false, $fileuuid = false,
                                                  $print = false, $cssurl = false, $logourl = false) {
        $baseurl = get_config(self::PLUGIN, 'safeassign_api');
        if (empty($baseurl)) {
            return false;
        }

        $params = [];
        if (!empty($fileuuid)) {
            $params['file_uuid'] = $fileuuid;
        }
        if ($print) {
            $params['print'] = true;
        }
        if (!empty($cssurl)) {
            $params['css_url'] = $cssurl;
        }
        if (!empty($logourl)) {
            $params['logo_url'] = $logourl;
        }

        $url = new \moodle_url($baseurl . '/api/v1/submissions/' . $submissionuuid . '/report', $params);

        // This request needs special headers.
        $headers = [
            'Accept: text/html'
        ];

        $result = self::generic_getcall_raw($url->out(false), $userid, $isinstructor, $headers);
        return $result;
    }


    /**
     * Resubmit files from a submission.
     * @param int $userid
     * @param string $submissionuuid
     * @param string $fileuuid
     * @param array $urls
     * @param array $engines
     * @return bool | mixed
     */
    public static function resubmit_file($userid, $submissionuuid, $fileuuid, array $urls, array $engines) {
        $baseurl = get_config(self::PLUGIN, 'safeassign_api');
        $putparams = array(
            'file_uuid' => $fileuuid,
            'skipped_citations' => array()
        );
        for ($i = 0; $i < count($urls); $i++) {
            array_push($putparams['skipped_citations'], array('url' => $urls[$i], 'engine_name' => $engines[$i]));
        }
        $putdata = json_encode($putparams);
        $url = new \moodle_url($baseurl . '/api/v1/submissions/' . $submissionuuid);
        $result = self::generic_putcall($url->out(false), $userid, true, [], $putdata);
        return $result;
    }

    /**
     * Deletes the given submission from the SafeAssign server.
     * @param string $submissionuuid
     * @param int $userid it should be an instructor.
     * @return bool
     */
    public static function delete_submission($submissionuuid, $userid) {
        $baseurl = get_config(self::PLUGIN, 'safeassign_api');
        if (empty($baseurl)) {
            return false;
        }
        $url = new \moodle_url($baseurl . '/api/v1/submissions/' . $submissionuuid);
        $result = self::generic_deletecall($url->out(false), $userid, true);
        return $result;
    }

    /**
     * The user accepts latest license or specified if present in request body, for his registration.
     * @param string $userid
     * @param string $acceptorfirstname
     * @param string $acceptorlastname
     * @param string $acceptoremail
     * @param string $licenseversion
     * @return bool | mixed
     */
    public static function accept_license($userid, $acceptorfirstname, $acceptorlastname, $acceptoremail, $licenseversion = '') {
        $baseurl = get_config(self::PLUGIN, 'safeassign_api');
        $putparams = array(
            'acceptorFirstName' => $acceptorfirstname,
            'acceptorLastName' => $acceptorlastname,
            'acceptorEmail' => $acceptoremail
        );
        if (!empty($licenseversion)) {
            $putparams['licenseVersionAccepted'] = $licenseversion;
        }

        $putdata = json_encode($putparams);
        $url = new \moodle_url($baseurl . '/api/v1/licenses');
        $result = self::generic_putcall($url->out(false), $userid, true, [], $putdata);
        return $result;
    }

    /**
     * The user revokes latest license or specified license if present in query, for his registration.
     * @param string $userid
     * @param string $licenseversion
     * @return bool | mixed
     */
    public static function revoke_license($userid, $licenseversion = '') {
        $baseurl = get_config(self::PLUGIN, 'safeassign_api');
        if (empty($baseurl)) {
            return false;
        }
        $url = new \moodle_url($baseurl . '/api/v1/licenses?license_version=' . $licenseversion);
        $result = self::generic_deletecall($url->out(false), $userid, true);
        return $result;
    }

    /**
     * The user retrieves all accepted licenses for his registration.
     * The licenseAccepted Timestamp precision: seconds.
     * @param string $userid
     * @return bool | mixed
     */
    public static function get_accepted_licenses($userid) {
        $baseurl = get_config(self::PLUGIN, 'safeassign_api');
        if (empty($baseurl)) {
            return false;
        }
        $url = new \moodle_url($baseurl . '/api/v1/licenses/accepted');
        $result = self::generic_getcall($url->out(false), $userid, true);
        return $result;
    }

    /**
     * The user retrieves all available licenses, consist of already accepted and unaccepted licenses.
     * The licenseCreated Timestamp precision: seconds.
     * @param string $userid
     * @return bool | mixed
     */
    public static function get_licenses($userid) {
        $baseurl = get_config(self::PLUGIN, 'safeassign_api');
        if (empty($baseurl)) {
            return false;
        }
        $url = new \moodle_url($baseurl . '/api/v1/licenses/all');
        $result = self::generic_getcall($url->out(false), $userid, true);
        return $result;
    }
}
