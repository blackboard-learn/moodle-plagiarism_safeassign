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
 * Event observers used in SafeAssign Plagiarism plugin.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/plagiarism/safeassign/lib.php');

/**
 * Class plagiarism_safeassign_observer
 * @copyright Copyright (c) 2017 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plagiarism_safeassign_observer {

    /**
     * Upload a forum file
     * @param  \mod_forum\event\assessable_uploaded $event Event
     * @return void
     */
    public static function forum_file_uploaded(
        \mod_forum\event\assessable_uploaded $event) {

    }

    /**
     * Upload a workshop file
     * @param  \mod_workshop\event\assessable_uploaded $event Event
     * @return void
     */
    public static function workshop_file_uploaded(
        \mod_workshop\event\assessable_uploaded $event) {

    }

    /**
     * Uploads an online submission text.
     * @param  \assignsubmission_onlinetext\event\submission_created $event Event
     * @return void
     */
    public static function assignsubmission_onlinetext_created(
        \assignsubmission_onlinetext\event\submission_created $event) {
        $eventdata = $event->get_data();
        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->make_file_from_text_submission($eventdata);
        $safeassign->create_submission($eventdata);

    }

    /**
     * Uploads a submission file.
     * @param  \assignsubmission_file\event\assessable_uploaded $event Event
     * @return void
     */
    public static function assignsubmission_file_uploaded(
        \assignsubmission_file\event\assessable_uploaded $event) {
        $eventdata = $event->get_data();
        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->create_submission($eventdata);
    }

    /**
     * Detects a change in the submission text and call a function to update the corresponding file.
     * @param \assignsubmission_onlinetext\event\submission_updated $event Event
     */
    public static function assignsubmission_onlinetext_updated(
        \assignsubmission_onlinetext\event\submission_updated $event ) {
        $eventdata = $event->get_data();
        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->make_file_from_text_submission($eventdata);
        $safeassign->create_submission($eventdata);
    }

    /**
     * Creates an instructor record if the given enrolment correspond to an editing teacher.
     * @param \core\event\user_enrolment_created
     */
    public static function role_assigned(\core\event\role_assigned $event) {
        $eventdata = $event->get_data();
        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->process_role_assignments($eventdata, 'create');
    }

    /**
     * Creates an instructor record if the given enrolment correspond to an editing teacher.
     * @param \core\event\user_enrolment_created
     */
    public static function role_unassigned(\core\event\role_unassigned $event) {
        $eventdata = $event->get_data();
        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->process_role_assignments($eventdata, 'delete');
    }

    /**
     * Remove submissions when they were removed from grader view.
     * @param \mod_assign\event\submission_status_updated $event
     */
    public static function submission_removed(\mod_assign\event\submission_status_updated $event) {
        $eventdata = $event->get_data();
        if ($eventdata['other']['newstatus'] == ASSIGN_SUBMISSION_STATUS_SUBMITTED) {
            $safeassign = new plagiarism_plugin_safeassign();
            $safeassign->remove_submission($eventdata);
        }
    }

}
