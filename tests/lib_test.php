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
 * @category  phpunit
 * @copyright Copyright (c) 2017 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $CFG, $DB;

require_once($CFG->dirroot . '/plagiarism/safeassign/lib.php');
require_once($CFG->dirroot . '/plagiarism/safeassign/classes/observer.php');
require_once($CFG->dirroot . '/lib/classes/event/course_module_created.php');

class plagiarism_safeassign_testcase extends advanced_testcase {

    private $user;

    protected function setUp() {
        global $USER;

        $this->setAdminUser();
        $this->user = $USER;
    }

    public function test_assigndbsaver_new_assignment()
    {
        global $DB;

        $this->resetAfterTest(true);

        // Generate course.
        $course1 = $this->getDataGenerator()->create_course();

        // Create an activity.
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_assign');
        $instance = $generator->create_instance(array('course' => $course1->id));
        $cm = get_coursemodule_from_instance('assign', $instance->id);
        $modcontext = context_module::instance($instance->cmid);

        $event = \core\event\course_module_created::create(array(
            'courseid' => $course1->id,
            'context'  => $modcontext,
            'objectid' => $cm->id,
            'other'    => array(
                'modulename' => 'assign',
                'name'       => 'My assignment',
                'instanceid' => $instance->id
            )
        ));

        $sink = $this->redirectEvents();
        $event->trigger();
        $result = $sink->get_events();
        $event = reset($result);
        $sink->close();

        plagiarism_safeassign_observer::course_module_created($event);

        $confirmdb = $DB->get_record('plagiarism_safeassign_assign', array('assignmentid'=>$instance->id));

        $this->assertEquals($instance->id, $confirmdb->assignmentid);
    }
}