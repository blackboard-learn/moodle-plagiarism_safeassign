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
 * @copyright Copyright (c) 2017 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace plagiarism_safeassign\event;

use core\event\base;
use plagiarism_safeassign\api\error_handler;

defined('MOODLE_INTERNAL') || die();

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
        $messagedesc = get_string('getscoreslogfailed_desc', 'plagiarism_safeassign', $this->other['submissionid']);
        $messagedesc .= $this->other['message'];
        return $messagedesc;
    }

    /**
     *
     * @return self
     * @throws \coding_exception
     */
    public static function create_from_error_handler($submid) {
        $lasterror = error_handler::process_last_api_error();

        return self::create([
            'other' => [
                'message' => $lasterror,
                'submissionid' => $submid,
            ]
        ]);
    }
}