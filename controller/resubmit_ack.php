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
 * SafeAssign resubmit ack controller.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') or die('Direct access to this script is forbidden.');
global $CFG;
require_once($CFG->dirroot . '/plagiarism/safeassign/lib.php');

/**
 * SafeAssign resubmit ack controller.
 *
 * @author    David Castro
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plagiarism_safeassign_controller_resubmit_ack extends mr_controller {

    /**
     * View action.
     */
    public function view_action() {
        $this->ajax_err_response('404 Unauthorized');
    }

    /**
     * Defines the json headers.
     */
    private function define_json_headers() {
        if (!defined('AJAX_SCRIPT') && !defined('NO_DEBUG_DISPLAY')) {
            define('AJAX_SCRIPT', true);
            define('NO_DEBUG_DISPLAY', true);
        }
    }

    /**
     * Acknowledge action.
     */
    public function ack_action() {
        $this->define_json_headers();

        $submissionuuid = required_param('uuid', PARAM_ALPHANUMEXT);

        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->resubmit_acknowlegment($submissionuuid);

        echo json_encode(['success' => true]);
    }

    /**
     * Generate ajax error
     *
     * @param string $errstr
     */
    protected function ajax_err_response($errstr) {
        header("HTTP/1.0 401 Not Authorized");
        echo $errstr;
        die();
    }
}