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
# @package    plagiarism_safeassign
# @copyright  Copyright (c) 2017 Blackboard Inc.
# @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

@plugin @plagiarism_safeassign
Feature: See plagiarism overall score for a submission in an assignment with SafeAssign enabled
  As a Student/Teacher
  I should be able to se the overall score for a submission with SafeAssign as plagiarism plugin

  Background:
    Given the following config values are set as admin:
      | enableplagiarism | 1 |
    And the following config values are set as admin:
      | safeassign_api | http://safeassign.foo.com | plagiarism_safeassign |
    And the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1        | 0        | 1         |
    And the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Teacher   | 1        | teacher@example.com  |
      | student1 | Student   | 1        | student1@example.com |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |
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
      | safeassign_enabled            | 1 |
      | safeassign_originality_report | 1 |
      | safeassign_global_reference   | 1 |
    And I log out
  Given I log in as "student1"
    And I am on "Course 1" course homepage
    And I follow "Assignment One"
   When I press "Add submission"
    And I upload "lib/tests/fixtures/empty.txt" file to "File submissions" filemanager
    And I press "Save changes"
   Then I log out
  Given set test helper teacher "teacher1"
    And set test helper student "student1"
    And set test helper course with shortname "C1"
    And set test helper assignment with name "Assignment One"
    And submission with file "lib/tests/fixtures/empty.txt" is synced

  @javascript
  Scenario: See plagiarism overall score in the submission view
    Given I log in as "student1"
      And I am on "Course 1" course homepage
      And I follow "Assignment One"
     Then I should see "Submission Text"
      And I wait until "Plagiarism overall score" "text" exists
      And I log out

  @javascript
  Scenario: See plagiarism overall score in the assignment feedback view and MR grader view
    Given I log in as "teacher1"
      And I am on "Course 1" course homepage
      And I follow "Assignment One"
     Then I navigate to "View all submissions" in current page administration
      And I wait until "Plagiarism overall score" "text" exists
      And I am on "Course 1" course homepage
     Then I navigate to "Moodlerooms grader" in current page administration
      And I wait until "Plagiarism overall score" "text" exists
      And I log out
