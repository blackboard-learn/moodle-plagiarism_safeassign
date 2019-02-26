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
 * SafeAssign upgrade function.
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/plagiarism/safeassign/lib.php');

/**
 * Updates SafeAssign data model.
 * @param int $oldversion
 * @return bool
 */
function xmldb_plagiarism_safeassign_upgrade($oldversion) {

    global $CFG, $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2017080701) {

        // Define fields to be added to plagiarism_safeassign_files.
        $table = new xmldb_table('plagiarism_safeassign_files');
        $fields[] = new xmldb_field('uuid', XMLDB_TYPE_CHAR, '36', null, null, null, null, 'userid');
        $fields[] = new xmldb_field('supported', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'timesubmitted');
        $fields[] = new xmldb_field('submissionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'supported');
        $fields[] = new xmldb_field('fileid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'submissionid');

        // Go through each field and add if it doesn't already exist.
        foreach ($fields as $field) {
            // Conditionally launch add field.
            if (!$dbman->field_exists($table, $field)) {
                $dbman->add_field($table, $field);
            }
        }

        $key = new xmldb_key('submissionid', XMLDB_KEY_FOREIGN, array('submissionid'), 'assign_submission', array('id'));

        // Launch add key submissionid.
        $dbman->add_key($table, $key);

        $key = new xmldb_key('fileid', XMLDB_KEY_FOREIGN, array('fileid'), 'files', array('id'));

        // Launch add key fileid.
        $dbman->add_key($table, $key);

        // Changing type of field similarityscore on table plagiarism_safeassign_files to number.
        $field = new xmldb_field('similarityscore', XMLDB_TYPE_NUMBER, '3, 2', null, null, null, '0', 'reporturl');

        // Launch change of type for field similarityscore.
        $dbman->change_field_type($table, $field);

        // Define fields to be dropped from plagiarism_safeassign_files.
        $table = new xmldb_table('plagiarism_safeassign_files');
        $fields = [];
        $fields[] = new xmldb_field('identifier');
        $fields[] = new xmldb_field('filename');
        $fields[] = new xmldb_field('optout');
        $fields[] = new xmldb_field('statuscode');
        $fields[] = new xmldb_field('attempt');
        $fields[] = new xmldb_field('errorresponse');

        // Go through each field and drop if it exist.
        foreach ($fields as $field) {
            // Conditionally launch drop field.
            if ($dbman->field_exists($table, $field)) {
                $dbman->drop_field($table, $field);
            }
        }

        // Safeassign savepoint reached.
        upgrade_plugin_savepoint(true, 2017080701, 'plagiarism', 'safeassign');
    }

    if ($oldversion < 2017080702) {

        // Define table plagiarism_safeassign_course to be created.
        $table = new xmldb_table('plagiarism_safeassign_course');

        // Adding fields to table plagiarism_safeassign_course.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('uuid', XMLDB_TYPE_CHAR, '36', null, null, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table plagiarism_safeassign_course.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('courseid', XMLDB_KEY_FOREIGN, array('courseid'), 'course', array('id'));

        // Conditionally launch create table for plagiarism_safeassign_course.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Safeassign savepoint reached.
        upgrade_plugin_savepoint(true, 2017080702, 'plagiarism', 'safeassign');
    }

    if ($oldversion < 2017080703) {

        // Define table plagiarism_safeassign_assign to be created.
        $table = new xmldb_table('plagiarism_safeassign_assign');

        // Adding fields to table plagiarism_safeassign_assign.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('uuid', XMLDB_TYPE_CHAR, '36', null, null, null, null);
        $table->add_field('assignmentid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table plagiarism_safeassign_assign.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('assignmentid', XMLDB_KEY_FOREIGN, array('assignmentid'), 'assign', array('id'));

        // Conditionally launch create table for plagiarism_safeassign_assign.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Safeassign savepoint reached.
        upgrade_plugin_savepoint(true, 2017080703, 'plagiarism', 'safeassign');
    }

    if ($oldversion < 2017080704) {

        // Define table plagiarism_safeassign_subm to be created.
        $table = new xmldb_table('plagiarism_safeassign_subm');

        // Adding fields to table plagiarism_safeassign_subm.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('uuid', XMLDB_TYPE_CHAR, '36', null, null, null, null);
        $table->add_field('globalcheck', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('groupsubmission', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('highscore', XMLDB_TYPE_NUMBER, '3, 2', null, null, null, null);
        $table->add_field('avgscore', XMLDB_TYPE_NUMBER, '3, 2', null, null, null, null);
        $table->add_field('submitted', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('reportgenerated', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('submissionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table plagiarism_safeassign_subm.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('submissionid', XMLDB_KEY_FOREIGN, array('submissionid'), 'assign_submission', array('id'));

        // Conditionally launch create table for plagiarism_safeassign_subm.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Safeassign savepoint reached.
        upgrade_plugin_savepoint(true, 2017080704, 'plagiarism', 'safeassign');
    }

    if ($oldversion < 2017081505) {

        // Define field deprecated to be added to plagiarism_safeassign_subm.
        $table = new xmldb_table('plagiarism_safeassign_subm');
        $fields = [];
        $fields[] = new xmldb_field('deprecated', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'submissionid');
        $fields[] = new xmldb_field('hasfile', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'deprecated');
        $fields[] = new xmldb_field('hasonlinetext', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'hasfile');
        $fields[] = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '14', null, XMLDB_NOTNULL, null, '0', 'hasonlinetext');

        // Go through each field and add if it doesn't already exist.
        foreach ($fields as $field) {
            // Conditionally launch add field.
            if (!$dbman->field_exists($table, $field)) {
                $dbman->add_field($table, $field);
            }
        }

        // Safeassign savepoint reached.
        upgrade_plugin_savepoint(true, 2017081505, 'plagiarism', 'safeassign');
    }

    if ($oldversion < 2017081507) {

        if (!get_config('plagiarism_safeassign', 'connecttimeout')) {
            set_config('connecttimeout', 600, 'plagiarism_safeassign');
        }

        upgrade_plugin_savepoint(true, 2017081507, 'plagiarism', 'safeassign');
    }

    if ($oldversion < 2017091107) {

        // Define field instructorid to be added to plagiarism_safeassign_course.
        $table = new xmldb_table('plagiarism_safeassign_course');
        $field = new xmldb_field('instructorid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0, 'courseid');

        // Conditionally launch add field instructorid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Safeassign savepoint reached.
        upgrade_plugin_savepoint(true, 2017091107, 'plagiarism', 'safeassign');
    }

    if ($oldversion < 2017111556) {

        // Define field courseid to be added to plagiarism_safeassign_assign.
        $table = new xmldb_table('plagiarism_safeassign_assign');
        $field = new xmldb_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'assignmentid');

        // Conditionally launch add field courseid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define key courseid (foreign) to be added to plagiarism_safeassign_assign.
        $key = new xmldb_key('courseid', XMLDB_KEY_FOREIGN, array('courseid'), 'course', array('id'));

        // Launch add key courseid.
        $dbman->add_key($table, $key);

        // Define field assignmentid to be added to plagiarism_safeassign_subm.
        $table = new xmldb_table('plagiarism_safeassign_subm');
        $field = new xmldb_field('assignmentid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'timecreated');

        // Conditionally launch add field assignmentid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $key = new xmldb_key('assignmentid', XMLDB_KEY_FOREIGN, array('assignmentid'), 'assignment', array('id'));

        // Launch add key assignmentid.
        $dbman->add_key($table, $key);

        // Define field deleted to be added to plagiarism_safeassign_subm.
        $field = new xmldb_field('deleted', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'assignmentid');

        // Conditionally launch add field deleted.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Safeassign savepoint reached.
        upgrade_plugin_savepoint(true, 2017111556, 'plagiarism', 'safeassign');
    }

    if ($oldversion < 2017111558) {

        // License text updated, SafeAssign should be disabled until agree the new one.
        set_config('safeassign_use', 0, 'plagiarism_safeassign');
        upgrade_plugin_savepoint(true, 2017111558, 'plagiarism', 'safeassign');
    }

    if ($oldversion < 2017121502) {

        // Define table plagiarism_safeassign_instr to be created.
        $table = new xmldb_table('plagiarism_safeassign_instr');

        // Adding fields to table plagiarism_safeassign_instr.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('instructorid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('synced', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('unenrolled', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('deleted', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table plagiarism_safeassign_instr.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('courseid', XMLDB_KEY_FOREIGN, array('courseid'), 'course', array('id'));
        $table->add_key('instructorid', XMLDB_KEY_FOREIGN, array('instructorid'), 'user', array('id'));

        // Conditionally launch create table for plagiarism_safeassign_instr.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Safeassign savepoint reached.
        upgrade_plugin_savepoint(true, 2017121502, 'plagiarism', 'safeassign');
    }

    if ($oldversion < 2017121503) {

        $table = new xmldb_table('plagiarism_safeassign_instr');
        // Update the 'plagiarism_safeassign_instr' table with course instructors and site admins.
        if ($dbman->table_exists($table)) {
            set_config('siteadmins', $CFG->siteadmins, 'plagiarism_safeassign');
            $safeassign = new plagiarism_plugin_safeassign();
            $safeassign->set_course_instructors();
        }

        // Safeassign savepoint reached.
        upgrade_plugin_savepoint(true, 2017121503, 'plagiarism', 'safeassign');
    }

    if ($oldversion < 2017121504) {

        // New setting field to store the record id for system roles which will be synced as instructors on each course.
        set_config('safeassign_additional_roles', '', 'plagiarism_safeassign');
        set_config('safeassign_synced_roles', '', 'plagiarism_safeassign');

        // Safeassign savepoint reached.
        upgrade_plugin_savepoint(true, 2017121504, 'plagiarism', 'safeassign');
    }

    if ($oldversion < 2017121508) {

        // New setting field to store the new version and status of SafeAssign license.
        set_config('safeassign_latest_license_vers', '0.2', 'plagiarism_safeassign');
        set_config('safeassign_license_agreement_status', 0, 'plagiarism_safeassign');

        // Safeassign savepoint reached.
        upgrade_plugin_savepoint(true, 2017121508, 'plagiarism', 'safeassign');
    }

    if ($oldversion < 2019030100) {
        // New database schema implemented. Old data is restored to new schema if needed.
        // Old data will be preserved in tables renamed as this:
        // plagiarism_safeassign_assign -> plagiarism_safeassign_old_a.
        // plagiarism_safeassign_submission -> plagiarism_safeassign_old_s.
        // plagiarism_safeassign_course -> plagiarism_safeassign_old_c.
        // plagiarism_safeassign_files -> plagiarism_safeassign_old_f.
        // Old data for other tables will remain due no changes on tables structure.

        // SafeAssign assignments table.
        // Old table: plagiarism_safeassign_assign.
        // New table: plagiarism_safeassign_mod.

        // Update records from old assign table to new mod table.
        $table = new xmldb_table('plagiarism_safeassign_assign');
        $oldrecords = null;
        if ($dbman->table_exists($table)) {
            $oldrecords = $DB->get_records('plagiarism_safeassign_assign');
            // Rename original table.
            $dbman->rename_table($table, 'plagiarism_safeassign_old_a');
        }

        // Define table plagiarism_safeassign_mod to be created.
        $table = new xmldb_table('plagiarism_safeassign_mod');
        // Adding fields to table plagiarism_safeassign_mod.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('uuid', XMLDB_TYPE_CHAR, '36', null, null, null, null);
        $table->add_field('moduleid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('instanceid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('cmid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        // Adding keys to table plagiarism_safeassign_mod.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('courseid', XMLDB_KEY_FOREIGN, ['courseid'], 'course', ['id']);
        $table->add_key('moduleid', XMLDB_KEY_FOREIGN, ['moduleid'], 'modules', ['id']);
        $table->add_key('cmid', XMLDB_KEY_FOREIGN, ['cmid'], 'course_modules', ['id']);

        // Conditionally launch create table for plagiarism_safeassign_mod.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // If there are old records, update new table with old records.
        if (!empty($oldrecords)) {
            $assignmoduleid = $DB->get_field("modules", "id", ["name" => "assign"]);
            $newrecords = [];
            foreach ($oldrecords as $oldrecord) {
                $newrecord = new stdClass();
                $newrecord->uuid = $oldrecord->uuid;
                $newrecord->moduleid = $assignmoduleid;
                $newrecord->courseid = $oldrecord->courseid;
                $newrecord->instanceid = $oldrecord->assignmentid;
                $newrecord->cmid = $DB->get_field("course_modules", "id",
                    ["course" => $newrecord->courseid, "module" => $assignmoduleid, "instance" => $newrecord->instanceid]);

                array_push($newrecords, $newrecord);
            }
            if (!empty($newrecords)) {
                $DB->insert_records('plagiarism_safeassign_mod', $newrecords);
            }
        }

        // SafeAssign configuration table has no changes.

        // SafeAssign courses table.
        $table = new xmldb_table('plagiarism_safeassign_course');
        $oldrecords = null;
        if ($dbman->table_exists('plagiarism_safeassign_course')) {
            $oldrecords = $DB->get_records('plagiarism_safeassign_course');
            // Rename original table.
            $dbman->rename_table($table, 'plagiarism_safeassign_old_c');
        }

        // Create new course table.
        $table = new xmldb_table('plagiarism_safeassign_course');
        // Adding fields to table plagiarism_safeassign_course.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('uuid', XMLDB_TYPE_CHAR, '36', null, null, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('creatorid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        // Adding keys to table plagiarism_safeassign_course.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('creatorid', XMLDB_KEY_FOREIGN, array('creatorid'), 'user', array('id'));

        // Conditionally launch create table for plagiarism_safeassign_course.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        // If there are old records, update new table with old records.
        if (!empty($oldrecords)) {
            $newrecords = [];
            foreach ($oldrecords as $oldrecord) {
                $newrecord = new stdClass();
                $newrecord->uuid = $oldrecord->uuid;
                $newrecord->courseid = $oldrecord->courseid;
                $newrecord->creatorid = $oldrecord->instructorid;
                array_push($newrecords, $newrecord);
            }
            if (!empty($newrecords)) {
                $DB->insert_records('plagiarism_safeassign_course', $newrecords);
            }
        }

        // SafeAssign files table.
        $table = new xmldb_table('plagiarism_safeassign_files');
        $oldrecords = null;
        if ($dbman->table_exists('plagiarism_safeassign_files')) {
            $oldrecords = $DB->get_records('plagiarism_safeassign_files');
        }

        // Define table plagiarism_safeassign_old_f to be created. This table will hold the old files if there are some.
        $table = new xmldb_table('plagiarism_safeassign_old_f');

        // Adding fields to table plagiarism_safeassign_old_f.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('cm', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('olduserid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('uuid', XMLDB_TYPE_CHAR, '36', null, null, null, null);
        $table->add_field('reporturl', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('similarityscore', XMLDB_TYPE_NUMBER, '3, 2', null, null, null, '0');
        $table->add_field('timesubmitted', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('supported', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('submissionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('fileid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table plagiarism_safeassign_old_f.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('cm', XMLDB_KEY_FOREIGN, array('cm'), 'course_modules', array('id'));
        $table->add_key('submissionid', XMLDB_KEY_FOREIGN, array('submissionid'), 'assign_submission', array('id'));
        $table->add_key('fileid', XMLDB_KEY_FOREIGN, array('fileid'), 'files', array('id'));

        // Conditionally launch create table for plagiarism_safeassign_old_f.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // If there are old records, save records.
        if (!empty($oldrecords)) {
            $newrecords = [];
            foreach ($oldrecords as $oldrecord) {
                $newrecord = new stdClass();
                $newrecord->cm = $oldrecord->cm;
                $newrecord->olduserid = $oldrecord->userid;
                $newrecord->uuid = $oldrecord->uuid;
                $newrecord->reporturl = $oldrecord->reporturl;
                $newrecord->similarityscore = $oldrecord->similarityscore;
                $newrecord->timesubmitted = $oldrecord->timesubmitted;
                $newrecord->supported = $oldrecord->supported;
                $newrecord->submissionid = $oldrecord->submissionid;
                $newrecord->fileid = $oldrecord->fileid;
                array_push($newrecords, $newrecord);
            }
            if (!empty($newrecords)) {
                $DB->insert_records('plagiarism_safeassign_sa_f', $newrecords);
            }
        }

        // Define table plagiarism_safeassign_files to be created.
        $table = new xmldb_table('plagiarism_safeassign_files');
        $dbman->drop_table($table);

        // Adding fields to table plagiarism_safeassign_files.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('uuid', XMLDB_TYPE_CHAR, '36', null, null, null, null);
        $table->add_field('reporturl', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('similarityscore', XMLDB_TYPE_NUMBER, '3, 2', null, null, null, '0');
        $table->add_field('timesubmitted', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('supported', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('submissionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('fileid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table plagiarism_safeassign_files.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('submissionid', XMLDB_KEY_FOREIGN, array('submissionid'), 'assign_submission', array('id'));
        $table->add_key('fileid', XMLDB_KEY_FOREIGN, array('fileid'), 'files', array('id'));

        // Conditionally launch create table for plagiarism_safeassign_files.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // If there are old records, update new table with old records.
        if (!empty($oldrecords)) {
            $newrecords = [];
            foreach ($oldrecords as $oldrecord) {
                $newrecord = new stdClass();
                $newrecord->uuid = $oldrecord->uuid;
                $newrecord->reporturl = $oldrecord->reporturl;
                $newrecord->similarityscore = $oldrecord->similarityscore;
                $newrecord->timesubmitted = $oldrecord->timesubmitted;
                $newrecord->supported = $oldrecord->supported;
                $newrecord->submissionid = $oldrecord->submissionid;
                $newrecord->fileid = $oldrecord->fileid;
                array_push($newrecords, $newrecord);
            }
            if (!empty($newrecords)) {
                $DB->insert_records('plagiarism_safeassign_files', $newrecords);
            }
        }

        // SafeAssign instructors table has no changes.

        // SafeAssign submissions table.
        $table = new xmldb_table('plagiarism_safeassign_subm');
        $oldrecords = null;
        if ($dbman->table_exists('plagiarism_safeassign_subm')) {
            $oldrecords = $DB->get_records('plagiarism_safeassign_subm');
            // Rename original table.
            $dbman->rename_table($table, 'plagiarism_safeassign_old_s');
        }

        // Define table plagiarism_safeassign_subm to be created.
        $table = new xmldb_table('plagiarism_safeassign_subm');

        // Adding fields to table plagiarism_safeassign_subm.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('uuid', XMLDB_TYPE_CHAR, '36', null, null, null, null);
        $table->add_field('globalcheck', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('groupsubmission', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('highscore', XMLDB_TYPE_NUMBER, '3, 2', null, null, null, null);
        $table->add_field('avgscore', XMLDB_TYPE_NUMBER, '3, 2', null, null, null, null);
        $table->add_field('submitted', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('reportgenerated', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('submissionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('status', XMLDB_TYPE_CHAR, '10', null, null, null, null);
        $table->add_field('type', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('cmid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table plagiarism_safeassign_subm.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('submissionid', XMLDB_KEY_FOREIGN, array('submissionid'), 'assign_submission', array('id'));
        $table->add_key('cmid', XMLDB_KEY_FOREIGN, array('cmid'), 'course_modules', array('id'));
        $table->add_key('userid', XMLDB_KEY_FOREIGN, array('userid'), 'user', array('id'));

        // Conditionally launch create table for plagiarism_safeassign_subm.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        // If there are old records, update new table with old records.
        if (!empty($oldrecords)) {
            $assignmoduleid = $DB->get_field("modules", "id", ["name" => "assign"]);
            $newrecords = [];
            foreach ($oldrecords as $oldrecord) {
                $newrecord = new stdClass();
                $newrecord->uuid = $oldrecord->uuid;
                $newrecord->globalcheck = $oldrecord->globalcheck;
                $newrecord->groupsubmission = $oldrecord->groupsubmission;
                $newrecord->highscore = $oldrecord->highscore;
                $newrecord->avgscore = $oldrecord->avgscore;
                $newrecord->submitted = $oldrecord->submitted;
                $newrecord->reportgenerated = $oldrecord->reportgenerated;
                $newrecord->submissionid = $oldrecord->submissionid;
                $newrecord->status = $oldrecord->uuid ?
                    \plagiarism_safeassign\api\safeassign_submission::STATUS_SUBMISSION_SUBMITTED :
                    \plagiarism_safeassign\api\safeassign_submission::STATUS_SUBMISSION_RESTORED;
                $newrecord->type = $oldrecord->hasfile && $oldrecord->hasonlinetext ?
                    \plagiarism_safeassign\api\safeassign_submission::TYPE_SUBMISSION_FILE_AND_TEXT :
                    $oldrecord->hasfile ? \plagiarism_safeassign\api\safeassign_submission::TYPE_SUBMISSION_FILE :
                    $oldrecord->hasonlinetext ? \plagiarism_safeassign\api\safeassign_submission::TYPE_SUBMISSION_ONLINETEXT : 0;

                // In old versions, SafeAssign only get info from assign submissions.
                $newrecord->cmid = $DB->get_field('course_modules', 'id',
                    ['module' => $assignmoduleid, 'instance' => $oldrecord->submissionid]);
                $newrecord->userid = $DB->get_field('assign_submission', 'userid', ['id' => $oldrecord->submissionid]);
                array_push($newrecords, $newrecord);
            }
            if (!empty($newrecords)) {
                $DB->insert_records('plagiarism_safeassign_subm', $newrecords);
            }
        }

        // Safeassign savepoint reached.
        upgrade_plugin_savepoint(true, 2019030100, 'plagiarism', 'safeassign');
    }

    return true;
}