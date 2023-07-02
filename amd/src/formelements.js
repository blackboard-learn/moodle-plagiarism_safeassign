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
 * @author    Guillermo Leon Alvarez Salamanca
 * @copyright Copyright (c) 2017 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * JS code to disable temporarily the SafeAssign settings parameters in module edit from
 * until the page ends to load all the elements on it.
 */
import $ from 'jquery';
import {get_string as getString} from 'core/str';

const formElements = {
    /**
     * Hides via javascript the form elements until page is ready.
     */
    init: function() {

        /**
         * Create a div to display the loading message inside module edit form.
         */
        const createDiv = function() {
            const div = $('<div></div>').attr('class', child.attr('class'));
            div.attr('id', 'safeassign_loading_div');
            parent.append(div);
            getMessage();
        };

        /**
         * Returns a string with the loading message.
         */
        const getMessage = function() {

            // Get loading message via ajax.
            const messageString = getString('safeassign_loading_settings', 'plagiarism_safeassign');

            $.when(messageString).done(function(s) {
                $('#safeassign_loading_div').append(s);
            });

        };

        /**
         * Checks if the element is disabled.
         * @returns {boolean}
         */
        const isElementDisabled = function() {
            return $('#id_safeassign_global_reference').prop('disabled');
        };

        /**
         * Makes a JQuery promise to see if some element is disabled.
         * @param {function} evaluateFunction
         * @param {int} maxIterations
         * @returns {promise} JQuery promise
         */
        const whenTrue = function(evaluateFunction, maxIterations) {
            maxIterations = !maxIterations ? 10 : maxIterations;

            const prom = $.Deferred();
            let i = 0;
            const checker = setInterval(function() {
                i = i + 1;
                if (i > maxIterations) {
                    prom.reject();
                    clearInterval(checker);
                } else {
                    if (evaluateFunction()) {
                        prom.resolve();
                        clearInterval(checker);
                    }
                }
            }, 1000);

            return prom.promise();
        };

        /**
         * Print the settings checkboxes when tha page has been loaded.
         */
        const printSettings = function() {
            const div = $('#safeassign_loading_div');
            div.addClass('hidden-div');
            child.removeAttr('style');
            selectorCheckbox.prop('checked', checkboxInitialValue);
            selectorCheckbox.prop('disabled', false);
            if (checkboxInitialValue) {
                $('#id_safeassign_originality_report').prop('disabled', false);
                $('#id_safeassign_global_reference').prop('disabled', false);
            }
        };

        const parent = $('#id_plagiarismdesc');

        // Hide the settings for SafeAssign in the module edit form.
        const child = parent.children('div').hide();

        // Disable SafeAssign enable settings.
        const selectorCheckbox = $('#id_safeassign_enabled');
        const checkboxInitialValue = selectorCheckbox.prop('checked');
        selectorCheckbox.prop('checked', false);
        selectorCheckbox.prop('disabled', true);

        createDiv();

        const ready = whenTrue(isElementDisabled, 30);
        ready.then(printSettings);

    }
};

export default formElements;
