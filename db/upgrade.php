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

    if ($oldversion < 2019013100) {
        // New database schema implemented. Old data is restored to new schema if needed.

        // SafeAssign assignments table.
        // Old table: plagiarism_safeassign_assign.
        // New table: plagiarism_safeassign_mod.

        // Update records from old assign table to new mod table.
        $table = new xmldb_table('plagiarism_safeassign_assign');
        $oldrecords = null;
        if ($dbman->table_exists($table)) {
            $oldrecords = $DB->get_records('plagiarism_safeassign_assign');
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
        // Change in name from instructorid to creatorid.
        $table = new xmldb_table('plagiarism_safeassign_course');
        $field = new xmldb_field('instructorid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'courseid');
        // Launch rename field instructorid.
        $dbman->rename_field($table, $field, 'creatorid');
        $key = new xmldb_key('creatorid', XMLDB_KEY_FOREIGN, ['creatorid'], 'user', ['id']);
        // Launch add key creatorid.
        $dbman->add_key($table, $key);

        // SafeAssign files table.
        // Fields to remove: cm, userid.
        $table = new xmldb_table('plagiarism_safeassign_files');
        // Drop keys cm, userid.
        $key = new xmldb_key('cm', XMLDB_KEY_FOREIGN, ['cm'], 'course_modules', ['id']);
        $dbman->drop_key($table, $key);
        $key = new xmldb_key('userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);
        $dbman->drop_key($table, $key);
        // Drop fields.
        $fieldstoremove = ['cm', 'userid'];
        foreach ($fieldstoremove as $fieldname) {
            $field = new xmldb_field($fieldname);
            // Conditionally launch drop field.
            if ($dbman->field_exists($table, $field)) {
                $dbman->drop_field($table, $field);
            }
        }

        // SafeAssign instructors table has no changes.

        // SafeAssign submissions table.
        // Fields to remove: deprecated, hasfile, hasonlinetext, timecreated, assignmentid.
        $table = new xmldb_table('plagiarism_safeassign_subm');
        // Drop key assignmentid.
        $key = new xmldb_key('assignmentid', XMLDB_KEY_FOREIGN, ['assignmentid'], 'assignment', ['id']);
        $dbman->drop_key($table, $key);
        $fieldstoremove = ['deprecated', 'hasfile', 'hasonlinetext', 'timecreated', 'assignmentid', 'deleted'];
        foreach ($fieldstoremove as $fieldname) {
            $field = new xmldb_field($fieldname);
            // Conditionally launch drop field.
            if ($dbman->field_exists($table, $field)) {
                $dbman->drop_field($table, $field);
            }
        }

        // Fields to add: status, type, cmid, userid.
        $fieldstoadd = [];
        array_push($fieldstoadd, new xmldb_field('status', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'submissionid'));
        array_push($fieldstoadd, new xmldb_field('type', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, '0', 'status'));
        array_push($fieldstoadd, new xmldb_field('cmid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'type'));
        array_push($fieldstoadd, new xmldb_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'cmid'));

        foreach ($fieldstoadd as $field) {
            // Conditionally launch add field.
            if (!$dbman->field_exists($table, $field)) {
                $dbman->add_field($table, $field);
            }
        }

        // Keys to add: cmid, userid.
        $keystoadd = [];
        array_push($keystoadd, new xmldb_key('cmid', XMLDB_KEY_FOREIGN, ['cmid'], 'course_modules', ['id']));
        array_push($keystoadd, new xmldb_key('userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']));

        foreach ($keystoadd as $key) {
            // Launch add key.
            $dbman->add_key($table, $key);
        }

        // Remove old assign table.
        $table = new xmldb_table('plagiarism_safeassign_assign');
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Safeassign savepoint reached.
        upgrade_plugin_savepoint(true, 2019013100, 'plagiarism', 'safeassign');
    }

    return true;
}