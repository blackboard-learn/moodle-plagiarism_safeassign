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
 * Convenient wrappers and helper for using the SafeAssign web service API.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\api;

/**
 * Class memfile
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class memfile {
    /**
     * @var null|resource
     */
    private $fp = null;

    /**
     * memfile constructor.
     */
    public function __construct() {
        $this->open();
    }

    /**
     * Opens a file.
     */
    protected function open() {
        $fp = fopen('php://temp', 'wb+');
        if (is_resource($fp)) {
            $this->fp = $fp;
        }
    }

    /**
     * Closes the file.
     */
    public function __destruct() {
        $this->close();
    }

    /**
     * Gets the pointer to the file.
     * @return null|resource
     */
    public function get() {
        return $this->fp;
    }

    /**
     * Gets the content of the file.
     * @return null|string
     */
    public function get_content() {
        $content = null;
        if (is_resource($this->fp)) {
            rewind($this->fp);
            $content = stream_get_contents($this->fp);
        }
        return $content;
    }

    /**
     * Closes the file.
     */
    public function close() {
        if (is_resource($this->fp)) {
            fclose($this->fp);
            $this->fp = null;
        }
    }

    /**
     * Resets the file.
     */
    public function reset() {
        $this->close();
        $this->open();
    }
}
