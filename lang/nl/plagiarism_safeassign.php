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

$string['pluginname'] = 'SafeAssign-plugin voor plagiaat';
$string['getscores'] = 'Scores ophalen voor inzendingen';
$string['getscoreslog'] = 'SafeAssign-scoretaaklogboek';
$string['getscoreslogfailed'] = 'SafeAssign-scoretaak mislukt';
$string['getscoreslog_desc'] = 'SafeAssign-scoretaak met succes uitgevoerd.';
$string['servicedown'] = 'SafeAssign-service is niet beschikbaar.';
$string['studentdisclosuredefault'] = 'Alle geüploade bestanden worden verzonden naar een service voor detectie van plagiaat';
$string['studentdisclosure'] = 'Releaseverklaring instelling';
$string['studentdisclosure_help'] = 'Deze tekst wordt weergegeven voor alle cursisten op de pagina waarop het uploaden van bestanden plaatsvindt. Als dit
veld leeg wordt gelaten, wordt de gelokaliseerde standaardstring (studentdisclosuredefault) gebruikt.';
$string['safeassignexplain'] = 'Zie voor meer informatie over deze plug-in:';
$string['safeassign'] = 'SafeAssign-plugin voor plagiaat';
$string['safeassign:enable'] = 'De docent toestaan SafeAssign in/uit te schakelen binnen een activiteit';
$string['safeassign:report'] = 'Weergave van het originaliteitsrapport vanuit SafeAssign toestaan';
$string['usesafeassign'] = 'SafeAssign inschakelen';
$string['savedconfigsuccess'] = 'Plagiaatinstellingen opgeslagen';
$string['safeassign_additionalroles'] = 'Extra rollen';
$string['safeassign_additionalroles_help'] = 'Gebruikers met deze rollen op systeemniveau worden aan elke SafeAssign-cursus
toegevoegd als cursusleiders.';
$string['safeassign_api'] = 'URL SafeAssign-integratie';
$string['safeassign_api_help'] = 'Dit is het adres van de SafeAssign API.';
$string['instructor_role_credentials'] = 'Referenties cursusleiderrol';
$string['safeassign_instructor_username'] = 'Gedeelde sleutel';
$string['safeassign_instructor_username_help'] = 'Gedeelde sleutel van cursusleider verstrekt door SafeAssign.';
$string['safeassign_instructor_password'] = 'Gedeeld geheim';
$string['safeassign_instructor_password_help'] = 'Gedeeld geheim van de cursusleider verstrekt door SafeAssign.';
$string['student_role_credentials'] = 'Referenties voor studentenrol';
$string['safeassign_student_username'] = 'Gedeelde sleutel';
$string['safeassign_student_username_help'] = 'Gedeelde sleutel van student verstrekt door SafeAssign.';
$string['safeassign_student_password'] = 'Gedeeld geheim';
$string['safeassign_student_password_help'] = 'Gedeeld geheim van student verstrekt door SafeAssign.';
$string['safeassign_license_acceptor_givenname'] = 'Voornaam licentieacceptant';
$string['safeassign_license_acceptor_surname'] = 'Achternaam licentieacceptant';
$string['safeassign_license_acceptor_email'] = 'E-mail licentieacceptant';
$string['safeassign_license_header'] = 'Voorwaarden SafeAssign&trade;-licentie';
$string['license_already_accepted'] = 'De huidige licentievoorwaarden zijn al geaccepteerd door je beheerder.';
$string['acceptlicense'] = 'SafeAssign-licentie accepteren';
$string['acceptlicenselog'] = 'Taaklogboek SafeAssign-licentie';
$string['safeassign_license_warning'] = 'Er is een probleem bij het valideren van de SafeAssign&trade;-licentiegegevens. Klik
op de knop &apos;Verbinding testen&apos;. Probeer het later opnieuw indien de test is geslaagd.';
$string['safeassign_enableplugin'] = 'SafeAssign inschakelen voor {$a}';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&amp;Nbsp Standaardwaarde: 0</div> <br>';
$string['safeassign_showid'] = 'Student-id tonen';
$string['safeassign_alloworganizations'] = 'SafeAssignments toestaan in organisaties';
$string['safeassign_referencedbactivity'] = 'Activiteit <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Algemene naslagdatabase</a>';
$string['safeassing_response_header'] = '<br>SafeAssign-serverrespons:<br>';
$string['safeassign_instructor_credentials'] = 'Referenties cursusleiderrol:';
$string['safeassign_student_credentials'] = 'Referenties studentrol:';
$string['safeassign_credentials_verified'] = 'Verbinding geverifieerd.';
$string['safeassign_credentials_fail'] = 'Verbinding niet geverifieerd. Controleer sleutel, geheim en URL.';
$string['credentials'] = 'Referenties en service-URL';
$string['shareinfo'] = 'Informatie delen met SafeAssign';
$string['disclaimer'] = '<br>Door inzending naar de <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Algemene naslagdatabase</a> van SafeAssign kunnen papers van andere instituten<br>
worden gecontroleerd in relatie tot de paper van je student om de oorsprong van hun werk te beschermen.';
$string['settings'] = 'SafeAssign-instellingen';
$string['timezone_help'] = 'De tijdzone die is ingesteld in je Open LMS-omgeving.';
$string['timezone'] = 'Tijdzone';
$string['safeassign_status'] = 'SafeAssign-status';
$string['status:pending'] = 'Bezig';
$string['safeassign_score'] = 'SafeAssign-score';
$string['safeassign_reporturl'] = 'Rapport-URL';
$string['button_disabled'] = 'Formulier opslaan om verbinding te testen';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'Fout bij ophalen van json-bestand &quot;{$a}&quot; uit map plagiarisme/safeassign/tests/fixtures voor het simuleren van een aanroep naar SafeAssign-webservices bij het uitvoeren van Behat-tests.';
$string['safeassign_curlcache'] = 'Time-out cache';
$string['safeassign_curlcache_help'] = 'Time-out cache webservice.';
$string['rest_error_nocurl'] = 'cURL-module moet aanwezig en ingeschakeld zijn!';
$string['rest_error_nourl'] = 'Je moet een URL opgeven!';
$string['rest_error_nomethod'] = 'Je moet de aanvraagmethode opgeven!';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'Test verbinding';
$string['connectionfailed'] = 'Verbinding mislukt';
$string['connectionverified'] = 'Verbinding geverifieerd';
$string['cachedef_request'] = 'Cache SafeAssign-aanvraag';
$string['error_behat_instancefail'] = 'Dit is een instantie die zo is geconfigureerd dat deze mislukt met Behat-tests.';
$string['assignment_check_submissions'] = 'Inzendingen controleren met SafeAssign';
$string['assignment_check_submissions_help'] = 'Originaliteitsrapporten van SafeAssign zijn niet beschikbaar voor docenten indien anonieme beoordeling
is ingesteld, maar studenten kunnen hun eigen originaliteitsrapporten van  SafeAssign bekijken indien &quot;Studenten toestaan originaliteitsrapport in te zien&quot; wordt geselecteerd.
<br><br>SafeAssign produceert één enkel originaliteitsrapport wanneer gebruikers meerdere bestanden inzenden. Vanuit dit rapport kun je kiezen welk bestand je wilt bekijken.
<br><br>Hoewel SafeAssign officieel alleen Engels ondersteunt, kun je proberen SafeAssign in andere talen te gebruiken.
SafeAssign heeft geen technische beperkingen die het gebruik ervan in andere talen uitsluiten.
Zie <a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">Blackboard Help</a> voor meer informatie.';
$string['students_originality_report'] = 'Studenten toestaan om het originaliteitsrapport in te zien';
$string['submissions_global_reference'] = 'Inzendingen uitsluiten van <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a>';
$string['submissions_global_reference_help'] = 'Inzendingen worden nog steeds verwerkt door SafeAssign, maar ze worden niet geregistreerd in databases. Dit voorkomt dat bestanden als plagiaat worden aangemerkt wanneer docenten in een bepaalde opdracht herinzendingen toestaan.';
$string['plagiarism_tools'] = 'Plagiaattools';
$string['files_accepted'] = 'SafeAssign accepteert alleen bestanden met het bestandsformaat .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf en .html. Bestanden van een ander formaat, waaronder .zip en andere gecomprimeerde bestandsindelingen, worden niet gecontroleerd via SafeAssign.
<br><br>Door deze paper in te zenden, ga je ermee akkoord:
(1) dat je je paper inzendt voor gebruik en opslag als onderdeel van de SafeAssign&trade;-services in overeenstemming met de <a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">Voorwaarden en service</a> van Blackboard en het <a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">Blackboard-privacybeleid</a>;
(2) dat je instelling je paper mag gebruiken in overeenstemming met het beleid van je instelling; en
(3) dat je SafeAssign gebruikt zonder enige vordering jegens Open LMS en haar dochterondernemingen.';
$string['agreement'] = 'Ik ga ermee akkoord dat mijn paper(s) worden ingezonden naar de <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Algemene naslagdatabase</a>.';
$string['error_api_generic'] = 'Er is een fout opgetreden bij het verwerken van je aanvraag';
$string['error_api_unauthorized'] = 'Er is een verificatiefout opgetreden bij het verwerken van je aanvraag';
$string['error_api_forbidden'] = 'Er is een autorisatiefout opgetreden bij het verwerken van je aanvraag';
$string['error_api_not_found'] = 'De opgevraagde bron is niet gevonden';
$string['sync_assignments'] = 'Verzendt de beschikbare informatie naar de SafeAssign-server.';
$string['api_call_log_event'] = 'SafeAssign-logboek voor API-aanroepen.';
$string['course_error_sync'] = 'Er is een fout opgetreden bij de poging de cursus met id: {$a} te synchroniseren met SafeAssign:<br>';
$string['assign_error_sync'] = 'Er is een fout opgetreden bij de poging de opdracht met id: {$a} te synchroniseren met SafeAssign:<br>';
$string['submission_error_sync'] = 'Er is een fout opgetreden bij de poging de inzending met id {$a} te synchroniseren met SafeAssign:<br>';
$string['submission_success_sync'] = 'Inzendingen zijn met succes gesynchroniseerd';
$string['assign_success_sync'] = 'Opdrachten zijn met succes gesynchroniseerd';
$string['course_success_sync'] = 'Cursussen zijn met succes gesynchroniseerd';
$string['license_header'] = 'Licentieovereenkomst SafeAssign&trade;';
$string['license_title'] = 'Licentieovereenkomst SafeAssign';
$string['not_configured'] = 'SafeAssign&trade; is niet geconfigureerd. Laat je systeembeheerder een ticket indienen
bij <a href="https://support.openlms.net/" target="_blank" rel="noopener">Open LMS Support</a> voor hulp.';
$string['agree_continue'] = 'Formulier opslaan';
$string['safeassign_file_not_supported'] = 'Niet ondersteund.';
$string['safeassign_file_not_supported_help'] = 'De bestandsextensie wordt niet ondersteund door SafeAssign of de bestandsgrootte overschrijdt de maximale capaciteit.';
$string['safeassign_submission_not_supported'] = 'Deze inzending wordt niet geëvalueerd door SafeAssign.';
$string['safeassign_submission_not_supported_help'] = 'Inzendingen die door cursusleiders zijn gemaakt, worden niet naar SafeAssign verzonden.';
$string['safeassign_file_in_review'] = 'Originaliteitsrapport SafeAssign wordt uitgevoerd...';
$string['safeassign_file_similarity_score'] = 'SafeAssign-score: {$a}%<br>';
$string['safeassign_link_originality_report'] = 'Originaliteitsrapport weergeven';
$string['safeassign_file_limit_exceeded'] = 'Deze inzending overschrijdt de gecombineerde groottelimiet van 10 MB en wordt niet verwerkt door SafeAssign';
$string['originality_report'] = 'Originaliteitsrapport SafeAssign';
$string['originality_report_unavailable'] = 'Het gevraagde originaliteitsrapport is niet beschikbaar. Kijk later nog eens of neem contact op met de systeembeheerder.';
$string['originality_report_error'] = 'Er is een fout geconstateerd met het originaliteitsrapport van SafeAssign. Neem contact op met de systeembeheerder.';
$string['safeassign_overall_score'] = '<b>SafeAssign-totaalscore: {$a}%</b>';
$string['messageprovider:safeassign_graded'] = 'SafeAssign verstuurt meldingen naar cursusleiders wanneer een inzending is beoordeeld op plagiaat';
$string['safeassign_loading_settings'] = 'Bezig met laden van instellingen, even geduld';
$string['safeassign:get_messages'] = 'Ontvangen van meldingen van SafeAssign toestaan';
$string['safeassign_notification_message'] = 'Plagiaatscores zijn verwerkt voor {$a->counter} {$a->plural} in {$a->assignmentname}';
$string['safeassign_notification_grading_link'] = 'Beoordelingspagina';
$string['safeassign_notification_message_hdr'] = 'Plagiaatscores van SafeAssign zijn verwerkt';
$string['safeassign_notification_subm_singular'] = 'inzending';
$string['safeassign_notification_subm_plural'] = 'inzendingen';
$string['messageprovider:safeassign_notification'] = 'SafeAssign verstuurt meldingen naar sitebeheerders wanneer er nieuwe licentievoorwaarden beschikbaar zijn';
$string['safeassign:get_notifications'] = 'Meldingen van SafeAssign toestaan';
$string['license_agreement_notification_subject'] = 'Nieuwe licentievoorwaarden voor SafeAssign beschikbaar';
$string['license_agreement_notification_message'] = 'Je kunt de nieuwe licentievoorwaarden hier accepteren: {$a}';
$string['settings_page'] = 'Pagina met SafeAssign-instellingen';
$string['send_notifications'] = 'Meldingen voor nieuwe licentievoorwaarden verzenden';
$string['privacy:metadata:core_files'] = 'Bestanden bijgevoegd bij inzendingen of gemaakt van online tekstinzendingen.';
$string['privacy:metadata:core_plagiarism'] = 'Deze plugin wordt aangeroepen door het plagiaatsubsysteem van Moodle.';
$string['privacy:metadata:safeassign_service'] = 'Om over een originaliteitsrapport te beschikken, moeten bepaalde gebruikersgegevens worden verzonden naar de SafeAssign-service.';
$string['privacy:metadata:safeassign_service:adminemail'] = 'De beheerder moet zijn of haar e-mailadres verzenden om de servicelicentie te accepteren.';
$string['privacy:metadata:safeassign_service:filecontent'] = 'We moeten de bestanden naar SafeAssign versturen om het originaliteitsrapport te genereren.';
$string['privacy:metadata:safeassign_service:filename'] = 'De bestandsnaam is vereist voor de SafeAssign-service.';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'Met behulp van de bestands-uuid kunnen Moodle-bestanden worden gerelateerd op de SafeAssign-server.';
$string['privacy:metadata:safeassign_service:fullname'] = 'De gebruikersnaam wordt naar SafeAssign verzonden om het verificatietoken te verkrijgen.';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'Deze inzendings-uuid is vereist om het originaliteitsrapport op te halen.';
$string['privacy:metadata:safeassign_service:userid'] = 'De gebruikers-id die door Moodle is verzonden om je in staat te stellen SafeAssign-services te gebruiken.';
$string['privacy:metadata:plagiarism_safeassign_files'] = 'Informatie over de originaliteit van de bestanden die zijn geüpload door de gebruiker';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'De id van de student die deze inzending heeft verstuurd.';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'Unieke bestands-id in de SafeAssign-service.';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'URL naar het originaliteitsrapport.';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = 'Overeenkomstigheidsscore voor het ingezonden bestand.';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'Tijdstip waarop het bestand is ingezonden.';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'Unieke id van inzending in SafeAssign-service';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = 'De id van het bestand dat werd ingezonden.';
$string['privacy:metadata:plagiarism_safeassign_course'] = 'Informatie over Moodle-cursussen waarvoor SafeAssign is ingeschakeld.';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'Unieke id van cursus in SafeAssign-service.';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'De cursus die een activiteit heeft waarvoor SafeAssign is ingeschakeld.';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'De id van de gebruiker die een docent is in deze cursus.';
$string['privacy:metadata:plagiarism_safeassign_subm'] = 'Informatie over inzendingen van studenten.';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'De opdracht-id van deze inzending.';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'De gemiddelde overeenkomstigheidsscore voor alle ingezonden bestanden.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = 'Markering om aan te geven of de inzending een bestand bevat.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = 'Markering om aan te geven of de inzending online tekst bevat.';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = 'De hoogste overeenkomstigheidsscore voor één ingezonden bestand.';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'De inzending-id van een activiteit waarvoor SafeAssign is ingeschakeld.';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'Markering om aan te geven of het bestand is verzonden naar SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = 'Tijdstip waarop de inzending is gemaakt.';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'Unieke inzending-id in SafeAssign-service.';
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'Informatie over de docenten in het platform.';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = 'De id van één gebruiker die docent is in één cursus.';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'De id van de cursus waarin de gebruiker een docent is.';
