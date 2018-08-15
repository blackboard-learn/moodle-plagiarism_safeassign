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
# @copyright  Copyright (c) 2017 Moodlerooms Inc.
# @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

@plugin @plagiarism_safeassign
Feature: Sending a submission to a SafeAssign assignment and receiving notifications
  As a Teacher
  I should receive a notification when I enable notifications and a SafeAssign submission has been graded

  Background:
    Given the following config values are set as admin:
      | enableplagiarism | 1 |
    And the following config values are set as admin:
      | safeassign_api | http://safeassign.foo.com.co | plagiarism_safeassign |
      | message_provider_plagiarism_safeassign_safeassign_graded_loggedin | popup | message |
      | message_provider_plagiarism_safeassign_safeassign_graded_loggedoff | popup | message |
    And the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 1 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher@example.com |
      | teacher2 | Teacher | 2 | teacher2@example.com |
      | student1 | Student | 1 | student1@example.com |
      | student2 | Student | 2 | student2@example.com |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | teacher1 | C1     | editingteacher |
      | teacher2 | C1     | teacher |
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
      | safeassign_enabled            | 1 |
      | safeassign_originality_report | 1 |
      | safeassign_global_reference   | 1 |
    And I log out
    Given I log in as "student1"
    And I am on "Course 1" course homepage
    And I follow "Assignment One"
    When I press "Add submission"
    And I upload "plagiarism/safeassign/tests/fixtures/dummy-files/test1.txt" file to "File submissions" filemanager
    And I press "Save changes"
    Then I log out
    Given set test helper teacher "teacher1"
    And set test helper student "student1"
    And set test helper course with shortname "C1"
    And set test helper assignment with name "Assignment One"
    And I send a submission with file "plagiarism/safeassign/tests/fixtures/dummy-files/test2.txt"
    Given I log in as "student2"
    And I am on "Course 1" course homepage
    And I follow "Assignment One"
    When I press "Add submission"
    And I upload "plagiarism/safeassign/tests/fixtures/dummy-files/test3.txt" file to "File submissions" filemanager
    And I press "Save changes"
    Then I log out

    @javascript
    Scenario: Student sends submission, submission gets graded by SafeAssign and both teachers receive a notification
      Given I change notifications setup for user "teacher1" with "popup"
      And I change notifications setup for user "teacher2" with "popup"
      Then I sync submissions
      Then I log in as "teacher1"
      And I open the notification popover
      And I click on "#nav-notification-popover-container .see-all-link" "css_element"
      And I click on ".notification-message" "css_element"
      Then I should see "Plagiarism scores have been processed for 1 submission in Assignment One"
      Then I log out
      Given I log in as "teacher2"
      And I open the notification popover
      And I click on "#nav-notification-popover-container .see-all-link" "css_element"
      And I click on ".notification-message" "css_element"
      And I wait "3" seconds
      Then I should see "Plagiarism scores have been processed for 1 submission in Assignment One"

    @javascript
    Scenario: 2 students send a submission for same assignment, submissions get graded and both teachers receive a notification
      Given I change notifications setup for user "teacher1" with "popup"
      And I change notifications setup for user "teacher2" with "popup"
      And set test helper student "student2"
      And I send a submission with file "plagiarism/safeassign/tests/fixtures/dummy-files/test4.txt"
      Then I sync submissions
      Then I log in as "teacher1"
      And I open the notification popover
      And I click on "#nav-notification-popover-container .see-all-link" "css_element"
      And I click on ".notification-message" "css_element"
      And I wait "3" seconds
      Then I should see "Plagiarism scores have been processed for 2 submissions in Assignment One"
      Then I log out
      Given I log in as "teacher2"
      And I open the notification popover
      And I click on "#nav-notification-popover-container .see-all-link" "css_element"
      And I click on ".notification-message" "css_element"
      And I wait "3" seconds
      Then I should see "Plagiarism scores have been processed for 2 submissions in Assignment One"
      Then I log out

    @javascript
    Scenario: Student sends submission, submission gets graded by SafeAssign and neither of teachers receive a notification
      Given I change notifications setup for user "teacher1" with "email"
      And I change notifications setup for user "teacher2" with "email"
      Given I sync submissions
      Then I log in as "teacher1"
      And I open the notification popover
      And I click on "#nav-notification-popover-container .see-all-link" "css_element"
      Then I should not see "Plagiarism SafeAssign"
      Then I log out
      Given I log in as "teacher2"
      And I open the notification popover
      And I click on "#nav-notification-popover-container .see-all-link" "css_element"
      Then I should not see "Plagiarism SafeAssign"
