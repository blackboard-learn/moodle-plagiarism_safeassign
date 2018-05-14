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
 * Test safeassign functions created for license feature.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $CFG, $DB;

require_once($CFG->dirroot . '/plagiarism/safeassign/lib.php');
require_once($CFG->dirroot . '/mod/assign/tests/base_test.php');
require_once(__DIR__.'/base.php');

use plagiarism_safeassign\api\safeassign_api;
use plagiarism_safeassign\api\testhelper;
use plagiarism_safeassign\api\test_safeassign_api_connectors;

/**
 * Class plagiarism_safeassign_license_testcase
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plagiarism_safeassign_license_testcase extends plagiarism_safeassign_base_testcase {

    /**
     * @var stdClass $user
     */
    private $user;

    protected function setUp() {
        global $USER;

        $this->resetAfterTest();
        $this->config_set_ok();
        set_config('safeassign_use', 1, 'plagiarism');
        $this->setAdminUser();
        $this->user = $USER;
    }

    /**
     * Test that the license data is correctly updated in config_plugins table.
     */
    public function test_accept_license() {
        // Add the license data.
        set_config('safeassign_latest_license_vers', '1.0', 'plagiarism_safeassign');
        set_config('safeassign_license_agreement_status', 0, 'plagiarism_safeassign');

        // Add the admin data required to accept license.
        set_config('safeassign_license_acceptor_givenname', 'admin', 'plagiarism_safeassign');
        set_config('safeassign_license_acceptor_surname', 'user', 'plagiarism_safeassign');
        set_config('safeassign_license_acceptor_email', 'adminuser@example.com', 'plagiarism_safeassign');
        set_config('safeassign_license_agreement_adminid', $this->user->id, 'plagiarism_safeassign');

        // The admin read the license agreement and accepts it.
        set_config('safeassign_license_agreement_readbyadmin', 1, 'plagiarism_safeassign');

        $loginurl = test_safeassign_api_connectors::create_login_url($this->user);
        testhelper::push_pair($loginurl, 'user-login-final.json');
        safeassign_api::login($this->user->id);
        $acceptlicenseurl = test_safeassign_api_connectors::create_accept_license_url();
        testhelper::push_pair($acceptlicenseurl, 'empty-file.json');
        $safeassign = new plagiarism_plugin_safeassign();
        $safeassign->accept_safeassign_license();

        $licstatus = get_config('plagiarism_safeassign', 'safeassign_license_agreement_status');
        $this->assertEquals(1, $licstatus);

        // Now let's clean the data.
        $safeassign->clean_safeassign_license_data();
        $flagstatus = get_config('plagiarism_safeassign', 'safeassign_license_agreement_readbyadmin');
        $licstatus = get_config('plagiarism_safeassign', 'safeassign_license_agreement_status');
        $this->assertEquals(0, $licstatus);
        $this->assertEquals(0, $flagstatus);

        // If I try to accept license again, the status must not change because the flag value is zero.
        // The flag in 0 means that the admin has not read the license terms and conditions.
        $safeassign->accept_safeassign_license();
        $licstatus = get_config('plagiarism_safeassign', 'safeassign_license_agreement_status');
        $this->assertEquals(0, $licstatus);
    }
}