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
# Tests for processing message using SafeAssign plagiarism plugin.
# Messages should only appear when the files are submitted. (Drafts are not submitted).
#
# @author     Juan Ibarra
# @package    plagiarism_safeassign
# @copyright  Copyright (c) 2017 Open LMS / 2023 Anthology Inc. and its affiliates
# @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

@plugin @plagiarism_safeassign @_file_upload
Feature: SafeAssign displays the submission processing message only when there is a submission
  As a Student/Teacher
  I should be able to see the status "On progress" only when files are submitted.

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
      | student2 | Student | 2 | student1@example.com |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student |
      | student2 | C1     | student |
    And the following config values are set as admin:
      | enabled   | 1 | plagiarism_safeassign |

  @javascript
  Scenario: SafeAssign shows Report in progress... only when files are submitted.
    Then I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I add a "Assignment" to section "1" and I fill the form with:
      | Assignment name | Assignment One |
      | Description | Submit your file |
      | assignsubmission_file_enabled | 1 |
      | id_submissiondrafts           | 1 |
      | id_assignsubmission_file_maxfiles | 2 |
      | safeassign_enabled            | 1 |
      | safeassign_originality_report | 1 |
      | safeassign_global_reference   | 0 |
    And I add a "Assignment" to section "1" and I fill the form with:
      | Assignment name | Assignment Two |
      | Description | Submit your file |
      | assignsubmission_file_enabled | 1 |
      | id_submissiondrafts           | 0 |
      | safeassign_enabled            | 1 |
      | safeassign_originality_report | 1 |
      | safeassign_global_reference   | 0 |
    Then I log out
    Given I log in as "student1"
    And I am on "Course 1" course homepage
    And I follow "Assignment One"
    When I press "Add submission"
    And I upload "plagiarism/safeassign/tests/fixtures/dummy-files/test1.txt" file to "File submissions" filemanager
    And I press "Save changes"
    Then I should see "Draft (not submitted)"
    And I should not see "SafeAssign Originality Report in progress..."
    Then I am on "Course 1" course homepage
    And I follow "Assignment Two"
    When I press "Add submission"
    And I upload "plagiarism/safeassign/tests/fixtures/dummy-files/test2.txt" file to "File submissions" filemanager
    And I press "Save changes"
    And I should see "SafeAssign Originality Report in progress..."
    Then I log out
    Given I log in as "student2"
    And I am on "Course 1" course homepage
    And I follow "Assignment One"
    When I press "Add submission"
    And I upload "plagiarism/safeassign/tests/fixtures/dummy-files/test3.txt" file to "File submissions" filemanager
    And I upload "plagiarism/safeassign/tests/fixtures/dummy-files/test4.txt" file to "File submissions" filemanager
    And I press "Save changes"
    Then I should see "Draft (not submitted)"
    And I should not see "SafeAssign Originality Report in progress..."
    Then I am on "Course 1" course homepage
    And I follow "Assignment Two"
    When I press "Add submission"
    And I upload "plagiarism/safeassign/tests/fixtures/dummy-files/test5.txt" file to "File submissions" filemanager
    And I press "Save changes"
    And I should see "SafeAssign Originality Report in progress..."
    Then I log out
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Assignment One" "link" in the ".activityname" "css_element"
    And I follow "View all submissions"
    And I should see "Draft (not submitted)" in the "(//tr[contains(@id,'mod_assign_grading')])[1]//div[@class='submissionstatusdraft']" "xpath_element"
    And I should not see "SafeAssign Originality Report in progress..." in the "(//tr[contains(@id,'mod_assign_grading')])[1]//td[@class='cell c8']" "xpath_element"
    And I should see "Draft (not submitted)" in the "(//tr[contains(@id,'mod_assign_grading')])[2]//div[@class='submissionstatusdraft']" "xpath_element"
    And I should not see "SafeAssign Originality Report in progress..." in the "(//tr[contains(@id,'mod_assign_grading')])[2]//td[@class='cell c8']" "xpath_element"
    Then I log out
    Given I log in as "student1"
    And I am on "Course 1" course homepage
    And I follow "Assignment One"
    Then I press "Submit assignment"
    Then I press "Continue"
    And I should see "SafeAssign Originality Report in progress..."
    Then I log out
    Given I log in as "student2"
    And I am on "Course 1" course homepage
    And I follow "Assignment One"
    Then I press "Submit assignment"
    Then I press "Continue"
    And I should see "SafeAssign Originality Report in progress..."
    Then I log out
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Assignment One" "link" in the ".activityname" "css_element"
    And I follow "View all submissions"
    And I should see "Submitted for grading" in the "(//tr[contains(@id,'mod_assign_grading')])[1]//div[@class='submissionstatussubmitted']" "xpath_element"
    And I should see "SafeAssign Originality Report in progress..." in the "(//tr[contains(@id,'mod_assign_grading')])[1]//td[@class='cell c8']" "xpath_element"
    And I should see "Submitted for grading" in the "(//tr[contains(@id,'mod_assign_grading')])[2]//div[@class='submissionstatussubmitted']" "xpath_element"
    And I should see "SafeAssign Originality Report in progress..." in the "(//tr[contains(@id,'mod_assign_grading')])[2]//td[@class='cell c8']" "xpath_element"
    Then I am on "Course 1" course homepage
    And I click on "//*[@data-activityname='Assignment Two']//a" "xpath_element"
    And I follow "View all submissions"
    And I should see "Submitted for grading" in the "(//tr[contains(@id,'mod_assign_grading')])[1]//div[@class='submissionstatussubmitted']" "xpath_element"
    And I should see "SafeAssign Originality Report in progress..." in the "(//tr[contains(@id,'mod_assign_grading')])[1]//td[@class='cell c8']" "xpath_element"
    And I should see "Submitted for grading" in the "(//tr[contains(@id,'mod_assign_grading')])[2]//div[@class='submissionstatussubmitted']" "xpath_element"
    And I should see "SafeAssign Originality Report in progress..." in the "(//tr[contains(@id,'mod_assign_grading')])[2]//td[@class='cell c8']" "xpath_element"

