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
 * Submission event processor
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2019 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\api;

defined('MOODLE_INTERNAL') || die();

/**
 * Class submission_event_processor
 *
 * It process a submission event.
 * @package plagiarism_safeassign\api
 * @copyright Copyright (c) 2019 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class submission_event_processor {
    private $eventdata;
    private $submissionid;

    public function __construct($event) {
        global $DB;
        $this->eventdata = $event->get_data();
        $this->submissionid = $this->eventdata['objectid'];

        $record = $DB->get_record('plagiarism_safeassign_subm',
                array("submissionid" => $this->submissionid),
                'id, status'
                );

        if ($record) {
            $said = $record->id;
            $submsent = new \stdClass();
            $submsent->id = $said;
            if ($record->status == safeassign_submission::STATUS_SUBMISSION_DRAFT) {
                $submsent->status = safeassign_submission::STATUS_SUBMISSION_SUBMITTED;
                $DB->update_record('plagiarism_safeassign_subm', $submsent);
            }
        } else {
            abstract_submission_processor::log_error(
                    "Submission with ID=" . $this->submissionid . " does not exist.");
        }
    }
}