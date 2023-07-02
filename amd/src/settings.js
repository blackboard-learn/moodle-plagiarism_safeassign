/**
 * This file is part of Moodle - http://moodle.org/
 *
 * Moodle is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Moodle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Jonathan Garcia Gomez jonathan.garcia@openlms.net
 * @copyright Copyright (c) 2017 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * JS code to test SafeAssing Credentials.
 */
import $ from 'jquery';
import ModalFactory from 'core/modal_factory';
import Templates from 'core/templates';
import {get_string as getString, get_strings as getStrings} from 'core/str';
import ajax from 'core/ajax';

const settings = {
    /**
     * Through the Moodle core functions sends the data for instructor and student so their credentials
     * can be tested.
     * @param {string} storedUrl
     */
    init: function(storedUrl) {

        // We need all the required inputs to test connection.
        const inputs = '#id_safeassign_api, #id_safeassign_instructor_username, #id_safeassign_instructor_password, ' +
            '#id_safeassign_student_username, #id_safeassign_student_password';

        // Disables the "Test connection" button and changes the text value.
        const disableButton = function(string) {
            $('#id_test_credentials').attr('disabled', 'disabled');
            $('#id_test_credentials').attr('value', string);
        };

        const buttonText = getString('button_disabled', 'plagiarism_safeassign');

        $.when(buttonText).done(function(localizedString) {

            // New forms should have the "Test connection" button disabled until the fields have content.
            $(inputs).each(function() {
                if ($(this).val() == '') {
                    disableButton(localizedString);
                }
            });

            // Changes in the input fields should be saved to enable the "Test connection" button.
            $(inputs).on('input', function() {
                disableButton(localizedString);
            });

            // If the stored URL is different from the one selected in the dropdown, disable the "Test connection" button.
            const url = $('#id_safeassign_api').val();
            if (url != storedUrl) {
                disableButton(localizedString);
            }
        });

        /**
         * Changes the class of the html element to match the response status.
         * @param {string} selector Id for the html element
         * @param {bool} status Response status
         */
        const swapClasses = function(selector, status) {
            if (status) {
                if ($(selector).hasClass('alert-warning')) {
                    $(selector).removeClass("alert-warning").addClass('alert-success');
                } else if ($(selector).hasClass('alert-danger')) {
                    $(selector).removeClass("alert-danger").addClass('alert-success');
                }
            } else {
                if ($(selector).hasClass('alert-warning')) {
                    $(selector).removeClass("alert-warning").addClass('alert-danger');
                } else if ($(selector).hasClass('alert-success')) {
                    $(selector).removeClass("alert-success").addClass('alert-danger');
                }
            }
        };

        const credentialsTrigger = $('#id_test_credentials');

        // First bring the required stings for the modal.
        const strings = getStrings([
            {key: 'test_credentials', component: 'plagiarism_safeassign'},
            {key: 'safeassign_credentials_verified', component: 'plagiarism_safeassign'},
            {key: 'safeassign_credentials_fail', component: 'plagiarism_safeassign'}
        ]);

        let localizedStrings = [];

        // When the strings are ready we can create the modal.
        $.when(strings).done(function(localizedEditString) {
            localizedStrings = localizedEditString;
            ModalFactory.create({
                title: localizedEditString[0],
                body: Templates.render('plagiarism_safeassign/modal', {}),
                type: ModalFactory.types.DEFAULT,
            }, credentialsTrigger);
        });

        /**
         * Set the default state for the modal
         * @param {string} selector
         */
        const setDefault = function(selector) {
            if ($(selector).hasClass('alert-success')) {
                $(selector).removeClass("alert-success").addClass('alert-warning');
            } else if ($(selector).hasClass('alert-danger')) {
                $(selector).removeClass("alert-danger").addClass('alert-warning');
            }
        };

        /**
         * Sends the username and password to validate student/instructor credentials.
         * It also changes the text and the class of the modal <p> tag.
         * @param {string} resultText Selector used to identify the text that should change.
         * @param {string} resultElement Selector used to identify the html tag to change its color.
         * @param {string} username
         * @param {string} password
         * @param {string} baseUrl
         * @param {int}    userId
         */
        const testCredentials = function(resultText, resultElement, username, password, baseUrl, userId) {
            const promise = ajax.call(
                [{
                    methodname: 'plagiarism_safeassign_test_api_credentials',
                    args: {
                        username: username,
                        password: password,
                        baseurl: baseUrl,
                        userid: userId
                    }
                }]
            )[0];
            promise.done(function(response) {
                if (response.success) {
                    swapClasses(resultElement, true);
                    $(resultText).text(localizedStrings[1]);
                } else {
                    swapClasses(resultElement, false);
                    $(resultText).text(localizedStrings[2]);
                }
            }).fail(function() {
                swapClasses(resultElement, false);
                $(resultText).text(localizedStrings[2]);
            });
        };

        credentialsTrigger.click(function(e) {
            e.preventDefault();
            setDefault('#instructor_credentials');
            $('#instructor_result').text('...');
            setDefault('#student_credentials');
            $('#student_result').text('...');
            const instructorUsername = $('#id_safeassign_instructor_username').val();
            const instructorPassword = $('#id_safeassign_instructor_password').val();
            const studentUsername = $('#id_safeassign_student_username').val();
            const studentPassword = $('#id_safeassign_student_password').val();
            const userId = $('input[name=userid]').val();
            const baseUrl = $('#id_safeassign_api').val();
            testCredentials('#instructor_result', '#instructor_credentials', instructorUsername,
                instructorPassword, baseUrl, userId);
            testCredentials('#student_result', '#student_credentials', studentUsername, studentPassword,
                baseUrl, userId);
        });

        const cleanSelect = $('#id_safeassign_additional_roles');
        if (cleanSelect.find('option').length > 1) {
            cleanSelect.change(function() {
                $( "option:selected" ).each(function() {
                    if ($(this).val() == 0) {
                        cleanSelect.val(0);
                    }
                });
            });
        }
    }
};

export default settings;
