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
//

/**
 * SafeAssign external file.
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
use plagiarism_safeassign\api\rest_provider;
use plagiarism_safeassign\api\safeassign_api;
require_once($CFG->libdir . '/externallib.php');

/**
 * Test the instructor and student credentials.
 * @author    Jonathan Garcia
 * @copyright Copyright (c) 2017 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plagiarism_safeassign_test_api_credentials_external extends external_api {

    /**
     * Defines the input parameters of the web service.
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
     * Defines the response of the web service.
     * @return \external_single_structure
     */
    public static function plagiarism_safeassign_test_api_credentials_returns() {
        $keys = [
            'success' => new \external_value(PARAM_BOOL, 'Credential verified', VALUE_REQUIRED)
        ];
        return new \external_single_structure($keys, 'confirmed');
    }

    /**
     * Checks if the API credentials are working correctly.
     * @param string $username
     * @param string $password
     * @param string $baseurl
     * @param int $userid
     * @return @boolean
     */
    public static function plagiarism_safeassign_test_api_credentials($username, $password, $baseurl, $userid) {
        rest_provider::instance()->reset_cache();
        $result = safeassign_api::test_credentials($userid, $username, $password, $baseurl);
        return ['success' => $result];
    }
}

/**
 * Save the global check flag state in the DB.
 * @author    Juan Felipe Martinez Ramos
 * @copyright Copyright (c) 2017 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plagiarism_safeassign_update_flag_external extends external_api {

    /**
     * Defines the input parameters of the web service.
     * @return \external_function_parameters
     */
    public static function plagiarism_safeassign_update_flag_parameters() {
        $parameters = [
            'cmid' => new \external_value(PARAM_INT, 'Course module ID', VALUE_REQUIRED),
            'userid' => new \external_value(PARAM_INT, 'User ID', VALUE_REQUIRED),
            'flag'  => new \external_value(PARAM_INT, 'Global check flag 1 or 0', VALUE_REQUIRED)
        ];
        return new \external_function_parameters($parameters);
    }

    /**
     * Defines the response of the web service.
     * @return \external_single_structure
     */
    public static function plagiarism_safeassign_update_flag_returns() {
        $keys = [
            'success' => new \external_value(PARAM_BOOL, 'Flag updated', VALUE_REQUIRED)
        ];
        return new \external_single_structure($keys, 'confirmed');
    }

    /**
     * Saves and updates the state of the global check flag.
     * @param int $cmid
     * @param int $userid
     * @param int $flag
     * @return @boolean
     */
    public static function plagiarism_safeassign_update_flag($cmid, $userid, $flag) {
        global $DB;
        $config = new stdClass();
        $config->cm = (int) $cmid;
        $config->name = (string) $userid;
        $config->value = (string) $flag;
        $info = $DB->get_record('plagiarism_safeassign_config', array('cm' => $cmid, 'name' => $userid));
        if (empty($info)) {
            $res = $DB->insert_record('plagiarism_safeassign_config', $config);
        } else {
            $info->value = (string)$flag;
            $res = $DB->update_record('plagiarism_safeassign_config', $info);
        }
        return ['success' => $res];
    }
}
