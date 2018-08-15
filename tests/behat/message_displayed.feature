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
# Tests for students sending assignment using SafeAssign plagiarism plugin
#
# @author     Rafael Monterroza
# @package    plagiarism_safeassign
# @copyright  Copyright (c) 2017 Moodlerooms Inc. (http://www.blackboard.com)
# @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

@plugin @plagiarism_safeassign
Feature: SafeAssign displays the submission status for the correct files
  As a Student/Teacher
  I should be able to see the status only for submission done after Safeassign activation.

  Background:
    Given the following config values are set as admin:
      | enableplagiarism | 1 |
    And the following config values are set as admin:
      | safeassign_api | http://safeassign.example.com | plagiarism_safeassign |
    And the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 1 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher@example.com |
      | student1 | Student | 1 | student1@example.com |
      | student2 | Student | 2 | student2@example.com |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student |
      | student2 | C1     | student |
    And the following config values are set as admin:
      | safeassign_use   | 1 | plagiarism |
    Then I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I add a "Assignment" to section "1" and I fill the form with:
      | Assignment name | Assignment One |
      | Description | Submit your online text |
      | assignsubmission_onlinetext_enabled | 0 |
      | assignsubmission_onlinetext_wordlimit_enabled | 0 |
      | assignsubmission_file_enabled | 1 |
    And I log out

  @javascript
  Scenario: SafeAssign shows Report in progress... text only for submissions done after its activation.
    Given I log in as "student1"
    And I am on "Course 1" course homepage
    And I follow "Assignment One"
    When I press "Add submission"
    And I upload "lib/tests/fixtures/empty.txt" file to "File submissions" filemanager
    And I press "Save changes"
    Then I should not see "SafeAssign Originality Report in progress..."
    Then I log out
    Then I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I follow "Assignment One"
    And I navigate to "Edit settings" in current page administration
    And I set the following fields to these values:
      | safeassign_enabled            | 1 |
      | safeassign_originality_report | 1 |
      | safeassign_global_reference   | 1 |
    And I press "Save and display"
    And I navigate to "View all submissions" in current page administration
    And I should not see "Report in progress" in the "Student 1" "table_row"
    Then I log out
    Then I log in as "student2"
    And I am on "Course 1" course homepage
    And I follow "Assignment One"
    When I press "Add submission"
    And I upload "lib/tests/fixtures/empty.txt" file to "File submissions" filemanager
    And I press "Save changes"
    Then I should see "SafeAssign Originality Report in progress..."
    Then I log out
    Then I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I follow "Assignment One"
    And I navigate to "View all submissions" in current page administration
    And I should see "Report in progress" in the "Student 2" "table_row"
    Then I log out
