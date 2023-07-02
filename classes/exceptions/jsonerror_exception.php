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
 * @author    Darko Miletic
 * @copyright Copyright (c) 2017 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\api;

/**
 * Class jsonerror_exception
 *
 * @package plagiarism_safeassign
 * @copyright Copyright (c) 2017 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class jsonerror_exception extends \Exception {
    /**
     * jsonerror_exception constructor.
     */
    public function __construct() {
        $error = 'No Error!';
        $errornr = 0;
        if (json_last_error()) {
            $error = json_last_error_msg();
            $errornr = json_last_error();
        }
        parent::__construct($error, $errornr);
    }
}
