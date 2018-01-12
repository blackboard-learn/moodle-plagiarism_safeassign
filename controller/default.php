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
 * Controller
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') or die('Direct access to this script is forbidden.');

use plagiarism_safeassign\api\safeassign_api;
use plagiarism_safeassign\api\rest_provider;
use plagiarism_safeassign\api\fixture_helper;
use plagiarism_safeassign\local;

/**
 * SafeAssign default controller.
 *
 * @package plagiarism/safeassign
 */
class plagiarism_safeassign_controller_default extends mr_controller {

    /**
     * Require capabilities
     */
    public function require_capability() {
        require_capability('plagiarism/safeassign:report', $this->get_context());
    }

    /**
     * Controller Initialization
     *
     */
    public function init() {
        $this->heading->set('originality_report');
    }

    /**
     * Main view action
     *
     * @return string - the html for the view action
     */
    public function view_action() {
        global $OUTPUT, $USER, $CFG;
        $courseid = required_param('courseid', PARAM_INT);
        $uuid = required_param('uuid', PARAM_ALPHANUMEXT);
        $fileuuid = optional_param('fileuuid', false, PARAM_ALPHANUMEXT);

        if (!local::duringtesting() || !defined('SAFEASSIGN_OMIT_CACHE')) {
            define('SAFEASSIGN_OMIT_CACHE', true);
        }

        // Login as teacher or instructor.
        $context = context_course::instance($courseid);
        // This capability is for instructors only.
        $enablecap = 'plagiarism/safeassign:enable';
        $teachers = get_enrolled_users($context, $enablecap);
        $isinstructor = false;
        foreach ($teachers as $teacher) {
            if ($teacher->id == $USER->id) {
                $isinstructor = true;
                break;
            }
        }

        // This saves the correct token for the report display (student or instructor).
        if (local::duringtesting()) {
            fixture_helper::push_login_and_report($USER, $uuid, $fileuuid);
        }
        $out = safeassign_api::get_originality_report($USER->id, $uuid, $isinstructor, $fileuuid);

        if (local::duringtesting()) {
            require_once($CFG->dirroot.'/lib/jquery/plugins.php');
            /* @noinspection PhpUndefinedFieldInspection */
            $jqueryfile = $plugins['jquery']['files'][0];
            $out .= '<script src="'.$CFG->wwwroot.'/lib/jquery/'.$jqueryfile.'"></script>';
        }

        if (empty($out)) {
            $errortext = '<p>';
            $httpcode = rest_provider::instance()->lasthttpcode();
            if ($httpcode == 404) {
                $errortext .= get_string('originality_report_unavailable', 'plagiarism_safeassign');
            } else {
                $errortext .= get_string('originality_report_error', 'plagiarism_safeassign');
            }
            $errortext .= '</p>';
            $errortext .= \plagiarism_safeassign\api\error_handler::process_last_api_error(false, true, true);

            $out = $OUTPUT->notification($errortext, 'notifyerror');
            return $out;
        }

        echo $OUTPUT->render_from_template('plagiarism_safeassign/originalityreport',
            (object) [
                'content' => $out,
                'uuid' => $uuid,
                'courseid' => $courseid,
                'wwwroot' => $CFG->wwwroot,
                'sesskey' => sesskey()
            ]);

        return;
    }
}