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
 * Class api_error_handler
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\api;

defined('MOODLE_INTERNAL') || die();

/**
 * Class error_handler
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class error_handler {

    /**
     * Plugin string for ease of use.
     */
    const PLUGIN = 'plagiarism_safeassign';

    /**
     * Processes the last error that the api consumption got.
     * @param bool $treatasgeneric Treat the error as a generic error.
     * @param bool $addserverressponse Adds the server response to the result.
     * @param bool $htmllisterrors Should the server error be displayed as a list in html
     * @return null|string
     */
    public static function process_last_api_error($treatasgeneric = false, $addserverressponse = false, $htmllisterrors = false) {
        if (rest_provider::instance()->lasthttpcode() < 400) {
            return null;
        }

        $errortext = $htmllisterrors ? '<p>' : '';
        if ($treatasgeneric) {
            $errortext .= get_string('error_api_generic', self::PLUGIN);
        }
        switch (rest_provider::instance()->lasthttpcode()) {
            case 401:
                $errortext .= get_string('error_api_unauthorized', self::PLUGIN);
                break;
            case 403:
                $errortext .= get_string('error_api_forbidden', self::PLUGIN);
                break;
            case 404:
                $errortext .= get_string('error_api_not_found', self::PLUGIN);
                break;
            case 500:
                $errortext .= get_string('error_api_generic', self::PLUGIN);
                break;
            default:
                $errortext .= get_string('error_api_generic', self::PLUGIN);
                break;
        }
        $errortext .= $htmllisterrors ? '</p>' : '';

        if (!empty($errortext) && $addserverressponse) {
            $serverres = json_decode(rest_provider::instance()->lastresponse(), true);
            if (isset($serverres)) {
                $errortext .= self::print_list($serverres, $htmllisterrors);
            }
        }

        return $errortext;
    }

    /**
     * Prints a list of elements in html or broken by line breaks.
     * @param array $list
     * @param bool $htmllist
     * @return string
     */
    private static function print_list(array $list, $htmllist = false) {
        if (empty($list)) {
            return '';
        }

        $res = $htmllist ? '<ul>' : PHP_EOL;
        foreach ($list as $key => $value) {
            $res .= $htmllist ? '<li>' : '';
            $res .= "$key: $value";
            $res .= $htmllist ? '</li>' : PHP_EOL;
        }
        $res .= $htmllist ? '</ul>' : '';

        return $res;
    }
}
