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
 * Defines SafeAssign as a new message provider.
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Open LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
$messageproviders = array (

    // Notify teacher that a submission has been graded in SafeAssign.
    'safeassign_graded' => array (
        'capability' => 'plagiarism/safeassign:get_messages'
    ),

    // Notify Admins that a new SafeAssign License is available.
    'safeassign_notification' => array (
        'capability' => 'plagiarism/safeassign:get_notifications',
        'defaults' => array(
            'popup' => MESSAGE_FORCED
        )
    )
);
