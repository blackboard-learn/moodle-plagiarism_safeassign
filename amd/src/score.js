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
 * @author    Guillermo Leon Alvarez Salamanca
 * @copyright Copyright (c) 2017 Open LMS (https://www.openlms.net)
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
         * @param {string} originalityReportLink
         */
        init: function(avgScore, userId, originalityReportLink) {

            /**
             * Checks if some element exist in page DOM.
             * @param {string} selector
             * @returns {boolean}
             */
            var elementExists = function(selector) {
                var el = $(selector);
                return (el.length) ? true : false;
            };

            /**
             * Creates a new DOM element and attach it into the file submission tree.
             * @param {string} selector
             */
            var appendAvgScoreFilesTree = function(selector) {
                if (!elementExists('#safeassign_score_' + userId)) {
                    var tree = $(selector);
                    var td = $('<td></td>').attr('id', 'safeassign_text_' + userId);
                    td.addClass('ygtvcell ygtvhtml ygtvcontent');
                    var trow = $('<tr></tr>').addClass('ygtvrow').append(td);
                    var table = $('<table></table>').attr('id', 'safeassign_score_' + userId).append(trow);
                    var div = $('<div></div>').addClass('ygtvitem').append(table);
                    tree.prepend(div);
                    if (originalityReportLink) {
                        var reporttd = $('<td></td>').attr('id', 'safeassign_or_' + userId)
                            .addClass('ygtvcell ygtvhtml ygtvcontent');
                        reporttd.append(originalityReportLink);
                        var reportrow = $('<tr></tr>').addClass('ygtvrow').append(reporttd);
                        $('#safeassign_score_' + userId).append(reportrow);
                    }
                    getMessage(avgScore, '#safeassign_text_' + userId);
                }
            };

            /**
             * Creates a new DOM element and attach it into the online submission region.
             * @param {string} selector
             */
            var appendAvgScoreOnlineSubm = function(selector) {
                if (!elementExists('#safeassign_online_sub_' + userId)) {
                    var el = $(selector).parent();
                    var div = $('<div></div>').attr('id', 'safeassign_online_sub_' + userId);
                    if (originalityReportLink) {
                        var reportdiv = $('<div></div>').attr('id', 'safeassign_online_or_' + userId);
                        reportdiv.append(originalityReportLink);
                        el.prepend(reportdiv);
                    }
                    el.prepend(div);
                    getMessage(avgScore, '#safeassign_online_sub_' + userId);
                }
            };

            /**
             * Returns a message with the average score.
             * @param {int} avgScore
             * @param {string} selector
             */
            var getMessage = function(avgScore, selector) {

                // Get overall score string via ajax.
                var messageString = str.get_string('safeassign_overall_score', 'plagiarism_safeassign', avgScore);

                $.when(messageString).done(function(s) {
                    $(selector).append(s);
                });

            };

            /**
             * Makes a JQuery promise to see if some element exist in the DOM.
             * @param {string} containerSelector
             * @param {int} maxIterations
             * @returns {promise} JQuery promise
             */
            var whenTrue = function(containerSelector, maxIterations) {
                maxIterations = !maxIterations ? 10 : maxIterations;

                var prom = $.Deferred();
                var i = 0;
                var checker = setInterval(function() {
                    i = i + 1;
                    if (i > maxIterations) {
                        prom.reject();
                        clearInterval(checker);
                    } else {
                        if (elementExists(containerSelector)) {
                            prom.resolve();
                            clearInterval(checker);
                        }
                    }
                }, 200);

                return prom.promise();
            };

            // Checks if we are on assign grading view.
            var pageObject = $('#page-mod-assign-grading');
            var isFeedbackView = pageObject.length;
            var fileSelector = '.ygtvchildren';
            var onlineSelector = '.plagiarism-inline.online-text-div';
            if (isFeedbackView) {
                fileSelector = '.user' + userId + ' .ygtvchildren';
                onlineSelector = '.user' + userId + ' td div.plagiarism-inline.online-text-div';
            }

            var readyFiles = whenTrue(fileSelector, 20);
            readyFiles.then(function() {
                appendAvgScoreFilesTree(fileSelector);
            });

            var readyOnline = whenTrue(onlineSelector, 20);
            readyOnline.then(function() {
                appendAvgScoreOnlineSubm(onlineSelector);
            });

        }
    };
});
