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
 * File submission processor.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2019 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\api;

defined('MOODLE_INTERNAL') || die();

/**
 * Class file_submission_processor
 *
 * It will process a file submission.
 * @package plagiarism_safeassign\api
 * @copyright Copyright (c) 2019 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class file_submission_processor extends abstract_submission_processor {
    /**
     * {@inheritdoc }
     */
    protected function process() {
        global $DB;
        $this->submissionobject->__set("type", safeassign_submission::TYPE_SUBMISSION_FILE);

        // Check if this submission has valid files on tables.
        if ($DB->count_records("plagiarism_safeassign_files", array("submissionid" => $this->submissionid)) > 0) {
            if ($this->submissionobject->__get('update')) {
                $this->update_submission_record($this->submissionobject);
            } else {
                $this->create_submission_record($this->submissionobject->get_std_class());
            }
        }
    }
}