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
 * Task to send notifications to Admins when a new SafeAssign License Terms & Conditions is available.
 * @package    plagiarism_safeassign
 * @subpackage plagiarism
 * @copyright Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace plagiarism_safeassign\task;
defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->dirroot . '/plagiarism/safeassign/lib.php');
use plagiarism_safeassign\event\sync_content_log;

/**
 * Class send_notifications
 * @package    plagiarism_safeassign
 * @copyright  Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class send_notifications extends \core\task\scheduled_task {

    public function get_name() {
        return get_string('send_notifications', 'plagiarism_safeassign');
    }

    public function execute() {

        try {

            if (get_config('plagiarism_safeassign', 'enabled')) {

                // Get the current status from the API, 0 when there is a new license version.
                $status = get_config('plagiarism_safeassign', 'safeassign_license_agreement_status');
                // Get checkbox status, 0 when there is a new license version.
                $readbyadmin = get_config('plagiarism_safeassign', 'safeassign_license_agreement_readbyadmin');

                if (empty($status) && empty($readbyadmin)) {
                    $safeassign = new \plagiarism_plugin_safeassign();
                    $safeassign->new_safeassign_license_notification();
                }
            }

        } catch (\moodle_exception $exception) {
            $event = sync_content_log::create_log_message('error', null, true, $exception->getMessage());
            $event->trigger();
        }

    }
}
