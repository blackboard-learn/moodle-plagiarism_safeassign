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
 * plagiarism.php - allows the admin to configure plagiarism stuff
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(__FILE__)) . '/../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/plagiarismlib.php');
require_once($CFG->dirroot.'/plagiarism/safeassign/lib.php');
require_once($CFG->dirroot.'/plagiarism/safeassign/plagiarism_form.php');
global $CFG;
require_login();
admin_externalpage_setup('plagiarismsafeassign');
$context = context_system::instance();

require_capability('moodle/site:config', $context, $USER->id, true, "nopermissions");

$mform = new plagiarism_setup_form();
$plagiarismplugin = new plagiarism_plugin_safeassign();
$PAGE->requires->css('/plagiarism/safeassign/styles.css');
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/plagiarism/safeassign/settings.php'));
}
$PAGE->requires->strings_for_js(array('test_credentials'),
    'plagiarism_safeassign');

echo $OUTPUT->header();

if (($data = $mform->get_data()) && confirm_sesskey()) {
    if (!isset($data->safeassign_use)) {
        $data->safeassign_use = 0;
    }
    if (!isset($data->safeassign_showid)) {
        $data->safeassign_showid = 0;
    }
    if (!isset($data->safeassign_alloworganizations)) {
        $data->safeassign_alloworganizations = 0;
    }
    if (!isset($data->safeassign_referencedbactivity)) {
        $data->safeassign_referencedbactivity = 0;
    }
    foreach ($data as $field => $value) {
        if (strpos($field, 'safeassign') === 0) {
            if ($field == 'safeassign_use') {
                set_config($field, $value, 'plagiarism');
            }
            set_config($field, $value, 'plagiarism_safeassign');
        }
        if ($field == 'default_safeassign_api') {
            set_config('safeassign_api', $value, 'plagiarism_safeassign');
        }
    }
    echo $OUTPUT->notification(get_string('savedconfigsuccess', 'plagiarism_safeassign'),
        \core\output\notification::NOTIFY_SUCCESS);
}

$storedurl = get_config('plagiarism_safeassign', 'safeassign_api');
$PAGE->requires->js_call_amd('plagiarism_safeassign/settings', 'init', array($storedurl));

$plagiarismsettings = (array)get_config('plagiarism_safeassign');
$mform->set_data($plagiarismsettings);

echo $OUTPUT->box_start('generalbox boxaligncenter', 'intro');

if (!empty($CFG->plagiarism_safeassign_urls)) {
    $urls = array_filter(array_column($CFG->plagiarism_safeassign_urls, 'url'));
    if (!empty($urls)) {
        $mform->display();
    } else {
        echo $OUTPUT->notification(get_string('not_configured', 'plagiarism_safeassign'), \core\output\notification::NOTIFY_ERROR);
    }
} else {
    echo $OUTPUT->notification(get_string('not_configured', 'plagiarism_safeassign'), \core\output\notification::NOTIFY_ERROR);
}

echo $OUTPUT->box_end();
echo $OUTPUT->footer();