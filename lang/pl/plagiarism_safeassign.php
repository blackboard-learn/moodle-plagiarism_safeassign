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

$string['pluginname'] = 'Wtyczka wykrywania plagiatów SafeAssign';
$string['getscores'] = 'Pobierz wyniki złożonych prac';
$string['getscoreslog'] = 'Dziennik zadania pobierania wyniku wygenerowanego przez usługę SafeAssign';
$string['getscoreslogfailed'] = 'Niepowodzenie zadania pobierania wyniku wygenerowanego przez usługę SafeAssign';
$string['getscoreslog_desc'] = 'Działanie zadania pobierania wyniku wygenerowanego przez usługę SafeAssign zakończyło się pomyślnie.';
$string['servicedown'] = 'Usługa SafeAssign jest niedostępna.';
$string['studentdisclosuredefault'] = 'Wszystkie załadowane pliki zostaną przesłane do usługi do wykrywania plagiatów';
$string['studentdisclosure'] = 'Oświadczenie instytucji o udostępnieniu';
$string['studentdisclosure_help'] = 'Ten tekst będzie wyświetlany dla wszystkich studentów na stronie przesyłania pliku. Jeśli to
pole pozostanie puste, zamiast niego będzie używany domyślny przetłumaczony ciąg znaków (studentdisclosuredefault).';
$string['safeassignexplain'] = 'Więcej informacji na temat tej wtyczki: ';
$string['safeassign'] = 'Wtyczka wykrywania plagiatów SafeAssign';
$string['safeassign:enable'] = 'Zezwalaj nauczycielowi na włączanie/wyłączanie usługi SafeAssign w trakcie działania';
$string['safeassign:report'] = 'Zezwalaj na wyświetlanie raportu usługi SafeAssign dotyczącego oryginalności';
$string['usesafeassign'] = 'Włącz usługę SafeAssign';
$string['savedconfigsuccess'] = 'Zapisano ustawienia wykrywania plagiatów';
$string['safeassign_additionalroles'] = 'Dodatkowe role';
$string['safeassign_additionalroles_help'] = 'Użytkownicy o przypisanych tych rolach na poziomie systemu zostaną 
dodani do każdego kursu SafeAssign jako instruktorzy.';
$string['safeassign_api'] = 'Adres URL integracji z usługą SafeAssign';
$string['safeassign_api_help'] = 'To jest adres interfejsu API usługi SafeAssign.';
$string['instructor_role_credentials'] = 'Dane logowania roli instruktora';
$string['safeassign_instructor_username'] = 'Klucz udostępniony';
$string['safeassign_instructor_username_help'] = 'Udostępniony klucz instruktora dostarczony przez usługę SafeAssign.';
$string['safeassign_instructor_password'] = 'Udostępniony tajny klucz';
$string['safeassign_instructor_password_help'] = 'Udostępniony tajny klucz instruktora dostarczony przez usługę SafeAssign.';
$string['student_role_credentials'] = 'Dane logowania roli studenta';
$string['safeassign_student_username'] = 'Klucz udostępniony';
$string['safeassign_student_username_help'] = 'Udostępniony klucz studenta dostarczony przez usługę SafeAssign.';
$string['safeassign_student_password'] = 'Udostępniony tajny klucz';
$string['safeassign_student_password_help'] = 'Udostępniony tajny klucz studenta dostarczony przez usługę SafeAssign.';
$string['safeassign_license_acceptor_givenname'] = 'Imię osoby akceptującej licencję';
$string['safeassign_license_acceptor_surname'] = 'Nazwisko osoby akceptującej licencję';
$string['safeassign_license_acceptor_email'] = 'Adres e-mail osoby akceptującej licencję';
$string['safeassign_license_header'] = 'Warunki licencji usługi SafeAssign&trade;';
$string['license_already_accepted'] = 'Bieżące warunki licencji zostały już zaakceptowane przez administratora.';
$string['acceptlicense'] = 'Zaakceptuj licencję usługi SafeAssign';
$string['acceptlicenselog'] = 'Dziennik zadania obsługi licencji usługi SafeAssign';
$string['safeassign_license_warning'] = 'Wystąpił problem z weryfikacją danych licencji usługi SafeAssign&trade;.
Kliknij przycisk Testuj połączenie. Jeśli test się powiedzie, spróbuj jeszcze raz później.';
$string['safeassign_enableplugin'] = 'Włącz usługę SafeAssign dla: {$a}';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&nbsp Wartość domyślna: 0</div> <br>';
$string['safeassign_showid'] = 'Pokaż identyfikator studenta';
$string['safeassign_alloworganizations'] = 'Zezwalaj na użycie usługi SafeAssign w organizacjach';
$string['safeassign_referencedbactivity'] = 'Aktywność <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">globalnej referencyjnej bazy danych</a>';
$string['safeassing_response_header'] = '<br>Odpowiedź serwera SafeAssign: <br>';
$string['safeassign_instructor_credentials'] = 'Dane logowania roli instruktora: ';
$string['safeassign_student_credentials'] = 'Dane logowania roli studenta: ';
$string['safeassign_credentials_verified'] = 'Zweryfikowano połączenie.';
$string['safeassign_credentials_fail'] = 'Nie zweryfikowano połączenia. Sprawdź klucz, tajny klucz i adres URL.';
$string['credentials'] = 'Dane logowania i adres URL usługi';
$string['shareinfo'] = 'Udostępnij informacje usłudze SafeAssign';
$string['disclaimer'] = '<br>Przesłanie do <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">globalnej referencyjnej bazy danych</a> usługi SafeAssign umożliwia porównanie prac studentów <br>
                        z pracami pochodzącymi z innych instytucji w celu ochrony ich oryginalności.';
$string['settings'] = 'Ustawienia usługi SafeAssign';
$string['timezone_help'] = 'Strefa czasowa ustawiona w środowisku systemu Blackboard Open LMS.';
$string['timezone'] = 'Strefa czasowa';
$string['safeassign_status'] = 'Status usługi SafeAssign';
$string['status:pending'] = 'Oczekujące';
$string['safeassign_score'] = 'Wynik wygenerowany przez usługę SafeAssign';
$string['safeassign_reporturl'] = 'Adres URL raportu';
$string['button_disabled'] = 'Zapisz formularz, aby przetestować połączenie';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'Błąd podczas pobierania pliku json „{$a}” z folderu plagiarism/safeassign/tests/fixtures w celu zasymulowania wywołania usług sieciowych usługi SafeAssign podczas wykonywania testów behat.';
$string['safeassign_curlcache'] = 'Limit czasu pamięci podręcznej';
$string['safeassign_curlcache_help'] = 'Limit czasu pamięci podręcznej usługi sieciowej.';
$string['rest_error_nocurl'] = 'Moduł cURL musi być dostępny i włączony!';
$string['rest_error_nourl'] = 'Należy określić adres URL!';
$string['rest_error_nomethod'] = 'Należy określić metodę żądania!';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'Testuj połączenie';
$string['connectionfailed'] = 'Błąd połączenia';
$string['connectionverified'] = 'Zweryfikowano połączenie';
$string['cachedef_request'] = 'Pamięć podręczna żądań usługi SafeAssign';
$string['error_behat_instancefail'] = 'To jest instancja skonfigurowana w taki sposób, aby generować błąd podczas testów behat.';
$string['assignment_check_submissions'] = 'Sprawdź złożone prace za pomocą usługi SafeAssign';
$string['assignment_check_submissions_help'] = 'Raporty usługi SafeAssign na temat oryginalności będą niedostępne dla nauczycieli, jeśli zostanie ustawione ocenianie anonimowe, 
ale studenci będą mogli wyświetlać swoje własne raporty usługi SafeAssign na temat oryginalności, jeśli zostanie ustawiona opcja Zezwól studentom na wyświetlanie raportu na temat oryginalności.
<br><br>Usługa SafeAssign oficjalnie obsługuje tylko język angielski, zachęca się jednak klientów, aby próbowali korzystać z usługi SafeAssign także w przypadku języków innych niż angielski.
W usłudze SafeAssign nie występują żadne ograniczenia techniczne, które wykluczają jej użycie z innymi językami.
Więcej informacji można znaleźć w <a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">pomocy do systemu Blackboard</a>.';
$string['students_originality_report'] = 'Zezwól studentom na wyświetlanie raportu na temat oryginalności';
$string['submissions_global_reference'] = 'Wyklucz złożone prace z instytucjonalnej i <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">globalnej referencyjnej bazy danych</a>';
$string['submissions_global_reference_help'] = 'Złożone prace będą nadal przetwarzane przez usługę SafeAssign, ale nie zostaną zarejestrowane w bazach danych. Pozwala to uniknąć oznaczenia plików jako plagiaty, gdy nauczyciele pozwalają na ponowne składanie prac w przypadku niektórych zadań.';
$string['plagiarism_tools'] = 'Narzędzia do wykrywania plagiatów';
$string['files_accepted'] = 'Usługa SafeAssign przyjmuje tylko pliki w formatach .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf i .html. Pliki w innym formacie, na przykład .zip lub innych formatach plików skompresowanych, nie będą sprawdzane przy użyciu usługi SafeAssign.
<br><br>Przesłanie pracy jest równoznaczne z uznaniem:
 (1) że przesłana praca będzie używana i przechowywana w ramach usług SafeAssign&trade; zgodnie z <a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">Warunkami korzystania z usługi</a> firmy Blackboard oraz <a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">Zasadami ochrony prywatności firmy Blackboard</a>;
 (2) że instytucja użytkownika może użyć pracy zgodnie z własnymi zasadami; a także 
 (3) że korzystanie z usługi SafeAssign nie będzie się wiązało z odpowiedzialnością firmy Blackboard Inc. ani podmiotów od niej zależnych.';
$string['agreement'] = 'Zgadzam się przesłać prace do <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">globalnej referencyjnej bazy danych</a>.';
$string['error_api_generic'] = 'Podczas przetwarzania żądania wystąpił błąd';
$string['error_api_unauthorized'] = 'Podczas przetwarzania żądania wystąpił błąd uwierzytelniania';
$string['error_api_forbidden'] = 'Podczas przetwarzania żądania wystąpił błąd autoryzacji';
$string['error_api_not_found'] = 'Nie znaleziono żądanego zasobu';
$string['sync_assignments'] = 'Wysyła dostępne informacje do serwera SafeAssign.';
$string['api_call_log_event'] = 'Dziennik usługi SafeAssign na potrzeby wywołań interfejsu API.';
$string['course_error_sync'] = 'Wystąpił błąd podczas próby zsynchronizowania kursu o identyfikatorze {$a} z usługą SafeAssign: <br>';
$string['assign_error_sync'] = 'Wystąpił błąd podczas próby zsynchronizowania zadania o identyfikatorze {$a} z usługą SafeAssign: <br>';
$string['submission_error_sync'] = 'Wystąpił błąd podczas próby zsynchronizowania złożonej pracy o identyfikatorze {$a} z usługą SafeAssign: <br>';
$string['submission_success_sync'] = 'Złożone prace zostały pomyślnie zsynchronizowane';
$string['assign_success_sync'] = 'Zadania zostały pomyślnie zsynchronizowane';
$string['course_success_sync'] = 'Kursy zostały pomyślnie zsynchronizowane';
$string['license_header'] = 'Umowa licencyjna usługi SafeAssign&trade;';
$string['license_title'] = 'Umowa licencyjna usługi SafeAssign';
$string['not_configured'] = 'Usługa SafeAssign&trade; nie jest skonfigurowana. Aby uzyskać pomoc, poproś administratora systemu o przesłanie zgłoszenia za pomocą platformy Behind the Blackboard.';
$string['agree_continue'] = 'Zapisz formularz';
$string['safeassign_file_not_supported'] = 'Nieobsługiwane.';
$string['safeassign_file_not_supported_help'] = 'Rozszerzenie pliku nie jest obsługiwane przez usługę SafeAssign lub rozmiar pliku przekracza maksymalny.';
$string['safeassign_submission_not_supported'] = 'Ta złożona praca nie zostanie sprawdzona przez usługę SafeAssign.';
$string['safeassign_submission_not_supported_help'] = 'Złożone prace utworzone przez instruktorów kursów nie są wysyłane do usługi SafeAssign.';
$string['safeassign_file_in_review'] = 'Trwa tworzenie raportu usługi SafeAssign na temat oryginalności...';
$string['safeassign_file_similarity_score'] = 'Wynik wygenerowany przez usługę SafeAssign: {$a}%<br>';
$string['safeassign_link_originality_report'] = 'Wyświetl raport dotyczący oryginalności';
$string['safeassign_file_limit_exceeded'] = 'Ta złożona praca przekracza limit połączonego rozmiaru wynoszący 10 MB i nie zostanie przetworzona przez usługę SafeAssign';
$string['originality_report'] = 'Raport usługi SafeAssign na temat oryginalności';
$string['originality_report_unavailable'] = 'Żądany raport dotyczący oryginalności jest niedostępny. Sprawdź ponownie później lub skontaktuj się z administratorem systemu.';
$string['originality_report_error'] = 'Wystąpił błąd związany z raportem usługi SafeAssign na temat oryginalności. Skontaktuj się z administratorem systemu.';
$string['safeassign_overall_score'] = '<b>Wynik ogólny wygenerowany przez usługę SafeAssign: {$a}%</b>';
$string['messageprovider:safeassign_graded'] = 'Usługa SafeAssign wysyła powiadomienia do instruktorów, gdy złożona praca zostanie oceniona pod kątem plagiatu';
$string['safeassign_loading_settings'] = 'Ładowanie ustawień, proszę czekać';
$string['safeassign:get_messages'] = 'Zezwalaj na odbieranie powiadomień z usługi SafeAssign';
$string['safeassign_notification_message'] = 'Obliczono wyniki przetwarzania pod kątem plagiatu dla {$a->counter} {$a->plural} w zadaniu {$a->assignmentname}';
$string['safeassign_notification_grading_link'] = 'Strona oceniania';
$string['safeassign_notification_message_hdr'] = 'Przetworzono wyniki wykrywania plagiatów przez usługę SafeAssign';
$string['safeassign_notification_subm_singular'] = 'złożonej pracy';
$string['safeassign_notification_subm_plural'] = 'złożonych prac';
$string['messageprovider:safeassign_notification'] = 'Usługa SafeAssign wysyła powiadomienia do administratorów witryny po udostępnieniu nowych warunków licencji';
$string['safeassign:get_notifications'] = 'Zezwalaj na powiadomienia z usługi SafeAssign';
$string['license_agreement_notification_subject'] = 'Dostępne są nowe warunki licencji usługi SafeAssign';
$string['license_agreement_notification_message'] = 'Nowe warunki licencji możesz zaakceptować tutaj: {$a}';
$string['settings_page'] = 'Strona ustawień usługi SafeAssign';
$string['send_notifications'] = 'Wysyłaj powiadomienia o nowych warunkach licencji usługi SafeAssign.';
$string['privacy:metadata:core_files'] = 'Pliki dołączone do złożonych prac lub utworzone na podstawie przesyłanych tekstów online.';
$string['privacy:metadata:core_plagiarism'] = 'Ta wtyczka jest wywoływana przez podsystem wykrywania plagiatów Moodle.';
$string['privacy:metadata:safeassign_service'] = 'Aby uzyskać raport dotyczący oryginalności pracy, należy wysłać do usługi SafeAssign pewne dane użytkownika.';
$string['privacy:metadata:safeassign_service:adminemail'] = 'Administrator powinien wysłać wiadomość e-mail, aby zaakceptować licencję.';
$string['privacy:metadata:safeassign_service:filecontent'] = 'W celu wygenerowania raportu na temat oryginalności prac musimy wysłać pliki do usługi SafeAssign.';
$string['privacy:metadata:safeassign_service:filename'] = 'Usługa SafeAssign wymaga nazwy pliku.';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'Identyfikator UUID pliku pozwala powiązać pliki Moodle na serwerze SafeAssign.';
$string['privacy:metadata:safeassign_service:fullname'] = 'Nazwa użytkownika jest wysyłana do usługi SafeAssign, aby umożliwić uzyskanie tokenu uwierzytelniania.';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'Identyfikator UUID złożonej pracy jest wymagany do pobrania raportu na temat oryginalności prac.';
$string['privacy:metadata:safeassign_service:userid'] = 'Identyfikator użytkownika jest wysyłany z Moodle, aby umożliwić skorzystanie z usługi SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_files'] = 'Informacje na temat oryginalności plików przesłanych przez użytkownika';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'Identyfikator studenta, który złożył tę pracę.';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'Unikalny identyfikator pliku w usłudze SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'Adres URL raportu na temat oryginalności prac.';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = 'Wynik oceny podobieństwa przesłanego pliku.';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'Czas przesłania pliku.';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'Unikalny identyfikator złożonej pracy w usłudze SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = 'Identyfikator przesłanego pliku.';
$string['privacy:metadata:plagiarism_safeassign_course'] = 'Informacje na temat kursów Moodle z włączoną usługą SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'Unikalny identyfikator kursu w usłudze SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'Kurs bez żadnej aktywności, w której włączono usługę SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'Identyfikator użytkownika, który jest nauczycielem na tym kursie.';
$string['privacy:metadata:plagiarism_safeassign_subm'] = 'Informacje o pracach złożonych przez studentów.';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'Identyfikator zadania tej złożonej pracy.';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'Średni wynik oceny podobieństwa wszystkich przesłanych plików.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = 'Flaga wskazująca, czy złożona praca zawiera plik.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = 'Flaga wskazująca, czy złożona praca zawiera tekst online.';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = 'Najwyższy wynik oceny podobieństwa dla jednego przesłanego pliku.';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'Identyfikator złożonej pracy związany z aktywnością, w której włączono usługę SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'Flaga wskazująca, czy plik został wysłany do usługi SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = 'Czas utworzenia złożonej pracy.';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'Unikalny identyfikator złożonej pracy w usłudze SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'Informacje na temat nauczycieli na platformie.';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = 'Identyfikator użytkownika, który jest nauczycielem na jednym kursie.';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'Identyfikator kursu, na którym użytkownik jest nauczycielem.';
