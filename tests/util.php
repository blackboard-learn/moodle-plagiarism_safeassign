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
 * Test utilities for plagiarism safeassign.
 *
 * @package plagiarism_safeassign
 * @author    Guy Thomas
 * @copyright Copyright (c) 2023 Anthology Inc. and its affiliates
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign;

/**
 * Return true if the current environment is an OpenLMS environment.
 * @return bool
 */
function env_is_openlms() {
    global $CFG;
    return file_exists($CFG->dirroot.'/local/mrooms');
}