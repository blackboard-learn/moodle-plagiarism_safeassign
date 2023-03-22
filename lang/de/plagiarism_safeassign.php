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

$string['pluginname'] = 'SafeAssign-Plagiats-Plugin';
$string['getscores'] = 'Punktzahl für abgegebene Aufgaben abrufen';
$string['getscoreslog'] = 'Protokoll der SafeAssign-Punktzahlaufgabe';
$string['getscoreslogfailed'] = 'SafeAssign-Punktzahlaufgabe fehlgeschlagen';
$string['getscoreslog_desc'] = 'SafeAssign-Punktzahlaufgabe erfolgreich ausgeführt.';
$string['servicedown'] = 'SafeAssign-Service ist nicht verfügbar.';
$string['studentdisclosuredefault'] = 'Alle hochgeladenen Dateien werden an den Plagiat-Prüfservice übermittelt';
$string['studentdisclosure'] = 'Versionshinweis der Institution';
$string['studentdisclosure_help'] = 'Dieser Text wird allen Teilnehmern/Teilnehmerinnen auf der Seite zum Hochladen von Dateien angezeigt. Wenn dieses Feld
leer gelassen wird, wird stattdessen die lokalisierte Standardzeichenfolge (studentdisclosuredefault) verwendet.';
$string['safeassignexplain'] = 'Weitere Informationen zu diesem Plugin unter:';
$string['safeassign'] = 'SafeAssign-Plagiats-Plugin';
$string['safeassign:enable'] = 'Trainer/innen erlauben, SafeAssign in einer Aktivität zu aktivieren/deaktivieren';
$string['safeassign:report'] = 'Anzeigen des Echtheitsberichts von SafeAssign zulassen';
$string['usesafeassign'] = 'SafeAssign aktivieren';
$string['savedconfigsuccess'] = 'Plagiats-Einstellungen gespeichert';
$string['safeassign_additionalroles'] = 'Zusätzliche Rollen';
$string['safeassign_additionalroles_help'] = 'Nutzer/innen mit diesen Rollen auf Systemebene werden zu jedem SafeAssign-Kurs
als Kursleiter/in hinzugefügt.';
$string['safeassign_api'] = 'SafeAssign-Integrations-URL';
$string['safeassign_api_help'] = 'Dies ist die Adresse der SafeAssign-API.';
$string['instructor_role_credentials'] = 'Anmeldedaten der Kursleiterrolle';
$string['safeassign_instructor_username'] = 'Shared Key';
$string['safeassign_instructor_username_help'] = 'Shared Key des Kursleiters, bereitgestellt von SafeAssign.';
$string['safeassign_instructor_password'] = 'Shared Secret';
$string['safeassign_instructor_password_help'] = 'Shared Secret des Kursleiters, bereitgestellt von SafeAssign.';
$string['student_role_credentials'] = 'Anmeldedaten der Teilnehmerrolle';
$string['safeassign_student_username'] = 'Shared Key';
$string['safeassign_student_username_help'] = 'Shared Key des Teilnehmers, bereitgestellt von SafeAssign.';
$string['safeassign_student_password'] = 'Shared Secret';
$string['safeassign_student_password_help'] = 'Shared Secret des Teilnehmers, bereitgestellt von SafeAssign.';
$string['safeassign_license_acceptor_givenname'] = 'Vorname des Lizenzakzeptors';
$string['safeassign_license_acceptor_surname'] = 'Nachname des Lizenzakzeptors';
$string['safeassign_license_acceptor_email'] = 'E-Mail-Adresse des Lizenzakzeptors';
$string['safeassign_license_header'] = 'SafeAssign&trade; Lizenzbestimmungen';
$string['license_already_accepted'] = 'Die aktuellen Lizenzbestimmungen wurden von Ihrem Administrator bereits akzeptiert.';
$string['acceptlicense'] = 'SafeAssign-Lizenz akzeptieren';
$string['acceptlicenselog'] = 'Protokoll zur SafeAssign-Lizenzaufgabe';
$string['safeassign_license_warning'] = 'Beim Validieren der SafeAssign&trade;-Lizenzdaten ist ein Problem aufgetreten.
Klicken Sie auf die Schaltfläche &quot;Verbindung testen&quot;. Wenn der Test erfolgreich war, versuchen Sie es später erneut.';
$string['safeassign_enableplugin'] = 'SafeAssign für {$a} aktivieren';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&amp;nbsp Standardwert: 0</div> <br>';
$string['safeassign_showid'] = 'Teilnehmer-ID anzeigen';
$string['safeassign_alloworganizations'] = 'SafeAssignments in Organisationen zulassen';
$string['safeassign_referencedbactivity'] = '<a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Globalen Referenzdatenbank</a>-Aktivität';
$string['safeassing_response_header'] = '<br>SafeAssign-Serverantwort:<br>';
$string['safeassign_instructor_credentials'] = 'Anmeldedaten der Kursleiterrolle:';
$string['safeassign_student_credentials'] = 'Anmeldedaten der Teilnehmerrolle:';
$string['safeassign_credentials_verified'] = 'Verbindung bestätigt.';
$string['safeassign_credentials_fail'] = 'Verbindung nicht bestätigt. Prüfen Sie Key, Secret und URL.';
$string['credentials'] = 'Anmeldedaten und Service-URL';
$string['shareinfo'] = 'Daten an SafeAssign weitergeben';
$string['disclaimer'] = '<br>Die Übermittlung an die SafeAssign <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a> ermöglicht die Überprüfung von Arbeiten anderer Einrichtungen<br>
anhand von Arbeiten Ihrer Kursteilnehmer/innen, um den Ursprung ihrer Arbeit zu schützen.';
$string['settings'] = 'SafeAssign-Einstellungen';
$string['timezone_help'] = 'Die in Ihrer Open LMS-Umgebung festgelegte Zeitzone.';
$string['timezone'] = 'Zeitzone';
$string['safeassign_status'] = 'SafeAssign-Status';
$string['status:pending'] = 'Ausstehend';
$string['safeassign_score'] = 'SafeAssign-Punktzahl';
$string['safeassign_reporturl'] = 'Bericht-URL';
$string['button_disabled'] = 'Formular speichern, um Verbindung zu testen';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'Fehler beim Abrufen der JSON-Datei „{$a}“ aus dem Ordner Plagiate/SafeAssign/Tests/Inventar, um einen Aufruf der SafeAssign-Webservices beim Ausführen von behat-Tests zu simulieren.';
$string['safeassign_curlcache'] = 'Cache-Zeitüberschreitung';
$string['safeassign_curlcache_help'] = 'Cache-Zeitüberschreitung für Webservice.';
$string['rest_error_nocurl'] = 'cURL-Modul muss vorhanden und aktiviert sein.';
$string['rest_error_nourl'] = 'Sie müssen eine URL angeben.';
$string['rest_error_nomethod'] = 'Sie müssen eine Anforderungsmethode angeben.';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'Testverbindung';
$string['connectionfailed'] = 'Verbindung fehlgeschlagen';
$string['connectionverified'] = 'Verbindung bestätigt';
$string['cachedef_request'] = 'SafeAssign-Anforderungszwischenspeicher';
$string['error_behat_instancefail'] = 'Diese Instanz ist so konfiguriert, dass Sie bei behat-Tests fehlschlägt.';
$string['assignment_check_submissions'] = 'Abgegebene Aufgaben bei SafeAssign überprüfen';
$string['assignment_check_submissions_help'] = 'SafeAssign-Echtheitsberichte stehen Trainern/Trainerinnen bei anonymer Bewertung nicht zur Verfügung.
Teilnehmer/innen können ihre eigenen SafeAssign-Echtheitsberichte anzeigen, wenn &quot;Teilnehmern/Teilnehmerinnen das Anzeigen des Echtheitsberichts gestatten&quot; ausgewählt ist.
<br><br>SafeAssign gibt einen einzelnen Echtheitsbericht zurück, wenn Nutzer/innen mehrere Dateien senden. In diesem Bericht können Sie auswählen, welche Datei überprüft werden soll.
<br><br>Obwohl SafeAssign offiziell nur Englisch unterstützt, können Sie SafeAssign mit anderen Sprachen verwenden.
SafeAssign weist keine technischen Einschränkungen auf, die die Verwendung mit anderen Sprachen ausschließen.
Weitere Informationen finden Sie in der <a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">Hilfe zu Blackboard</a>.';
$string['students_originality_report'] = 'Teilnehmern das Anzeigen des Echtheitsberichts gestatten';
$string['submissions_global_reference'] = 'Abgaben aus <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">globaler Referenzdatenbank</a> ausschließen';
$string['submissions_global_reference_help'] = 'Abgegebene Aufgaben werden weiterhin von SafeAssign verarbeitet, jedoch nicht in Datenbanken registriert. Dies verhindert, dass Dateien als Plagiate markiert werden, wenn Trainer/innen die erneute Abgabe bestimmter Aufgaben zugelassen haben.';
$string['plagiarism_tools'] = 'Plagiats-Tools';
$string['files_accepted'] = 'SafeAssign akzeptiert nur die Dateiformate .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf und .html. Dateien in anderen Formaten, einschließlich .zip und anderen komprimierten Dateiformaten, werden nicht über SafeAssign geprüft.
<br><br>Mit der Abgabe dieser Arbeit erklären Sie:
(1) dass Ihre abgegebene Arbeit im Rahmen der SafeAssign&trade;-Services und gemäß den Blackboard-<a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">Geschäftsbedingungen</a> und der Blackboard-<a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">Datenschutzrichtlinie</a> verwendet und gespeichert werden darf;
(2) dass Ihre Einrichtung Ihre Arbeit gemäß den Richtlinien Ihrer Einrichtung verwenden darf; und
(3) dass Sie SafeAssign ohne Rückgriff auf Open LMS und seine verbundenen Unternehmen nutzen.';
$string['agreement'] = 'Ich bin damit einverstanden, mein(e) Arbeit(en) bei der <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">globalen Referenzdatenbank</a>einzureichen.';
$string['error_api_generic'] = 'Bei der Verarbeitung Ihrer Anforderung ist ein Fehler aufgetreten';
$string['error_api_unauthorized'] = 'Bei der Verarbeitung Ihrer Anforderung ist ein Authentifizierungsfehler aufgetreten';
$string['error_api_forbidden'] = 'Bei der Verarbeitung Ihrer Anforderung ist ein Autorisierungsfehler aufgetreten';
$string['error_api_not_found'] = 'Die angeforderten Arbeitsmaterialien wurden nicht gefunden';
$string['sync_assignments'] = 'Sendet die verfügbaren Informationen an den SafeAssign-Server.';
$string['api_call_log_event'] = 'SafeAssign-Protokolle für API-Aufrufe.';
$string['course_error_sync'] = 'Beim Synchronisieren des Kurses mit der ID {$a} in SafeAssign ist ein Fehler aufgetreten:<br>';
$string['assign_error_sync'] = 'Beim Synchronisieren der Aufgabe mit der ID {$a} in SafeAssign ist ein Fehler aufgetreten:<br>';
$string['submission_error_sync'] = 'Beim Synchronisieren der abgegebenen Aufgabe mit der ID {$a} in SafeAssign ist ein Fehler aufgetreten:<br>';
$string['submission_success_sync'] = 'Abgegebene Aufgaben erfolgreich synchronisiert';
$string['assign_success_sync'] = 'Aufgaben erfolgreich synchronisiert';
$string['course_success_sync'] = 'Kurses erfolgreich synchronisiert';
$string['license_header'] = 'SafeAssign&trade;-Lizenzvereinbarung';
$string['license_title'] = 'SafeAssign-Lizenzvereinbarung';
$string['not_configured'] = 'SafeAssign&trade; ist nicht konfiguriert. Bitten Sie Ihre/n Systemadministrator/in, ein Ticket
an <a href="https://support.openlms.net/" target="_blank" rel="noopener">den LMS-Support</a> zu senden.';
$string['agree_continue'] = 'Formular speichern';
$string['safeassign_file_not_supported'] = 'Nicht unterstützt.';
$string['safeassign_file_not_supported_help'] = 'Die Dateierweiterung wird von SafeAssign nicht unterstützt oder die Dateigröße überschreitet die maximale Kapazität.';
$string['safeassign_submission_not_supported'] = 'Diese abgegebene Aufgabe wird nicht von SafeAssign überprüft.';
$string['safeassign_submission_not_supported_help'] = 'Abgegebene Aufgaben, die von Kursleitern/Kursleiterinnen erstellt wurden, werden nicht an SafeAssign gesendet.';
$string['safeassign_file_in_review'] = 'SafeAssign-Echtzeitsbericht in Bearbeitung...';
$string['safeassign_file_similarity_score'] = 'SafeAssign-Punktzahl: {$a}%<br>';
$string['safeassign_link_originality_report'] = 'Echtheitsbericht anzeigen';
$string['safeassign_file_limit_exceeded'] = 'Diese abgegebene Aufgabe überschreitet die kombinierte maximale Größe von 10 MB und wird nicht von SafeAssign verarbeitet';
$string['originality_report'] = 'SafeAssign-Echtheitsbericht';
$string['originality_report_unavailable'] = 'Der angeforderte Echtheitsbericht ist nicht verfügbar. Versuchen Sie es später erneut oder kontaktieren Sie Ihren Systemadministrator.';
$string['originality_report_error'] = 'Es ist ein Fehler mit dem SafeAssign-Echtheitsbericht aufgetreten. Kontaktieren Sie Ihren Systemadministrator.';
$string['safeassign_overall_score'] = '<b>SafeAssign-Gesamtpunktzahl: {$a}%</b>';
$string['messageprovider:safeassign_graded'] = 'SafeAssign sendet Benachrichtigungen an Kursleiter, wenn eine abgegebene Aufgabe als Plagiat eingestuft wurde';
$string['safeassign_loading_settings'] = 'Einstellungen werden geladen, bitte warten';
$string['safeassign:get_messages'] = 'Empfangen von Benachrichtigungen von SafeAssign zulassen';
$string['safeassign_notification_message'] = 'Plagiatspunke wurde für {$a->counter} {$a->plural} in {$a->assignmentname} verarbeitet';
$string['safeassign_notification_grading_link'] = 'Bewertungsseite';
$string['safeassign_notification_message_hdr'] = 'SafeAssign-Plagiatspunkte wurden verarbeitet';
$string['safeassign_notification_subm_singular'] = 'Abgegebene Aufgabe';
$string['safeassign_notification_subm_plural'] = 'Abgegebene Aufgaben';
$string['messageprovider:safeassign_notification'] = 'SafeAssign sendet Benachrichtigungen an den/die Website-Administrator/in, wenn neue Lizenzbestimmungen verfügbar sind';
$string['safeassign:get_notifications'] = 'Benachrichtigungen von SafeAssign zulassen';
$string['license_agreement_notification_subject'] = 'Neue Lizenzbestimmungen von SafeAssign verfügbar';
$string['license_agreement_notification_message'] = 'Sie können die neuen Lizenzbestimmungen hier annehmen: {$a}';
$string['settings_page'] = 'SafeAssign-Einstellungsseite';
$string['send_notifications'] = 'Benachrichtigungen zu neuen Lizenzbestimmungen von SafeAssign senden.';
$string['privacy:metadata:core_files'] = 'Dateien, die an abgegebene Aufgaben angehängt waren oder aus Online-Textübermittlungen erstellt wurden.';
$string['privacy:metadata:core_plagiarism'] = 'Dieses Plugin wird vom Moodle-Plagiatssubsystem aufgerufen.';
$string['privacy:metadata:safeassign_service'] = 'Um einen Echtheitsbericht zu erzeugen, müssen einige Nutzerdaten an den SafeAssign-Service gesendet werden.';
$string['privacy:metadata:safeassign_service:adminemail'] = 'Der Administrator sollte eine E-Mail senden, um die Servicelizenz zu akzeptieren.';
$string['privacy:metadata:safeassign_service:filecontent'] = 'Wir müssen die Dateien an SafeAssign senden, um den Echtheitsbericht zu erzeugen.';
$string['privacy:metadata:safeassign_service:filename'] = 'Für den SafeAssign-Service ist der Dateiname erforderlich.';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'Datei-UUID ermöglicht die Zuordnung von Moodle-Dateien im SafeAssign-Server.';
$string['privacy:metadata:safeassign_service:fullname'] = 'Der Nutzername wird an SafeAssign gesendet, um das Authentifizierungstoken zu erhalten.';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'Die UUID der abgegebenen Aufgabe ist erforderlich, um den Echtheitsbericht abzurufen.';
$string['privacy:metadata:safeassign_service:userid'] = 'Die von Moodle gesendete Nutzer-ID zur Nutzung der SafeAssign-Services.';
$string['privacy:metadata:plagiarism_safeassign_files'] = 'Informationen zur Echtheit der vom Nutzer/von der Nutzerin hochgeladenen Dateien';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'Die ID des Teilnehmers, der diese Aufgabe abgegeben hat.';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'Eindeutige Datei-ID im SafeAssign-Service.';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'URL zum Echtheitsbericht.';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = 'Ähnlichkeitspunktzahl für die abgegebene Datei.';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'Uhrzeit der Abgabe der Datei.';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'Eindeutige ID der abgegebenen Aufgabe im SafeAssign-Service';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = 'ID der abgegebenen Datei.';
$string['privacy:metadata:plagiarism_safeassign_course'] = 'Informationen zu Moodle-Kursen, bei denen SafeAssign aktiviert ist.';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'Eindeutige Kurs-ID im SafeAssign-Service.';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'Im Kurs gibt es eine Aktivität, für die SafeAssign aktiviert ist.';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'ID des Nutzers/der Nutzerin, der/die als Trainer/in dieses Kurses fungiert.';
$string['privacy:metadata:plagiarism_safeassign_subm'] = 'Informationen zu von Teilnehmern abgegebenen Aufgaben.';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'Aufgaben-ID dieser abgegebenen Aufgabe.';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'Durchschnittliche Ähnlichkeitspunktzahl aller abgegebenen Dateien.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = 'Markieren, um festzustellen, ob in der abgegebenen Aufgabe eine Datei vorliegt.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = 'Markieren, um festzustellen, ob in der abgegebenen Aufgabe Online-Text vorliegt.';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = 'Höchste Ähnlichkeitspunktzahl für eine abgegebene Datei.';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'Aufgaben-ID einer Aktivität, für die SafeAssign aktiviert ist.';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'Markieren, um festzustellen, ob die Datei an SafeAssign gesendet wurde.';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = 'Uhrzeit, zu der die abgegebene Aufgabe erstellt wurde.';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'Eindeutige ID der abgegebenen Aufgabe im SafeAssign-Service.';
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'Informationen zu Trainer/innen auf der Plattform.';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = 'ID eines Nutzers/einer Nutzerin, der/die als Trainer/in in einem Kurs fungiert.';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'ID des Kurses, in dem der/die Nutzer/in als Trainer/in fungiert.';
