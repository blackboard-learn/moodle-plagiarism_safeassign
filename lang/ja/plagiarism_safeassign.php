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

$string['pluginname'] = 'SafeAssign盗作プラグイン';
$string['getscores'] = '提出物のスコアを取得する';
$string['getscoreslog'] = 'SafeAssignスコアタスクログ';
$string['getscoreslogfailed'] = 'SafeAssignスコアタスク失敗';
$string['getscoreslog_desc'] = 'SafeAssignスコアタスクが正常に実行されました。';
$string['servicedown'] = 'SafeAssignサービスは利用できません。';
$string['studentdisclosuredefault'] = 'アップロードしたファイルはすべて盗作検出サービスに提出されます';
$string['studentdisclosure'] = '教育機関リリースステートメント';
$string['studentdisclosure_help'] = 'このテキストが、ファイルアップロードページのすべての学生に表示されます。
このフィールドを空のままにすると、代わりにデフォルトのローカライズされた文字列（studentdisclosuredefault）が使用されます。';
$string['safeassignexplain'] = 'このプラグインの詳細については、こちらを参照してください：';
$string['safeassign'] = 'SafeAssign盗作プラグイン';
$string['safeassign:enable'] = '教師がアクティビティ内でSafeAssignを有効/無効にすることを許可する';
$string['safeassign:report'] = 'SafeAssignからオリジナリティレポートの表示を許可する';
$string['usesafeassign'] = 'SafeAssignを有効にする';
$string['savedconfigsuccess'] = '盗作設定が保存されました';
$string['safeassign_additionalroles'] = '追加ロール';
$string['safeassign_additionalroles_help'] = 'システムレベルでこれらのロールを持つユーザは各SafeAssign
コースに教員として追加されます。';
$string['safeassign_api'] = 'SafeAssign統合URL';
$string['safeassign_api_help'] = 'これはSafeAssign APIのアドレスです。';
$string['instructor_role_credentials'] = '教員ロールの認証情報';
$string['safeassign_instructor_username'] = '共有キー';
$string['safeassign_instructor_username_help'] = 'SafeAssignによって提供される教員の共有キーです。';
$string['safeassign_instructor_password'] = '共有プライベートキー';
$string['safeassign_instructor_password_help'] = 'SafeAssignによって提供される教員の共有プライベートキーです。';
$string['student_role_credentials'] = '学生ロールの認証情報';
$string['safeassign_student_username'] = '共有キー';
$string['safeassign_student_username_help'] = 'SafeAssignによって提供される学生の共有キーです。';
$string['safeassign_student_password'] = '共有プライベートキー';
$string['safeassign_student_password_help'] = 'SafeAssignによって提供される学生の共有プライベートキーです。';
$string['safeassign_license_acceptor_givenname'] = 'ライセンス受諾者の名';
$string['safeassign_license_acceptor_surname'] = 'ライセンス受諾者の姓';
$string['safeassign_license_acceptor_email'] = 'ライセンス受諾者のメール';
$string['safeassign_license_header'] = 'SafeAssigntrade&trade;ライセンス契約条件';
$string['license_already_accepted'] = '現在のライセンス条項はすでに管理者によって受諾されています。';
$string['acceptlicense'] = 'SafeAssignライセンスを受諾する';
$string['acceptlicenselog'] = 'SafeAssignライセンスタスクログ';
$string['safeassign_license_warning'] = 'SafeAssigntrade&trade;ライセンスデータの検証に問題があります。
[テスト接続]ボタンをクリックしてください。テストが成功した場合、後でやり直してください。';
$string['safeassign_enableplugin'] = '{$a} に対してSafeAssignを有効にする';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&amp;nbspデフォルト値：0</div> <br>';
$string['safeassign_showid'] = '学生IDを表示する';
$string['safeassign_alloworganizations'] = '組織でのSafeAssignmentsを許可する';
$string['safeassign_referencedbactivity'] = '<a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a>アクティビティ';
$string['safeassing_response_header'] = '<br>SafeAssignサーバの応答：<br>';
$string['safeassign_instructor_credentials'] = '教員ロールの認証情報：';
$string['safeassign_student_credentials'] = '学生ロールの認証情報：';
$string['safeassign_credentials_verified'] = '接続が確認されました。';
$string['safeassign_credentials_fail'] = '接続が確認されませんでした。キー、プライベートキー、URLを確認してください。';
$string['credentials'] = '認証情報とサービスURL';
$string['shareinfo'] = 'SafeAssignと情報を共有する';
$string['disclaimer'] = '<br>SafeAssign <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a>に提出すると、他の機関のレポートと学生のレポートを照合して、<br>
学生レポートがオリジナルであることを確認できます。';
$string['settings'] = 'SafeAssign設定';
$string['timezone_help'] = 'Open LMS環境に設定されたタイムゾーン。';
$string['timezone'] = 'タイムゾーン';
$string['safeassign_status'] = 'SafeAssignステータス';
$string['status:pending'] = '保留中';
$string['safeassign_score'] = 'SafeAssignスコア';
$string['safeassign_reporturl'] = 'レポートURL';
$string['button_disabled'] = '接続をテストするためにフォームを保存する';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'Behatテスト実行時にSafeAssignウェブサービスの呼び出しをシミュレーションするために、フォルダplagiarism/safeassign/tests/fixturesからjsonファイル「{$a}」を取得するときのエラー。';
$string['safeassign_curlcache'] = 'キャッシュタイムアウト';
$string['safeassign_curlcache_help'] = 'ウェブサービスのキャッシュタイムアウト。';
$string['rest_error_nocurl'] = 'cURLモジュールが存在し、有効になっている必要があります！';
$string['rest_error_nourl'] = 'URLを指定する必要があります！';
$string['rest_error_nomethod'] = 'リクエスト方法を指定する必要があります！';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'テスト接続';
$string['connectionfailed'] = '接続に失敗しました';
$string['connectionverified'] = '接続が確認されました';
$string['cachedef_request'] = 'SafeAssignリクエストキャッシュ';
$string['error_behat_instancefail'] = 'これは、Behatテストで失敗するように設定されたインスタンスです。';
$string['assignment_check_submissions'] = 'SafeAssignで提出物を確認する';
$string['assignment_check_submissions_help'] = 'SafeAssign Originality Reportは、匿名評定が設定されている場合、使用できません。
ただし、[学生にオリジナリティレポートの表示を許可する]が選択されている場合、学生は自分のSafeAssign Originality Reportを表示できます。
<br><br>ユーザが複数のファイルを提出すると、SafeAssignは単一のオリジナリティレポートを送信します。このレポートの中からレビューするファイルを選択できます。
<br><br>SafeAssignは公式には英語のみをサポートしていますが、他の言語でSafeAssignを使用することもできます。
SafeAssignには、他の言語での使用に対して技術的な制限はありません。
詳細については、<a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">Blackboardのヘルプ</a>を参照してください。';
$string['students_originality_report'] = '学生にオリジナリティレポートの表示を許可する';
$string['submissions_global_reference'] = '<a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a>からの提出を除外する';
$string['submissions_global_reference_help'] = '提出物は引き続きSafeAssignによって処理されますが、データベースには登録されません。これにより、教師が特定の課題で再提出を許可する場合に、ファイルが盗作としてマークされるのを防ぎます。';
$string['plagiarism_tools'] = '盗作ツール';
$string['files_accepted'] = 'SafeAssignがサポートしているファイル形式は、doc、.docx、.docm、.ppt、.pptx、.odt、.txt、.rtf、.pdf、.htmlです。.zipやその他の圧縮ファイル形式については、SafeAssignでは確認されません。
<br><br>このレポートを提出するには、次に同意する必要があります。
(1) Blackboard<a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">利用規約</a>および<a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">Blackboardプライバシーポリシー</a>に従って、SafeAssign&trade;サービスの一部として、使用および保存するためにレポートを提出すること
(2) 所属機関がそのポリシーに従ってレポートを使用すること
(3) SafeAssignの使用に関して、Open LMSおよびその関連企業に対して償還請求しないこと。';
$string['agreement'] = '<a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a>にレポートを提出することに同意します。';
$string['error_api_generic'] = 'リクエストの処理中にエラーが発生しました';
$string['error_api_unauthorized'] = 'リクエストの処理中に認証エラーが発生しました';
$string['error_api_forbidden'] = 'リクエストの処理中に承認エラーが発生しました';
$string['error_api_not_found'] = 'リクエストされたリソースが見つかりませんでした';
$string['sync_assignments'] = 'SafeAssignサーバに利用可能な情報を送信します。';
$string['api_call_log_event'] = 'API呼び出しのSafeAssignログ。';
$string['course_error_sync'] = 'ID：{$a}のコースをSafeAssignに同期しようとしてエラーが発生しました：<br>';
$string['assign_error_sync'] = 'ID：{$a}の課題をSafeAssignに同期しようとしてエラーが発生しました：<br>';
$string['submission_error_sync'] = 'ID：{$a}の提出物をSafeAssignに同期しようとしてエラーが発生しました：<br>';
$string['submission_success_sync'] = '提出物が正常に同期されました';
$string['assign_success_sync'] = '課題が正常に同期されました';
$string['course_success_sync'] = 'コースが正常に同期されました';
$string['license_header'] = 'SafeAssign&trade;ライセンス契約';
$string['license_title'] = 'SafeAssignライセンス契約';
$string['not_configured'] = 'SafeAssigntrade&trade;が設定されていません。支援を受けるために、
<a href="https://support.openlms.net/" target="_blank" rel="noopener">Open LMSサポート</a>にチケットを送信することをシステム管理者に依頼してください。';
$string['agree_continue'] = 'フォームを保存する';
$string['safeassign_file_not_supported'] = 'サポートされていません。';
$string['safeassign_file_not_supported_help'] = 'ファイル拡張子がSafeAssignでサポートされていないか、ファイルサイズが最大容量を超えています。';
$string['safeassign_submission_not_supported'] = 'この提出物はSafeAssignによってレビューされません。';
$string['safeassign_submission_not_supported_help'] = 'コース教員によって作成された提出物はSafeAssignに送信されません。';
$string['safeassign_file_in_review'] = 'SafeAssign Originality Reportが進行中...';
$string['safeassign_file_similarity_score'] = 'SafeAssignスコア：{$a}%<br>';
$string['safeassign_link_originality_report'] = 'オリジナリティレポートを表示する';
$string['safeassign_file_limit_exceeded'] = 'この提出物は合計サイズ制限の10 MBを超えているため、SafeAssignで処理されません';
$string['originality_report'] = 'SafeAssign Originality Report';
$string['originality_report_unavailable'] = 'リクエストされたOriginality Reportは利用できません。後で確認するか、システム管理者に連絡してください。';
$string['originality_report_error'] = 'SafeAssign\'s Originality Reportでエラーが発生しました。システム管理者に連絡してください。';
$string['safeassign_overall_score'] = '<b>SafeAssignの総合スコア：{$a}%</b>';
$string['messageprovider:safeassign_graded'] = '提出物の盗作が評定されると、SafeAssignは教員に通知を送信します。';
$string['safeassign_loading_settings'] = '設定をロードしています。お待ちください';
$string['safeassign:get_messages'] = 'SafeAssignからの通知の受信を許可する';
$string['safeassign_notification_message'] = '{$a->assignmentname}の{$a->counter} {$a->plural}の盗作スコアが処理されました';
$string['safeassign_notification_grading_link'] = '評定ページ';
$string['safeassign_notification_message_hdr'] = '盗作SafeAssignスコアが処理されました';
$string['safeassign_notification_subm_singular'] = '提出物';
$string['safeassign_notification_subm_plural'] = '提出物';
$string['messageprovider:safeassign_notification'] = 'SafeAssignは、新しいライセンス契約条件が利用可能になるとサイト管理者に通知を送信します';
$string['safeassign:get_notifications'] = 'SafeAssignからの通知を許可する';
$string['license_agreement_notification_subject'] = '新しいSafeAssignライセンス契約条件が利用可能';
$string['license_agreement_notification_message'] = '新しいライセンス契約条件はここで承諾することができます：{$a}';
$string['settings_page'] = 'SafeAssign設定ページ';
$string['send_notifications'] = 'SafeAssignの新しいライセンス契約条件の通知を送信します。';
$string['privacy:metadata:core_files'] = '提出物に添付されたファイル、またはオンラインのテキスト提出物から作成されたファイル。';
$string['privacy:metadata:core_plagiarism'] = 'このプラグインはMoodle盗作サブシステムによって呼び出されます。';
$string['privacy:metadata:safeassign_service'] = 'オリジナリティレポートを取得するためには、ユーザデータをSafeAssignサービスに送信する必要があります。';
$string['privacy:metadata:safeassign_service:adminemail'] = '管理者はサービスライセンスを承諾するために、メールを送信する必要があります。';
$string['privacy:metadata:safeassign_service:filecontent'] = 'オリジナリティレポートを生成するには、SafeAssignにファイルを送信する必要があります。';
$string['privacy:metadata:safeassign_service:filename'] = 'SafeAssignサービスのためにファイル名が必要です。';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'ファイルUUIDを使用すると、SafeAssignサーバでMoodleファイルを関連付けることができます。';
$string['privacy:metadata:safeassign_service:fullname'] = '認証トークンを取得できるようにSafeAssignにユーザ名が送信されます。';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'この提出物UUIDはオリジナリティレポートを取得するために必要です。';
$string['privacy:metadata:safeassign_service:userid'] = 'SafeAssignサービスを使用できるようにMoodleからユーザIDが送信されます。';
$string['privacy:metadata:plagiarism_safeassign_files'] = 'ユーザによってアップロードされたファイルのオリジナリティに関する情報';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'この提出を行った学生のID。';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'SafeAssignサービス内のファイルの一意の識別子。';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'オリジナリティレポートへのURL。';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = '提出されたファイルの類似性スコア。';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'ファイルが提出された日時。';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'SafeAssignサービスの提出物に対する一意の識別子';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = '提出されたファイルのID。';
$string['privacy:metadata:plagiarism_safeassign_course'] = 'SafeAssignが有効になっているMoodleコースに関する情報。';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'SafeAssignサービス内のコースの一意の識別子。';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'SafeAssignが有効になっているアクティビティがあるコース。';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'このコースの教師であるユーザのID。';
$string['privacy:metadata:plagiarism_safeassign_subm'] = '学生の提出物に関する情報。';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'この提出物の課題ID。';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'すべての提出済みファイルの平均類似性スコア。';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = '提出物にファイルが含まれるかどうかを判断するためのフラグ。';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = '提出物にオンラインのテキストが含まれるかどうかを判断するためのフラグ。';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = '1つの提出済みファイルの最高類似性スコア。';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'SafeAssignが有効になっているアクティビティの提出物ID。';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'ファイルがSafeAssignに送信されたかどうかを判断するためのフラグ。';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = '提出物が作成された日時。';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'SafeAssignサービス内の提出物の一意の識別子。';
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'プラットフォーム内の教師に関する情報。';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = '1つのコースの教師であるユーザのID。';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'ユーザが教師であるコースのID。';
