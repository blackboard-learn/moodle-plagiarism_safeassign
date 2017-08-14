<?php

namespace  plagiarism_safeassign\webservice;
use plagiarism_safeassign\local\api\rest_provider;
use plagiarism_safeassign\local\api\safeassign_api;
require_once('../../../../config.php');

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../../lib/externallib.php');

/**
 * Test the intructor and student credentiales.
 * @autor Jonathan Garcia
 * @copyright Copyright (c) 2016 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class test_api_credentials extends  \external_api {

    /**
     * @return \external_function_parameters
     */
    public static function service_parameters() {
        $parameters = [
            'username' => new \external_value(PARAM_ALPHAEXT, 'Username for the SafeAssign Site', VALUE_REQUIRED),
            'password' => new \external_value(PARAM_ALPHAEXT, 'Password for the SafeAssign Site', VALUE_REQUIRED),
            'isinstructor' => new \external_value(PARAM_BOOL, 'Indicates if the credentials are for instructor', VALUE_REQUIRED)

        ];
        return new \external_function_parameters($parameters);
    }

    /**
     * @return \external_single_structure
     */
    public static function service_returns() {
        $keys = [
            'success' => new \external_value(PARAM_BOOL, 'Credential verified', VALUE_REQUIRED)
        ];
        return new \external_single_structure($keys, 'confirmed');
    }

    /**
     * @param string $username
     * @param string $password
     * @param boolean $isintructor
     * @return @boleean
     */
    public static function service($username, $password, $isinstructor) {
        global $USER;

        rest_provider::instance()->reset_cache();
        $result = safeassign_api::login($USER->id, $isinstructor, $username, $password);

        return ['success' => $result];

    }
}