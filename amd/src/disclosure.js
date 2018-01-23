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
 * @author    Juan Felipe Martinez juan.martinez@blackboard.com
 * @copyright Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * JS code to listen the disclosure agreement checkbox in an assignment
 * configured with SafeAssign.
 */
define(['jquery', 'core/ajax'],
    function($, ajax) {
        return {

            /**
             * Detects the change of from the  disclosure agreement
             * checkbox and sends the flag state to a webservice
             * to be stored in the DB.
             * @param {int} cmid - course module ID
             * @param {int} userid - user ID
             */
            init: function(cmid, userid) {
                $("input[name = agreement]").click(function() {
                    var flag = 0;
                    if ($("input[name = agreement]").is(':checked')) {
                        flag = 1;
                    }
                    ajax.call(
                        [{
                            methodname: 'plagiarism_safeassign_update_flag',
                            args: {
                                cmid: cmid,
                                userid: userid,
                                flag: flag
                            }
                        }]
                    );
                });
            }
        };
    }
);
