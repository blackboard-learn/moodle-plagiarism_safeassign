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
 * Test safeassign lib functions interaction with Moodle.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $CFG, $DB;

require_once($CFG->dirroot . '/plagiarism/safeassign/lib.php');
require_once($CFG->dirroot . '/plagiarism/safeassign/classes/observer.php');
require_once($CFG->dirroot . '/lib/classes/event/course_module_created.php');
require_once($CFG->dirroot . '/mod/assign/tests/base_test.php');

/**
 * Class plagiarism_safeassign_testcase
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plagiarism_safeassign_testcase extends advanced_testcase {

    /**
     * @var $user
     */
    private $user;

    protected function setUp() {
        global $USER;

        $this->setAdminUser();
        $this->user = $USER;
        // Enable SafeAssign in the platform.
        set_config('safeassign_use', 1, 'plagiarism');
    }

    public function test_assigndbsaver_assignments() {
        global $DB, $CFG;

        $this->resetAfterTest(true);

        // Generate course.
        $course1 = $this->getDataGenerator()->create_course();

        // Create an activity.
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $this->teacher = self::getDataGenerator()->create_user([
            'firstname' => 'Teacher',
            'lastname' => 'WhoTeaches']);
        $editingteacherrole = $DB->get_record('role', array('shortname' => 'editingteacher'));
        $this->getDataGenerator()->enrol_user($this->teacher->id, $course1->id, $editingteacherrole->id);
        $this->teacher2 = self::getDataGenerator()->create_user([
            'firstname' => 'Not editing Teacher', 'lastname' => 'WhoTeaches']);
        $noteditingteacherrole = $DB->get_record('role', array('shortname' => 'teacher'));
        $this->getDataGenerator()->enrol_user($this->teacher2->id, $course1->id, $noteditingteacherrole->id);

        $instance = $generator->create_instance(array('course' => $course1->id));
        $cm = get_coursemodule_from_instance('assign', $instance->id);

        // Create an activity with SafeAssign enabled.
        $data = new stdClass();
        $data->coursemodule = $cm->id;
        $data->safeassign_enabled = 1;
        $data->course = $course1->id;
        $data->instance = $instance->id;
        $data->module = $cm->module;

        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->save_form_elements($data);
        $confirmdbassign = $DB->get_record('plagiarism_safeassign_mod',
            ['moduleid' => $cm->module, 'instanceid' => $instance->id]);
        $confirmdbcourse = $DB->get_record('plagiarism_safeassign_course', ['courseid' => $course1->id]);

        $this->assertEquals($instance->id, $confirmdbassign->instanceid);
        $this->assertEquals($cm->module, $confirmdbassign->moduleid);
        $this->assertEquals($course1->id, $confirmdbcourse->courseid);

        // Test that the editing instructor has been added to the instructors table and also the admin.
        $instructors1 = $DB->get_records('plagiarism_safeassign_instr', array('courseid' => $course1->id));
        $this->assertCount(2, $instructors1);
        $this->assertTrue($DB->record_exists('plagiarism_safeassign_instr', array('courseid' => $course1->id,
            'instructorid' => $this->teacher->id, 'synced' => 0)));

        // Now let's add a second assign on the same course without SafeAssign enabled
        // and see that course records are not being duplicated.
        $instance2 = $generator->create_instance(array('course' => $course1->id));
        $cm2 = get_coursemodule_from_instance('assign', $instance2->id);

        $data = new stdClass();
        $data->coursemodule = $cm2->id;
        $data->safeassign_enabled = 0;
        $data->course = $course1->id;
        $data->instance = $instance2->id;
        $data->module = $cm2->module;

        $safeassign->save_form_elements($data);
        $confirmdbassign2 = $DB->get_record('plagiarism_safeassign_mod',
            ['moduleid' => $cm2->module, 'instanceid' => $instance2->id]);
        $confirmdbcourse2 = $DB->count_records('plagiarism_safeassign_course', ['courseid' => $course1->id]);

        $this->assertEmpty($confirmdbassign2);
        $this->assertEquals(1, $confirmdbcourse2);

        // Now let's add a third assign on a different course and check that course records are being saved.

        $course2 = $this->getDataGenerator()->create_course();
        $this->teacher3 = self::getDataGenerator()->create_user([
            'firstname' => 'Teacher3',
            'lastname' => 'WhoTeaches'
        ]);
        $this->getDataGenerator()->enrol_user($this->teacher3->id,
            $course2->id,
            $editingteacherrole->id);
        $instance3 = $generator->create_instance(array('course' => $course2->id));
        $cm3 = get_coursemodule_from_instance('assign', $instance3->id);

        $data = new stdClass();
        $data->coursemodule = $cm3->id;
        $data->safeassign_enabled = 1;
        $data->course = $course2->id;
        $data->instance = $instance3->id;
        $data->module = $cm3->module;

        $safeassign->save_form_elements($data);
        $confirmdbassign3 = $DB->get_record('plagiarism_safeassign_mod',
            ['moduleid' => $cm3->module, 'instanceid' => $instance3->id]);
        $confirmdbcourse3 = $DB->get_record('plagiarism_safeassign_course', ['courseid' => $course2->id]);

        $this->assertEquals($instance3->id, $confirmdbassign3->instanceid);
        $this->assertEquals($cm3->module,   $confirmdbassign3->moduleid);
        $this->assertEquals($course2->id, $confirmdbcourse3->courseid);
        $this->assertEquals(2, $DB->count_records('plagiarism_safeassign_mod'));
        $this->assertEquals(2, $DB->count_records('plagiarism_safeassign_course'));

        // Now there must be 2 records for editing teachers.
        $this->assertCount(4, $DB->get_records('plagiarism_safeassign_instr'));
        $this->assertTrue($DB->record_exists('plagiarism_safeassign_instr', array('courseid' => $course2->id,
            'instructorid' => $this->teacher3->id, 'synced' => 0)));

        set_config('safeassign_use', 0, 'plagiarism');
        $course3 = $this->getDataGenerator()->create_course();

        // Create an activity.
        $instance = $generator->create_instance(array('course' => $course3->id));
        $this->teacher4 = self::getDataGenerator()->create_user([
            'firstname' => 'Teacher4',
            'lastname' => 'WhoTeaches'
        ]);
        $this->getDataGenerator()->enrol_user($this->teacher4->id,
            $course3->id,
            $editingteacherrole->id);

        $cm = get_coursemodule_from_instance('assign', $instance->id);

        // Create an activity with SafeAssign enabled.
        $data = new stdClass();
        $data->coursemodule = $cm->id;
        $data->safeassign_enabled = 0;
        $data->course = $course3->id;
        $data->instance = $instance->id;
        $data->module = $cm->module;
        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->save_form_elements($data);

        // Test that the editing instructor has not been added to the instructors table.
        $instructors2 = $DB->get_records('plagiarism_safeassign_instr', array('courseid' => $course3->id));
        $this->assertCount(0, $instructors2);
        $this->assertFalse($DB->record_exists('plagiarism_safeassign_instr', array('courseid' => $course3->id,
            'instructorid' => $this->teacher4->id, 'synced' => 0)));

    }

    /**
     * Test the function that handles that a user has become a site admin.
     */
    public function test_new_admin_added() {
        global $DB, $CFG;
        $this->resetAfterTest(true);
        // Generate course.
        $course1 = $this->getDataGenerator()->create_course();
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $this->teacher = self::getDataGenerator()->create_user([
            'firstname' => 'Teacher',
            'lastname' => 'WhoTeaches']);
        $editingteacherrole = $DB->get_record('role', array('shortname' => 'editingteacher'));
        $this->getDataGenerator()->enrol_user($this->teacher->id, $course1->id, $editingteacherrole->id);
        $instance = $generator->create_instance(array('course' => $course1->id));
        $cm = get_coursemodule_from_instance('assign', $instance->id);

        // Create an activity with SafeAssign enabled.
        $data = new stdClass();
        $data->coursemodule = $cm->id;
        $data->safeassign_enabled = 1;
        $data->course = $course1->id;
        $data->instance = $instance->id;
        $data->module = $cm->module;
        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->save_form_elements($data);
        $this->assertCount(2, $DB->get_records('plagiarism_safeassign_instr'));
        $this->admin2 = self::getDataGenerator()->create_user();

        $admins = array();
        foreach (explode(',', $CFG->siteadmins) as $admin) {
            $admin = (int)$admin;
            if ($admin) {
                $admins[$admin] = $admin;
            }
        }
        $admins[] = $this->admin2->id;

        set_config('siteadmins', implode(',', $admins));
        $CFG->siteadmins = implode(',', $admins);
        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->set_siteadmins();
        $this->assertCount(3, $DB->get_records('plagiarism_safeassign_instr'));

    }

    /**
     * Test additional roles configuration.
     */
    public function test_additional_roles() {
        global $DB;
        $this->resetAfterTest(true);
        $this->getDataGenerator()->create_role(['name' => 'Dean', 'shortname' => 'dean', 'archetype' => 'manager']);
        $manager = $DB->get_record('role', array('shortname' => 'manager', 'archetype' => 'manager'));
        $dean = $DB->get_record('role', array('shortname' => 'dean', 'archetype' => 'manager'));

        $course1 = $this->getDataGenerator()->create_course();
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $this->teacher = $this->getDataGenerator()->create_user([
            'firstname' => 'Teacher',
            'lastname' => 'WhoTeaches']);
        $editingteacherrole = $DB->get_record('role', array('shortname' => 'editingteacher'));
        $this->getDataGenerator()->enrol_user($this->teacher->id, $course1->id, $editingteacherrole->id);
        $instance = $generator->create_instance(array('course' => $course1->id));
        $cm = get_coursemodule_from_instance('assign', $instance->id);

        // Create an activity with SafeAssign enabled.
        $data = new stdClass();
        $data->coursemodule = $cm->id;
        $data->safeassign_enabled = 1;
        $data->course = $course1->id;
        $data->instance = $instance->id;
        $data->module = $cm->module;
        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->save_form_elements($data);
        // Teacher and admin should be there.
        $this->assertCount(2, $DB->get_records('plagiarism_safeassign_instr'));
        // Now add the roles that are going to be synced.
        $roles = array();
        $roles[] = $manager->id;
        $roles[] = $dean->id;
        $roles = implode(',', $roles);
        set_config('safeassign_additional_roles', $roles, 'plagiarism_safeassign');

        // Emulate SA task.
        $additionalroles = get_config('plagiarism_safeassign', 'safeassign_additional_roles');
        $syncedroles = get_config('plagiarism_safeassign', 'safeassign_synced_roles');
        $safeassign->set_additional_role_users($additionalroles, $syncedroles);

        $systemcontext = context_system::instance();
        // Still have 2 users since enrolments at site level have not been created.
        $this->assertCount(2, $DB->get_records('plagiarism_safeassign_instr'));
        $deanuser = $this->getDataGenerator()->create_user();
        role_assign($dean->id, $deanuser->id, $systemcontext->id);
        $manageruser = $this->getDataGenerator()->create_user();
        role_assign($manager->id, $manageruser->id, $systemcontext->id);

        // We should have 4 records.
        $this->assertCount(4, $DB->get_records('plagiarism_safeassign_instr'));
        $this->assertTrue($DB->record_exists('plagiarism_safeassign_instr',
            array('instructorid' => $deanuser->id, 'courseid' => $course1->id)));
        $this->assertTrue($DB->record_exists('plagiarism_safeassign_instr',
            array('instructorid' => $manageruser->id, 'courseid' => $course1->id)));

        // Test role assignment for synced roles.
        $deanuser2 = $this->getDataGenerator()->create_user();
        // Multirole test.
        role_assign($dean->id, $deanuser2->id, $systemcontext->id);
        // New record for the tracked role user.
        $this->assertCount(5, $DB->get_records('plagiarism_safeassign_instr'));
        $this->assertTrue($DB->record_exists('plagiarism_safeassign_instr',
            array('instructorid' => $deanuser2->id, 'courseid' => $course1->id)));
        // Enrol the same user in the existing course.
        $this->getDataGenerator()->enrol_user($deanuser2->id, $course1->id, $editingteacherrole->id);
        // No new record should be added.
        $this->assertCount(5, $DB->get_records('plagiarism_safeassign_instr'));
        // The second dean user lost his role at system level.
        role_unassign($dean->id, $deanuser2->id, $systemcontext->id);
        // Record should exist since the user still has an active enrolment as editing teacher at course level.
        $this->assertCount(5, $DB->get_records('plagiarism_safeassign_instr'));
        $this->assertTrue($DB->record_exists('plagiarism_safeassign_instr',
            array('instructorid' => $deanuser2->id, 'courseid' => $course1->id)));
        $course1context = context_course::instance($course1->id);
        role_unassign($editingteacherrole->id, $deanuser2->id, $course1context->id);
        $this->assertCount(4, $DB->get_records('plagiarism_safeassign_instr'));
        $this->assertFalse($DB->record_exists('plagiarism_safeassign_instr',
            array('instructorid' => $deanuser2->id, 'courseid' => $course1->id)));
        // Dean role elminated, users with that role should be deleted.
        set_config('safeassign_additional_roles', $manager->id, 'plagiarism_safeassign');
        $additionalroles = get_config('plagiarism_safeassign', 'safeassign_additional_roles');
        $syncedroles = get_config('plagiarism_safeassign', 'safeassign_synced_roles');
        $safeassign->set_additional_role_users($additionalroles, $syncedroles);
        $this->assertCount(3, $DB->get_records('plagiarism_safeassign_instr'));
        $this->assertFalse($DB->record_exists('plagiarism_safeassign_instr',
            array('instructorid' => $deanuser->id, 'courseid' => $course1->id)));

        // Test role deletion.
        delete_role($manager->id);
        // Users with that role should be deleted from the instructors table.
        $this->assertFalse($DB->record_exists('plagiarism_safeassign_instr', array('instructorid' => $manageruser->id)));

        // Emulate sync task.
        set_config('safeassign_additional_roles', $dean->id, 'plagiarism_safeassign');
        $additionalroles = get_config('plagiarism_safeassign', 'safeassign_additional_roles');
        $syncedroles = get_config('plagiarism_safeassign', 'safeassign_synced_roles');
        $safeassign->set_additional_role_users($additionalroles, $syncedroles);
        $DB->set_field('plagiarism_safeassign_instr', 'synced', 1, array('synced' => 0));
        delete_role($dean->id);
        // The remaining dean user should be marked as unenrolled.
        $this->assertTrue($DB->record_exists('plagiarism_safeassign_instr',
            array('instructorid' => $deanuser->id, 'unenrolled' => 1)));

        $course2 = $this->getDataGenerator()->create_course();
        $this->getDataGenerator()->enrol_user($this->teacher->id, $course2->id, $editingteacherrole->id);
        $instance2 = $generator->create_instance(array('course' => $course2->id));
        $cm2 = get_coursemodule_from_instance('assign', $instance2->id);
        $this->getDataGenerator()->create_role(['name' => 'New Dean', 'shortname' => 'newdean',
            'archetype' => 'editingteacher']);

        $newdean = $DB->get_record('role', array('shortname' => 'newdean', 'archetype' => 'editingteacher'));

        $data = new stdClass();
        $data->coursemodule = $cm2->id;
        $data->safeassign_enabled = 1;
        $data->course = $course2->id;
        $data->instance = $instance2->id;
        $data->module = $cm2->module;
        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->save_form_elements($data);
        $this->assertCount(5, $DB->get_records('plagiarism_safeassign_instr'));
        set_config('safeassign_additional_roles', $newdean->id, 'plagiarism_safeassign');

        // Emulate sync task.
        $DB->set_field('plagiarism_safeassign_instr', 'synced', 1, array('synced' => 0));
        delete_course($course2->id, false);
        role_assign($newdean->id, $manageruser->id, $systemcontext->id);

        // Only 1 record should exist, since we only have 1 existing course.
        $this->assertCount(1, $DB->get_records('plagiarism_safeassign_instr', array('instructorid' => $manageruser->id)));
    }

    /**
     * Test for deletion of a course in moodle.
     */
    public function test_delete_course() {
        global $DB;
        $this->resetAfterTest(true);

        $course = $this->getDataGenerator()->create_course();
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $this->teacher = $this->getDataGenerator()->create_user([
            'firstname' => 'Teacher',
            'lastname' => 'WhoTeaches']);
        $editingteacherrole = $DB->get_record('role', ['shortname' => 'editingteacher']);
        $this->getDataGenerator()->enrol_user($this->teacher->id, $course->id, $editingteacherrole->id);
        $instance = $generator->create_instance(['course' => $course->id]);
        $cm = get_coursemodule_from_instance('assign', $instance->id);

        // Create an activity with SafeAssign enabled for course 1.
        $data = new stdClass();
        $data->coursemodule = $cm->id;
        $data->safeassign_enabled = 1;
        $data->course = $course->id;
        $data->instance = $instance->id;
        $data->module = $cm->module;
        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->save_form_elements($data);

        // Send a submission to this activity.
        $testsubmissionid = 1111111;
        $testuserid = 111;

        $linkarray = $this->create_submitted_file_object($testuserid, $cm->id, $testsubmissionid);
        $file = $linkarray['file'];
        $this->insert_submission_for_testing(1, 1, $testsubmissionid, $cm->id, $testuserid);
        $this->insert_files_for_testing('http://fakeurl1.com', 0.99, 1502484564, $testsubmissionid, $file->get_id());

        // Create a second course with respective submission.
        $course2 = $this->getDataGenerator()->create_course();
        $this->getDataGenerator()->enrol_user($this->teacher->id, $course2->id, $editingteacherrole->id);
        $instance2 = $generator->create_instance(['course' => $course2->id]);
        $cm2 = get_coursemodule_from_instance('assign', $instance2->id);

        // Create an activity with SafeAssign enabled for course 1.
        $data = new stdClass();
        $data->coursemodule = $cm2->id;
        $data->safeassign_enabled = 1;
        $data->course = $course2->id;
        $data->instance = $instance2->id;
        $data->module = $cm2->module;
        $safeassign = new plagiarism_plugin_safeassign();

        $safeassign->save_form_elements($data);

        // Send a submission to this activity.
        $testsubmissionid2 = 2222222;
        $testuserid2 = 222;

        $linkarray = $this->create_submitted_file_object($testuserid2, $cm2->id, $testsubmissionid2);
        $file2 = $linkarray['file'];
        $this->insert_submission_for_testing(1, 1, $testsubmissionid2, $cm2->id, $testuserid2);
        $this->insert_files_for_testing('http://fakeurl1.com', 0.99, 1502484564, $testsubmissionid2, $file2->get_id());

        $this->assertEquals(2, $DB->count_records("plagiarism_safeassign_course"));
        $this->assertEquals(2, $DB->count_records("plagiarism_safeassign_mod"));
        $this->assertEquals(2, $DB->count_records("plagiarism_safeassign_subm"));
        $this->assertEquals(2, $DB->count_records("plagiarism_safeassign_files"));

        // Emulate sync task.
        $DB->set_field('plagiarism_safeassign_instr', 'synced', 1, array('synced' => 0));
        delete_course($course->id, false);

        $this->assertEquals(1, $DB->count_records("plagiarism_safeassign_course"));
        $this->assertEquals(1, $DB->count_records("plagiarism_safeassign_mod"));
        $this->assertEquals(1, $DB->count_records("plagiarism_safeassign_subm",
                ["status" => \plagiarism_safeassign\api\safeassign_submission::STATUS_SUBMISSION_DELETED]));
        $this->assertEquals(1, $DB->count_records("plagiarism_safeassign_files"));

        // Check that records remaining are indeed the ones not being deleted.
        $this->assertEquals(1, $DB->count_records("plagiarism_safeassign_course", ['courseid' => $course2->id]));
        $this->assertEquals(1, $DB->count_records("plagiarism_safeassign_mod", [
            'moduleid' => $cm2->module,
            'instanceid' => $instance2->id,
            'courseid' => $course2->id]));
        $this->assertEquals(1, $DB->count_records("plagiarism_safeassign_subm", [
            'submissionid' => $testsubmissionid2,
            'cmid' => $cm2->id,
            'userid' => $testuserid2
        ]));
        $this->assertEquals(1, $DB->count_records("plagiarism_safeassign_files", [
            'submissionid' => $testsubmissionid2,
            'fileid' => $file2->get_id()
        ]));
    }

    /**
     * Test for deletion of a course module in moodle.
     */
    public function test_delete_course_module() {
        global $DB;
        $this->resetAfterTest(true);

        $course = $this->getDataGenerator()->create_course();
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $this->teacher = $this->getDataGenerator()->create_user([
            'firstname' => 'Teacher',
            'lastname' => 'WhoTeaches']);
        $editingteacherrole = $DB->get_record('role', ['shortname' => 'editingteacher']);
        $this->getDataGenerator()->enrol_user($this->teacher->id, $course->id, $editingteacherrole->id);
        $instance = $generator->create_instance(['course' => $course->id]);
        $cm = get_coursemodule_from_instance('assign', $instance->id);

        // Create an activity with SafeAssign enabled.
        $data = new stdClass();
        $data->coursemodule = $cm->id;
        $data->safeassign_enabled = 1;
        $data->course = $course->id;
        $data->instance = $instance->id;
        $data->module = $cm->module;
        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->save_form_elements($data);

        // Send a submission to this activity.
        $testsubmissionid = 1111111;
        $testuserid = 111;

        $linkarray = $this->create_submitted_file_object($testuserid, $cm->id, $testsubmissionid);
        $file = $linkarray['file'];
        $this->insert_submission_for_testing(1, 1, $testsubmissionid, $cm->id, $testuserid);
        $this->insert_files_for_testing('http://fakeurl1.com', 0.99, 1502484564, $testsubmissionid, $file->get_id());

        $this->assertEquals(1, $DB->count_records("plagiarism_safeassign_course"));
        $this->assertEquals(1, $DB->count_records("plagiarism_safeassign_mod"));
        $this->assertEquals(1, $DB->count_records("plagiarism_safeassign_subm"));
        $this->assertEquals(1, $DB->count_records("plagiarism_safeassign_files"));

        // Emulate sync task.
        $DB->set_field('plagiarism_safeassign_instr', 'synced', 1, array('synced' => 0));
        course_delete_module($cm->id);

        $this->assertEquals(1, $DB->count_records("plagiarism_safeassign_course"));
        $this->assertEquals(0, $DB->count_records("plagiarism_safeassign_mod"));
        $this->assertEquals(1, $DB->count_records("plagiarism_safeassign_subm",
                ["status" => \plagiarism_safeassign\api\safeassign_submission::STATUS_SUBMISSION_DELETED]));
        $this->assertEquals(0, $DB->count_records("plagiarism_safeassign_files"));
    }

    /**
     * Builds a submitted file object.
     * @param int $userid ID of the user.
     * @param int $cmid course module ID.
     * @param int $submissionid ID of the submission.
     * @return array returns an array with file object, userid and cmid.
     */
    public function create_submitted_file_object($userid, $cmid, $submissionid) {
        $this->user = $this->getDataGenerator()->create_user();
        $this->course = $this->getDataGenerator()->create_course();
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $params['course'] = $this->course->id;
        $instance = $generator->create_instance($params);
        $this->cm = get_coursemodule_from_instance('assign', $instance->id);
        $this->context = context_module::instance($this->cm->id);
        $this->setUser($this->user->id);
        $fs = get_file_storage();
        $dummy = (object) array(
            'contextid' => $this->context->id,
            'component' => 'assignsubmission_file',
            'filearea' => ASSIGNSUBMISSION_FILE_FILEAREA,
            'itemid' => $submissionid,
            'filepath' => '/',
            'filename' => 'myassignmnent.pdf'
        );
        $this->fi = $fs->create_file_from_string($dummy, 'Content of ' . $dummy->filename);
        $dummy = (object) array(
            'contextid' => $this->context->id,
            'component' => 'assignsubmission_file',
            'filearea' => ASSIGNSUBMISSION_FILE_FILEAREA,
            'itemid' => $submissionid,
            'filepath' => '/',
            'filename' => 'myassignmnent.png'
        );
        $this->fi2 = $fs->create_file_from_string($dummy, 'Content of ' . $dummy->filename);
        $this->files = $fs->get_area_files($this->context->id, 'assignsubmission_file', ASSIGNSUBMISSION_FILE_FILEAREA,
            $submissionid, 'id', false);
        return array('userid' => $userid, 'cmid' => $cmid, 'file' => $this->fi2);
    }

    /**
     * Inserts files in the "mdl_plagiarism_safeassign_files" database table for testing.
     * @param string $reporturl URL of the report provided by SafeAssign.
     * @param number $score Similarity score with two decimal numbers (i.e. 0.75) provided by SafeAssign.
     * @param int $time Timestamp of the file upload.
     * @param int $subid ID of the submission.
     * @param int $fileid ID of the submitted file.
     */
    public function insert_files_for_testing($reporturl, $score, $time, $subid, $fileid) {
        global $DB;
        $file = new stdClass();
        $file->uuid = uniqid();
        $file->reporturl = $reporturl;
        $file->similarityscore = $score;
        $file->timesubmitted = $time;
        $file->supported = 1;
        $file->submissionid = $subid;
        $file->fileid = $fileid;
        $DB->insert_record('plagiarism_safeassign_files', $file, true);
    }

    /**
     * Inserts a submission in the "mdl_plagiarism_safeassign_subm" database table for testing.
     * @param int $submitted Flag that indicates if the file was submitted to SafeAssign.
     * @param int $report Flag that indicates if the report file was generated by SafeAssign.
     * @param int $subid  ID of the submission.
     * @param int $cmid Context module id
     * @param int $userid User id
     * @return stdClass $submission submission object.
     */
    public function insert_submission_for_testing($submitted, $report, $subid, $cmid, $userid) {
        global $DB;
        $submission = new stdClass();
        $submission->uuid = uniqid();
        $submission->globalcheck = '1';
        $submission->groupsubmission = '0';
        $submission->highscore = 1.00;
        $submission->avgscore = 0.50;
        $submission->submitted = $submitted;
        $submission->reportgenerated = $report;
        $submission->submissionid = $subid;
        $submission->deleted = 0;
        $submission->status = 'submitted';
        $submission->type = 1;
        $submission->cmid = $cmid;
        $submission->userid = $userid;

        $DB->insert_record('plagiarism_safeassign_subm', $submission, true);
        return $submission;
    }

    /**
     * Case 0: Ideal case, file was submitted and analyzed.
     * Tests the get_file_results() function.
     */
    public function test_get_file_results() {
        $this->resetAfterTest(true);
        $linkarray = $this->create_submitted_file_object(111, 000, 1111111);
        $file = $linkarray['file'];
        $this->insert_submission_for_testing(1, 1, 1111111, 0, 0);
        $this->insert_files_for_testing('http://fakeurl1.com', 0.99, 1502484564, 1111111, $file->get_id());
        $lib = new plagiarism_plugin_safeassign();
        $results = $lib->get_file_results(000, 111, $file->get_id());
        $this->assertequals(1, $results['analyzed']);
        $this->assertequals(0.99, $results['score']);
        $this->assertequals('http://fakeurl1.com', $results['reporturl']);
    }

    /**
     * Case 1: File submitted but not analyzed yet.
     * Tests the get_file_results() function.
     */
    public function test_get_results_submitted_not_analyzed() {
        $this->resetAfterTest(true);
        $linkarray = $this->create_submitted_file_object(222, 000, 2222222);
        $file = $linkarray['file'];
        $this->insert_submission_for_testing(1, 0, 2222222, 0, 0);
        $this->insert_files_for_testing('', null, 1502484564, 2222222, $file->get_id());
        $lib = new plagiarism_plugin_safeassign();
        $results = $lib->get_file_results(000, 222, $file->get_id());
        $this->assertequals(0, $results['analyzed']);
        $this->assertequals('', $results['score']);
        $this->assertequals('', $results['reporturl']);
    }

    /**
     * Case 2: Testing with an unexisting submission.
     * Tests the get_file_results() function.
     */
    public function test_get_file_results_no_submission() {
        $this->resetAfterTest(true);
        $linkarray = $this->create_submitted_file_object(333, 000, 3333333);
        $file = $linkarray['file'];
        $lib = new plagiarism_plugin_safeassign();
        $results = $lib->get_file_results(000, 333, $file->get_id());
        $this->assertequals(0, $results['analyzed']);
        $this->assertequals('', $results['score']);
        $this->assertequals('', $results['reporturl']);
    }

    /**
     * Tests if resubmit ack marks submission as having no report generated.
     */
    public function test_resubmit_ack() {
        global $DB;
        $this->resetAfterTest(true);

        $testobject = $this->insert_submission_for_testing(1, 1, 11111, 0, 0);
        $uuid = $testobject->uuid;

        $lib = new plagiarism_plugin_safeassign();
        $lib->resubmit_acknowlegment($uuid);

        $result = $DB->get_record("plagiarism_safeassign_subm", ["submissionid" => 11111]);
        $this->assertEquals($result->reportgenerated, 0);
    }

    /**
     * Tests if the notifications are sent to the Admins.
     */
    public function test_new_safeassign_license_notification() {
        global $DB;
        $this->resetAfterTest(true);

        $lib = new plagiarism_plugin_safeassign();
        $lib->new_safeassign_license_notification();

        $adminids = get_config(null, 'siteadmins');
        $adminids = explode(',', $adminids);

        foreach ($adminids as $id) {
            $mail = $DB->get_record('notifications', array('useridto' => $id));
            $this->assertEquals($mail->subject, 'New SafeAssign License Terms & Conditions available');
            $this->assertEquals($mail->component, 'plagiarism_safeassign');
            $this->assertEquals($mail->eventtype, 'safeassign_notification');
        }
    }

    /**
     * Tests if a variable contains a specific string.
     */
    public function test_institutional_release_statement_value() {
        global $DB;
        $this->resetAfterTest(true);

        set_config('safeassign_new_student_disclosure', 'disclosure example for testing.', 'plagiarism_safeassign');
        set_config('safeassign_use', 1, 'plagiarism');
        set_config('safeassign_referencedbactivity', 1, 'plagiarism_safeassign');
        $course1 = $this->getDataGenerator()->create_course();
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $teacher = self::getDataGenerator()->create_user([
            'firstname' => 'Teacher',
            'lastname' => 'WhoTeaches']);
        $editingteacherrole = $DB->get_record('role', array('shortname' => 'editingteacher'));
        $this->getDataGenerator()->enrol_user($teacher->id, $course1->id, $editingteacherrole->id);
        $instance = $generator->create_instance(array('course' => $course1->id));
        $cm = get_coursemodule_from_instance('assign', $instance->id);
        // Enable SafeAssign for this particular activity.
        $this->setAdminUser();
        $assignconf = new stdClass();
        $assignconf->cm = $cm->id;
        $assignconf->name = 'safeassign_enabled';
        $assignconf->value = 1;
        $DB->insert_record('plagiarism_safeassign_config', $assignconf);
        $assignconf->name = 'safeassign_global_reference';
        $assignconf->value = 0;
        $DB->insert_record('plagiarism_safeassign_config', $assignconf);

        $lib = new plagiarism_plugin_safeassign();
        $result = $lib->print_disclosure($cm->id);
        $findneedle = strpos($result, 'disclosure example for testing.');
        $this->assertNotFalse($findneedle);

        // Save the config value for institutional release statement as empty. Localized string should appear.
        set_config('safeassign_new_student_disclosure', '', 'plagiarism_safeassign');
        $result2 = $lib->print_disclosure($cm->id);
        $findneedle = strpos($result2, 'disclosure example for testing.');
        $this->assertFalse($findneedle);
        $findneedle2 = strpos($result2, get_string('studentdisclosuredefault', 'plagiarism_safeassign'));
        $this->assertNotFalse($findneedle2);
    }

    public function test_global_reference_db_logic() {
        $this->resetAfterTest(true);
        $generator = $this->getDataGenerator();
        $student = $generator->create_user();

        $bits = 3;
        $max = (1 << $bits);
        $lib = new plagiarism_plugin_safeassign();

        for ($i = 0; $i < $max; $i++) {
            $numbercombinations = str_pad(decbin($i), $bits, '0', STR_PAD_LEFT);
            $flagcombinations = str_split($numbercombinations, 1);

            set_config('safeassign_referencedbactivity', $flagcombinations[0], 'plagiarism_safeassign'); // Admin flag.
            $assignconfig = [ // We need to simulate the array returned from safeassign_config table.
                'safeassign_enabled' => 1,
                'safeassign_originality_report' => 0,
                'safeassign_global_reference' => $flagcombinations[1], // Teacher flag.
            ];
            $assignconfig[$student->id] = $flagcombinations[2]; // Submitter flag.

            if ($numbercombinations === '101') { // This is the only combination that allows globalcheck to be true.
                $result = $lib->should_send_to_global_check($assignconfig, $student->id);
                $this->assertTrue($result);
            } else {
                $result = $lib->should_send_to_global_check($assignconfig, $student->id);
                $this->assertFalse($result);
            }
        }
    }
}