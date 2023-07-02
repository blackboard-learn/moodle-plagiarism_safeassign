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
 * Test safeassign task interaction with Moodle.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace plagiarism_safeassign;
defined('MOODLE_INTERNAL') || die();
global $CFG, $DB;

require_once($CFG->dirroot . '/plagiarism/safeassign/lib.php');
require_once($CFG->dirroot . '/mod/assign/tests/base_test.php');
require_once($CFG->dirroot . '/plagiarism/safeassign/classes/observer.php');
require_once(__DIR__.'/base.php');

use plagiarism_safeassign\api\safeassign_api;
use plagiarism_safeassign\api\testhelper;
use plagiarism_safeassign\api\rest_provider;

/**
 * Class plagiarism_safeassign_tasks_testcase
 * @copyright Copyright (c) 2018 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class scores_task_test extends plagiarism_safeassign_base_testcase {

    /**
     * @var stdClass $user
     */
    private $user;

    protected function setUp():void {
        $this->user = $this->getDataGenerator()->create_user([
            'firstname' => 'Oliver',
            'lastname' => 'Atom'
        ]);
        $this->course = $this->getDataGenerator()->create_course();
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $params['course'] = $this->course->id;
        $this->moduleinstance = $generator->create_instance($params);
        $this->cm = get_coursemodule_from_instance('assign', $this->moduleinstance->id);
        $this->context = \context_module::instance($this->cm->id);
        $this->assign = new \testable_assign($this->context, $this->cm, $this->course);
        $this->setUser($this->user->id);
        $this->submission = $this->assign->get_user_submission($this->user->id, true);
    }

    /**
     * Test that the score is correctly inserted in db.
     */
    public function test_submissions_scores() {
        global $DB;

        $this->resetAfterTest();
        $this->config_set_ok();
        set_config('enabled', 1, 'plagiarism_safeassign');

        // Login to SafeAssign.
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $loginurl = '%s' . safeassign_api::APIVER . 'tokens?';
        $loginurl .= 'grant_type=client_credentials&user_id=%s&user_firstname=%s&user_lastname=%s';
        $loginurl = sprintf($loginurl, $baseapiurl, $this->user->id, $this->user->firstname, $this->user->lastname);

        testhelper::push_pair($loginurl, 'user-login-final.json');
        $resultoflogin = safeassign_api::login($this->user->id);

        // Enable SafeAssign in the assignment.
        $record = new \stdClass();
        $record->course = $this->course->id;
        $record->instance = $this->moduleinstance->id;
        $record->coursemodule = $this->cm->id;
        $record->safeassign_enabled = 1;
        $record->name = 'safeassign_enabled';
        $record->value = 1;
        plagiarism_safeassign_coursemodule_edit_post_actions($record);
        $record->name = 'safeassign_global_reference';
        $record->value = 0;
        plagiarism_safeassign_coursemodule_edit_post_actions($record);

        $this->data = new \stdClass();
        $this->data->onlinetext_editor = array(
            'itemid' => file_get_unused_draft_itemid(),
            'text' => 'Submission text',
            'format' => FORMAT_PLAIN
        );

        $plugin = $this->assign->get_submission_plugin_by_type('onlinetext');
        $sink = $this->redirectEvents();
        $plugin->save($this->submission, $this->data);
        $events = $sink->get_events();
        $this->assertCount(2, $events);
        $event = $events[1];

        // Submission is processed by the event observer class.
        \plagiarism_safeassign_observer::assignsubmission_onlinetext_created($event);
        $record = $DB->get_record('plagiarism_safeassign_subm', array());

        // Simulate submission's creation on SafeAssign side.
        $submissionuuid = "c93e61c6-be1f-6c49-5c86-76d8f04f3f2f";
        $record->uuid = $submissionuuid;
        $record->submitted = 1;
        $DB->update_record('plagiarism_safeassign_subm', $record);

        // Simulate file sync.
        $filerecord = new \stdClass();
        $filerecord->cm = $this->cm->id;
        $filerecord->userid = $this->user->id;
        $filerecord->uuid = "k93e61c6-be1f-6c49-5c86-76d8f04f3f2b";
        $filerecord->reporturl = null;
        $filerecord->similarityscore = 0;
        $filerecord->timesubmitted = time();
        $filerecord->supported = 1;
        $filerecord->submissionid = $record->submissionid;
        $fs = get_file_storage();
        $usercontext = \context_user::instance($this->user->id);
        $textfile = $fs->get_file($usercontext->id, 'assignsubmission_text_as_file', 'submission_text_files', $record->submissionid,
            '/', 'userid_' . $this->user->id . '_text_submissionid_' . $record->submissionid . '.html');
        $filerecord->fileid = $textfile->get_id();
        $DB->insert_record('plagiarism_safeassign_files', $filerecord);

        // Get originality report basic data.
        $getreporturl = '%s' . safeassign_api::APIVER . 'submissions/%s/report/metadata';
        $getreporturl = sprintf($getreporturl, $baseapiurl, $submissionuuid);

        testhelper::push_pair($getreporturl, 'get-originality-report-basic-data-ok.json');

        $safeassign = new \plagiarism_plugin_safeassign();
        $safeassign->safeassign_get_scores();

        $record = $DB->get_record('plagiarism_safeassign_subm', array());
        $this->assertEquals(1.00, $record->highscore);
        $this->assertEquals(1.00, $record->avgscore);
        $this->assertEquals(1, $record->reportgenerated);
        $this->assertEquals(rest_provider::instance()->lasthttpcode(), 200);
    }
}
