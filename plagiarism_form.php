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

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/lib/formslib.php');

class plagiarism_setup_form extends moodleform {

    /**
     * Define the form.
     */
    function definition () {
        global $CFG, $USER;
        $mform =& $this->_form;
        $mform->addElement('header', 'moodle', get_string('credentials', 'plagiarism_safeassign'));
        $mform->addElement('checkbox', 'safeassign_use', get_string('usesafeassign', 'plagiarism_safeassign'));

        $urls = [];
        $disabled = 'disabled';
        $required = 'safeassign_api';
        if (!empty($CFG->plagiarism_safeassign_urls)) {
            foreach ($CFG->plagiarism_safeassign_urls as $url) {
                if (!empty($url['url'])) {
                    $urls[$url['url']] = implode(' - ', $url);
                }
            }
            $disabled = '';
            if (count($urls) <= 1) {
                $disabled = 'disabled';
                if (count($urls) === 1) {
                    $mform->addElement('hidden', 'default_safeassign_api', key($urls));
                    $mform->setType('default_safeassign_api', PARAM_TEXT);
                    $required = 'default_safeassign_api';
                }
            }
        }

        $mform->addElement('select', 'safeassign_api', get_string('credentials', 'plagiarism_safeassign'), $urls, array($disabled));
        $mform->addRule($required, null, 'required', null, 'client');
        $mform->addHelpButton('safeassign_api', 'safeassign_api', 'plagiarism_safeassign');
        $mform->setType('safeassign_api', PARAM_URL);

        $mform->addElement('header', 'moodle', get_string('instructor_role_credentials', 'plagiarism_safeassign'));
        $mform->addElement('hidden', 'userid', $USER->id);
        $mform->setType('userid', PARAM_INT);

        $mform->addElement('text', 'safeassign_instructor_username', get_string('safeassign_instructor_username', 'plagiarism_safeassign'));
        $mform->addHelpButton('safeassign_instructor_username', 'safeassign_instructor_username', 'plagiarism_safeassign');
        $mform->addRule('safeassign_instructor_username', null, 'required', null, 'client');
        $mform->setType('safeassign_instructor_username', PARAM_TEXT);

        $mform->addElement('passwordunmask', 'safeassign_instructor_password', get_string('safeassign_instructor_password', 'plagiarism_safeassign'));
        $mform->addHelpButton('safeassign_instructor_password', 'safeassign_instructor_password', 'plagiarism_safeassign');
        $mform->addRule('safeassign_instructor_password', null, 'required', null, 'client');
        $mform->setType('safeassign_instructor_password', PARAM_TEXT);

        $mform->addElement('header', 'moodle', get_string('student_role_credentials', 'plagiarism_safeassign'));
        $mform->addElement('text', 'safeassign_student_username', get_string('safeassign_student_username', 'plagiarism_safeassign'));
        $mform->addHelpButton('safeassign_student_username', 'safeassign_student_username', 'plagiarism_safeassign');
        $mform->addRule('safeassign_student_username', null, 'required', null, 'client');
        $mform->setType('safeassign_student_username', PARAM_TEXT);

        $mform->addElement('passwordunmask', 'safeassign_student_password', get_string('safeassign_student_password', 'plagiarism_safeassign'));
        $mform->addHelpButton('safeassign_student_password', 'safeassign_student_password', 'plagiarism_safeassign');
        $mform->addRule('safeassign_student_password', null, 'required', null, 'client');
        $mform->setType('safeassign_student_password', PARAM_TEXT);
        $mform->addElement('header');

        $mform->addElement('duration', 'safeassign_curlcache', get_string('safeassign_curlcache', 'plagiarism_safeassign'));
        $mform->addHelpButton('safeassign_curlcache', 'safeassign_curlcache', 'plagiarism_safeassign');
        $mform->addRule('safeassign_curlcache', null, 'required', null, 'client');
        $mform->setType('safeassign_curlcache', PARAM_INT);

        $mform->addElement('html', get_string('safeassign_cachedefault', 'plagiarism_safeassign'));
        $mform->addElement('submit', 'test_credentials', get_string('test_credentials', 'plagiarism_safeassign'), null);

        if (!empty($CFG->plagiarism_safeassign_display_general_settings)) {

            $mform->addElement('header', 'moodle', get_string('general'));

            $mform->addElement('text', 'safeassign_institutioninfo', get_string('safeassign_institutioninfo', 'plagiarism_safeassign'));
            $mform->addRule('safeassign_institutioninfo', null, 'required', null, 'client');
            $mform->setType('safeassign_institutioninfo', PARAM_TEXT);

            $mform->addElement('text', 'safeassign_contactemail', get_string('safeassign_contactemail', 'plagiarism_safeassign'));
            $mform->addRule('safeassign_contactemail', null, 'required', null, 'client');
            $mform->setType('safeassign_contactemail', PARAM_EMAIL);
        }

        $mform->addElement('header', 'moodle', get_string('settings', 'plagiarism_safeassign'));
        $mform->addElement('html', get_string('disclaimer', 'plagiarism_safeassign'));
        $mform->addElement('checkbox', 'safeassign_referencedbactivity', get_string('safeassign_referencedbactivity', 'plagiarism_safeassign'));
        $mform->setDefault('safeassign_referencedbactivity', false);

        $mform->addElement('textarea', 'safeassign_new_student_disclosure', get_string('studentdisclosure','plagiarism_safeassign'),'wrap="virtual" rows="6" cols="50"');
        $mform->addHelpButton('safeassign_new_student_disclosure', 'studentdisclosure', 'plagiarism_safeassign');
        $mform->setDefault('safeassign_new_student_disclosure', get_string('studentdisclosuredefault','plagiarism_safeassign'));

        $mform->addElement('header', 'moodle', get_string('license_header', 'plagiarism_safeassign'));
        $mform->addElement('textarea', 'license', get_string('license_title', 'plagiarism_safeassign'), 'wrap = "virtual" rows = "15" cols = "100" readonly');
        $terms = new plagiarism_safeassign_terms();
        $mform->setDefault('license', $terms->get_license_agreement());

        $this->add_action_buttons(true);
    }
}

