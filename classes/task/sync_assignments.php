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
 * Task to sync assignments, course and submissions from Moodle to SafeAssign.
 * @package    plagiarism_safeassign
 * @subpackage plagiarism
 * @copyright Copyright (c) 2018 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace plagiarism_safeassign\task;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/plagiarism/safeassign/lib.php');
use plagiarism_safeassign\event\sync_content_log;
use plagiarism_safeassign\event\serv_unavailable_log;

/**
 * Class sync_assignments
 * @package    plagiarism_safeassign
 * @copyright  Copyright (c) 2017 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class sync_assignments extends \core\task\scheduled_task {

    /**
     * {@inheritdoc}
     * @return string
     */
    public function get_name() {

        return get_string('sync_assignments', 'plagiarism_safeassign');
    }

    /**
     * {@inheritdoc}
     */
    public function execute() {
        global $DB, $CFG;

        if (get_config('plagiarism_safeassign', 'enabled')) {

            set_error_handler(function ($n, $errstr, $file, $line) {
                $errormessage = $errstr . ' in ' . $file . ' on line ' . $line;
                throw new \moodle_exception($errormessage);
            });
            try {
                if (!PHPUNIT_TEST) {
                    if (!defined('SAFEASSIGN_OMIT_CACHE')) {
                        define('SAFEASSIGN_OMIT_CACHE', true);
                    }
                }
                $safeassign = new \plagiarism_plugin_safeassign();
                $serviceavail = $safeassign->test_credentials_before_tasks();
                if ($serviceavail === true) {
                    $safeassign->delete_submissions();
                    $unsynccourses = $DB->get_records('plagiarism_safeassign_course', array('uuid' => null));
                    if (!empty($unsynccourses)) {
                        $safeassign->sync_courses($unsynccourses);
                    }
                    $safeassign->sync_course_assignments();
                    $safeassign->sync_assign_submissions();
                    if ($CFG->siteadmins != get_config('plagiarism_safeassign', 'siteadmins')) {
                        $safeassign->set_siteadmins();
                    }
                    $additionalroles = get_config('plagiarism_safeassign', 'safeassign_additional_roles');
                    $syncedroles = get_config('plagiarism_safeassign', 'safeassign_synced_roles');
                    if ($additionalroles != $syncedroles) {
                        $safeassign->set_additional_role_users($additionalroles, $syncedroles);
                    }
                    $safeassign->sync_instructors();
                    $safeassign->delete_instructors();
                } else {
                    $event = serv_unavailable_log::create();
                    $event->trigger();
                }
            } catch (\moodle_exception $exception) {
                $event = sync_content_log::create_log_message('error', null, true, $exception->getMessage());
                $event->trigger();
            }
            restore_error_handler();
        }
    }
}
