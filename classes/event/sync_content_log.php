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
 * Logging event.
 *
 * @package    plagiarism_safeassign
 * @author     Jonathan Garcia
 * @copyright  Copyright (c) 2017 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\event;
use plagiarism_safeassign\api\error_handler;
use core\event\base;
defined('MOODLE_INTERNAL') || die();


/**
 * Class safeassign_log_fail.
 *
 * @package    plagiarism_safeassign
 * @author     Jonathan Garcia
 * @copyright  Copyright (c) 2017 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class sync_content_log extends base {

    const PLUGIN = 'plagiarism_safeassign';

    /**
     * Init method.
     *
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->context = \context_system::instance();
    }

    /**
     * Returns the event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('api_call_log_event', self::PLUGIN);
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        if (empty($this->other['message'])) {
            $message = $this->other['resource'] . ' synced successfully';
        } else {
            $message = 'An error occurred trying to sync the ' . $this->other['resource'] . 'with ID: ';
            $message .= $this->other['itemid'] . ' into SafeAssign: <br>';
            $message .= $this->other['message'];
        }
        return $message;
    }

    /**
     * Returns the log message and if exist a description gotten from the SafeAssign server.
     * @param string $resource
     * @param int $itemid
     * @param bool $error
     * @return self
     * @throws \coding_exception
     */
    public static function create_log_message($resource, $itemid = null, $error = true) {
        $lasterror = '';
        if ($error) {
            $lasterror = error_handler::process_last_api_error();
        }

        return self::create([
            'other' => [
                'message' => $lasterror,
                'itemid' => $itemid,
                'resource' => $resource
            ]
        ]);
    }
}