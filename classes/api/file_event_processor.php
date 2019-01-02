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
 * Process upload file events
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2019 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\api;

use Horde\Socket\Client\Exception;

defined('MOODLE_INTERNAL') || die();

/**
 * Class file_event_processor
 * @package plagiarism_safeassign\api
 * @copyright Copyright (c) 2019 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class file_event_processor {
    use trait_event_processor;

    /**
     * @var array All information of the event being received.
     */
    private $eventdata;

    /**
     * @var int The submission id.
     */
    private $submissionid;

    public function __construct(\core\event\base $event) {
        $this->eventdata = $event->get_data();
        $this->submissionid = $this->eventdata['objectid'];
        $this->process();
    }

    /**
     * Process the file data from event.
     * @return null
     */
    private function process() {
        $pathnamehashes = $this->eventdata['other']['pathnamehashes'];
        if ($pathnamehashes != null) {
            $this->update_file_records($pathnamehashes);
        }
    }

    /**
     * {@inheritdoc }
     */
    protected function update_file_records($pathnamehashes = null) {
        global $DB;
        // Get all ids of current submission files.
        $currentids = $DB->get_fieldset_select("plagiarism_safeassign_files", 'fileid', 'submissionid = :submissionid',
            ["submissionid" => $this->eventdata["objectid"], "component" => "assignsubmission_file"]);

        // Get all ids of pathnamehashes.
        $sql = "SELECT id, timecreated
                  FROM {files}
                 WHERE pathnamehash ";

        list($sqlin, $params) = $DB->get_in_or_equal($pathnamehashes);
        $mrfiles = $DB->get_records_sql($sql . $sqlin, $params);

        $newids = [];
        foreach ($mrfiles as $record) {
            array_push($newids, $record->id);
        }

        // Delete all ids that has been removed from the submission.
        $diff = array_diff($currentids, $newids);
        try {
            // Create transactional state for deletion and update of records.
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
                    $safile->timesubmitted = $mrfiles[$id]->timecreated;
                    array_push($safiles, $safile);
                }
                $DB->insert_records('plagiarism_safeassign_files', $safiles);
            }
            $transaction->allow_commit();
        } catch (Exception $e) {
            $transaction->rollback($e);
            throw new \dml_exception("Update of files records for submission " . $this->submissionid . " could not be completed.");
        }
    }
}