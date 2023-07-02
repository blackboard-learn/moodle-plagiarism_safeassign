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
# Tests for visibility of admin block by user type and page.
#
# @package    plagiarism_safeassign
# @copyright  Copyright (c) 2017 Open LMS / 2023 Anthology Inc. and its affiliates
# @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

@plugin @plagiarism_safeassign
Feature: Enable SafeAssign in an assignment
  As a Teacher
  I should be able of configure an assignment with SafeAssign as plagiarism plugin

  Background:
    Given the following config values are set as admin:
      | enableplagiarism | 1 |
    And the following "courses" exist:
      | fullname | shortname | format | category | groupmode | enablecompletion |
      | Course 1 | C1        | topics | 0        | 1         | 1                |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | student1 | Student | 1 | student1@example.com |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | admin    | C1     | teacher |
      | student1 | C1     | student |

  Scenario: Enable an assignment with SafeAssign Plagiarism plugin
    Given I log in as "admin"
      And the following "activity" exists:
        | activity | assign |
        | course   | C1     |
        | section  | 1      |
        | name     | Test Assignment |
      And I am on the course with shortname "C1"
     Then I turn editing mode on
      And I follow "Test Assignment"
      And I navigate to "Settings" in current page administration
      And I should not see "SafeAssign Plagiarism plugin"
      And I press "Cancel"
      And the following "activity" exists:
        | activity | assign |
        | course   | C1     |
        | section  | 1      |
        | name     | Assignment One |
      And I am on the course with shortname "C1"
      And I turn editing mode off
     Then the following config values are set as admin:
        | enabled   | 1 | plagiarism_safeassign |
     Then I turn editing mode on
      And I follow "Assignment One"
      And I navigate to "Settings" in current page administration
      And I should see "SafeAssign Plagiarism plugin"
     Then I set the field "Assignment name" to "Assignment One"
      And I set the field "Description" to "Assignment One"
      And navigation node "SafeAssign Plagiarism plugin" should be expandable
     Then I expand "SafeAssign Plagiarism plugin" node
      And I set the field "safeassign_enabled" to "1"
      And I set the field "safeassign_originality_report" to "1"
      And I set the field "safeassign_global_reference" to "0"
      And I press "Save and return to course"
     Then I follow "Assignment One"
      And I navigate to "Settings" in current page administration
     Then the field "safeassign_enabled" matches value "1"
      And the field "safeassign_originality_report" matches value "1"
      And the field "safeassign_global_reference" does not match value "1"
      And I log out

  @javascript @wip
  Scenario: Disclosure agreement checkbox should appear and maintain value
    Given I log in as "admin"
     Then the following config values are set as admin:
       | enabled   | 1 | plagiarism_safeassign |
       |safeassign_referencedbactivity | 1 | plagiarism_safeassign |
      And I am on the course with shortname "C1"
     Then I turn editing mode on
      And I add a "Assignment" to section "1"
      And I should see "SafeAssign Plagiarism plugin"
     Then I set the field "Assignment name" to "Assignment One"
      And I set the field "Description" to "Assignmnet One"
      And I press "Expand all"
      And I set the field "safeassign_enabled" to "1"
      And I set the field "safeassign_originality_report" to "1"
      And I set the field "safeassign_global_reference" to "0"
      And I press "Save and return to course"
     Then I click on "Assignment One" "link" in the ".activityname" "css_element"
      And I log out
      And I log in as "student1"
      And I am on the course with shortname "C1"
     Then I follow "Assignment One"
      And I press "Add submission"
      And I should see "Plagiarism Tools"
      And I should see "SafeAssign accepts files in .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf and .html file formats only."
      And I should see "I agree to submit my paper(s) to the Global Reference Database."
      And I set the field "agreement" to "1"
      And I follow "Course 1"
     Then I follow "Assignment One"
      And I press "Add submission"
      And the field "agreement" matches value "1"
      And I set the field "agreement" to "0"
      And I follow "Course 1"
     Then I follow "Assignment One"
      And I press "Add submission"
      And the field "agreement" matches value "0"

  @javascript
  Scenario: Disclosure agreement checkbox should not appear if SafeAssign is not enabled in the Assignment
    Given I log in as "admin"
     Then the following config values are set as admin:
       | enabled   | 1 | plagiarism_safeassign |
       |safeassign_referencedbactivity | 1 | plagiarism_safeassign |
      And I am on the course with shortname "C1"
     Then I turn editing mode on
      And I add a "Assignment" to section "1"
      And I should see "SafeAssign Plagiarism plugin"
     Then I set the field "Assignment name" to "Assignment One"
      And I set the field "Description" to "Assignmnet One"
      And I press "Expand all"
      And I set the field "safeassign_enabled" to "0"
      And I set the field "safeassign_originality_report" to "0"
      And I set the field "safeassign_global_reference" to "0"
      And I press "Save and return to course"
     Then I click on "Assignment One" "link" in the ".activityname" "css_element"
      And I log out
      And I log in as "student1"
      And I am on the course with shortname "C1"
     Then I follow "Assignment One"
      And I press "Add submission"
      And I should not see "Plagiarism Tools"
      And I should not see "I agree to submit my paper(s) to the Global Reference Database."

  @javascript
  Scenario: Disclosure agreement checkbox should not appear if The Global Reference Database setting is unchecked
    Given I log in as "admin"
    Then the following config values are set as admin:
      | enabled   | 1 | plagiarism_safeassign |
      |safeassign_referencedbactivity | 0 | plagiarism_safeassign |
    And I am on the course with shortname "C1"
    Then I turn editing mode on
    And I add a "Assignment" to section "1"
    And I should see "SafeAssign Plagiarism plugin"
    Then I set the field "Assignment name" to "Assignment One"
    And I set the field "Description" to "Assignmnet One"
    And I press "Expand all"
    And I set the field "safeassign_enabled" to "1"
    And I set the field "safeassign_originality_report" to "1"
    And I set the field "safeassign_global_reference" to "0"
    And I press "Save and return to course"
    Then I click on "Assignment One" "link" in the ".activityname" "css_element"
    And I log out
    And I log in as "student1"
    And I am on the course with shortname "C1"
    Then I follow "Assignment One"
    And I press "Add submission"
    And I should see "Plagiarism Tools"
    And I should not see "I agree to submit my paper(s) to the Global Reference Database."
    And I should see "SafeAssign accepts files in .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf and .html file formats only."

  @javascript
  Scenario: SafeAssign settings should only appear in assignments
    Given I log in as "admin"
    Then the following config values are set as admin:
      | enabled   | 1 | plagiarism_safeassign |
    And I am on the course with shortname "C1"
    Then I turn editing mode on
    And I add a "Assignment" to section "1"
    And I should see "SafeAssign Plagiarism plugin"
    And I press "Expand all"
    And I should see "Check submissions with SafeAssign"
    And I should see "Allow students to view originality report"
    And I should see "Exclude submissions from Global Reference Database"
    And I am on the course with shortname "C1"
    And I add a "Open Forum" to section "1"
    And I should not see "SafeAssign Plagiarism plugin"
    And I press "Expand all"
    And I should not see "Check submissions with SafeAssign"
    And I should not see "Allow students to view originality report"
    And I should not see "Exclude submissions from Global Reference Database"
    And I am on the course with shortname "C1"
    And I add a "Forum" to section "1"
    And I should not see "SafeAssign Plagiarism plugin"
    And I press "Expand all"
    And I should not see "Check submissions with SafeAssign"
    And I should not see "Allow students to view originality report"
    And I should not see "Exclude submissions from Global Reference Database"
    And I am on the course with shortname "C1"
    And I add a "Workshop" to section "1"
    And I should not see "SafeAssign Plagiarism plugin"
    And I press "Expand all"
    And I should not see "Check submissions with SafeAssign"
    And I should not see "Allow students to view originality report"
    And I should not see "Exclude submissions from Global Reference Database"




