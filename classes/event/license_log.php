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
 * Class license_log.
 *
 * This event is fired when Safeassign license is trying to be synced.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace plagiarism_safeassign\event;

use core\event\base;
use plagiarism_safeassign\api\error_handler;

defined('MOODLE_INTERNAL') || die();

/**
 * Class license_log
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class license_log extends base {

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
     * Return the event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('acceptlicenselog', 'plagiarism_safeassign');
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        if ($this->other['resource'] === 'error') {
            $messagedesc = $this->other['message'];
        } else if ($this->other['resource'] === 'license') {
            if (is_null($this->other['message']) && $this->other['error'] === false) {
                $messagedesc = 'SafeAssign license was accepted succesfully.';
            } else {
                $messagedesc = 'An error occurred trying to sync the SafeAssign license preferences';
                if (!is_null($this->other['message'])) {
                    $messagedesc .= ': <br>' .$this->other['message'];
                }
            }
        }
        return $messagedesc;
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
}