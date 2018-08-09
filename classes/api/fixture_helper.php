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
 * SafeAssign plagiarism integration package.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\api;

defined('MOODLE_INTERNAL') || die();
/**
 * Class originality_report_fixture_helper
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class fixture_helper {

    /**
     * Pushes fixtures for login and originality report
     * @param object $user
     * @param string $uuid
     * @param string $fileuuid
     * @param bool $force
     */
    public static function push_login_and_report($user, $uuid, $fileuuid, $force = false) {
        test_safeassign_api_connectors::config_set_ok();
        $teacherloginurl = test_safeassign_api_connectors::create_login_url($user);
        testhelper::push_pair($teacherloginurl, 'user-login-final.json');
        // Get the originality report from SafeAssign.
        $getreporthtmlurl = test_safeassign_api_connectors::create_get_originality_report_with_file_url($uuid, $fileuuid, $force);
        $version = 'v' . ($force ? 1 : 2);
        testhelper::push_pair($getreporthtmlurl, 'sample-originality-report-' . $version . '.html');
    }

}