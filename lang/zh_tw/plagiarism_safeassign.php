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
 * @copyright  Copyright (c) 2023 Open LMS (https://www.openlms.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'SafeAssign 抄襲外掛程式';
$string['getscores'] = '取得送出項目得分';
$string['getscoreslog'] = 'SafeAssign 得分工作記錄';
$string['getscoreslogfailed'] = 'SafeAssign 得分工作失敗';
$string['getscoreslog_desc'] = 'SafeAssign 得分工作已成功執行。';
$string['servicedown'] = 'SafeAssign 服務無法使用。';
$string['studentdisclosuredefault'] = '會將所有上傳的檔案送出至抄襲偵測服務';
$string['studentdisclosure'] = '機構發行聲明';
$string['studentdisclosure_help'] = '此內文會顯示於檔案上傳頁面，供所有學員觀看。如果將此
欄位為空白，則將改用預設本地化字串 (studentdisclosuredefault)。';
$string['safeassignexplain'] = '如需有關此外掛程式的詳細資訊，請參閱：';
$string['safeassign'] = 'SafeAssign 抄襲外掛程式';
$string['safeassign:enable'] = '允許教師在活動內啟用/停用 SafeAssign';
$string['safeassign:report'] = '允許從 SafeAssign 檢視 Originality Report';
$string['usesafeassign'] = '啟用 SafeAssign';
$string['savedconfigsuccess'] = '已儲存抄襲設定';
$string['safeassign_additionalroles'] = '其他角色';
$string['safeassign_additionalroles_help'] = '在系統層級具有這些角色的使用者將新增為
每門 SafeAssign 課程的講師。';
$string['safeassign_api'] = 'SafeAssign 整合 URL';
$string['safeassign_api_help'] = '這是 SafeAssign API 的位址。';
$string['instructor_role_credentials'] = '講師角色認證';
$string['safeassign_instructor_username'] = '共用金鑰';
$string['safeassign_instructor_username_help'] = '由 SafeAssign 提供的講師共用金鑰。';
$string['safeassign_instructor_password'] = '共用密鑰';
$string['safeassign_instructor_password_help'] = '由 SafeAssign 提供的講師共用密鑰。';
$string['student_role_credentials'] = '學員角色認證';
$string['safeassign_student_username'] = '共用金鑰';
$string['safeassign_student_username_help'] = '由 SafeAssign 提供的學員共用金鑰。';
$string['safeassign_student_password'] = '共用密鑰';
$string['safeassign_student_password_help'] = '由 SafeAssign 提供的學員共用密鑰。';
$string['safeassign_license_acceptor_givenname'] = '授權接受者名字';
$string['safeassign_license_acceptor_surname'] = '授權接受者姓氏';
$string['safeassign_license_acceptor_email'] = '授權接受者電子郵件地址';
$string['safeassign_license_header'] = 'SafeAssign&trade; 授權條款與條件';
$string['license_already_accepted'] = '您的管理員已接受目前的授權條款。';
$string['acceptlicense'] = '接受 SafeAssign 授權';
$string['acceptlicenselog'] = 'SafeAssign 授權工作記錄';
$string['safeassign_license_warning'] = '驗證 SafeAssign&trade; 授權資料時發生問題，請
按一下「測試連線」按鈕。如果測試成功，請稍後再試一次。';
$string['safeassign_enableplugin'] = '對 {$a} 啟用 SafeAssign';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&amp;nbsp 預設值：0</div> <br>';
$string['safeassign_showid'] = '顯示學員編號';
$string['safeassign_alloworganizations'] = '在組織中允許 SafeAssignment';
$string['safeassign_referencedbactivity'] = '<a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a> 活動';
$string['safeassing_response_header'] = '<br>SafeAssign 伺服器回應：<br>';
$string['safeassign_instructor_credentials'] = '講師角色認證：';
$string['safeassign_student_credentials'] = '學員角色認證：';
$string['safeassign_credentials_verified'] = '已驗證連線。';
$string['safeassign_credentials_fail'] = '未驗證連線。請檢查金鑰、密鑰和 URL。';
$string['credentials'] = '認證和服務 URL';
$string['shareinfo'] = '與 SafeAssign 共用資訊';
$string['disclaimer'] = '<br>送出至 SafeAssign <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a> 可讓其他機構的文件<br>
與您學員的文件互相比對，以保障作業的原創性。';
$string['settings'] = 'SafeAssign 設定';
$string['timezone_help'] = '在您的 Open LMS 環境中設定的時區。';
$string['timezone'] = '時區';
$string['safeassign_status'] = 'SafeAssign 狀態';
$string['status:pending'] = '待決';
$string['safeassign_score'] = 'SafeAssign 得分';
$string['safeassign_reporturl'] = '報告 URL';
$string['button_disabled'] = '儲存表單以測試連線';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = '從 plagiarism/safeassign/tests/fixtures 資料夾取得 json 檔案「{$a}」，以在執行 behat 測試時模擬對 SafeAssign Web 服務的呼叫時，發生錯誤。';
$string['safeassign_curlcache'] = '快取逾時';
$string['safeassign_curlcache_help'] = 'Web 服務快取逾時。';
$string['rest_error_nocurl'] = 'cURL 模組必須存在且啟用！';
$string['rest_error_nourl'] = '您必須指定 URL！';
$string['rest_error_nomethod'] = '您必須指定請求方式！';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = '測試連線';
$string['connectionfailed'] = '連線失敗';
$string['connectionverified'] = '已驗證連線';
$string['cachedef_request'] = 'SafeAssign 請求快取';
$string['error_behat_instancefail'] = '這是配置為 behat 測試失敗的例項。';
$string['assignment_check_submissions'] = '透過 SafeAssign 檢查送出項目';
$string['assignment_check_submissions_help'] = '如果設定匿名評分，則教師無法使用 SafeAssign 原創性報告，
但如果選取「允許學員檢視原創性報告」，則學員可以檢視自己的 SafeAssign 原創性報告。
<br><br>使用者送出多個檔案時，SafeAssign 會產生一份原創性報告。您可以選擇要在此報告內檢閱哪一個檔案。
<br><br>雖然 SafeAssign 官方僅支援英文，但您可以嘗試以其他語言使用 SafeAssign。
SafeAssign 並沒有防止使用其他語言的技術性限制。
請見 <a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">Blackboard 協助</a>以瞭解更多資訊。';
$string['students_originality_report'] = '允許學員檢視 Originality Report';
$string['submissions_global_reference'] = '排除 <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a> 的送出項目';
$string['submissions_global_reference_help'] = '送出項目仍會由 SafeAssign 進行處理，但不會在資料庫中註冊。如果教師在特定作業中允許重新送出，這樣可避免將檔案標記為抄襲。';
$string['plagiarism_tools'] = '抄襲工具';
$string['files_accepted'] = 'SafeAssign 僅接受 .doc、.docx、.docm、.ppt、.pptx、.odt、.txt、.rtf、.pdf 和 .html 檔案格式的檔案。任何其他格式的檔案 (包含 .zip 和其他壓縮檔案格式) 將不會透過 SafeAssign 比對。
<br><br>送出此文件表示您同意：
(1) 您送出的文件，將根據 Blackboard <a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">服務條款</a>和 <a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">Blackboard 隱私權政策</a>作為 SafeAssign&trade; 服務的一部分使用及儲存；
(2) 貴機構可以根據貴機構政策使用您的文件；以及
(3) 您使用 SafeAssign 的行為對 Open LMS 及其關係企業無追索權。';
$string['agreement'] = '我同意將我的文件送出至 <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a>。';
$string['error_api_generic'] = '處理您的請求時發生錯誤';
$string['error_api_unauthorized'] = '處理您的請求時發生驗證錯誤';
$string['error_api_forbidden'] = '處理您的請求時發生授權錯誤';
$string['error_api_not_found'] = '未找到請求的資源';
$string['sync_assignments'] = '將可用資訊傳送至 SafeAssign 伺服器。';
$string['api_call_log_event'] = 'SafeAssign 的 API 呼叫記錄。';
$string['course_error_sync'] = '嘗試將編號：{$a} 的課程同步至 SafeAssign 時發生錯誤：<br>';
$string['assign_error_sync'] = '嘗試將編號：{$a} 的作業同步至 SafeAssign 時發生錯誤：<br>';
$string['submission_error_sync'] = '嘗試將編號：{$a} 的送出項目同步至 SafeAssign 時發生錯誤：<br>';
$string['submission_success_sync'] = '送出項目已成功同步';
$string['assign_success_sync'] = '作業已成功同步';
$string['course_success_sync'] = '課程已成功同步';
$string['license_header'] = 'SafeAssign&trade; 授權合約';
$string['license_title'] = 'SafeAssign 授權合約';
$string['not_configured'] = '未配置 SafeAssign&trade;。請您的系統管理員在
<a href="https://support.openlms.net/" target="_blank" rel="noopener">Open LMS Support</a> 上送出票證以尋求協助。';
$string['agree_continue'] = '儲存表單';
$string['safeassign_file_not_supported'] = '不受支援。';
$string['safeassign_file_not_supported_help'] = '檔案副檔名不受 SafeAssign 支援，或檔案大小超過最大容量。';
$string['safeassign_submission_not_supported'] = 'SafeAssign 不會審閱此送出項目。';
$string['safeassign_submission_not_supported_help'] = '課程講師建立的送出項目不會傳送至 SafeAssign。';
$string['safeassign_file_in_review'] = 'SafeAssign Originality Report 進行中...';
$string['safeassign_file_similarity_score'] = 'SafeAssign 得分：{$a}%<br>';
$string['safeassign_link_originality_report'] = '檢視 Originality Report';
$string['safeassign_file_limit_exceeded'] = '此送出項目超出 10 MB 的組合大小上限，SafeAssign 不會對其進行處理';
$string['originality_report'] = 'SafeAssign Originality Report';
$string['originality_report_unavailable'] = '請求的 Originality Report 無法使用。請稍後回來查看或聯絡您的系統管理員。';
$string['originality_report_error'] = 'SafeAssign Originality Report 發生錯誤。請聯絡您的系統管理員。';
$string['safeassign_overall_score'] = '<b>SafeAssign 整體得分：{$a}%</b>';
$string['messageprovider:safeassign_graded'] = '已對送出項目進行抄襲評分後，SafeAssign 會向講師傳送通知';
$string['safeassign_loading_settings'] = '正在載入設定，請稍候';
$string['safeassign:get_messages'] = '允許接收 SafeAssign 通知';
$string['safeassign_notification_message'] = '已處理 {$a->assignmentname} 中 {$a->counter} {$a->plural} 的抄襲得分';
$string['safeassign_notification_grading_link'] = '評分頁面';
$string['safeassign_notification_message_hdr'] = '已處理抄襲 SafeAssign 得分';
$string['safeassign_notification_subm_singular'] = '送出項目';
$string['safeassign_notification_subm_plural'] = '送出項目';
$string['messageprovider:safeassign_notification'] = '有新的授權條款與條件時，SafeAssign 會向網站管理員傳送通知';
$string['safeassign:get_notifications'] = '允許來自 SafeAssign 的通知';
$string['license_agreement_notification_subject'] = '有新的 SafeAssign 授權條款與條件';
$string['license_agreement_notification_message'] = '您可以在此處接受新的授權條款與條件：{$a}';
$string['settings_page'] = 'SafeAssign 設定頁面';
$string['send_notifications'] = '傳送 SafeAssign 新授權條款與條件通知。';
$string['privacy:metadata:core_files'] = '附加到送出項目或從線上文字送出項目建立的檔案。';
$string['privacy:metadata:core_plagiarism'] = '此外掛程式由 Moodle 抄襲子系統呼叫。';
$string['privacy:metadata:safeassign_service'] = '若要取得 Originality Report，必須將一些使用者資料傳送至 SafeAssign 服務。';
$string['privacy:metadata:safeassign_service:adminemail'] = '管理員應傳送其電子郵件，以接受服務授權。';
$string['privacy:metadata:safeassign_service:filecontent'] = '我們需要將檔案傳送至 SafeAssign，以產生 Originality Report。';
$string['privacy:metadata:safeassign_service:filename'] = 'SafeAssign 服務需要提供檔案名稱。';
$string['privacy:metadata:safeassign_service:fileuuid'] = '檔案 uuid 可允許在 SafeAssign 伺服器中關聯 Moodle 檔案。';
$string['privacy:metadata:safeassign_service:fullname'] = '使用者名稱已傳送至 SafeAssign，以便取得驗證 Token。';
$string['privacy:metadata:safeassign_service:submissionuuid'] = '需要此送出項目 uuid 以擷取 Originality Report。';
$string['privacy:metadata:safeassign_service:userid'] = '已從 Moodle 傳送 userid，以便您可以使用 SafeAssign 服務。';
$string['privacy:metadata:plagiarism_safeassign_files'] = '有關使用者所上傳檔案之原創性的資訊';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = '送出此項目的學員編號。';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'SafeAssign 服務中的檔案唯一識別碼。';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'Originality Report 的 URL。';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = '所送出檔案的相似度得分。';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = '送出檔案的時間。';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'SafeAssign 服務中的送出項目唯一識別碼';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = '送出的檔案編號。';
$string['privacy:metadata:plagiarism_safeassign_course'] = '有關已啟用 SafeAssign 之 Moodle 課程的資訊。';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'SafeAssign 服務中的課程唯一識別碼。';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = '其中有活動已啟用 SafeAssign 的課程。';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = '身為此課程中教師的使用者編號。';
$string['privacy:metadata:plagiarism_safeassign_subm'] = '有關學員送出項目的資訊。';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = '此送出項目的作業編號。';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = '所有送出檔案的平均相似度得分。';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = '用於確定送出項目是否包含檔案的旗標。';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = '用於確定送出項目是否包含線上文字的旗標。';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = '一個已送出檔案的最高相似度得分。';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = '已啟用 SafeAssign 之活動的送出項目編號。';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = '用於確定檔案是否已傳送至 SafeAssign 的旗標。';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = '建立送出項目的時間。';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'SafeAssign 服務中的送出項目唯一識別碼。';
$string['privacy:metadata:plagiarism_safeassign_instr'] = '有關平台中教師的資訊。';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = '身為一門課程中教師的使用者編號。';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = '使用者身為其中教師的課程編號。';
