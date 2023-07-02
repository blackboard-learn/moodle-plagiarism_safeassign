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
 * Install script for plagiarism_safeassign.
 *
 * @package    plagiarism_safeassign
 * @copyright  2023 Anthology Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Perform the post-install procedures.
 */
function xmldb_plagiarism_safeassign_install() {
    // Set license version and agreement status.
    set_config('safeassign_latest_license_vers', '0.2', 'plagiarism_safeassign');
    set_config('safeassign_license_agreement_status', 0, 'plagiarism_safeassign');
}
