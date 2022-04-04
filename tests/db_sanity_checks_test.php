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
 * Unit tests for sanity checks on SafeAssign databases.
 *
 * @package    plagiarism_safeassign
 * @author     Juan Ibarra
 * @copyright  Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace plagiarism_safeassign;
defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once(__DIR__.'/base.php');
require_once($CFG->dirroot . '/mod/assign/tests/base_test.php');
require_once($CFG->dirroot . '/mod/assign/externallib.php');
require_once($CFG->dirroot . '/plagiarism/safeassign/tests/base.php');
require_once($CFG->dirroot . '/plagiarism/safeassign/tests/safeassign_api_test.php');

/**
 * Test the sanity checks processes on SafeAssign.
 * @copyright  Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class db_sanity_checks_test extends plagiarism_safeassign_base_testcase {

    public function setUp(): void {
        global $DB;
        set_config('enabled', 1, 'plagiarism_safeassign');
        // Create a course, assignment, and users.
        $this->course = self::getDataGenerator()->create_course();

        $this->teacher = self::getDataGenerator()->create_user([
            'firstname' => 'Teacher',
            'lastname' => 'WhoTeaches'
        ]);
        $teacherrole = $DB->get_record('role', array('shortname' => 'editingteacher'));
        $this->getDataGenerator()->enrol_user($this->teacher->id,
            $this->course->id,
            $teacherrole->id);
        $this->setUser($this->teacher);

        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $params['course'] = $this->course->id;
        $params['assignsubmission_onlinetext_enabled'] = 1;
        $params['assignsubmission_file_enabled'] = 1;
        $params['assignsubmission_file_maxfiles'] = 5;
        $params['assignsubmission_file_maxsizebytes'] = 1024 * 1024;
        $instance = $generator->create_instance($params);
        $this->assigninstance = $instance;
        $this->cm = get_coursemodule_from_instance('assign', $instance->id);
        $context = \context_module::instance($this->cm->id);

        $assign = new \assign($context, $this->cm, $this->course);

        $studentrole = $DB->get_record('role', array('shortname' => 'student'));
        $this->students = [];
        array_push($this->students, array(
            "student" => null,
            "firstname" => "Student1",
            "lastname" => "WhoStudies",
            "hasfile" => true,
            "hasonlinetext" => true,
            "submissionid" => 0
        ));
        array_push($this->students, array(
            "student" => null,
            "firstname" => "Student2",
            "lastname" => "WhoStudies",
            "hasfile" => true,
            "hasonlinetext" => false,
            "submissionid" => 0
        ));
        array_push($this->students, array(
            "student" => null,
            "firstname" => "Student3",
            "lastname" => "WhoStudies",
            "hasfile" => false,
            "hasonlinetext" => true,
            "submissionid" => 0
        ));
        for ($i = 0; $i < count($this->students); $i++) {
            $studenti = self::getDataGenerator()->create_user([
                'firstname' => $this->students[$i]["firstname"],
                'lastname' => $this->students[$i]["lastname"]]);
            $this->students[$i]["student"] = $studenti;
            $this->getDataGenerator()->enrol_user($studenti->id,
                $this->course->id,
                $studentrole->id);
            $submissionid = $this->create_submission($studenti, $assign, $this->students[$i]["hasfile"],
                $this->students[$i]["hasonlinetext"]);
            $this->students[$i]["submissionid"] = $submissionid;
        }
    }

    private function create_submission($student, $assign, $file, $onlinetext) {
        $this->setUser($student);

        $context = $assign->get_context();
        $submission = $assign->get_user_submission($student->id, true);
        if ($file) {
            $fs = get_file_storage();
            $dummy = (object) array(
                'contextid' => $context->id,
                'component' => 'assignsubmission_file',
                'filearea' => ASSIGNSUBMISSION_FILE_FILEAREA,
                'itemid' => $submission->id,
                'filepath' => '/',
                'filename' => 'myassignmnent' . $student->id . '.pdf'
            );
            $fi = $fs->create_file_from_string($dummy, 'Content of ' . $dummy->filename);
            $files = $fs->get_area_files($context->id, 'assignsubmission_file', ASSIGNSUBMISSION_FILE_FILEAREA,
                $submission->id, 'id', false);

            $data = new \stdClass();
            $plugin = $assign->get_submission_plugin_by_type('file');
            $plugin->save($submission, $data);
        }

        if ($onlinetext) {
            $data = (object) [
                'onlinetext_editor' => [
                    'itemid' => file_get_unused_draft_itemid(),
                    'text' => 'Submission text',
                    'format' => FORMAT_PLAIN,
                ],
            ];

            $plugin = $assign->get_submission_plugin_by_type('onlinetext');
            $plugin->save($submission, $data);
        }
        return $submission->id;
    }

    /**
     * Test that when a submission is corrupted, SafeAssign can recover a correct value.
     */
    public function test_hasfile_hasonlinetext_correct_values() {
        global $DB;
        $this->resetAfterTest(true);
        $this->set_safeassign_records();

        $originalrecords = $DB->get_records("plagiarism_safeassign_subm");

        // Corrupt records.
        $sql = '
            UPDATE {plagiarism_safeassign_subm} SET hasonlinetext = 0, hasfile = 0';
        $DB->execute($sql);

        $corruptrecords = $DB->count_records("plagiarism_safeassign_subm",
            array("deprecated" => 0, "hasfile" => 0, "hasonlinetext" => 0));
        $this->assertEquals(3, $corruptrecords);

        // Getting unsynced submissions. This process will recover records to correct values.
        $plagiarismplugin = new \plagiarism_plugin_safeassign();
        $plagiarismplugin->get_unsynced_submissions();

        $recoverrecords = $DB->get_records("plagiarism_safeassign_subm");
        foreach ($recoverrecords as $key => $record) {
            $this->assertEquals($record->hasfile, $originalrecords[$key]->hasfile);
            $this->assertEquals($record->hasonlinetext, $originalrecords[$key]->hasonlinetext);
        }
    }

    /**
     * Insert some SafeAssign records directly on the database.
     */
    private function set_safeassign_records() {
        global $DB;
        $record = new \stdClass();
        $record->uuid = null;
        $record->courseid = $this->course->id;
        $record->instructorid = $this->teacher->id;
        $DB->insert_record('plagiarism_safeassign_course', $record);

        $record2 = new \stdClass();
        $record2->uuid = null;
        $record2->assignmentid = $this->assigninstance->id;
        $record2->courseid = $this->course->id;
        $DB->insert_record('plagiarism_safeassign_assign', $record2);

        // Turn on SafeAssign for the test assignment.
        $enablesafeassign = new \stdClass();
        $enablesafeassign->cm = $this->cm->id;
        $enablesafeassign->name = 'safeassign_enabled';
        $enablesafeassign->value = 1;
        $DB->insert_record('plagiarism_safeassign_config', $enablesafeassign);

        for ($i = 0; $i < count($this->students); $i++) {
            $record = new \stdClass();
            $record->uuid = null;
            $record->globalcheck = 1;
            $record->groupsubmission = 1;
            $record->submitted = 0;
            $record->submissionid = $this->students[$i]["submissionid"];
            $record->deprecated = 0;
            $record->hasfile = $this->students[$i]["hasfile"];
            $record->hasonlinetext = $this->students[$i]["hasonlinetext"];
            $record->timecreated = time();
            $record->assignmentid = $this->assigninstance->id;
            $DB->insert_record('plagiarism_safeassign_subm', $record);
        }
    }
}
