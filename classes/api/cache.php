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
 * MUC support.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\api;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/filelib.php');
use plagiarism_safeassign\local;
/**
 * Class cache
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cache {
    /**
     * @var \cache_application|null
     *
     */
    protected $muc = null;

    /**
     * cache constructor.
     */
    public function __construct() {
        if (local::duringtesting()) {
            return;
        }
        $this->muc = \cache::make('plagiarism_safeassign', 'request');
    }

    /**
     * Get the cache timeout.
     * @return int
     */
    public static function cache_timeout() {
        $result = (int)get_config('plagiarism_safeassign', 'safeassign_curlcache');
        return $result;
    }

    /**
     * Get the cache timeout in hours.
     * @return int
     */
    public static function cache_timeout_hours() {
        $result = (int)round(self::cache_timeout() / HOURSECS);
        return $result;
    }

    /**
     * Calculates the md5 of a string.
     * @param  mixed  $seed
     * @return string
     */
    protected function getkeyname($seed) {
        $result = md5($seed);
        return $result;
    }

    /**
     * Get keys.
     * @param mixed $seed
     * @return array
     */
    protected function getkeys($seed) {
        $basekeyname = $this->getkeyname($seed);
        $result = [$basekeyname, $basekeyname.'_created'];
        return $result;
    }

    /**
     * Retrieves elements from cache.
     * @param mixed $param
     * @return bool|string
     */
    public function get($param) {
        if (local::duringtesting()) {
            $result = testhelper::get_fixture_data($param);
            if ($result) {
                rest_provider::instance()->settoken(0, 'sometokenvalue');
            }
            return $result;
        }
        $result = false;
        if (self::cache_timeout() == 0) {
            return $result;
        }
        $keyarray = $this->getkeys($param);
        list($key, $created) = $keyarray;
        $items = $this->muc->get_many($keyarray);
        if (!empty($items[$key])) {
            $ellapsedtime = time() - $items[$created];
            if ((!isset($items[$key.'_timeout']) && $ellapsedtime > self::cache_timeout()) ||
                (isset($items[$key.'_timeout']) && $ellapsedtime > $items[$key.'_timeout'])) {
                $this->muc->delete_many($keyarray);
                return $result;
            }
        }
        if (!empty($items[$key])) {
            $result = unserialize($items[$key]);
        }
        return $result;
    }

    /**
     * Stores a value in the cache.
     * @param mixed $param
     * @param mixed $val
     * @param bool|int $timeout False if using default timeout, secs otherwise
     * @return void
     */
    public function set($param, $val, $timeout = false) {
        if (local::duringtesting()) {
            if ($timeout) {
                testhelper::set_timed_value($param, $val, $timeout);
            }
            return;
        }
        if (self::cache_timeout() > 0) {
            list($key, $created) = $this->getkeys($param);
            $mucarr = [$key => serialize($val), $created => time()];
            if ($timeout !== false) {
                $mucarr[$key.'_timeout'] = $timeout;
            }
            $this->muc->set_many($mucarr);
        }
    }

    /**
     * Deletes element from the cache.
     * @param mixed $param
     * @return int
     */
    public function delete($param) {
        if (local::duringtesting() || $this->get($param) == false) {
            return 0;
        }
        return $this->muc->delete_many($this->getkeys($param));
    }

    /**
     * Purges cache.
     * @return bool
     */
    public function refresh() {
        if (local::duringtesting()) {
            return false;
        }
        return $this->muc->purge();
    }
}
