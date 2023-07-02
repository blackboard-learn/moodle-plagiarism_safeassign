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
 * Definition of SafeAssign scheduled tasks.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$tasks = array(
    array(
        'classname' => 'plagiarism_safeassign\task\get_scores',
        'blocking' => 0,
        'minute' => '*/5',
        'hour' => '*',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    ),
    array(
        'classname' => 'plagiarism_safeassign\task\sync_assignments',
        'blocking' => 0,
        'minute' => '*/5',
        'hour' => '*',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    ),
    array(
        'classname' => 'plagiarism_safeassign\task\accept_license',
        'blocking' => 0,
        'minute' => 'R',
        'hour' => 'R',
        'day' => '*/5',
        'dayofweek' => '*',
        'month' => '*'
    ),
    array(
        'classname' => 'plagiarism_safeassign\task\send_notifications',
        'blocking' => 0,
        'minute' => 'R',
        'hour' => 'R',
        'day' => '*/3',
        'dayofweek' => '*',
        'month' => '*'
    )
);
