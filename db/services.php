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

defined('MOODLE_INTERNAL') || die();

// Services
// @author    Jonathan García Gómez
// @copyright Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
// @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.

$functions = [
    'plagiarism_safeassign_test_api_credentials' => [
        'classname' => 'plagiarism_safeassign_test_api_credentials_external',
        'methodname'    => 'plagiarism_safeassign_test_api_credentials',
        'description'   => 'Validates the given credentials against the SafeAssign site',
        'classpath'     => 'plagiarism/safeassign/externallib.php',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => true
    ],
    'plagiarism_safeassign_update_flag' => [
        'classname' => 'plagiarism_safeassign_update_flag_external',
        'methodname'    => 'plagiarism_safeassign_update_flag',
        'description'   => 'Saves the global check flag in the DB',
        'classpath'     => 'plagiarism/safeassign/externallib.php',
        'type'          => 'write',
        'ajax'          => true,
        'loginrequired' => true
    ],
    'plagiarism_safeassign_resubmit_ack' => [
        'classname' => 'plagiarism_safeassign_resubmit_ack_external',
        'methodname'    => 'plagiarism_safeassign_resubmit_ack',
        'description'   => 'Sends an ACK to the system about a re-submission',
        'classpath'     => 'plagiarism/safeassign/externallib.php',
        'type'          => 'write',
        'ajax'          => true,
        'loginrequired' => true
    ]
];