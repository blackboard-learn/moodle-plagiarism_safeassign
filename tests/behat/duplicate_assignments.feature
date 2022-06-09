# This file is part of Moodle - http://moodle.org/
#
# Moodle is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# Moodle is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
#
# Tests for duplicating assignments using SafeAssign plagiarism plugin
#
# @package    plagiarism_safeassign
# @copyright  Copyright (c) 2017 Open LMS (https://www.openlms.net)
# @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

@plugin @plagiarism_safeassign
Feature: Duplicate an assignment with SafeAssign parameters
  As a Teacher
  I should be able to duplicate an assignment with SafeAssign as plagiarism plugin

  Background:
    Given the following config values are set as admin:
      | enableplagiarism | 1 |
    And the following config values are set as admin:
      | safeassign_api | http://safeassign.foo.com | plagiarism_safeassign |
    And the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 1 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher@example.com |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | teacher1 | C1     | editingteacher |
    And the following config values are set as admin:
      | enabled   | 1 | plagiarism_safeassign |
    Then I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I add a "Assignment" to section "1" and I fill the form with:
      | Assignment name | Assignment One |
      | Description | Submit your online text |
      | assignsubmission_onlinetext_enabled | 0 |
      | assignsubmission_onlinetext_wordlimit_enabled | 0 |
      | assignsubmission_file_enabled | 1 |
      | safeassign_enabled            | 1 |
      | safeassign_originality_report | 1 |
      | safeassign_global_reference   | 1 |
    And I log out

  @javascript
  Scenario: Safeassign parameters should appear checked
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    And I duplicate "Assignment One" activity
    And I wait until the page is ready
    And I turn editing mode off
    When I follow duplicate "1" of assignment "Assignment One"
    And I navigate to "Edit settings" in current page administration
    And I follow "SafeAssign Plagiarism plugin"
    Then the field "safeassign_enabled" matches value "1"
    Then the field "safeassign_originality_report" matches value "1"
    Then the field "safeassign_global_reference" matches value "1"
    And I set the field "name" to "copy_1"
    Then I press "Save and return to course"

    Given I follow "Assignment One"
    And I navigate to "Edit settings" in current page administration
    And I expand all fieldsets
    And I set the field "safeassign_originality_report" to "0"
    And I press "Save and return to course"
    Then I turn editing mode on
    And I duplicate "Assignment One" activity
    And I wait until the page is ready
    And I turn editing mode off
    When I follow duplicate "1" of assignment "Assignment One"
    And I navigate to "Edit settings" in current page administration
    And I follow "SafeAssign Plagiarism plugin"
    Then the field "safeassign_enabled" matches value "1"
    Then the field "safeassign_originality_report" matches value "0"
    Then the field "safeassign_global_reference" matches value "1"
    And I set the field "name" to "copy_2"
    Then I press "Save and return to course"

    Given I follow "Assignment One"
    And I navigate to "Edit settings" in current page administration
    And I expand all fieldsets
    And I set the field "safeassign_global_reference" to "0"
    And I press "Save and return to course"
    Then I turn editing mode on
    And I duplicate "Assignment One" activity
    And I wait until the page is ready
    And I turn editing mode off
    When I follow duplicate "1" of assignment "Assignment One"
    And I navigate to "Edit settings" in current page administration
    And I follow "SafeAssign Plagiarism plugin"
    Then the field "safeassign_enabled" matches value "1"
    Then the field "safeassign_originality_report" matches value "0"
    Then the field "safeassign_global_reference" matches value "0"
    And I set the field "name" to "copy_3"
    Then I press "Save and return to course"

    Given I follow "Assignment One"
    And I navigate to "Edit settings" in current page administration
    And I expand all fieldsets
    And I set the field "safeassign_enabled" to "0"
    And I press "Save and return to course"
    Then I turn editing mode on
    And I duplicate "Assignment One" activity
    And I wait until the page is ready
    And I turn editing mode off
    When I follow duplicate "1" of assignment "Assignment One"
    And I navigate to "Edit settings" in current page administration
    And I follow "SafeAssign Plagiarism plugin"
    Then the field "safeassign_enabled" matches value "0"
    Then the field "safeassign_originality_report" matches value "0"
    Then the field "safeassign_global_reference" matches value "0"
    And I set the field "name" to "copy_4"
    Then I press "Save and return to course"
