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

require_once(__DIR__.'/base.php');

use plagiarism_safeassign\api\safeassign_api;
use plagiarism_safeassign\api\testhelper;

/**
 * Class plagiarism_safeassign_safeassign_api_testcase
 *
 * All tests in this class will fail in case there is no appropriate fixture to be loaded.
 *
 * @group plagiarism_safeassign
 */
class plagiarism_safeassign_safeassign_api_testcase extends plagiarism_safeassign_base_testcase {
    /**
     * @return void
     */
    public function setUp() {
        $this->reset_ws();
    }

    /**
     * @param $user
     * @return string
     */
    private function create_login_url($user) {
        $baseapiurl = get_config('plagiarism_safeassign', 'safeassign_api');
        $loginurl = '%s/api/v1/tokens?';
        $loginurl .= 'grant_type=client_credentials&user_id=%s&user_firstname=%s&user_lastname=%s';
        $loginurl = sprintf($loginurl, $baseapiurl, $user->id, $user->firstname, $user->lastname);
        return $loginurl;
    }

    /**
     * @return void
     */
    public function test_login_configured_ok() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $user = $this->getDataGenerator()->create_user([
            'firstname' => 'Teacher',
            'lastname' => 'WhoTeaches'
        ]);

        // Tell the cache to load specific fixture for login url.
        $loginurl = $this->create_login_url($user);
        testhelper::push_pair($loginurl, 'user-login-final.json');
        $result = safeassign_api::login($user->id);
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function test_login_fail() {
        $this->resetAfterTest(true);
        $this->config_set_ok();

        $user = $this->getDataGenerator()->create_user([
            'firstname' => 'Teacher',
            'lastname' => 'WhoTeaches'
        ]);

        // Tell the cache to load specific fixture for login url.
        $loginurl = $this->create_login_url($user);
        testhelper::push_pair($loginurl, 'user-login-fail-final.json');
        $result = safeassign_api::login($user->id);
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function test_login_notconfigured_fail() {
        $this->resetAfterTest(true);
        $this->config_cleanup();

        $user = $this->getDataGenerator()->create_user();
        $this->assertFalse( safeassign_api::login($user->id) );
    }

}
