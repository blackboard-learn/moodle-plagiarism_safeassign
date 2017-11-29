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
define(['jquery'], function($) {

    return {

        /**
         * Adds a new DOM element in the submission tree object to display the average plagiarism
         * score for some submission.
         * @param {int} avgscore
         * @param {int} userid
         */
        init: function(avgscore, userid) {

            /**
             * Checks if we have already added a new DOM element in the submission tree.
             * @returns {boolean}
             */
            var alreadyhaveavgsscore = function () {
                var table = $('#safeassign_score_' + userid);
                return (table.length) ? true : false;
            };

            /**
             * Creates a new DOM element and attach it into the file submission tree.
             * @param {string} selector
             */
            var appendavgscore = function (selector) {
                if (!alreadyhaveavgsscore()) {
                    var tree = $(selector);
                    var message = getmessage(avgscore);
                    var td = $('<td>' + message + '</td>').addClass('ygtvcell ygtvhtml ygtvcontent');
                    var trow = $('<tr></tr>').addClass('ygtvrow').append(td);
                    var table = $('<table></table>').attr('id', 'safeassign_score_' + userid).append(trow);
                    var div = $('<div></div>').addClass('ygtvitem').append(table);
                    tree.prepend(div);
                    clearInterval(print_score);
                }
            };

            /**
             * Returns a message with the average score.
             * @param {int} avgscore
             * @returns {string}
             */
            var getmessage = function (avgscore) {
                return '<b>Plagiarism overall score: ' + avgscore + '%</b>';
            };

            // Checks if we are on assign grading view.
            var page_object = $('#page-mod-assign-grading');
            var is_feedback_view = page_object.length;
            var selector = '';
            if (is_feedback_view) {
                selector = '.user' + userid + ' .ygtvchildren';
            } else {
                // Checks if we are on mr grader view or in mod_assign grader view.
                page_object = $('#page-local-joulegrader-view');
                var is_mr_grader_view = page_object.length;
                page_object = $('#page-mod-assign-grader');
                var is_mod_assign_grader_view = page_object.length;
                if (is_mr_grader_view || is_mod_assign_grader_view) {
                    selector = '.ygtvchildren';
                } else {
                    // By default, we are on student submission view.
                    selector = '.plugincontentsummary .ygtvchildren';
                }
            }
            var print_score = setInterval( function() { appendavgscore(selector);}, 200);
        }
    };
});
