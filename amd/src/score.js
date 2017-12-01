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
 * @author    Guillermo Leon Alvarez Salamanca guillermo.alvarez@blackboard.com
 * @copyright Blackboard 2017
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * JS code to listen the disclosure agreement checkbox in an assignment
 * configured with SafeAssign.
 */
define(['jquery', 'core/str'], function($, str) {

    return {

        /**
         * Adds a new DOM element in the submission tree object to display the average plagiarism
         * score for some submission.
         * @param {int} avgScore
         * @param {int} userId
         */
        init: function(avgScore, userId) {

            /**
             * Checks if we have already added a new DOM element in the submission tree.
             * @returns {boolean}
             */
            var alreadyHaveAvgScore = function () {
                var table = $('#safeassign_score_' + userId);
                return (table.length) ? true : false;
            };

            /**
             * Creates a new DOM element and attach it into the file submission tree.
             * @param {string} selector
             */
            var appendAvgScore = function (selector) {
                if (!alreadyHaveAvgScore()) {
                    var tree = $(selector);
                    var td = $('<td></td>').attr('id', 'safeassign_text_' + userId);
                    td.addClass('ygtvcell ygtvhtml ygtvcontent');
                    var trow = $('<tr></tr>').addClass('ygtvrow').append(td);
                    var table = $('<table></table>').attr('id', 'safeassign_score_' + userId).append(trow);
                    var div = $('<div></div>').addClass('ygtvitem').append(table);
                    tree.prepend(div);
                    clearInterval(printScore);
                    getMessage(avgScore);
                }
            };

            /**
             * Returns a message with the average score.
             * @param {int} avgScore
             */
            var getMessage = function (avgScore) {

                // Get overall score string via ajax.
                var messageString = str.get_string('safeassign_overall_score', 'plagiarism_safeassign', avgScore);

                $.when(messageString).done(function(s) {
                    $('#safeassign_text_' + userId).append(s);
                });

            };

            // Checks if we are on assign grading view.
            var pageObject = $('#page-mod-assign-grading');
            var isFeedbackView = pageObject.length;
            var selector = '';
            if (isFeedbackView) {
                selector = '.user' + userId + ' .ygtvchildren';
            } else {
                // Checks if we are on mr grader view or in mod_assign grader view.
                pageObject = $('#page-local-joulegrader-view');
                var isMrGraderView = pageObject.length;
                pageObject = $('#page-mod-assign-grader');
                var isModAssignGraderView = pageObject.length;
                if (isMrGraderView || isModAssignGraderView) {
                    selector = '.ygtvchildren';
                } else {
                    // By default, we are on student submission view.
                    selector = '.plugincontentsummary .ygtvchildren';
                }
            }
            var printScore = setInterval( function() { appendAvgScore(selector);}, 200);
        }
    };
});
