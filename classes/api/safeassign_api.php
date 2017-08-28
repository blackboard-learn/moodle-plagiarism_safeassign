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
 * @copyright Copyright (c) 2017 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\api;

defined('MOODLE_INTERNAL') || die();

/**
 * Class safeassign_api
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class safeassign_api {
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
        $baseurl  = get_config(self::PLUGIN, 'safeassign_api');
        if (($username === false) || ($password === false) || ($baseurl === false)) {
            return false;
        }

        $firstname = $DB->get_field('user', 'firstname', array('id' => $userid));
        $lastname = $DB->get_field('user', 'lastname', array('id' => $userid));

        $url = sprintf('%s/api/v1/tokens?grant_type=client_credentials&user_id=%s&user_firstname=%s&user_lastname=%s',
            $baseurl, $userid, urlencode($firstname), urlencode($lastname));
        $result = rest_provider::instance()->post_withauth($url, $username, $password, array(), array());
        if ($result) {
            $data = json_decode(rest_provider::instance()->lastresponse());
            $result = (!is_null($data) && isset($data->access_token));
            if (!$result) {
                // Should check this some more?
                rest_provider::instance()->resettoken($userid);
            } else {
                rest_provider::instance()->settoken($userid, $data->access_token);
            }
        }

        return $result;
    }

    /**
     * Gets the credentials for logging into the SafeAssign API
     * @param bool $isinstructor
     * @return array
     */
    private static function get_login_credentials($isinstructor = false) {
        $type = 'student';
        if ($isinstructor) {
            $type = 'instructor';
        }
        $username = get_config(self::PLUGIN, 'safeassign_'.$type.'_username');
        $password = get_config(self::PLUGIN, 'safeassign_'.$type.'_password');
        return array($username, $password);
    }

    /**
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
     * Generic http post call
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
     * Creates a course in SafeAssign.
     *
     * @param int $userid User id of an instructor of this course.
     * @param int $courseid
     * @return bool|mixed
     */
    public static function create_course($userid, $courseid) {
        $course = get_course($courseid);
        $baseurl  = get_config(self::PLUGIN, 'safeassign_api');
        if (empty($baseurl)) {
            return false;
        }
        $url = new \moodle_url($baseurl.'/api/v1/courses');

        $postparams = array(
            'id' => $courseid,
            'title' => $course->fullname
        );

        return self::generic_postcall($url->out(), $userid, json_encode($postparams), true);
    }

    /**
     * Creates a course in SafeAssign.
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

        return self::generic_getcall($url->out(), $userid, true);
    }

    /**
     * Test the given credentials
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
}
