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
 * @copyright  Copyright (c) 2023 Open LMS / 2023 Anthology Inc. and its affiliates
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'ปลั๊กอินการคัดลอกผลงานของ SafeAssign';
$string['getscores'] = 'ได้รับคะแนนสำหรับการบ้านที่ส่ง';
$string['getscoreslog'] = 'บันทึกงานคะแนนของ SafeAssign';
$string['getscoreslogfailed'] = 'ความล้มเหลวของงานคะแนนของ SafeAssign';
$string['getscoreslog_desc'] = 'งานคะแนนของ SafeAssign ทำงานได้สำเร็จ';
$string['servicedown'] = 'บริการ SafeAssign ไม่มีให้ใช้งาน';
$string['studentdisclosuredefault'] = 'ไฟล์ทั้งหมดที่อัปโหลดจะส่งไปยังบริการตรวจสอบการคัดลอกผลงาน';
$string['studentdisclosure'] = 'ข้อความเผยแพร่ของสถาบัน';
$string['studentdisclosure_help'] = 'ข้อความนี้จะปรากฏต่อผู้เรียนทุกคนในหน้าอัปโหลดไฟล์ หากฟิลด์
นี้ถูกเว้นว่างไว้ ระบบจะนำสตริงที่แปลแล้วตามค่าเริ่มต้น (studentdisclosuredefault) มาใช้แทน';
$string['safeassignexplain'] = 'สำหรับข้อมูลเพิ่มเติมเกี่ยวกับปลั๊กอินนี้ โปรดดู:';
$string['safeassign'] = 'ปลั๊กอินการคัดลอกผลงานของ SafeAssign';
$string['safeassign:enable'] = 'อนุญาตให้อาจารย์สามารถเปิดใช้งาน/ปิดใช้งาน SafeAssign ในกิจกรรมได้';
$string['safeassign:report'] = 'อนุญาตให้ดูรายงานความคิดริเริ่มจาก SafeAssign ได้';
$string['usesafeassign'] = 'เปิดใช้งาน SafeAssign';
$string['savedconfigsuccess'] = 'การตั้งค่าการคัดลอกผลงานบันทึกแล้ว';
$string['safeassign_additionalroles'] = 'ไฟล์เพิ่มเติม';
$string['safeassign_additionalroles_help'] = 'ผู้ใช้งานที่มีบทบาทเหล่านี้ในระดับระบบจถูกเพิ่มไปยังแต่ละรายวิชาของ SafeAssign
เป็นผู้สอน';
$string['safeassign_api'] = 'URL การผสานการทำงาน SafeAssign';
$string['safeassign_api_help'] = 'นี่คือที่อยู่ API ของ SafeAssign';
$string['instructor_role_credentials'] = 'ข้อมูลประจำตัวสำหรับบทบาทของผู้สอน';
$string['safeassign_instructor_username'] = 'คีย์ที่ใช้ร่วมกัน';
$string['safeassign_instructor_username_help'] = 'คีย์ที่ใช้ร่วมกันของผู้สอนที่ SafeAssign กำหนดให้';
$string['safeassign_instructor_password'] = 'ความลับที่แชร์';
$string['safeassign_instructor_password_help'] = 'ความลับที่แชร์ของผู้สอนที่ SafeAssign กำหนดให้';
$string['student_role_credentials'] = 'ข้อมูลประจำตัวสำหรับบทบาทของผู้เรียน';
$string['safeassign_student_username'] = 'คีย์ที่ใช้ร่วมกัน';
$string['safeassign_student_username_help'] = 'คีย์ที่ใช้ร่วมกันของผู้เรียนที่ SafeAssign กำหนดให้';
$string['safeassign_student_password'] = 'ความลับที่แชร์';
$string['safeassign_student_password_help'] = 'ความลับที่แชร์ของผู้เรียนที่ SafeAssign กำหนดให้';
$string['safeassign_license_acceptor_givenname'] = 'ชื่อของผู้รับรองสิทธิ์การใช้งาน';
$string['safeassign_license_acceptor_surname'] = 'นามสกุลของผู้รับรองสิทธิ์การใช้งาน';
$string['safeassign_license_acceptor_email'] = 'อีเมลของผู้รับรองสิทธิ์การใช้งาน';
$string['safeassign_license_header'] = 'ข้อกำหนดและเงื่อนไขสิทธิ์การใช้งาน SafeAssign&trade;';
$string['license_already_accepted'] = 'ผู้ดูแลระบบของคุณได้ยอมรับข้อกำหนดสิทธิ์การใช้งานในปัจจุบันแล้ว';
$string['acceptlicense'] = 'ยอมรับสิทธิ์การใช้งานของ SafeAssign';
$string['acceptlicenselog'] = 'บันทึกงานของสิทธิ์การใช้งานของ SafeAssign';
$string['safeassign_license_warning'] = 'เกิดปัญหาในการตรวจสอบยืนยันข้อมูลใบอนุญาต SafeAssign&trade; โปรด
คลิกที่ปุ่ม \'ทดสอบการเชื่อมต่อ\' หากการทดสอบสำเร็จ ให้ลองอีกครั้งในภายหลัง';
$string['safeassign_enableplugin'] = 'เปิดใช้งาน SafeAssign ให้ {$a}';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&amp;nbsp ค่าเริ่มต้น: 0</div> <br>';
$string['safeassign_showid'] = 'แสดง ID ผู้เรียน';
$string['safeassign_alloworganizations'] = 'อนุญาต SafeAssignments ในองค์กร';
$string['safeassign_referencedbactivity'] = 'กิจกรรมของ <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">ฐานข้อมูลอ้างอิงส่วนกลาง</a>';
$string['safeassing_response_header'] = '<br>การตอบกลับของเซิร์ฟเวอร์ SafeAssign:<br>';
$string['safeassign_instructor_credentials'] = 'ข้อมูลประจำตัวสำหรับบทบาทของผู้สอน:';
$string['safeassign_student_credentials'] = 'ข้อมูลประจำตัวสำหรับบทบาทของผู้เรียน:';
$string['safeassign_credentials_verified'] = 'ตรวจสอบการเชื่อมต่อแล้ว';
$string['safeassign_credentials_fail'] = 'การเชื่อมต่อยังไม่ได้ตรวจสอบ ตรวจสอบคีย์ ความลับและ URL';
$string['credentials'] = 'ข้อมูลประจำตัวและ URL บริการ';
$string['shareinfo'] = 'แบ่งปันข้อมูลกับ SafeAssign';
$string['disclaimer'] = '<br>การส่งงานไปยัง SafeAssign <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a> ช่วยให้เอกสารจากสถาบันอื่นๆ<br>
จากสถาบันอื่นๆ เพื่อเทียบกับเอกสารของผู้เรียนของคุณเพื่อปกป้องต้นฉบับของงาน';
$string['settings'] = 'การตั้งค่า SafeAssign';
$string['timezone_help'] = 'เขตเวลาที่ตั้งค่าไว้ในสภาพแวดล้อม Open LMS ของคุณ';
$string['timezone'] = 'เขตเวลา';
$string['safeassign_status'] = 'สถานะ SafeAssign';
$string['status:pending'] = 'รอดำเนินการ';
$string['safeassign_score'] = 'คะแนน SafeAssign';
$string['safeassign_reporturl'] = 'URL รายงาน';
$string['button_disabled'] = 'บันทึกแบบฟอร์มที่จะทดสอบการเชื่อมต่อ';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'เกิดข้อผิดพลาดในการรับไฟล์ Json "{$a}" จากโฟลเดอร์ plagiarism/safeassign/tests/fixtures สำหรับการจำลองการเรียกไปยังเว็บเซอร์วิส SafeAssign เมื่อเรียกใช้การทดสอบ Behat';
$string['safeassign_curlcache'] = 'แคชหมดเวลา';
$string['safeassign_curlcache_help'] = 'แคชของเว็บเซอร์วิสหมดเวลา';
$string['rest_error_nocurl'] = 'ต้องแสดงและเปิดใช้งานโมดูล cURL!';
$string['rest_error_nourl'] = 'คุณต้องระบุ URL!';
$string['rest_error_nomethod'] = 'คุณต้องระบุวิธีการร้องขอ!';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'ทดสอบการเชื่อมต่อ';
$string['connectionfailed'] = 'การเชื่อมต่อล้มเหลว';
$string['connectionverified'] = 'การเชื่อมต่อตรวจสอบแล้ว';
$string['cachedef_request'] = 'แคชของคำขอ SafeAssign';
$string['error_behat_instancefail'] = 'มีอินสแตนซ์ที่กำหนดค่าไม่สามารถทดสอบ Behat ได้';
$string['assignment_check_submissions'] = 'ตรวจสอบการบ้านที่ส่งด้วย SafeAssign';
$string['assignment_check_submissions_help'] = 'ผู้สอนจะไม่สามารถดูรายงานความเป็นต้นฉบับของ SafeAssign ได้หากมีการตั้งค่าการให้เกรด
แบบนิรนาม แต่ผู้เรียนสามารถดูรายงานความเป็นต้นฉบับของตนเอง SafeAssign ได้หากมีการเลือก "อนุญาตให้ผู้เรียนดูรายงานความเป็นต้นฉบับ" ไว้
<br><br>SafeAssign จะส่งกลับรายงานความเป็นต้นฉบับรายการเดียวเมื่อผู้ใช้งานส่งหลายไฟล์ คุณสามารถเลือกว่าต้องการตรวจทานไฟล์ใดจากภายในรายงาน
<br><br>แม้ว่า SafeAssign จะรองรับเฉพาะภาษาอังกฤษอย่างเป็นทางการ คุณสามารถลองใช้ SafeAssign กับภาษาอื่นๆ ได้
SafeAssign ไม่มีข้อจำกัดทางเทคนิคที่ป้องกันการใช้งานร่วมกันภาษาอื่นๆ
ดู <a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">วิธีใช้ Blackboard</a> สำหรับรายละเอียดเพิ่มเติม';
$string['students_originality_report'] = 'อนุญาตให้ผู้เรียนสามารถดูรายงานความคิดริเริ่ม';
$string['submissions_global_reference'] = 'ไม่รวมงานที่ส่งจาก <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">ฐานข้อมูลอ้างอิงส่วนกลาง</a>';
$string['submissions_global_reference_help'] = 'การบ้านที่ส่งจะยังคงได้รับการประมวลผลโดย SafeAssign แต่จะไม่ได้รับการลงทะเบียนในฐานข้อมูล ทั้งนี้เพื่อหลีกเลี่ยงไม่ให้ไฟล์ได้รับการทำเครื่องหมายเป็นคัดลอกผลงาน เมื่ออาจารย์อนุญาตให้ส่งซ้ำในงานที่มอบหมายตามที่กำหนด';
$string['plagiarism_tools'] = 'เครื่องมือเกี่ยวกับการคัดลอกผลงาน';
$string['files_accepted'] = 'SafeAssign ยอมรับไฟล์ในรูปแบบ .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf และ .html ไฟล์ในรูปแบบอื่นๆ รวมถึง .zip และรูปแบบไฟล์ที่ถูกบีบอัดอื่นๆ จะไม่ได้รับการตรวจสอบผ่าน SafeAssign
<br><br>การส่งงานเขียนนี้หมายความว่าคุณยอมรับว่า:
(1) คุณกำลังส่งงานเขียนของคุณเพื่อใช้และจัดเก็บเป็นส่วนหนึ่งของบริการ SafeAssign&trade; โดยสอดคล้องตาม<a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">เงื่อนไขการให้บริการของ Blackboard</a> และ<a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">นโยบายความเป็นส่วนตัวของ Blackboard</a>;
(2) สถาบันของคุณอาจใช้งานเขียนของคุณโดยสอดคล้องตามนโยบายของสถาบันของคุณ; และ
(3) การใช้ SafeAssign ของคุณจะเป็นไปโดนปราศจากการขอความช่วยเหลือจาก Open LMS และบริษัทในเครือ';
$string['agreement'] = 'ฉันยอมรับที่จะส่งเอกสารของฉันไปยัง <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">ฐานข้อมูลอ้างอิงส่วนกลาง</a>';
$string['error_api_generic'] = 'เกิดข้อผิดพลาดในขณะประมวลผลคำขอของคุณ';
$string['error_api_unauthorized'] = 'เกิดข้อผิดพลาดเกี่ยวกับการยืนยันตัวตนในขณะประมวลผลคำขอของคุณ';
$string['error_api_forbidden'] = 'เกิดข้อผิดพลาดเกี่ยวกับการยืนยันตัวตนในขณะประมวลผลคำขอของคุณ';
$string['error_api_not_found'] = 'ไม่พบแหล่งข้อมูลที่ขอ';
$string['sync_assignments'] = 'ส่งข้อมูลที่มีไปยังเซิร์ฟเวอร์ SafeAssign';
$string['api_call_log_event'] = 'บันทึก SafeAssign สำหรับการเรียก API';
$string['course_error_sync'] = 'เกิดข้อผิดพลาดในขณะพยายามซิงค์ข้อมูลรายวิชากับ ID: {$a} ลงใน SafeAssign:<br>';
$string['assign_error_sync'] = 'เกิดข้อผิดพลาดขณะพยายามซิงค์ข้อมูลงานที่มอบหมายกับ ID: {$a} ลงใน SafeAssign:<br>';
$string['submission_error_sync'] = 'เกิดข้อผิดพลาดในขณะพยายามซิงค์ข้อมูลการบ้านที่ส่งกับ ID: {$a} ลงใน SafeAssign:<br>';
$string['submission_success_sync'] = 'การบ้านที่ส่งและซิงค์ข้อมูลสำเร็จแล้ว';
$string['assign_success_sync'] = 'งานที่มอบหมายและซิงค์ข้อมูลสำเร็จแล้ว';
$string['course_success_sync'] = 'ซิงค์ข้อมูลรายวิชาสำเร็จแล้ว';
$string['license_header'] = 'ข้อตกลงสิทธิ์การใช้งาน SafeAssign&trade;';
$string['license_title'] = 'ข้อตกลงสิทธิ์การใช้งาน SafeAssign';
$string['not_configured'] = 'SafeAssign&trade; ไม่ได้รับการกำหนดค่า โปรดให้ผู้ดูแลระบบของคุณส่งทิกเก็ต
ไปยัง <a href="https://support.openlms.net/" target="_blank" rel="noopener">ฝ่ายสนับสนุน Open LMS</a> เพื่อรับความช่วยเหลือ';
$string['agree_continue'] = 'แบบฟอร์มการบันทึก';
$string['safeassign_file_not_supported'] = 'ไม่รองรับ';
$string['safeassign_file_not_supported_help'] = 'นามสกุลของไฟล์ไม่รองรับการทำงานกับ SafeAssign หรือไฟล์มีขนาดใหญ่เกินขีดจำกัดสูงสุด';
$string['safeassign_submission_not_supported'] = 'การบ้านที่ส่งนี้จะไม่ได้รับการทบทวนโดย SafeAssign';
$string['safeassign_submission_not_supported_help'] = 'ไม่ได้ส่งการบ้านที่ส่งและจัดทำโดยผู้สอนรายวิชาไปยัง SafeAssign';
$string['safeassign_file_in_review'] = 'รายงานความคิดริเริ่มของ SafeAssign อยู่ระหว่างดำเนินการ...';
$string['safeassign_file_similarity_score'] = 'คะแนน SafeAssign: {$a}%<br>';
$string['safeassign_link_originality_report'] = 'ดูรายงานต้นฉบับ';
$string['safeassign_file_limit_exceeded'] = 'การบ้านที่ส่งนี้มีเกินขีดจำกัดรวมของขนาดคือ 10 MB และจะไม่ได้รับการประมวลผลโดย SafeAssign';
$string['originality_report'] = 'รายงานความคิดริเริ่มของ SafeAssign';
$string['originality_report_unavailable'] = 'รายงานความคิดริเริ่มตามที่ขอไม่พร้อมใช้งาน ตรวจสอบย้อนกลับในภายหลังหรือติดต่อผู้ดูแลระบบของคุณ';
$string['originality_report_error'] = 'เกิดข้อผิดพลาดกับรายงานความคิดริเริ่มของ SafeAssign โปรดติดต่อผู้ดูแลระบบของคุณ';
$string['safeassign_overall_score'] = '<b>คะแนนรวมของ SafeAssign: {$a}%</b>';
$string['messageprovider:safeassign_graded'] = 'SafeAssign จะส่งการแจ้งเตือนไปยังผู้สอน เมื่อได้ให้คะแนนการบ้านที่ส่งเกี่ยวกับการคัดลอกผลงาน';
$string['safeassign_loading_settings'] = 'กำลังโหลดการตั้งค่า โปรดรอ';
$string['safeassign:get_messages'] = 'อนุญาตให้รับการแจ้งเตือนจาก SafeAssign';
$string['safeassign_notification_message'] = 'คะแนนการคัดลอกผลงานได้รับการประมวลผล {$a->counter} {$a->plural} ใน {$a->assignmentname}';
$string['safeassign_notification_grading_link'] = 'หน้าการให้คะแนน';
$string['safeassign_notification_message_hdr'] = 'ได้ประมวลผลคะแนนการคัดลอกผลงานของ Plagiarism แล้ว';
$string['safeassign_notification_subm_singular'] = 'การบ้านที่ส่ง';
$string['safeassign_notification_subm_plural'] = 'การบ้านที่ส่ง';
$string['messageprovider:safeassign_notification'] = 'SafeAssign จะส่งการแจ้งเตือนไปยังผู้ดูแลระบบไซต์เมื่อมีข้อกำหนดและเงื่อนไขใหม่ของสิทธิ์การใช้งาน';
$string['safeassign:get_notifications'] = 'อนุญาตให้ส่งการแจ้งเตือนจาก SafeAssign';
$string['license_agreement_notification_subject'] = 'มีข้อตกลงและเงื่อนไขใหม่ของสิทธิ์การใช้งาน SafeAssign';
$string['license_agreement_notification_message'] = 'คุณสามารถยอมรับข้อตกลงและเงื่อนไขของสิทธิ์การใช้งานใหม่ได้ที่นี่: {$a}';
$string['settings_page'] = 'หน้าการตั้งค่า SafeAssign';
$string['send_notifications'] = 'ส่งการแจ้งเตือนถึงข้อตกลงและเงื่อนไขใหม่ของสิทธิ์การใช้งาน SafeAssign';
$string['privacy:metadata:core_files'] = 'ไฟล์ที่แนบไปกับการบ้านที่ส่งหรือจัดทำจากการส่งข้อความแบบออนไลน์';
$string['privacy:metadata:core_plagiarism'] = 'ปลั๊กอินนี้เรียกโดยระบบย่อยการคัดลอกผลงานของ Moodle';
$string['privacy:metadata:safeassign_service'] = 'เมื่อต้องการรับรายงานความคิดริเริ่ม ข้อมูลบางรายการของผู้ใช้งานควรส่งไปยังบริการของ SafeAssign';
$string['privacy:metadata:safeassign_service:adminemail'] = 'ผู้ดูแลระบบควรส่งอีเมล เพื่อยอมรับสิทธิ์การใช้งานบริการ';
$string['privacy:metadata:safeassign_service:filecontent'] = 'เราต้องส่งไฟล์ไปยัง SafeAssign เพื่อจัดทำรายงานความคิดริเริ่ม';
$string['privacy:metadata:safeassign_service:filename'] = 'ต้องมีชื่อไฟล์สำหรับบริการของ SafeAssign';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'UUID ของไฟล์อนุญาตให้สร้างความสัมพันธ์กับไฟล์ของ Moodle ในเซิร์ฟเวอร์ SafeAssign';
$string['privacy:metadata:safeassign_service:fullname'] = 'ระบบจะส่งชื่อผู้ใช้งานไปยัง SafeAssign เพื่ออนุญาตให้รับ Token การยืนยันตัวตน';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'ต้องมี UUID การบ้านที่ส่งนี้ เพื่อเรียกใช้รายงานความคิดริเริ่ม';
$string['privacy:metadata:safeassign_service:userid'] = 'ID ของ userid ที่ส่งจาก Moodle เพื่อช่วยให้คุณสามารถใช้บริการของ SafeAssign ได้';
$string['privacy:metadata:plagiarism_safeassign_files'] = 'ข้อมูลเกี่ยวกับความคิดริเริ่มของไฟล์ที่อัปโหลดโดยผู้ใช้งาน';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'ID ของผู้เรียนที่จัดทำการบ้านที่ส่งนี้';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'ตัวระบุเฉพาะของไฟล์ในบริการของ SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'URL ไปยังรายงานความคิดริเริ่ม';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = 'คะแนนความคล้ายคลึงสำหรับไฟล์ที่ส่งแล้ว';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'เวลาเมื่อส่งไฟล์';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'ตัวระบุเฉพาะของการบ้านที่ส่งในบริการของ SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = 'ID ของไฟล์ที่ส่ง';
$string['privacy:metadata:plagiarism_safeassign_course'] = 'ข้อมูลเกี่ยวกับรายวิชาของ Moodle ที่มี SafeAssign เปิดใช้งานอยู่';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'ตัวระบุเฉพาะของรายวิชาในบริการของ SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'รายวิชาที่มีกิจกรรมและ SafeAssign เปิดใช้งานอยู่';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'ID ของผู้ใช้งานที่เป็นอาจารย์ในรายวิชานี้';
$string['privacy:metadata:plagiarism_safeassign_subm'] = 'ข้อมูลเกี่ยวกับการบ้านที่ส่งของผู้เรียน';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'ID งานที่มอบหมายของการบ้านที่ส่งนี้';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'คะแนนเฉลี่ยเกี่ยวกับความคล้ายคลึงสำหรับไฟล์ทั้งหมดที่ส่ง';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = 'ตั้งค่าสถานะเพื่อกำหนดว่าการบ้านที่ส่งนี้มีไฟล์อยู่หรือไม่';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = 'ตั้งค่าสถานะเพื่อกำหนดว่าการบ้านที่ส่งนี้มีข้อความออนไลน์อยู่หรือไม่';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = 'คะแนนสูงสุดเกี่ยวกับความคล้ายคลึงสำหรับไฟล์ที่ส่งหนึ่งไฟล์';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'ID การบ้านที่ส่งของกิจกรรมที่ SafeAssign เปิดใช้งานอยู่';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'ตั้งค่าสถานะเพื่อกำหนดว่าได้ส่งไฟล์ไปยัง SafeAssign แล้วหรือไม่';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = 'เวลาที่สร้างการแก้ไข';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'ตัวระบุเฉพาะของการบ้านที่ส่งในบริการของ SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'ข้อมูลเกี่ยวกับอาจารย์ในแพลตฟอร์มนี้';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = 'ID ของผู้ใช้งานหนึ่งรายซึ่งเป็นอาจารย์ในรายวิชาหนึ่งรายวิชา';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'ID ของรายวิชาซึ่งผู้ใช้งานเป็นอาจารย์';
