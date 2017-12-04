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

defined('MOODLE_INTERNAL') || die();

/**
 * Backup class for the SafeAssign plugin.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Blackboard Inc.
 * @licence   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_plagiarism_safeassign_plugin extends backup_plagiarism_plugin {

    /**
     * {@inheritdoc}
     * @return backup_plugin_element
     * @throws base_element_struct_exception
     */
    protected function define_module_plugin_structure() {
        $plugin = $this->get_plugin_element();
        $pluginelement = new backup_nested_element($this->get_recommended_name());
        $plugin->add_child($pluginelement);

        // Add module config elements.
        $safeassignconfigs = new backup_nested_element('safeassign_configs');
        $safeassignconfig = new backup_nested_element('safeassign_config', array('id'), array('name', 'value'));
        $pluginelement->add_child($safeassignconfigs);
        $safeassignconfigs->add_child($safeassignconfig);
        $safeassignconfig->set_source_table('plagiarism_safeassign_config', array('cm' => backup::VAR_PARENTID));

        return $plugin;
    }

    /**
     * {@inheritdoc}
     */
    protected function define_course_plugin_structure() {

    }
}