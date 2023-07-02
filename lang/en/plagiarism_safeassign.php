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
 * SafeAssign language strings.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'SafeAssign plagiarism plugin';
$string['getscores'] = 'Get scores for submissions';
$string['getscoreslog'] = 'SafeAssign score task log';
$string['getscoreslogfailed'] = 'SafeAssign score task fail';
$string['getscoreslog_desc'] = 'SafeAssign score task ran successfully.';
$string['servicedown'] = 'SafeAssign service is unavailable.';
$string['studentdisclosuredefault'] = 'All files uploaded will be submitted to a plagiarism detection service.';
$string['studentdisclosure'] = 'Institution Release Statement';
$string['studentdisclosure_help'] = 'This text will be displayed to all students on the file upload page. If this
field is left empty, the default localized string (studentdisclosuredefault) will be used instead.';
$string['safeassignexplain'] = 'For more information on this plugin see: ';
$string['safeassign'] = 'SafeAssign';
$string['safeassign:enable'] = 'Allow the teacher to enable/disable SafeAssign inside an activity';
$string['safeassign:report'] = 'Allow viewing the originality report from SafeAssign';
$string['usesafeassign'] = 'Enable SafeAssign';
$string['savedconfigsuccess'] = 'Plagiarism Settings Saved';
$string['safeassign_additionalroles'] = 'Additional roles';
$string['safeassign_additionalroles_help'] = 'Users with these roles at system level will be added to each SafeAssign
course as instructors.';
$string['safeassign_api'] = 'SafeAssign integration URL';
$string['safeassign_api_help'] = 'This is the address of the SafeAssign API.';
$string['instructor_role_credentials'] = 'Instructor Role Credentials';
$string['safeassign_instructor_username'] = 'Shared key';
$string['safeassign_instructor_username_help'] = "Instructor's shared key provided by SafeAssign.";
$string['safeassign_instructor_password'] = 'Shared secret';
$string['safeassign_instructor_password_help'] = "Instructor's shared secret provided by SafeAssign.";
$string['student_role_credentials'] = 'Student Role Credentials';
$string['safeassign_student_username'] = 'Shared key';
$string['safeassign_student_username_help'] = "Student's shared key provided by SafeAssign.";
$string['safeassign_student_password'] = 'Shared secret';
$string['safeassign_student_password_help'] = "Student's shared secret provided by SafeAssign.";
$string['safeassign_license_acceptor_givenname'] = 'License Acceptor First Name';
$string['safeassign_license_acceptor_surname'] = 'License Acceptor Surname';
$string['safeassign_license_acceptor_email'] = 'License Acceptor Email';
$string['safeassign_license_header'] = 'SafeAssign&trade; License Terms & Conditions';
$string['license_already_accepted'] = "The current license terms were already accepted by your administrator.";
$string['acceptlicense'] = 'Accept SafeAssign license';
$string['acceptlicenselog'] = 'SafeAssign license task log';
$string['safeassign_license_warning'] = "There is a problem validating the SafeAssign&trade; license data, please
click the 'Test connection' button. If the test is successful, try again later.";
$string['safeassign_enableplugin'] = 'Enable SafeAssign for {$a}';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&nbsp Default value: 0</div> <br>';
$string['safeassign_showid'] = 'Show Student ID';
$string['safeassign_alloworganizations'] = 'Allow SafeAssignments in Organizations';
$string['safeassign_referencedbactivity'] = '<a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a> Activity';
$string['safeassing_response_header'] = '<br>SafeAssign server response: <br>';
$string['safeassign_instructor_credentials'] = 'Instructor Role Credentials: ';
$string['safeassign_student_credentials'] = 'Student Role Credentials: ';
$string['safeassign_credentials_verified'] = 'Connection verified.';
$string['safeassign_credentials_fail'] = 'Connection not verified. Check key, secret and url.';
$string['credentials'] = 'Credentials and Service URL';
$string['shareinfo'] = 'Share info with SafeAssign';
$string['disclaimer'] = '<br>Submitting to the SafeAssign <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a> allows papers from other institutions <br>
                        to be checked against your students paper to protect the origin of their work.';
$string['settings'] = 'SafeAssign Settings';
$string['timezone_help'] = 'The timezone set in your Open LMS environment.';
$string['timezone'] = 'Timezone';
$string['safeassign_status'] = 'SafeAssign status';
$string['status:pending'] = 'Pending';
$string['safeassign_score'] = 'SafeAssign score';
$string['safeassign_reporturl'] = 'Report URL';
$string['button_disabled'] = 'Save form to test connection';
// Rest provider.
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'Timezone';
$string['safeassign_curlcache'] = 'Cache timeout';
$string['safeassign_curlcache_help'] = 'Web service cache timeout.';
$string['rest_error_nocurl'] = 'cURL module must be present and enabled!';
$string['rest_error_nourl'] = 'You must specify URL!';
$string['rest_error_nomethod'] = 'You must specify request method!';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'Test connection';
$string['connectionfailed'] = 'Connection Failed';
$string['connectionverified'] = 'Connection Verified';
$string['cachedef_request'] = 'SafeAssign request cache';
// Behat test.
$string['error_behat_getjson'] = 'Error to get json file "{$a}" from folder plagiarism/safeassign/tests/fixtures for simulating a call to SafeAssign webservices when running behat tests.';
$string['error_behat_instancefail'] = 'This is an instance configured to fail with behat tests.';
$string['safeassign'] = 'SafeAssign Plagiarism plugin';
$string['assignment_check_submissions'] = 'Check submissions with SafeAssign';
$string['assignment_check_submissions_help'] = 'SafeAssign Originality Reports aren\'t available to Teachers if anonymous grading
 is set, but Students can view their own SafeAssign Originality Reports if "Allow students to view originality report" is selected.
<br><br>SafeAssign returns a single Originality Report when users submit multiple files. You can choose which file to review from within this report.
<br><br>Although SafeAssign officially supports only English, you can try to use SafeAssign with other languages.
SafeAssign has no technical limitations that preclude using it with other languages.
See <a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">Blackboard help</a> for more information.';
$string['students_originality_report'] = 'Allow students to view originality report';
$string['submissions_global_reference'] = 'Exclude submissions from <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a>';
$string['submissions_global_reference_help'] = 'Submissions will still be processed by SafeAssign but won’t be registered in databases. This avoids files being marked as plagiarized when teachers allow re-submissions in a specific assignment.';
// Disclosure agreement.
$string['plagiarism_tools'] = 'Plagiarism Tools';
$string['files_accepted'] = 'SafeAssign accepts files in .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf and .html file formats only. Files of any other format, including .zip and other compressed file formats, will not be checked through SafeAssign. Files linked from an external repository will not be checked through SafeAssign.
<br><br>By submitting this paper, you agree:
 (1) that you are submitting your paper to be used and stored as part of the SafeAssign&trade; services in accordance with the Anthology <a href="https://www.anthology.com/trust-center/terms-of-use" target="_blank">Terms and Service</a> and <a href="https://www.anthology.com/trust-center/privacy-statement" target="_blank">Anthology Privacy Policy</a>;
 (2) that your institution may use your paper in accordance with your institution\'s policies; and
 (3) that your use of SafeAssign will be without recourse against Anthology Group and its affiliates.';
$string['agreement'] = 'I agree to submit my paper(s) to the <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a>.';
$string['error_api_generic'] = 'There was an error processing your request';
$string['error_api_unauthorized'] = 'There was an authentication error processing your request';
$string['error_api_forbidden'] = 'There was an authorization error processing your request';
$string['error_api_not_found'] = 'The requested resource was not found';
$string['sync_assignments'] = 'Sends the available information to the SafeAssign server.';
$string['api_call_log_event'] = 'SafeAssign log for API calls.';
$string['course_error_sync'] = 'An error occurred trying to sync the Course with ID: {$a} into SafeAssign: <br>';
$string['assign_error_sync'] = 'An error occurred trying to sync the Assignment with ID: {$a} into SafeAssign: <br>';
$string['submission_error_sync'] = 'An error ocurred trying to sync the Submission with ID: {$a} into SafeAssign: <br>';
$string['submission_success_sync'] = 'Submissions synced successfully';
$string['assign_success_sync'] = 'Assignments synced successfully';
$string['course_success_sync'] = 'Courses synced successfully';
$string['license_header'] = 'SafeAssign&trade; License Agreement';
$string['license_title'] = 'SafeAssign License Agreement';
$string['not_configured'] = 'SafeAssign&trade; is not configured. Please have your system administrator submit a ticket
to <a href="https://support.openlms.net/" target="_blank" rel="noopener">Open LMS Support</a> for assistance.';
$string['agree_continue'] = 'Save form';

$string['safeassign_file_not_supported'] = 'Not supported.';
$string['safeassign_file_not_supported_help'] = 'The file extension is not supported by SafeAssign or the file size exceeds maximum capacity.';
$string['safeassign_submission_not_supported'] = 'This submission will not be reviewed by SafeAssign.';
$string['safeassign_submission_not_supported_help'] = 'Submissions created by course instructors are not sent to SafeAssign.';
$string['safeassign_file_in_review'] = 'SafeAssign Originality Report in progress...';
$string['safeassign_file_similarity_score'] = 'SafeAssign score: {$a}%<br>';
$string['safeassign_link_originality_report'] = 'View originality report';
$string['safeassign_file_limit_exceeded'] = "This submission exceeds the combined size limit of 10 MB and won't be processed by SafeAssign";

// Originality report.
$string['originality_report'] = 'SafeAssign Originality Report';
$string['originality_report_unavailable'] = 'The requested Originality Report is unavailable. Check back later or contact your System Administrator.';
$string['originality_report_error'] = 'There was an error with SafeAssign\'s Originality Report. Contact your System Administrator.';

$string['safeassign_overall_score'] = '<b>SafeAssign overall score: {$a}%</b>';
// Notifications for instructors.
$string['messageprovider:safeassign_graded'] = 'SafeAssign sends notifications to instructors when a submission has been graded for plagiarism';
$string['safeassign_loading_settings'] = 'Loading settings, please wait';
$string['safeassign:get_messages'] = 'Allow receiving notifications from SafeAssign';
$string['safeassign_notification_message'] = 'Plagiarism scores have been processed for {$a->counter} {$a->plural} in {$a->assignmentname}';
$string['safeassign_notification_grading_link'] = 'Grading page';
$string['safeassign_notification_message_hdr'] = 'Plagiarism SafeAssign scores have been processed';
$string['safeassign_notification_subm_singular'] = 'submission';
$string['safeassign_notification_subm_plural'] = 'submissions';
// New license agreement notification.
$string['messageprovider:safeassign_notification'] = 'SafeAssign sends notifications to Site Administrators when a new License Terms & Conditions is available';
$string['safeassign:get_notifications'] = 'Allow notifications from SafeAssign';
$string['license_agreement_notification_subject'] = 'New SafeAssign License Terms & Conditions available';
$string['license_agreement_notification_message'] = 'You can accept the new License Terms & Conditions here: {$a}';
$string['settings_page'] = 'SafeAssign Settings Page';
$string['send_notifications'] = 'Send SafeAssign new License Terms & Conditions notifications.';

// SafeAssign core submodules.
$string['privacy:metadata:core_files'] = 'Files attached to submissions or created from online text submissions.';
$string['privacy:metadata:core_plagiarism'] = 'This plugin is called by Moodle plagiarism subsystem.';

// SafeAssign server metadata.
$string['privacy:metadata:safeassign_service'] = 'In order to get a originality report, some user data should be send to SafeAssign service.';
$string['privacy:metadata:safeassign_service:adminemail'] = 'Admin should sent its email in order to accept the service license.';
$string['privacy:metadata:safeassign_service:filecontent'] = 'We need to sent the files to SafeAssign in order to generate the originality report.';
$string['privacy:metadata:safeassign_service:filename'] = 'The file name is required for SafeAssign service.';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'File uuid allows to relate Moodle files in SafeAssign server.';
$string['privacy:metadata:safeassign_service:fullname'] = 'The user name is sent to SafeAssign to allow get the authentication token.';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'This submission uuid is required to retrieve the originality report.';
$string['privacy:metadata:safeassign_service:userid'] = 'The userid id sent from Moodle to allow you to use SafeAssign services.';

// SafeAssign files table metadata.
$string['privacy:metadata:plagiarism_safeassign_files'] = 'Information about the originality of the files uploaded by the user';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'The ID of the student that made this submission.';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'File unique identifier in SafeAssign service.';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'URL to the originality report.';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = 'Similarity score for the submitted file.';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'Time when the file was submitted.';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'Submission unique identifier in SafeAssign service';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = 'The ID of the file that was submitted.';

// SafeAssign course table metadata.
$string['privacy:metadata:plagiarism_safeassign_course'] = 'Information about Moodle courses with SafeAssign enabled on them.';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'Course unique identifier in SafeAssign service.';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'The course that has an activity with SafeAssign enable on it.';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'The ID of the user that is a teacher in this course.';

// SafeAssign submission table metadata.
$string['privacy:metadata:plagiarism_safeassign_subm'] = 'Information about students submissions.';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'The assignment id ofr this submission.';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'The average similarity score for all submitted files.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = 'Flag to determinate if the submission has a file on it.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = 'Flag to determinate if the submission has an online text on it.';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = 'The highest similarity score for one submitted file.';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'The submission id of an activity with SafeAssign enabled on it.';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'Flag to determinate if the file was send to SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = 'Time when the submission was created.';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'Submission unique identifier in SafeAssign service.';

// SafeAssign instructors table metadata.
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'Information about the teachers in the platform.';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = 'The ID of one user that is a teacher in one course.';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'The ID of the course in which the user is a teacher.';
