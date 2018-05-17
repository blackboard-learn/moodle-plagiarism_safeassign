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
 * Privacy class for requesting user data.
 *
 * @package    plagiarism_safeassign
 * @author      <@blackboard.com>
 * @copyright  Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\privacy;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot.'/mod/assign/externallib.php');
use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\context;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\writer;
use core_privacy\local\request\transform;

class provider implements
    // This plugin does export personal user data.
    \core_privacy\local\metadata\provider,

    // This plugin is always linked against another activity module via the Plagiarism API.
    \core_plagiarism\privacy\plagiarism_provider,

    // This plugin need to export other data that is not related to one activity module.
    \core_privacy\local\request\plugin\provider {

    // This trait must be included.
    use \core_privacy\local\legacy_polyfill;

    // This trait must be included to provide the relevant polyfill for the plagiarism provider.
    use \core_plagiarism\privacy\legacy_polyfill;

    /**
     * Returns meta data about this system.
     *
     * @param   collection     $collection The initialised collection to add items to.
     * @return  collection     A listing of user data stored through this system.
     */
    public static function _get_metadata(collection $collection) {
        $collection->add_database_table('plagiarism_safeassign_files', [
            'userid' => 'privacy:metadata:plagiarism_safeassign_files:userid',
            'uuid' => 'privacy:metadata:plagiarism_safeassign_files:uuid',
            'reporturl' => 'privacy:metadata:plagiarism_safeassign_files:reporturl',
            'similarityscore' => 'privacy:metadata:plagiarism_safeassign_files:similarityscore',
            'timesubmitted' => 'privacy:metadata:plagiarism_safeassign_files:timesubmitted',
            'submissionid' => 'privacy:metadata:plagiarism_safeassign_files:submissionid',
            'fileid' => 'privacy:metadata:plagiarism_safeassign_files:fileid',
        ], 'privacy:metadata:plagiarism_safeassign_files');

        $collection->add_database_table('plagiarism_safeassign_course', [
            'uuid' => 'privacy:metadata:plagiarism_safeassign_course:uuid',
            'courseid' => 'privacy:metadata:plagiarism_safeassign_course:courseid',
            'instructorid' => 'privacy:metadata:plagiarism_safeassign_course:instructorid',
        ], 'privacy:metadata:plagiarism_safeassign_course');

        $collection->add_database_table('plagiarism_safeassign_subm', [
            'uuid' => 'privacy:metadata:plagiarism_safeassign_subm:uuid',
            'highscore' => 'privacy:metadata:plagiarism_safeassign_subm:highscore',
            'avgscore' => 'privacy:metadata:plagiarism_safeassign_subm:avgscore',
            'submitted' => 'privacy:metadata:plagiarism_safeassign_subm:submitted',
            'submissionid' => 'privacy:metadata:plagiarism_safeassign_subm:submissionid',
            'hasfile' => 'privacy:metadata:plagiarism_safeassign_subm:hasfile',
            'hasonlinetext' => 'privacy:metadata:plagiarism_safeassign_subm:hasonlinetext',
            'timecreated' => 'privacy:metadata:plagiarism_safeassign_subm:timecreated',
            'assignmentid' => 'privacy:metadata:plagiarism_safeassign_subm:assignmentid',
        ], 'privacy:metadata:plagiarism_safeassign_subm');

        $collection->add_database_table('plagiarism_safeassign_instr', [
            'instructorid' => 'privacy:metadata:plagiarism_safeassign_instr:instructorid',
            'courseid' => 'privacy:metadata:plagiarism_safeassign_instr:courseid',
        ], 'privacy:metadata:plagiarism_safeassign_instr');

        // Moodle core components.
        $collection->add_subsystem_link('core_plagiarism', [], 'privacy:metadata:core_plagiarism');
        $collection->add_subsystem_link('core_files', [], 'privacy:metadata:core_files');

        // External Services.
        $collection->add_external_location_link('safeassign_service', [
            'userid' => 'privacy:metadata:safeassign_service:userid',
            'username' => 'privacy:metadata:safeassign_service:fullname',
            'submissionuuid' => 'privacy:metadata:safeassign_service:submissionuuid',
            'fileuuid' => 'privacy:metadata:safeassign_service:fileuuid',
            'filename' => 'privacy:metadata:safeassign_service:filename',
            'filecontent' => 'privacy:metadata:safeassign_service:filecontent',
            'adminemail' => 'privacy:metadata:safeassign_service:adminemail'
        ], 'privacy:metadata:safeassign_service');

        return $collection;
    }

    /**
     * Export all plagiarism data from each plagiarism plugin for the specified userid and context.
     *
     * @param   int         $userid The user to export.
     * @param   \context    $context The context to export.
     * @param   array       $subcontext The subcontext within the context to export this information to.
     * @param   array       $linkarray The weird and wonderful link array used to display information for a specific item
     */
    public static function _export_plagiarism_user_data($userid, \context $context, array $subcontext, array $linkarray) {
        global $DB;

        $moduledata = get_context_info_array($context->id);
        $module = $moduledata[2];

        $validmodnames = ['assign'];

        // Check if we are in a valid module.
        if (in_array($module->modname, $validmodnames)) {

            // Get the submissionid for the submission of this user.
            $sql = "SELECT asubm.id
                      FROM {assign_submission} asubm
                      JOIN {assign} assign ON assign.id = asubm.assignment
                     WHERE asubm.userid = :userid
                       AND assign.id = :assignid";

            $submissions = $DB->get_records_sql($sql, ['userid' => $userid, 'assignid' => $module->instance]);

            list($insql, $inparams) = $DB->get_in_or_equal(array_keys($submissions), SQL_PARAMS_NAMED);
            $submissionin = "submissionid $insql AND userid = :userid";

            // Check if we are speaking about an online text submission or a file submission.
            $condition = !empty($linkarray['file']) ? 'hasfile = 1' : 'hasonlinetext = 1';

            // Get all submissions in safeassign tables for this user.
            $sql = "SELECT id, highscore, avgscore, submitted,
                           submissionid, deprecated, timecreated
                      FROM {plagiarism_safeassign_subm} subm
                     WHERE subm.submissionid $insql
                       AND $condition";
            $allsubmissions = $DB->get_records_sql($sql, $inparams);

            // We need to get back the files or online-text files depending the kind of plugin that calls function.

            $component = !empty($linkarray['file']) ? 'assignsubmission_file' : 'assignsubmission_text_as_file';

            // We need to retrieve the files for this submission.
            $sql = "SELECT sfile.id, sfile.userid, file.filename, sfile.supported, sfile.reporturl,
                           sfile.similarityscore, sfile.timesubmitted, file.component, sfile.cm, sfile.fileid
                      FROM {plagiarism_safeassign_files} sfile
                      JOIN {files} file ON file.id = sfile.fileid
                     WHERE sfile.submissionid $insql
                       AND file.component = :component";

            $files = $DB->get_records_sql($sql, $inparams + ['component' => $component]);

            // Export submission and files.
            writer::with_context($context)->export_related_data($subcontext, 'safeassign-submissions',
                (object)[
                    'submissions' => $allsubmissions,
                ]);
            writer::with_context($context)->export_related_data($subcontext, 'safeassign-files',
                (object)[
                    'files' => $files]);
        }
    }

    /**
     * Delete all user information for the provided context.
     *
     * @param  \context $context The context to delete user data for.
     */
    public static function _delete_plagiarism_for_context(\context $context) {
        global $DB;

        $validmodnames = ['assign'];

        $moduledata = get_context_info_array($context->id);
        $module = $moduledata[2];

        // Check if we are in a valid module.
        if (in_array($module->modname, $validmodnames)) {

            // Get all submissions.
            $sql = "SELECT DISTINCT asubm.id
                      FROM {assign_submission} asubm
                      JOIN {assign} assign ON assign.id = asubm.assignment
                     WHERE assign.id = :assignid";

            $submissionsids = $DB->get_records_sql($sql, ['assignid' => $module->instance]);

            list($insql, $inparams) = $DB->get_in_or_equal(array_keys($submissionsids));
            $sql = "submissionid $insql";

            $DB->delete_records_select('plagiarism_safeassign_files', $sql, $inparams);
        }
    }

    /**
     * Delete all user information for the provided user and context.
     *
     * @param  int      $userid    The user to delete
     * @param  \context $context   The context to refine the deletion.
     */
    public static function _delete_plagiarism_for_user($userid, \context $context) {
        global $DB;

        $validmodnames = ['assign'];

        $moduledata = get_context_info_array($context->id);
        $module = $moduledata[2];

        // Check if we are in a valid module.
        if (in_array($module->modname, $validmodnames)) {

            // Get the submissionid for the submission of this user.
            $sql = "SELECT asubm.id
                      FROM {assign_submission} asubm
                      JOIN {assign} assign ON assign.id = asubm.assignment
                     WHERE asubm.userid = :userid
                       AND assign.id = :assignid";
            $submissions = $DB->get_records_sql($sql, ['userid' => $userid, 'assignid' => $module->instance]);

            list($insql, $inparams) = $DB->get_in_or_equal(array_keys($submissions), SQL_PARAMS_NAMED);
            $condition = "submissionid $insql AND userid = :userid";

            $DB->delete_records_select('plagiarism_safeassign_files', $condition , $inparams + ['userid' => $userid]);
        }
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid The user to search.
     * @return contextlist $contextlist The contextlist containing the list of contexts used in this plugin.
     */
    public static function _get_contexts_for_userid($userid) {
        $contextlist = new \core_privacy\local\request\contextlist();

        $sql = "SELECT ctx.id
                  FROM {context} ctx
                  JOIN {course} course ON course.id = ctx.instanceid AND ctx.contextlevel = :courselevel
                  JOIN {plagiarism_safeassign_instr} sai ON sai.courseid = course.id
				 WHERE sai.instructorid = :userid";

        $parameters = [
            'userid'     => $userid,
            'courselevel'   => CONTEXT_COURSE
        ];

        $contextlist->add_from_sql($sql, $parameters);

        return $contextlist;

    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for.
     */
    public static function _export_user_data(approved_contextlist $contextlist) {
        global $DB;
        $user = $contextlist->get_user();

        // Get all courses ID's.
        $courseids = [];
        foreach ($contextlist->get_contexts() as $context) {
            if ($context instanceof \context_course) {
                array_push($courseids, $context->instanceid);
            }
        }

        if (empty($courseids)) {
            return;
        }

        list($insql, $inparams) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);

        $sql = "SELECT sai.courseid, sai.instructorid, sai.synced, sai.unenrolled
                  FROM {plagiarism_safeassign_instr} sai
                 WHERE sai.instructorid = :instructorid
                   AND sai.courseid $insql";
        $params = ['instructorid' => $user->id] + $inparams;

        $data = $DB->get_records_sql($sql, $params);

        $courses = [];

        foreach ($data as $courseid => $record) {
            $courses[] = (object) [
                'instructorid' => transform::user($record->instructorid),
                'course' => get_course($record->courseid)->fullname,
                'synced' => transform::yesno($record->synced),
                'unenrolled' => transform::yesno($record->unenrolled),
            ];
        }

        $context = \context_user::instance($user->id);
        writer::with_context($context)->export_data(['plagiarism_safeassign', 'instructor'], (object) [
            'courses' => $courses
        ]);
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param context $context The specific context to delete data for.
     */
    public static function _delete_data_for_all_users_in_context(\context $context) {
        global $DB;

        $courseid = null;

        if ($context instanceof \context_course) {
            $courseid = $context->instanceid;
        }

        if (empty($courseid)) {
            return;
        }

        $instructors = $DB->get_records('plagiarism_safeassign_instr', ['courseid' => $courseid]);
        // Tag instructors in this context to be deleted in next cron execution.
        foreach ($instructors as $instructor) {
            $instructor->unenrolled = 1;
            $DB->update_record('plagiarism_safeassign_instr', $instructor);
        }

    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function _delete_data_for_user(approved_contextlist $contextlist) {

        global $DB;
        $userid = $contextlist->get_user()->id;

        // Get all valid context(courses ID's).
        $courseids = [];
        foreach ($contextlist->get_contexts() as $context) {
            if ($context instanceof \context_course) {
                array_push($courseids, $context->instanceid);
            }
        }

        if (empty($courseids)) {
            return;
        }

        list($insql, $inparams) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);

        $sql = "courseid $insql AND instructorid = :userid";
        $params = array_merge($inparams, ['userid' => $userid]);

        // Get all records for that user where he is an instructor.
        $records = $DB->get_records_select('plagiarism_safeassign_instr', $sql, $params);

        foreach ($records as $record) {
            $record->unenrolled = 1;
            $DB->update_record('plagiarism_safeassign_instr', $record);
        }
    }

}