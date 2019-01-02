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
 * Abstract class to centralize SafeAssign events.
 *
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2019 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace plagiarism_safeassign\event;

use core\event\base;
use plagiarism_safeassign\api\error_handler;

defined('MOODLE_INTERNAL') || die();

/**
 * Class safeassign_event
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2019 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class safeassign_event extends base {

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
     * Returns the log message and if exist a description gotten from the SafeAssign server.
     * @param string $resource
     * @param bool $error
     * @param mixed $message
     * @return self
     * @throws \coding_exception
     */
    public static function create_log_message($resource, $error = true, $message = null) {
        $lasterror = $message;
        if ($error === true && $message === null) {
            $lasterror = error_handler::process_last_api_error(false, true, true);
        }

        return self::create([
            'other' => [
                'message' => $lasterror,
                'resource' => $resource,
                'error' => $error
            ]
        ]);
    }

    /**
     * Builds the event object.
     * @param int $submid
     * @param mixed $message
     * @param string $resource
     * @param array $params
     * @return self
     * @throws \coding_exception
     */
    public static function create_from_error_handler($submid, $message = false, $resource = 'api_error', $params = array()) {
        $id = '';
        if ($resource === 'api_error') {
            $lasterror = error_handler::process_last_api_error(false, true, true);
            $id = $submid;
        } else if ($resource === 'task_error') {
            $lasterror = $message;
        }

        return self::create([
            'other' => [
                'message' => $lasterror,
                'submissionid' => $id,
                'resource' => $resource,
                'params' => $params
            ]
        ]);
    }
}