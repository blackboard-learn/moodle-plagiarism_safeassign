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
 * Process text based submissions.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2019 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\api;

defined('MOODLE_INTERNAL') || die();

/**
 * Class text_submission_processor
 *
 * It process text based submissions to SafeAssign.
 * @package plagiarism_safeassign\api
 * @copyright Copyright (c) 2019 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class text_submission_processor extends abstract_submission_processor {
    use trait_event_processor;

    /**
     * {@inheritdoc }
     */
    public function process() {
        global $DB;

        if (property_exists($this->submissionobject, 'type') &&
                $this->submissionobject->__get('type') == safeassign_submission::TYPE_SUBMISSION_FILE) {
            $this->submissionobject->__set("type", safeassign_submission::TYPE_SUBMISSION_FILE_AND_TEXT);
        } else {
            $this->submissionobject->__set("type", safeassign_submission::TYPE_SUBMISSION_ONLINETEXT);
        }

        $filecontent = $DB->get_fieldset_select('assignsubmission_onlinetext', 'onlinetext', 'submission = :submissionid',
                ['submissionid' => $this->submissionid]);

        $createdfile = $this->create_file_from_text(
                $this->submissionobject->__get('userid'),
                $filecontent[0], // Get fieldset returns an array, I need the content as string.
                'assignsubmission_text_as_file',
                'submission_text_files',
                $this->submissionid);

        $this->update_file_records($createdfile);

        // Check if this submission has valid files on tables.
        if ($DB->count_records("plagiarism_safeassign_files", ["submissionid" => $this->submissionid]) > 0) {
            if ($this->submissionobject->__get('update')) {
                $this->update_submission_record($this->submissionobject);
            } else {
                $this->create_submission_record($this->submissionobject->get_std_class());
            }
        }
    }

    /**
     * Creates a file to save the information of a text submission to send it to SafeAssign
     * @param int $userid
     * @param string $filecontent
     * @param string $component
     * @param string $filearea
     * @param int $submissionid
     * @return \stored_file The file created
     * @throws \file_exception
     * @throws \stored_file_creation_exception
     */
    private function create_file_from_text($userid, $filecontent, $component, $filearea, $submissionid) {
        $usercontext = \context_user::instance($userid);
        $contextid = $usercontext->id;

        $filename = 'userid_' . $userid . '_' .
            $component . '_' . $submissionid .'.txt';

        $fs = get_file_storage();
        $oldfile = $fs->get_file($contextid, $component, $filearea,
            $submissionid, '/',
            $filename);
        // Delete old file if exist.
        if ($oldfile) {
            $oldfile->delete();
        }
        // Create a new one.
        $filerecord = [
            'contextid' => $contextid,
            'component' => $component,
            'filearea' => $filearea,
            'itemid' => $submissionid,
            'filepath' => '/',
            'filename' => $filename,
            'userid' => $userid
        ];
        return $fs->create_file_from_string($filerecord, $filecontent);
    }

    /**
     * {@inheritdoc }
     */
    protected function update_file_records($createdfile = null) {
        global $DB;
        // Get all ids of current submission files.
        $currentids = $DB->get_fieldset_select("plagiarism_safeassign_files", 'fileid', 'submissionid = :submissionid',
            ["submissionid" => $this->submissionid]);

        $newids = [];
        // Get id of text submissions on DB.
        if ($this->submissionobject->__get('type') === safeassign_submission::TYPE_SUBMISSION_FILE_AND_TEXT) {
            $userid = $this->eventdata['userid'];
            // If this is a mixed submission, get file ids from files table.
            $sql = "SELECT id, timecreated
                      FROM {files}
                     WHERE itemid = :submissionid
                       AND userid = :userid
                       AND (component = 'assignsubmission_file' OR
                            component = 'assignsubmission_text_as_file')
                       AND mimetype IS NOT NULL";

            $params = ["submissionid" => $this->submissionid, 'userid' => $userid];
            $newids = $DB->get_fieldset_sql($sql, $params);
        } else {
            array_push($newids, $createdfile->get_id());
        }

        // Delete all ids that has been removed from the submission.
        $diff = array_diff($currentids, $newids);
        try {
            // Transactional state created for deletion and update of files.
            $transaction = $DB->start_delegated_transaction();
            if (count($diff) > 0) {
                $sql = "DELETE FROM {plagiarism_safeassign_files}
                          WHERE fileid ";
                list($sqlin, $params) = $DB->get_in_or_equal($diff);
                $DB->execute($sql . $sqlin, $params);
            }

            // Add all ids that are new for the submission.
            $diff = array_diff($newids, $currentids);
            if (count($diff) > 0) {
                $safiles = [];
                foreach ($diff as $id) {
                    $safile = new \stdClass();
                    $safile->submissionid = $this->submissionid;
                    $safile->fileid = $id;
                    $safile->timesubmitted = $createdfile->get_timecreated();
                    array_push($safiles, $safile);
                }
                $DB->insert_records('plagiarism_safeassign_files', $safiles);
            }
            $transaction->allow_commit();
        } catch (Exception $e) {
            $transaction->rollback($e);
            throw new \dml_exception("Update of file records for submission " . $this->submissionid . " could not be completed");
        }
    }
}