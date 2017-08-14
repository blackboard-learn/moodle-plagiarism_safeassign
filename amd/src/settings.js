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
 * @package   plagiarism_safeassign
 * @author    Jonathan Garcia Gomez jonathan.garcia@blackboard.com
 * @copyright Blackboard 2017
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * JS code to test SafeAssing Credentials
 */
define(['jquery', 'core/modal_factory', 'core/templates', 'core/str', 'core/notification', 'core/ajax'],
    function($, ModalFactory, Templates, str, Notification, ajax) {
        return {

            /**
             * Through the Moodle core functions sends the data for instructor and student so their credentilas
             * can be tested.
             */
            testCredentials: function(){

                // Todo: Validate that the fields for SafeAssign API have content to disable/enable the button.
                // $('#id_safeassign_api, #id_safeassign_instructor_username, #id_safeassign_instructor_password, ' +
                //     '#id_safeassign_student_username, #id_safeassign_student_password').keyup(function(e) {
                //     console.log(e);
                //     var empty = false;
                //     $('#id_safeassign_api, #id_safeassign_instructor_username, #id_safeassign_instructor_password, ' +
                //         '#id_safeassign_student_username, #id_safeassign_student_password').each(function() {
                //         if ($(this).val() == '') {
                //             empty = true;
                //         }
                //     });
                //
                //     if (empty) {
                //         $('#id_test_credentials').attr('disabled', 'disabled');
                //     } else {
                //         $('id_test_credentials').removeAttr('disabled');
                //     }
                // });

                /**
                * Changes the class of the html element to match the response status.
                * @param {string} selector Id for the html element
                * @param {bool} status Response status
                */
                var swapClasses = function(selector, status) {
                    if (status) {
                        if ($(selector).hasClass('alert-warning')) {
                            $(selector).removeClass( "alert-warning" ).addClass('alert-success');
                        } else if ($(selector).hasClass('alert-danger')) {
                            $(selector).removeClass( "alert-danger" ).addClass('alert-success');
                        }
                    } else {
                        if ($(selector).hasClass('alert-warning')) {
                            $(selector).removeClass( "alert-warning" ).addClass('alert-danger');
                        } else if ($(selector).hasClass('alert-success')) {
                            $(selector).removeClass( "alert-success" ).addClass('alert-danger');
                        }
                    }
                };

                var credentialsTrigger = $('#id_test_credentials');
                var strings = str.get_strings([
                    {key: 'test_credentials', component: 'plagiarism_safeassign'},
                    {key: 'safeassign_credentials_verified', component: 'plagiarism_safeassign'},
                    {key: 'safeassign_credentials_fail', component: 'plagiarism_safeassign'}
                ]);
                var localizedStrings = [];
                $.when(strings).done(function(localizedEditString) {
                    localizedStrings = localizedEditString;
                    ModalFactory.create({
                        title: localizedEditString[0],
                        body: Templates.render('plagiarism_safeassign/modal', {}),
                        type: ModalFactory.types.DEFAULT,
                    }, credentialsTrigger).done(function(modal) {
                            console.log('Modal: Done');
                            console.log(modal);
                        }
                    );
                });

                /**
                 * Sends the username, password and a flag to validate student/instructor credentials.
                 * It also changes the text and the class of the modal <p> tag.
                 * @param {string} resultText Selector used to identify the text that should change.
                 * @param {string} resultElement Selector used to identify the html tag to change its color.
                 * @param {string} username
                 * @param {string} password
                 * @param {string} baseUrl
                 * @param {int}    userId
                 */
                var testCredentials = function(resultText, resultElement, username, password, baseUrl, userId) {
                    var promise = ajax.call(
                        [{ methodname: 'plagiarism_safeassign_test_api_credentials',
                            args: { username: username, password: password, baseurl: baseUrl, userid: userId} }]
                    )[0];
                    promise.done(function(response) {
                        console.log(response);
                        if (response.success){
                            swapClasses(resultElement, true);
                            $(resultText).text(localizedStrings[1]);
                        } else {
                            swapClasses(resultElement, false);
                            $(resultText).text(localizedStrings[2]);
                        }
                    }).fail(function(ex) {
                        swapClasses(resultElement, false);
                        $(resultText).text(localizedStrings[2]);
                        console.log(ex);
                    });
                };

                credentialsTrigger.click(function(e){
                    e.preventDefault();
                    var instructorUsername = $('#id_safeassign_instructor_username').val();
                    var instructorPassword = $('#id_safeassign_instructor_password').val();
                    var studentUsername = $('#id_safeassign_student_username').val();
                    var studentPassword = $('#id_safeassign_student_password').val();
                    var userId = $('input[name=userid]').val();
                    var baseUrl = $('#id_safeassign_api').val();
                    $('#instructor_result').text('...');
                    testCredentials('#instructor_result', '#instructor_credentials', instructorUsername,
                        instructorPassword, baseUrl, userId);
                    $('#student_result').text('...');
                    testCredentials('#student_result', '#student_credentials', studentUsername, studentPassword,
                        baseUrl, userId);
                });
            },

            /**
             * Initialise.
             */
            init: function() {
                this.testCredentials();
            },

        }
    }
);
