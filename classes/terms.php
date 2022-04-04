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
 * terms.php - Contains the License Agreement Text for SafeAssign.
 *
 * @package    plagiarism_safeassign
 * @copyright  Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace plagiarism_safeassign;
use plagiarism_safeassign\api\safeassign_api;

/**
 * Class terms
 * @copyright  Copyright (c) 2017 Open LMS (https://www.openlms.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class terms {

    /**
     * Returns the License Agreement for use in the settings form.
     * @return string - License Agreement text.
     */
    public static function get_license_agreement() {
        return 'I accept the license agreement.';
    }

    /**
     * Returns the data of a specific license given the version.
     *
     * @param string $licenseversion
     * @return mixed object/false
     */
    public static function get_specific_license_data($licenseversion) {
        global $USER;

        $listoflicenses = safeassign_api::get_licenses($USER->id);
        if (!empty($listoflicenses)) {
            foreach ($listoflicenses as $license) {
                if ($license->licenseVersion == $licenseversion) {
                    return $license;
                }
            }
        }
        return false;
    }

    /**
     * Returns the data of the current license saved in DB.
     *
     * @return mixed object/false
     */
    public static function get_current_license_data() {
        global $USER;

        $licenseversion = get_config('plagiarism_safeassign', 'safeassign_latest_license_vers');
        $listoflicenses = safeassign_api::get_licenses($USER->id);
        if (!empty($listoflicenses)) {
            foreach ($listoflicenses as $license) {
                if ($license->licenseVersion == $licenseversion) {
                    return $license;
                }
            }
        }
        return false;
    }

}
