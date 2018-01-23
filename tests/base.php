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
 * Base class defines reusable things must place abstract here to avoid CI warning for non existing test.
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use plagiarism_safeassign\api\rest_provider;
use plagiarism_safeassign\api\testhelper;

/**
 * Class plagiarism_safeassign_base_testcase
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class plagiarism_safeassign_base_testcase extends advanced_testcase {
    /**
     * @var string PLUGIN
     */
    const PLUGIN = 'plagiarism_safeassign';

    /**
     * Cleans some variables for testing.
     * @return void
     */
    protected function reset_ws() {
        // Reset some internals.
        rest_provider::instance()->getopts();
        rest_provider::instance()->cleartoken();
        testhelper::reset_stash();
    }

    /**
     * Set configuration values.
     * @return void
     */
    protected function config_set_ok() {
        set_config('safeassign_instructor_username', 'someuser', self::PLUGIN);
        set_config('safeassign_instructor_password', 'somepass', self::PLUGIN);
        set_config('safeassign_student_username', 'someuser', self::PLUGIN);
        set_config('safeassign_student_password', 'somepass', self::PLUGIN);
        set_config('safeassign_api', 'http://safeassign.foo.com', self::PLUGIN);
        set_config('safeassign_curlcache', '3600', self::PLUGIN);
    }

    /**
     * Cleans configuration values.
     * @return void
     */
    protected function config_cleanup() {
        unset_config('safeassign_instructor_username', self::PLUGIN);
        unset_config('safeassign_instructor_password', self::PLUGIN);
        unset_config('safeassign_student_username', self::PLUGIN);
        unset_config('safeassign_student_password', self::PLUGIN);
        unset_config('safeassign_api', self::PLUGIN);
        unset_config('safeassign_curlcache', self::PLUGIN);
    }

    // @codingStandardsIgnoreStart
    /**
     * Provide missing method in Moodle 2.9
     * @param bool $condition
     * @param string $message
     */
    public static function assertNotFalse($condition, $message = '') {
        if (method_exists('PHPUnit_Framework_Assert', 'assertNotFalse')) {
            parent::assertNotFalse($condition, $message);
        } else {
            static::assertThat($condition, static::logicalNot(static::isFalse()), $message);
        }
    }
    // @codingStandardsIgnoreEnd

}
