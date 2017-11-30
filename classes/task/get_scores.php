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

namespace plagiarism_safeassign\task;
use plagiarism_safeassign\event\score_sync_fail;
defined('MOODLE_INTERNAL') || die();

/**
 * get_scores class - used to get scores for submitted files from SafeAssign.
 *
 * @package    plagiarism_safeassign
 * @copyright  Copyright (c) 2017 Blackboard Inc.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_scores extends \core\task\scheduled_task {
    public function get_name() {
        // Shown in admin screens.
        return get_string('getscores', 'plagiarism_safeassign');
    }

    public function execute() {
        global $CFG;
        if (get_config('plagiarism', 'safeassign_use')) {
            require_once($CFG->dirroot . '/plagiarism/safeassign/lib.php');
            set_error_handler(function ($n, $errstr, $file, $line) {
                $errormessage = $errstr . ' in ' . $file . ' on line ' . $line;
                throw new \moodle_exception($errormessage);
            });
            $safeassign = new \plagiarism_plugin_safeassign();
            try {
                $safeassign->safeassign_get_scores();
            } catch (\moodle_exception $exception) {
                $event = score_sync_fail::create_from_error_handler(0, $exception->getMessage(), 'task_error');
                $event->trigger();
            }
            restore_error_handler();
        }
    }
}