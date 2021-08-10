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
 * SafeAssign event hooks.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$observers = array (
    array(
        'eventname' => '\assignsubmission_file\event\assessable_uploaded',
        'callback' => 'plagiarism_safeassign_observer::assignsubmission_file_uploaded'
    ),
    array(
        'eventname' => '\mod_workshop\event\assessable_uploaded',
        'callback' => 'plagiarism_safeassign_observer::workshop_file_uploaded'
    ),
    array(
        'eventname' => '\mod_forum\event\assessable_uploaded',
        'callback' => 'plagiarism_safeassign_observer::forum_file_uploaded'
    ),
    array(
        'eventname' => '\assignsubmission_onlinetext\event\submission_created',
        'callback' => 'plagiarism_safeassign_observer::assignsubmission_onlinetext_created'
    ),
    array(
        'eventname' => '\assignsubmission_onlinetext\event\submission_updated',
        'callback' => 'plagiarism_safeassign_observer::assignsubmission_onlinetext_updated'
    ),
    array(
        'eventname' => '\core\event\role_assigned',
        'callback' => 'plagiarism_safeassign_observer::role_assigned'
    ),
    array(
        'eventname' => '\core\event\role_unassigned',
        'callback' => 'plagiarism_safeassign_observer::role_unassigned'
    ),
    array(
        'eventname' => '\mod_assign\event\submission_status_updated',
        'callback' => 'plagiarism_safeassign_observer::submission_removed'
    )
);
