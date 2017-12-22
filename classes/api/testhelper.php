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
 * Unit test helpers.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\api;

defined('MOODLE_INTERNAL') || die();

/**
 * Class testhelper
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class testhelper {

    /**
     * @var array
     */
    protected static $fixturestash = [];

    /**
     * Save the codes stash
     * @var array
     */
    protected static $codestash = [];

    /**
     * Timeout sensitive stash.
     * @var array
     */
    protected static $timeoutvalstash = [];

    /**
     * @param  string $rpath
     * @param  string $name
     * @return null|string
     */
    protected static function load_data($rpath,  $name) {
        $result = null;

        $repath = realpath($rpath.$name);
        if ($repath !== false) {
            $lresult = file_get_contents($repath);
            if ($lresult !== false) {
                $result = $lresult;
            }
        }

        return $result;
    }

    /**
     * @param  string $url
     * @return null|string
     */
    public static function get_fixture_data($url) {
        global $CFG;
        $result = null;
        $filename = self::generate_name($url);
        if (!empty($filename)) {
            $result = self::load_data($CFG->dirroot.'/plagiarism/safeassign/tests/fixtures/', $filename);
        }

        // Review if there's a value for that url in the timed values stash.
        if (!isset($result)) {
            $result = self::get_timed_value($url);
        }
        return $result;
    }

    /**
     * @param $url
     * @return int
     */
    public static function get_code_data($url) {
        return self::$codestash[$url];
    }

    /**
     * @param string $url
     * @param string $filename
     * @param int $httpcode
     * @return void
     */
    public static function push_pair($url, $filename, $httpcode = 200) {
        self::$fixturestash[$url] = $filename;
        self::$codestash[$url] = $httpcode;
    }

    /**
     * @param string $url
     * @return null|string
     */
    public static function generate_name($url) {
        if (!empty(self::$fixturestash[$url])) {
            return self::$fixturestash[$url];
        }
        return null;
    }

    /**
     * @return void
     */
    public static function reset_stash() {
        self::$fixturestash = [];
        self::$codestash = [];
        self::$timeoutvalstash = [];
    }

    /**
     * Simulates MUC storage with a timeout.
     * @param $param
     * @param $val
     * @param $timeout
     */
    public static function set_timed_value($param, $val, $timeout) {
        self::$timeoutvalstash[$param] = new \stdClass();
        self::$timeoutvalstash[$param]->val = $val;
        self::$timeoutvalstash[$param]->timeout = $timeout;
        self::$timeoutvalstash[$param]->created = time();
    }

    /**
     * Reviews and returns a stored value if found.
     * @param $param
     * @return null|mixed
     */
    protected static function get_timed_value($param) {
        $res = null;
        if (isset(self::$timeoutvalstash[$param])) {
            $val = self::$timeoutvalstash[$param];
            $ellapsedtime = time() - $val->created;
            if ($ellapsedtime < $val->timeout) {
                $res = $val->val;
            }
        }
        return $res;
    }

    public static function push_originality_report_data() {

    }

}
