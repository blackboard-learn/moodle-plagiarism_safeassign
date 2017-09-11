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
// GNU General Public License for more details.Bosa, Bogotá
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package    plagiarism_safeassign
 * @subpackage plagiarism
 * @copyright Copyright (c) 2017 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace plagiarism_safeassign\task;
use plagiarism_safeassign\api\safeassign_api;
global $CFG;
require_once($CFG->dirroot . '/plagiarism/safeassign/lib.php');
use plagiarism_safeassign\event\sync_content_log;
use plagiarism_safeassign\api\rest_provider;

defined('MOODLE_INTERNAL') || die();

class sync_assignments extends \core\task\scheduled_task {

    public function get_name() {

        return get_string('sync_assignments', 'plagiarism_safeassign');
    }

    public function execute() {
        global $DB;
        if (!PHPUNIT_TEST) {
            if (!defined('SAFEASSIGN_OMIT_CACHE')) {
                define('SAFEASSIGN_OMIT_CACHE', true);
            }
        }
        $safeassign = new \plagiarism_plugin_safeassign();
        $unsynccourses = $DB->get_records('plagiarism_safeassign_course', array('uuid' => null));
        if (!empty($unsynccourses)) {
            $safeassign->sync_courses($unsynccourses);
            $event = sync_content_log::create_log_message('Courses', null, false);
            $event->trigger();

        }
        $safeassign->sync_course_assignments();
        $safeassign->sync_assign_submissions();
    }
}
