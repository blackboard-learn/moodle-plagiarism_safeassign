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
 * Class score_sync_failed.
 *
 * This event is fired when the score sync fails for any submission.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace plagiarism_safeassign\event;

use core\event\base;
use plagiarism_safeassign\api\error_handler;

/**
 * Class score_sync_fail
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2018 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class score_sync_fail extends base {

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
        return get_string('getscoreslogfailed', 'plagiarism_safeassign');
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        if ($this->other['resource'] === 'api_error') {
            $messagedesc = 'An error ocurred trying to sync the scores for SafeAssign submission ID '. $this->other['submissionid'];
            $messagedesc .= $this->other['message'];
        } else if ($this->other['resource'] === 'task_error') {
            $messagedesc = $this->other['message'];
        }
        if (!empty($this->other['params'])) {
            $messagedesc .= '<br>Parameters:<br>';
            foreach ($this->other['params'] as $key => $value) {
                $messagedesc .= $key . ': ' . $value . '<br>';
            }
        }
        return $messagedesc;
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
