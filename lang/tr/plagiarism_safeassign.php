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

$string['pluginname'] = 'SafeAssign bilgi hırsızlığı eklentisi';
$string['getscores'] = 'Gönderimler için puanları al';
$string['getscoreslog'] = 'SafeAssign puanı görev günlüğü';
$string['getscoreslogfailed'] = 'SafeAssign puan görevi başarısız';
$string['getscoreslog_desc'] = 'SafeAssign puan görevi başarıyla çalıştırıldı.';
$string['servicedown'] = 'SafeAssign hizmeti kullanılamıyor.';
$string['studentdisclosuredefault'] = 'Yüklenen tüm dosyalar bilgi hırsızlığı algılama hizmetine gönderilecek';
$string['studentdisclosure'] = 'Kurum Sürüm Bildirimi';
$string['studentdisclosure_help'] = 'Bu metin dosya yükleme sayfasında tüm öğrencilere görüntülenecek. Bu alan boş bırakılırsa onun yerine varsayılan yerelleştirilmiş dize (studentdisclosuredefault) kullanılacak.';
$string['safeassignexplain'] = 'Bu eklentiyle ilgili daha fazla bilgi için bkz.: ';
$string['safeassign'] = 'SafeAssign Bilgi Hırsızlığı eklentisi';
$string['safeassign:enable'] = 'Öğretmenin bir etkinlik içinde SafeAssign\'ı etkinleştirmesine/devre dışı bırakmasına izin ver';
$string['safeassign:report'] = 'SafeAssign\'ın orijinallik raporunun görüntülenmesine izin ver';
$string['usesafeassign'] = 'SafeAssign\'ı etkinleştir';
$string['savedconfigsuccess'] = 'Bilgi Hırsızlığı Ayarları Kaydedildi';
$string['safeassign_additionalroles'] = 'Ek roller';
$string['safeassign_additionalroles_help'] = 'Sistem düzeyinde bu rollere sahip kullanıcılar her SafeAssign
kursuna eğitmen olarak eklenir.';
$string['safeassign_api'] = 'SafeAssign entegrasyon URL\'si';
$string['safeassign_api_help'] = 'Bu, SafeAssign API\'sının adresidir.';
$string['instructor_role_credentials'] = 'Eğitmen Rolü Kimlik Bilgileri';
$string['safeassign_instructor_username'] = 'Paylaşılan anahtar';
$string['safeassign_instructor_username_help'] = 'SafeAssign tarafından sağlanan eğitmen paylaşılan anahtarı.';
$string['safeassign_instructor_password'] = 'Paylaşılan parola';
$string['safeassign_instructor_password_help'] = 'SafeAssign tarafından sağlanan kaydedilmiş paylaşılan eğitmen parolası.';
$string['student_role_credentials'] = 'Öğrenci Rolü Kimlik Bilgileri';
$string['safeassign_student_username'] = 'Paylaşılan anahtar';
$string['safeassign_student_username_help'] = 'SafeAssign tarafından sağlanan öğrenci paylaşılan anahtarı.';
$string['safeassign_student_password'] = 'Paylaşılan parola';
$string['safeassign_student_password_help'] = 'SafeAssign tarafından sağlanan öğrenci paylaşılan parolası.';
$string['safeassign_license_acceptor_givenname'] = 'Lisansı Kabul Edenin Adı';
$string['safeassign_license_acceptor_surname'] = 'Lisansı Kabul Edenin Soyadı';
$string['safeassign_license_acceptor_email'] = 'Lisansı Kabul Edenin E-postası';
$string['safeassign_license_header'] = 'SafeAssign&trade; Lisans Şartları ve Koşulları';
$string['license_already_accepted'] = 'Geçerli lisans şartları zaten yöneticiniz tarafından kabul edilmiş.';
$string['acceptlicense'] = 'SafeAssign lisansını kabul et';
$string['acceptlicenselog'] = 'SafeAssign lisansı görev günlüğü';
$string['safeassign_license_warning'] = 'SafeAssign&trade; lisans verileri doğrulanırken bir sorun oluştu, lütfen \'Bağlantıyı test et\' düğmesini tıklatın. Test başarılı olursa daha sonra tekrar deneyin.';
$string['safeassign_enableplugin'] = '{$a} için SafeAssign\'ı etkinleştir';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&nbsp Varsayılan değer: 0</div> <br>';
$string['safeassign_showid'] = 'Öğrenci Kimliğini Göster';
$string['safeassign_alloworganizations'] = 'Organizasyonlarda SafeAssignment\'lara izin ver';
$string['safeassign_referencedbactivity'] = '<a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">Genel Referans Veritabanı</a> Etkinliği';
$string['safeassing_response_header'] = '<br>SafeAssign sunucu yanıtı: <br>';
$string['safeassign_instructor_credentials'] = 'Eğitmen Rolü Kimlik Bilgileri: ';
$string['safeassign_student_credentials'] = 'Öğrenci Rolü Kimlik Bilgileri: ';
$string['safeassign_credentials_verified'] = 'Bağlantı doğrulandı.';
$string['safeassign_credentials_fail'] = 'Bağlantı doğrulanmadı. Anahtarı, parolayı ve url\'yi kontrol edin.';
$string['credentials'] = 'Kimlik Bilgileri ve Hizmet URL\'si';
$string['shareinfo'] = 'Bilgiyi SafeAssign ile paylaş';
$string['disclaimer'] = '<br>SafeAssign <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">Genel Referans Veritabanı</a>\'na gönderildiğinde diğer kurumların kağıtları <br>
                        öğrencilerin kağıtlarıyla karşılaştırılarak kontrol edilip öğrencilerinizin çalışmalarının kaynağı korunur.';
$string['settings'] = 'SafeAssign Ayarları';
$string['timezone_help'] = 'Blackboard Open LMS ortamınızda ayarlanan saat dilimi.';
$string['timezone'] = 'Saat dilimi';
$string['safeassign_status'] = 'SafeAssign durumu';
$string['status:pending'] = 'Beklemede';
$string['safeassign_score'] = 'SafeAssign puanı';
$string['safeassign_reporturl'] = 'Rapor URL\'si';
$string['button_disabled'] = 'Bağlantıyı test etmek için formu kaydet';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'Behat testlerini çalıştırırken SafeAssign web hizmetlerine çağrı simülasyonu yapmak üzere "{$a}" json dosyası plagiarism/safeassign/tests/fixtures klasöründen alırken hata oluştu.';
$string['safeassign_curlcache'] = 'Önbellek zaman aşımı';
$string['safeassign_curlcache_help'] = 'Web hizmeti önbellek zaman aşımı.';
$string['rest_error_nocurl'] = 'cURL modülü kullanılabilir ve etkin olmalıdır!';
$string['rest_error_nourl'] = 'URL\'yi belirtmelisiniz!';
$string['rest_error_nomethod'] = 'İstek yöntemini belirtmelisiniz!';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'Test bağlantısı';
$string['connectionfailed'] = 'Bağlantı Başarısız Oldu';
$string['connectionverified'] = 'Bağlantı Doğrulandı';
$string['cachedef_request'] = 'SafeAssign istek önbelleği';
$string['error_behat_instancefail'] = 'Bu, behat testlerinde başarısız olacak şekilde yapılandırılmış bir örnektir.';
$string['assignment_check_submissions'] = 'SafeAssign ile gönderimleri kontrol et';
$string['assignment_check_submissions_help'] = 'Anonim not verme ayarlandığında SafeAssign Orijinallik Raporları Öğretmenler tarafından kullanılamaz ancak "Öğrencilerin orijinallik raporunu görüntülemesine izin ver" seçeneği seçilmişse Öğrenciler kendi SafeAssign Orijinallik Raporlarını görüntüleyebilir.
<br><br>SafeAssign resmi olarak sadece İngilizce dilini destekliyor olsa da istemciler SafeAssign\'ı İngilizce\'den başka dillerde de kullanabilirler.
SafeAssign\'da, başka dillerde kullanımını engelleyen bir teknik sınırlama yoktur.
Daha fazla bilgi için bkz. <a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">Blackboard yardımı</a>.';
$string['students_originality_report'] = 'Öğrencilerin orijinallik raporunu görüntülemesine izin ver';
$string['submissions_global_reference'] = 'Gönderimleri kurumsal ve <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">Genel Referans Veritabanı</a>\'ndan hariç tut';
$string['submissions_global_reference_help'] = 'Gönderimler yine SafeAssign tarafından işlenir ancak veritabanlarına kaydedilmez. Bu, öğretmenler belirli bir ödevde tekrar gönderim yapılmasına izin verdiğinde dosyaların bilgi hırsızlığından işaretlenmesini önler.';
$string['plagiarism_tools'] = 'Bilgi Hırsızlığı Araçları';
$string['files_accepted'] = 'SafeAssign yalnızca .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf ve .html biçimlerindeki dosyaları kabul eder. .zip gibi sıkıştırılmış dosya biçimleri de dahil olmak üzere diğer biçimlerdeki dosyalar SafeAssign ile kontrol edilmez.
<br><br>Bu kağıdı göndererek:
 (1) Kağıdınızı SafeAssign&trade; hizmetlerinin bir parçası olarak, Blackboard <a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">Hizmet Şartları</a> ile <a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">Blackboard Gizlilik Politikası</a>\'na uygun biçimde kullanılmak ve depolanmak üzere gönderdiğinizi;
 (2) kurumunuzun kağıdınızı kurum politikalarına uygun şekilde kullanabileceğini ve
 (3) SafeAssign\'ı kullanımınızla ilgili olarak Blackboard Inc. ve iştiraklerine karşı herhangi bir başvuruda bulunmayacağınızı kabul etmiş olursunuz.';
$string['agreement'] = 'Kağıdımı <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">Genel Referans Veritabanı</a>\'na göndermeyi kabul ediyorum.';
$string['error_api_generic'] = 'İsteğiniz işlenirken bir hata oluştu';
$string['error_api_unauthorized'] = 'İsteğiniz işlenirken bir kimlik doğrulama hatası oluştu';
$string['error_api_forbidden'] = 'İsteğiniz işlenirken bir yetkilendirme hatası oluştu';
$string['error_api_not_found'] = 'İstenen kaynak bulunamadı';
$string['sync_assignments'] = 'Mevcut bilgileri SafeAssign sunucusuna gönderir.';
$string['api_call_log_event'] = 'API çağrıları için SafeAssign günlüğü.';
$string['course_error_sync'] = '{$a} kimlikli Kurs SafeAssign ile eşitlenirken bir hata oluştu: <br>';
$string['assign_error_sync'] = '{$a} kimlikli ödev SafeAssign ile eşitlenirken bir hata oluştu: <br>';
$string['submission_error_sync'] = '{$a} kimlikli görev SafeAssign ile eşitlenirken bir hata oluştu: <br>';
$string['submission_success_sync'] = 'Gönderimler başarıyla eşitlendi';
$string['assign_success_sync'] = 'Ödevler başarıyla eşitlendi';
$string['course_success_sync'] = 'Kurslar başarıyla eşitlendi';
$string['license_header'] = 'SafeAssign&trade; Lisans Sözleşmesi';
$string['license_title'] = 'SafeAssign Lisans Sözleşmesi';
$string['not_configured'] = 'SafeAssign&trade; yapılandırılmamış. Lütfen sistem yöneticinizin yardım için Behind the Blackboard\'da bir bilet göndermesini sağlayın.';
$string['agree_continue'] = 'Formu kaydet';
$string['safeassign_file_not_supported'] = 'Desteklenmiyor.';
$string['safeassign_file_not_supported_help'] = 'Dosya uzantısı SafeAssign tarafından desteklenmiyor veya dosya boyutu maksimum kapasiteyi aşıyor.';
$string['safeassign_submission_not_supported'] = 'Bu gönderim SafeAssign tarafından incelenmeyecek.';
$string['safeassign_submission_not_supported_help'] = 'Kurs eğitmenleri tarafından oluşturulan gönderimler SafeAssign\'a gönderilmez.';
$string['safeassign_file_in_review'] = 'SafeAssign Orijinallik Raporı işlemi devam ediyor...';
$string['safeassign_file_similarity_score'] = 'SafeAssign puanı: %{$a}<br>';
$string['safeassign_link_originality_report'] = 'Orijinallik raporunu görüntüle';
$string['safeassign_file_limit_exceeded'] = 'Bu gönderim 10 MB\'lık birleşik boyut sınırını aştığından SafeAssign tarafından işlenmeyecek';
$string['originality_report'] = 'SafeAssign Orijinallik Raporu';
$string['originality_report_unavailable'] = 'İstenen Orijinallik Raporu kullanılamıyor. Daha sonra tekrar kontrol edin veya Sistem Yöneticinize başvurun.';
$string['originality_report_error'] = 'SafeAssign\'ın Orijinallik Raporu\'nda bir hata oluştu. Sistem Yöneticinize başvurun.';
$string['safeassign_overall_score'] = '<b>SafeAssign genel puanı: %{$a}</b>';
$string['messageprovider:safeassign_graded'] = 'Bir gönderime bilgi hırsızlığı notu verildiğinde SafeAssign eğitmenlere bildirimler gönderir';
$string['safeassign_loading_settings'] = 'Ayarlar yükleniyor, lütfen bekleyin';
$string['safeassign:get_messages'] = 'SafeAssign\'dan bildirim almaya izin ver';
$string['safeassign_notification_message'] = '{$a->assignmentname} adlı ödevde {$a->counter} {$a->plural} için bilgi hırsızlığı puanları işlendi';
$string['safeassign_notification_grading_link'] = 'Not verme sayfası';
$string['safeassign_notification_message_hdr'] = 'Bilgi Hırsızlığı SafeAssign puanları işlendi';
$string['safeassign_notification_subm_singular'] = 'gönderim';
$string['safeassign_notification_subm_plural'] = 'gönderimler';
$string['messageprovider:safeassign_notification'] = 'Yeni bir Lisans Şartları ve Koşulları bulunduğunda SafeAssign tarafından Site Yöneticilerine bildirimler gönderilir';
$string['safeassign:get_notifications'] = 'SafeAssign bildirimlerine izin ver';
$string['license_agreement_notification_subject'] = 'Yeni SafeAssign Lisans Şartları ve Koşulları bulundu';
$string['license_agreement_notification_message'] = 'Yeni Lisans Şartları ve Koşulları\'nı şu bölümden kabul edebilirsiniz: {$a}';
$string['settings_page'] = 'SafeAssign Ayarlar Sayfası';
$string['send_notifications'] = 'SafeAssign yeni Lisans Şartları ve Koşulları ile ilgili bildirimler gönderin.';
$string['privacy:metadata:core_files'] = 'Gönderimlere eklenen veya çevrim içi metin gönderiminden oluşturulan dosyalar.';
$string['privacy:metadata:core_plagiarism'] = 'Bu eklenti Moodle bilgi hırsızlığı alt sistemi tarafından çağrıldı.';
$string['privacy:metadata:safeassign_service'] = 'Orijinallik raporu almak için SafeAssign hizmetine bazı kullanıcı verileri gönderilmelidir.';
$string['privacy:metadata:safeassign_service:adminemail'] = 'Yönetici, hizmet lisansını kabul etmek için e-postasını göndermelidir.';
$string['privacy:metadata:safeassign_service:filecontent'] = 'Orijinallik raporunu oluşturmak için dosyaları SafeAssign\'a göndermemiz gerekir.';
$string['privacy:metadata:safeassign_service:filename'] = 'SafeAssign hizmeti için dosya adı gereklidir.';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'Dosya uuid\'i SafeAssign sunucusunda Moodle dosyalarını ilişkilendirmeye olanak verir.';
$string['privacy:metadata:safeassign_service:fullname'] = 'Kimlik doğrulama belirtecinin alınmasına izin vermek için kullanıcı adı SafeAssign\'a gönderilir.';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'Orijinallik raporunu almak için bu gönderim uuid\'i gereklidir.';
$string['privacy:metadata:safeassign_service:userid'] = 'SafeAssign hizmetlerini kullanmanıza izin vermek için Moodle\'dan gönderilen kullanıcı kimliği.';
$string['privacy:metadata:plagiarism_safeassign_files'] = 'Kullanıcı tarafından yüklenen dosyaların orijinalliği ile ilgili bilgiler';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'Bu gönderimi yapan öğrencinin kimliği.';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'SafeAssign hizmetinde benzersiz dosya tanımlayıcısı.';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'Orijinallik raporunun URL\'si.';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = 'Gönderilen dosyanın benzerlik puanı.';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'Dosyanın gönderildiği zaman.';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'SafeAssign hizmetinde benzersiz gönderim tanımlayıcısı';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = 'Gönderilen dosyanın kimliği.';
$string['privacy:metadata:plagiarism_safeassign_course'] = 'SafeAssign\'ın etkinleştirildiği Moodle kurslarıyla ilgili bilgiler.';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'SafeAssign hizmetinde benzersiz kurs tanımlayıcısı.';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'SafeAssign\'ın etkinleştirildiği bir etkinliği içeren kurs.';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'Bu kursta öğretmen konumundaki kullanıcının kimliği.';
$string['privacy:metadata:plagiarism_safeassign_subm'] = 'Öğrenci gönderimleriyle ilgili bilgiler.';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'Bu gönderimin ödev kimliği.';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'Gönderilen tüm dosyaların ortalama benzerlik puanı.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = 'Gönderimde bir dosya olup olmadığını belirten işaret.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = 'Gönderimde bir çevrimiçi metin olup olmadığını belirten işaret.';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = 'Gönderilen tek bir dosyanın en yüksek benzerlik puanı.';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'SafeAssign\'ın etkinleştirildiği bir etkinliğin gönderim kimliği.';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'Dosyanın SafeAssign\'a gönderilip gönderilmediğini belirleyen işaret.';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = 'Düzeltmenin oluşturulduğu zaman.';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'SafeAssign hizmetinde benzersiz gönderim tanımlayıcısı.';
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'Platformdaki öğretmenlerle ilgili bilgiler.';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = 'Bir kursta öğretmen konumundaki kullanıcının kimliği.';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'Kullanıcının öğretmen konumunda olduğu kursun kimliği.';
