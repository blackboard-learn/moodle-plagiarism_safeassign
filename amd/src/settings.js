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
define(['jquery', 'core/modal_factory', 'core/templates', 'core/str', 'core/notification', 'core/ajax', 'core/config'],
    function($, ModalFactory, Templates, str, Notification, ajax, mdlcfg) {


        return /** @alias module:core/addblockmodal */ {
            /**
             * Global init function for this module.
             *
             * @method init
             * @param {Object} context The template context for rendering this modal body.
             */
            init: function() {
                var testcredentials = $('#id_test_credentials');
                var title = str.get_string('test_credentials', 'plagiarism_safeassign');

                $.when(title).done(function(localizedEditString) {
                    ModalFactory.create({
                        title: localizedEditString,
                        body: 'olaolaolaola',
                        type: 'DEFAULT',
                    }, testcredentials).done(function(modal) {
                        console.log('Modal terminado');
                        console.log('Modal terminado');


                        // promises[1].done(function(response) {
                        //     console.log('mod_wiki/changerate is2' + response);
                        // }).fail(function(ex) {
                        //     // do something with the exception
                        // });

                        }
                    );
                });
                testcredentials.click(function(e){
                    var instructorUsername = $('#id_safeassign_instructor_username').val();
                    var instructorPassword = $('#id_safeassign_instructor_password').val();
                    var studentUsername = $('#id_safeassign_instructor_username').val();
                    var studentPassword = $('#id_safeassign_instructor_password').val();
                    // var promises = ajax.call(
                    //     { methodname: 'plagiarism_safeassign_test_credentials', args: { username: instructorUsername, password: instructorPassword, issinstructor: true } },
                    //     { methodname: 'plagiarism_safeassign_test_credentials', args: { username: studentUsername, password: studentPassword, issinstructor: false } }
                    //
                    // );

                    $.ajax({
                        type: 'POST',
                        url: mdlcfg.wwwroot + '/lib/ajax/service.php?sesskey=' + mdlcfg.sesskey,
                        contentType: 'application/json; charset=utf-8',
                        data: {
                            index: 0,
                            methodname: 'plagiarism_safeassign_test_credentials',
                            args: {username: instructorUsername, password: instructorPassword, isinstructor: false}
                        },
                        dataType: 'json'
                    }).done(function(response) {
                        console.log(response)
                    }).fail(function(response) {
                        console.log(response);
                    });
                    // console.log('vector');
                    // console.log(mdlcfg.wwwroot);
                    // promises.done(function(response) {
                    //     console.log('mod_wiki/pluginname is' + response);
                    // }).fail(function(ex) {
                    //     // do something with the exception
                    // });
                });
                // We need the fetch the names of the blocks. It was too much to send in the page.

            },


        }
    }
);
