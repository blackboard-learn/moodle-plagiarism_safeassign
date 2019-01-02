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
 * Abstract submission processor.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2019 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace plagiarism_safeassign\api;

defined('MOODLE_INTERNAL') || die();

use plagiarism_safeassign\event\sync_content_log;

/**
 * Class abstract_submission_processor
 * @package plagiarism_safeassign\api
 * @copyright Copyright (c) 2019 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class abstract_submission_processor {
    /**
     * @var array All data being received by the event.
     */
    protected $eventdata;

    /**
     * @var safeassign_submission Object containing all info for a SafeAssign submission.
     */
    protected $submissionobject;

    /**
     * @var int Submission Id.
     */
    protected $submissionid;

    /**
     * @var array Data related to SafeAssign configuration
     */
    protected $config;

    public function __construct(\core\event\base $event) {
        $this->eventdata = $event->get_data();
        $this->submissionid = $this->eventdata["other"]['submissionid'];

        /* Steps:
         * 1. Check if the submission has been sent by an instructor. Instructors cannot send submissions.
         * 2. Check if safeassign is enabled.
         * 3. If everything is ok, then process the event.
        */
        if ($this->is_instructor()) {
            $this->log_error("Subission: " . $this->submissionid . ". User " . $this->eventdata['userid'] .
                    " is instructor, hence, it cannot send submissions to SafeAssign");
            $this->submissionobject = $this->get_submission($this->submissionid);
            if ($this->submissionobject) {
                $this->submissionobject->__set('status', safeassign_submission::STATUS_SUBMISSION_IS_INSTRUCTOR);
                $this->update_submission_record();
            } else {
                $submisinstructor = new safeassign_submission();
                $submisinstructor->__set('status', safeassign_submission::STATUS_SUBMISSION_IS_INSTRUCTOR);
                $submisinstructor->__set('submissionid', $this->submissionid);
                $this->create_submission_record($submisinstructor->get_std_class());
            }
        } else if ($this->check_file_size()) {
            $this->submissionobject = $this->get_submission($this->submissionid);
            if ($this->submissionobject) {
                $this->submissionobject->__set('status', safeassign_submission::STATUS_SUBMISSION_MAX_SIZE);
                $this->update_submission_record();
            } else {
                $submisinstructor = new safeassign_submission();
                $submisinstructor->__set('status', safeassign_submission::STATUS_SUBMISSION_MAX_SIZE);
                $submisinstructor->__set('submissionid', $this->submissionid);
                $this->create_submission_record($submisinstructor->get_std_class());
            }
        } else {
            $this->config = \plagiarism_plugin_safeassign::check_assignment_config($this->eventdata);
            if (get_config('plagiarism', 'safeassign_use') & !empty($this->config) && $this->config['safeassign_enabled']) {
                $this->submissionobject = $this->get_submission($this->submissionid);

                if (is_object($this->submissionobject)) {
                    $this->submissionobject->__set('uuid', null);
                } else {
                    $this->submissionobject = new safeassign_submission();
                    $this->submissionobject->__set("userid", $this->eventdata['userid']);
                    $this->submissionobject->__set("submissionid", $this->submissionid);
                    $this->submissionobject->__set("cmid", $this->eventdata['contextinstanceid']);
                }

                $globalcheck = $this->get_global_check();
                $this->submissionobject->__set("globalcheck", $globalcheck);
                $this->process();
            }
        }
    }

    /**
     * Process the event depending on its kind.
     * @return null
     */
    abstract protected function process();

    /**
     * Checks if the user trying to send is an instructor
     * @return bool
     */
    protected function is_instructor() {
        global $DB;
        $arrayconditions = [
                "courseid" => $this->eventdata['courseid'],
                "instructorid" => $this->eventdata['userid'],
                "unenrolled" => 0];
        return $DB->record_exists("plagiarism_safeassign_instr", $arrayconditions);
    }

    /**
     * Creates a new safeassign_submission object from DB
     * @param $submid int submission id
     * @return object | boolean
     */
    public static function get_submission($submid) {
        global $DB;

        $record = $DB->get_record("plagiarism_safeassign_subm", ["submissionid" => $submid]);
        if ($record) {
            $subm = new safeassign_submission(
                $record->uuid,
                $record->globalcheck,
                $record->groupsubmission,
                $record->highscore,
                $record->avgscore,
                $record->submitted,
                $record->reportgenerated,
                $record->submissionid,
                $record->status,
                $record->type,
                $record->cmid,
                $record->userid,
                true
            );
            return $subm;
        }
        return $record;
    }

    /**
     * Updates submission values into DB.
     */
    protected function update_submission_record() {
        global $DB;
        $updatevalues = [];
        foreach ($this->submissionobject->updates as $update) {
            $updatevalues[$update] = $this->submissionobject->__get($update);
        }
        $submid = $DB->get_field("plagiarism_safeassign_subm", "id", ["submissionid" => $this->submissionid]);
        $updatedrecord = new \stdClass();
        $updatedrecord->id = $submid;
        if (!empty($updatevalues)) {
            foreach ($updatevalues as $key => $value) {
                $updatedrecord->$key = $value;
            }
            $DB->update_record('plagiarism_safeassign_subm', $updatedrecord);
        }
    }

    /**
     * Creates a new submission record
     *
     * @param $submissionobject | stdClass
     */
    protected function create_submission_record($submissionobject) {
        global $DB;
        $DB->insert_record('plagiarism_safeassign_subm', $submissionobject);
    }

    /**
     * Returns if a submission should be send to the Global Reference Database (GRD).
     * @return bool
     */
    protected function get_global_check() {
        $userid = $this->eventdata['userid'];
        $lib = new \plagiarism_plugin_safeassign();
        $response = $lib->should_send_to_global_check($this->config, $userid);
        return $response;
    }

    /**
     * Creates records on table safeassign files for all the files in the submission.
     * @param array $pathnamehashes
     * @throws \coding_exception
     * @throws \dml_exception
     */
    protected function create_file_records($pathnamehashes) {
        global $DB;

        $sql = "SELECT id
                  FROM {files}
                 WHERE pathnamehash ";

        list($sqlin, $params) = $DB->get_in_or_equal($pathnamehashes);
        $filerecords = $DB->get_records_sql($sql . $sqlin, $params);
        $safiles = [];
        foreach ($filerecords as $filerecord) {
            $safile = new \stdClass();
            $safile->submissionid = $this->submissionobject->__get('submissionid');
            $safile->fileid = $filerecord->id;
            array_push($safiles, $safile);
        }

        $DB->insert_records('plagiarism_safeassign_files', $safiles);
    }

    /**
     * Logs an error.
     * @param $errortext
     */
    public static function log_error($errortext) {
        $event = sync_content_log::create_log_message('error', null, true, $errortext);
        $event->trigger();
    }

    /**
     * Checks if the file size of the submission (sum of all files), is less than limit.
     * @return bool
     */
    protected function check_file_size() {
        $totalsize = $this->get_total_file_size();
        $exceeds = false;
        if ($totalsize > SAFEASSIGN_SUBMISSION_MAX_SIZE) {
            $this->log_error("Submissionid: " . $this->submissionid . ". File size exceeds limit " .
                SAFEASSIGN_SUBMISSION_MAX_SIZE . " expected " . $totalsize . " received");
            $exceeds = true;
        }
        return $exceeds;
    }

    /**
     * Returns the total file size for a submission
     * @return int file size in bytes
     */
    protected function get_total_file_size() {
        global $DB;
        $total = 0;
        $fs = get_file_storage();

        $userid = $this->eventdata['userid'];

        $sql = "SELECT id
                  FROM {files}
                 WHERE itemid = :submissionid
                   AND userid = :userid
                   AND (component = 'assignsubmission_file' OR
                        component = 'assignsubmission_text_as_file')
                   AND mimetype IS NOT NULL
                ";
        $records = $DB->get_records_sql($sql, ['submissionid' => $this->submissionid,
            'userid' => $userid]);

        foreach ($records as $record) {
            $fileid = $record->id;
            $file = $fs->get_file_by_id($fileid);
            $total += $file->get_filesize();
        }
        return $total;
    }
}


