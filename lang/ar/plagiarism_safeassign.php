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
 * @copyright  Copyright (c) 2019 Blackboard Inc. (http://www.blackboard.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'المكون الإضافي للانتحال SafeAssign';
$string['getscores'] = 'الحصول على درجات الواجبات المرسلة';
$string['getscoreslog'] = 'سجل مهام درجات SafeAssign';
$string['getscoreslogfailed'] = 'فشل مهمة درجة SafeAssign';
$string['getscoreslog_desc'] = 'تم تشغيل مهام درجات SafeAssign بنجاح.';
$string['servicedown'] = 'خدمة SafeAssign غير متاحة.';
$string['studentdisclosuredefault'] = 'سيتم إرسال جميع الملفات التي تم رفعها إلى خدمة اكتشاف الانتحال';
$string['studentdisclosure'] = 'بيان إصدار المؤسسة';
$string['studentdisclosure_help'] = 'سيتم عرض هذا النص لجميع الطلاب في صفحة رفع الملف. إذا تم ترك هذا
الحقل فارغًا، وسيتم استخدام السلسلة المحلية الافتراضية (studentdisclosuredefault) بدلاً من ذلك.';
$string['safeassignexplain'] = 'لمزيد من المعلومات حول هذا المكون الإضافي راجع: ';
$string['safeassign'] = 'المكون الإضافي للانتحال SafeAssign';
$string['safeassign:enable'] = 'السماح للمعلم بتمكين/تعطيل SafeAssign داخل النشاط';
$string['safeassign:report'] = 'السماح بعرض Originality Report من SafeAssign';
$string['usesafeassign'] = 'تمكين SafeAssign';
$string['savedconfigsuccess'] = 'تم حفظ إعدادات الانتحال';
$string['safeassign_additionalroles'] = 'الأدوار الإضافية';
$string['safeassign_additionalroles_help'] = 'سيتم إضافة المستخدمين الذين يقومون بهذه الأدوار على مستوى النظام إلى كل مقرر دراسي في SafeAssign
كمدرسين.';
$string['safeassign_api'] = 'عنوان URL لتكامل SafeAssign';
$string['safeassign_api_help'] = 'هذا هو عنوان API SafeAssign.';
$string['instructor_role_credentials'] = 'بيانات اعتماد دور المدرس';
$string['safeassign_instructor_username'] = 'مفتاح مشترك';
$string['safeassign_instructor_username_help'] = 'المفتاح المشترك للمدرس الذي تم توفيره من قبل SafeAssign.';
$string['safeassign_instructor_password'] = 'كلمة السر المشتركة';
$string['safeassign_instructor_password_help'] = 'كلمة السر المشتركة للمدرس المقدمة من SafeAssign.';
$string['student_role_credentials'] = 'بيانات اعتماد دور الطالب';
$string['safeassign_student_username'] = 'مفتاح مشترك';
$string['safeassign_student_username_help'] = 'المفتاح المشترك للطالب الذي تم توفيره من قبل SafeAssign.';
$string['safeassign_student_password'] = 'كلمة السر المشتركة';
$string['safeassign_student_password_help'] = 'كلمة السر المشتركة للطالب التي تم توفيرها من قبل SafeAssign.';
$string['safeassign_license_acceptor_givenname'] = 'الاسم الأول لمتلقي الترخيص';
$string['safeassign_license_acceptor_surname'] = 'لقب متلقي الترخيص';
$string['safeassign_license_acceptor_email'] = 'البريد الإلكتروني لمتلقي الترخيص';
$string['safeassign_license_header'] = 'شروط وأحكام ترخيص SafeAssign&trade;';
$string['license_already_accepted'] = 'تم قبول شروط الترخيص الحالية بالفعل من قبل المسؤول.';
$string['acceptlicense'] = 'قبول ترخيص SafeAssign';
$string['acceptlicenselog'] = 'سجل مهام ترخيص SafeAssign';
$string['safeassign_license_warning'] = 'توجد مشكلة في التحقق من صحة بيانات ترخيص SafeAssign&trade;، يرجى
النقر فوق زر "اختبار الاتصال". في حالة نجاح الاختبار، حاول مرة أخرى لاحقاً.';
$string['safeassign_enableplugin'] = 'تمكين SafeAssign لـ {$a}';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&nbsp القيمة الافتراضية: 0</div> <br>';
$string['safeassign_showid'] = 'إظهار معرف الطالب';
$string['safeassign_alloworganizations'] = 'السماح بـ SafeAssignments في منتديات المجموعة';
$string['safeassign_referencedbactivity'] = 'نشاط <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">Global Reference Database</a>';
$string['safeassing_response_header'] = '<br>إجابة خادم SafeAssign: <br>';
$string['safeassign_instructor_credentials'] = 'بيانات اعتماد دور المدرس: ';
$string['safeassign_student_credentials'] = 'بيانات اعتماد دور الطالب: ';
$string['safeassign_credentials_verified'] = 'تم التحقق من الاتصال.';
$string['safeassign_credentials_fail'] = 'لم يتم التحقق من الاتصال. تحقق من المفتاح وكلمة السر وعنوان URL.';
$string['credentials'] = 'بيانات الاعتماد وعنوان URL الخاص بالخدمة';
$string['shareinfo'] = 'مشاركة المعلومات مع SafeAssign';
$string['disclaimer'] = '<br>يسمح الإرسال إلى SafeAssign <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">Global Reference Database</a> بمقارنة الأوراق الواردة من المؤسسات الأخرى <br>
                        بورق الطلاب لحماية أصل الأعمال الخاصة بهم.';
$string['settings'] = 'إعدادات SafeAssign';
$string['timezone_help'] = 'المنطقة الزمنية المحددة في بيئة Blackboard Open LMS.';
$string['timezone'] = 'المنطقة الزمنية';
$string['safeassign_status'] = 'حالة SafeAssign';
$string['status:pending'] = 'معلق';
$string['safeassign_score'] = 'درجة SafeAssign';
$string['safeassign_reporturl'] = 'عنوان URL الخاص بالتقرير';
$string['button_disabled'] = 'حفظ النموذج لاختبار الاتصال';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'حدث خطأ في الحصول على ملف json "{$a}" من مجلد plagiarism/safeassign/tests/fixtures بشأن محاكاة استدعاء خدمات الويب الخاصة بـ SafeAssign عند تشغيل اختبارات behat.';
$string['safeassign_curlcache'] = 'مهلة ذاكرة التخزين المؤقت';
$string['safeassign_curlcache_help'] = 'مهلة ذاكرة التخزين المؤقت الخاصة بخدمة الويب.';
$string['rest_error_nocurl'] = 'يجب أن تكون الوحدة النمطية الخاصة بـ cURL موجودة ومُمكنة!';
$string['rest_error_nourl'] = 'يتعين عليك تعيين عنوان URL!';
$string['rest_error_nomethod'] = 'يتعين عليك تعيين أسلوب الطلب!';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'اختبار الاتصال';
$string['connectionfailed'] = 'فشل الاتصال';
$string['connectionverified'] = 'تم التحقق من الاتصال';
$string['cachedef_request'] = 'ذاكرة التخزين المؤقت الخاصة بطلب SafeAssign';
$string['error_behat_instancefail'] = 'تم تكوين هذا المثيل عند الفشل في اختبارات behat.';
$string['assignment_check_submissions'] = 'تحقق من الواجبات المرسلة باستخدام SafeAssign';
$string['assignment_check_submissions_help'] = 'لن تتم إتاحة SafeAssign Originality Reports للمعلمين في حالة تعيين التقدير المجهول
  ولكن سيتمكن الطلاب من عرض SafeAssign Originality Reports الخاصة بهم إذا تم تحديد "السماح للطلاب بمشاهدة Originality Report".
<br><br>على الرغم من دعم SafeAssign للغة الإنجليزية فقط رسميًا، فإن العملاء مدعوون لمحاولة استخدام SafeAssign مع لغات أخرى غير الإنجليزية.
لا تحتوي SafeAssign على أي قيود تقنية تمنع استخدامها مع لغات أخرى..
راجع <a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">Blackboard help</a> للحصول على مزيد من المعلومات.';
$string['students_originality_report'] = 'السماح للطلاب بعرض Originality Report';
$string['submissions_global_reference'] = 'استبعاد الواجبات المرسلة من institutional and <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">Global Reference Database</a>';
$string['submissions_global_reference_help'] = 'ستظل الواجبات المرسلة قيد معالجة SafeAssign ولكن لن يتم تسجيلها في قواعد البيانات. يؤدي ذلك إلى تجنب وضع علامات على الملفات على أنها مسروقة عند سماح المعلمون بإعادة تقديمها في واجب معين.';
$string['plagiarism_tools'] = 'أدوات الانتحال';
$string['files_accepted'] = 'تقبل SafeAssign الملفات الموجودة بتنسيقات الملفات .doc و.docx و.docm و.ppt و.pptx و.odt و.txt و.rtf و.pdf و.html فقط. لن يتم التحقق من الملفات الموجودة بأي تنسيق آخر، بما في ذلك تنسيق .zip وتنسيقات الملفات المضغوطة الأخرى، من خلال SafeAssign.
<br><br>من خلال إرسال هذه الورقة، فإنك توافق على:
 (1) أنك تقوم بإرسال ورقتك لاستخدامها وتخزينها كجزء من خدمات SafeAssign&trade; وفقًا لـ <a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">شروط وخدمة</a> Blackboard و<a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">سياسة خصوصية Blackboard</a>;
 (2) tأنه يجوز لمؤسستك استخدام ورقتك وفقاً لسياسات مؤسستك؛ و
 (3) أن استخدامك لـ SafeAssign سيكون دون اللجوء إلى شركة Blackboard Inc. والشركات التابعة لها.';
$string['agreement'] = 'أوافق على إرسال ورقتي (أوراقي) إلى <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">Global Reference Database</a>.';
$string['error_api_generic'] = 'حدث خطأ أثناء معالجة طلبك';
$string['error_api_unauthorized'] = 'حدث خطأ في التفويض أثناء معالجة طلبك';
$string['error_api_forbidden'] = 'لم يتم العثور على المورد المطلوب';
$string['error_api_not_found'] = 'تعذر العثور على المورد المطلوب';
$string['sync_assignments'] = 'إرسال المعلومات المتاحة إلى خادم SafeAssign.';
$string['api_call_log_event'] = 'سجل SafeAssign الخاص بعمليات استدعاء API.';
$string['course_error_sync'] = 'حدث خطأ أثناء محاولة مزامنة المقرر الدراسي مع المعرف: {$a} في SafeAssign: <br>';
$string['assign_error_sync'] = 'حدث خطأ أثناء محاولة مزامنة الواجب مع المعرف: {$a} في SafeAssign: <br>';
$string['submission_error_sync'] = 'حدث خطأ أثناء محاولة مزامنة الواجب المرسل مع المعرف: {$a} في SafeAssign: <br>';
$string['submission_success_sync'] = 'تمت مزامنة الواجبات المرسلة بنجاح';
$string['assign_success_sync'] = 'تمت مزامنة الواجبات بنجاح';
$string['course_success_sync'] = 'تمت مزامنة المقررات الدراسية بنجاح';
$string['license_header'] = 'اتفاقية ترخيص SafeAssign&trade;';
$string['license_title'] = 'اتفاقية ترخيص SafeAssign';
$string['not_configured'] = 'لم يتم تكوين SafeAssign&trade;. يرجى مطالبة مسؤول النظام بإرسال تذكرة إلى Behind the Blackboard للحصول على مساعدة.';
$string['agree_continue'] = 'حفظ النموذج';
$string['safeassign_file_not_supported'] = 'غير مدعوم.';
$string['safeassign_file_not_supported_help'] = 'ملحق الملف غير مدعوم من قبل SafeAssign أو أن حجم الملف يتجاوز الحد الأقصى للسعة.';
$string['safeassign_submission_not_supported'] = 'لن تتم مراجعة هذا الواجب المرسل من قبل SafeAssign.';
$string['safeassign_submission_not_supported_help'] = 'لا يتم إرسال الواجبات المرسلة التي تم إنشاؤها بواسطة مدرسي المقرر الدراسي إلى SafeAssign.';
$string['safeassign_file_in_review'] = 'SafeAssign Originality Report قيد التقدم...';
$string['safeassign_file_similarity_score'] = 'درجة SafeAssign: {$a}%<br>';
$string['safeassign_link_originality_report'] = 'عرض Originality Report';
$string['safeassign_file_limit_exceeded'] = 'يتجاوز هذا الواجب المرسل الحد الإجمالي للحجم الذي يبلغ 10 ميغابايت ولن تتم معالجته بواسطة SafeAssign';
$string['originality_report'] = 'SafeAssign Originality Report';
$string['originality_report_unavailable'] = 'Originality Report المطلوب غير متاح. تحقق مرة أخرى في وقت لاحق أو اتصل بـ"مسؤول النظام".';
$string['originality_report_error'] = 'حدث خطأ في Originality Report الخاص بـ SafeAssign. اتصل بـ"مسؤول النظام".';
$string['safeassign_overall_score'] = '<b>الدرجة الكلية لـ SafeAssign: {$a}%</b>';
$string['messageprovider:safeassign_graded'] = 'ترسل SafeAssign إعلامات إلى المدرسين عند تقدير الواجب المرسل للانتحال';
$string['safeassign_loading_settings'] = 'تحميل الإعدادات، يرجى الانتظار';
$string['safeassign:get_messages'] = 'السماح بتلقي الإعلامات من SafeAssign';
$string['safeassign_notification_message'] = 'تمت معالجة درجات الانتحال لـ {$a->counter} {$a->plural} في {$a->assignmentname}';
$string['safeassign_notification_grading_link'] = 'صفحة التقدير';
$string['safeassign_notification_message_hdr'] = 'تمت معالجة درجات SafeAssign للانتحال';
$string['safeassign_notification_subm_singular'] = 'الواجب المرسل';
$string['safeassign_notification_subm_plural'] = 'الواجبات المرسلة';
$string['messageprovider:safeassign_notification'] = 'ترسل SafeAssign إعلامات إلى "مسؤولي الموقع" عند توفر "شروط وأحكام ترخيص" جديدة';
$string['safeassign:get_notifications'] = 'السماح بالإعلامات من SafeAssign';
$string['license_agreement_notification_subject'] = 'شروط وأحكام ترخيص SafeAssign الجديدة متاحة';
$string['license_agreement_notification_message'] = 'يمكنك قبول شروط وأحكام الترخيص الجديدة هنا: {$a}';
$string['settings_page'] = 'صفحة إعدادات SafeAssign';
$string['send_notifications'] = 'إرسال إعلامات شروط وأحكام الترخيص الجديدة من SafeAssign.';
$string['privacy:metadata:core_files'] = 'الملفات المرفقة بالواجبات المرسلة أو التي تم إنشاؤها من الواجبات المرسلة النصية عبر الإنترنت.';
$string['privacy:metadata:core_plagiarism'] = 'يسمى هذا المكون الإضافي من قبل النظام الفرعي للانتحال من Moodle.';
$string['privacy:metadata:safeassign_service'] = 'للحصول على Originality Report، يجب إرسال بعض بيانات المستخدم إلى خدمة SafeAssign.';
$string['privacy:metadata:safeassign_service:adminemail'] = 'يتعين على المسؤول إرسال بريده الإلكتروني لقبول ترخيص الخدمة.';
$string['privacy:metadata:safeassign_service:filecontent'] = 'نحتاج إلى إرسال الملفات إلى SafeAssign لإنشاء Originality Report.';
$string['privacy:metadata:safeassign_service:filename'] = 'مطلوب توفير اسم الملف للحصول على خدمة SafeAssign.';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'يسمح ملف uuid بربط ملفات Moodle الموجودة في خادم SafeAssign.';
$string['privacy:metadata:safeassign_service:fullname'] = 'يتم إرسال اسم المستخدم إلى SafeAssign للسماح بالحصول على الرمز المميز للمصادقة.';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'مطلوب توفير uuid الخاص بالواجب المرسل لاسترداد Originality Report.';
$string['privacy:metadata:safeassign_service:userid'] = 'معرف userid الذي تم إرساله من Moodle للسماح لك باستخدام خدمات SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_files'] = 'معلومات حول أصالة الملفات التي تم رفعها من قبل المستخدم';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'معرف الطالب الذي قام بإجراء هذا الواجب المرسل.';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'معرف الملف الفريد في خدمة SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'عنوان URL الخاص بتقرير Originality Report.';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = 'درجة التشابه الخاصة بالملف المرسل.';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'الوقت الذي تم فيه إرسال الملف.';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'المعرف الفريد للواجب المرسل في خدمة SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = 'معرف الملف الذي تم إرساله.';
$string['privacy:metadata:plagiarism_safeassign_course'] = 'معلومات حول مقررات Moodle الدراسية التي تم تمكين SafeAssign بها.';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'المعرف الفريد للمقرر الدراسي في خدمة SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'المقرر الدراسي الذي يحتوي على أحد الأنشطة التي تم تمكين SafeAssign بها.';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'معرف المستخدم الذي يكون معلمًا في هذا المقرر الدراسي.';
$string['privacy:metadata:plagiarism_safeassign_subm'] = 'معلومات حول الواجبات المرسلة للطلاب.';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'معرف الواجب الخاص بهذا الواجب المرسل.';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'متوسط درجات التشابه لجميع الملفات المرسلة.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = 'ضع إشارة لتحديد ما إذا كان الواجب المرسل يحتوي على ملف.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = 'ضع إشارة لتحديد ما إذا كان الواجب المرسل يحتوي على نص عبر الإنترنت.';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = 'أعلى درجة تشابه موجودة لأحد الملفات التي تم إرسالها.';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'معرف الواجب المرسل الخاص بالنشاط الذي تم تمكين SafeAssign به.';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'ضع إشارة لتحديد ما إذا تم إرسال الملف إلى SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = 'الوقت الذي تم فيه إنشاء الواجب المرسل.';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'المعرف الفريد للواجب المرسل في خدمة SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'معلومات حول المعلمين في النظام الأساسي.';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = 'المعرف الذي يكون فيه المستخدم معلمًا في أحد المقررات الدراسية.';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'معرف المقرر الدراسي الذي يكون فيه المستخدم معلمًا.';
