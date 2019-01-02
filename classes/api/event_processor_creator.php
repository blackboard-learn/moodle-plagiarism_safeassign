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
 * Processor creator.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2019 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\api;

defined('MOODLE_INTERNAL') || die();

/**
 * Class event_processor_creator
 *
 * Given an event, this class will return an specific processor.
 *
 * @package plagiarism_safeassign\api
 * @copyright Copyright (c) 2019 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class event_processor_creator {
    /**
     * Creates an specific processor depending on the event being received.
     * @param \core\event\base $event
     * @return abstract_submission_processor | file_event_processor | submission_event_processor
     * @throws \coding_exception
     */
    public static function processor_factory(\core\event\base $event) {
        $eventclass = get_class($event);
        switch ($eventclass) {
            case 'assignsubmission_onlinetext\event\submission_created' :
                return new text_submission_processor($event);
            case 'assignsubmission_onlinetext\event\submission_updated' :
                return new text_submission_processor($event);
            case 'assignsubmission_file\event\assessable_uploaded' :
                return new file_event_processor($event);
            case 'assignsubmission_file\event\submission_created' :
                return new file_submission_processor($event);
            case 'assignsubmission_file\event\submission_updated' :
                return new file_submission_processor($event);
            case 'mod_assign\event\assessable_submitted':
                return new submission_event_processor($event);
            default:
                throw new \coding_exception('The \'event\' cannot be processed');
        }
    }
}