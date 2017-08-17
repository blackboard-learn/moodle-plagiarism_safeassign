<?php
/**
 * This file is part of Moodle - http://moodle.org/
 *
 * Moodle is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Moodle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   plagiarism_safeassign
 * @author    Jonathan Garcia Gomez jonathan.garcia@blackboard.com
 * @copyright Blackboard 2017
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $CFG;
use plagiarism_safeassign\api\rest_provider;
use plagiarism_safeassign\api\safeassign_api;
require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot  . '/config.php');
defined('MOODLE_INTERNAL') || die();

/**
 * Test the intructor and student credentiales.
 * @autor Jonathan Garcia
 * @copyright Copyright (c) 2017 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class plagiarism_safeassign_test_api_credentials_external extends external_api {

    /**
     * @return \external_function_parameters
     */
    public static function plagiarism_safeassign_test_api_credentials_parameters() {
        $parameters = [
            'username' => new \external_value(PARAM_TEXT, 'Username for the SafeAssign Site', VALUE_REQUIRED),
            'password' => new \external_value(PARAM_TEXT, 'Password for the SafeAssign Site', VALUE_REQUIRED),
            'baseurl'  => new \external_value(PARAM_URL, 'Password for the SafeAssign Site', VALUE_REQUIRED),
            'userid'   => new \external_value(PARAM_INT, 'User ID', VALUE_REQUIRED)
        ];
        return new \external_function_parameters($parameters);
    }

    /**
     * @return \external_single_structure
     */
    public static function plagiarism_safeassign_test_api_credentials_returns() {
        $keys = [
            'success' => new \external_value(PARAM_BOOL, 'Credential verified', VALUE_REQUIRED)
        ];
        return new \external_single_structure($keys, 'confirmed');
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $baseurl
     * @param int $userid
     * @return @boleean
     */
    public static function plagiarism_safeassign_test_api_credentials($username, $password, $baseurl, $userid) {
        rest_provider::instance()->reset_cache();
        $result = safeassign_api::test_credentials($userid, $username, $password, $baseurl);
        return ['success' => $result];
    }
}