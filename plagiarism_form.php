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
 * Define the settings configuration form for SafeAssign plagiarism plugin.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/lib/formslib.php');

class plagiarism_setup_form extends moodleform {

    /**
     * Define the form.
     */
    function definition () {
        global $CFG;
        $mform =& $this->_form;

        $mform->addElement('html', get_string('safeassignexplain', 'plagiarism_safeassign'));
        $mform->addElement('checkbox', 'new_use', get_string('usesafeassign', 'plagiarism_safeassign'));

        $mform->addElement('textarea', 'new_student_disclosure', get_string('studentdisclosure','plagiarism_safeassign'),'wrap="virtual" rows="6" cols="50"');
        $mform->addHelpButton('new_student_disclosure', 'studentdisclosure', 'plagiarism_safeassign');
        $mform->setDefault('new_student_disclosure', get_string('studentdisclosuredefault','plagiarism_safeassign'));

        $this->add_action_buttons(true);
    }
}

