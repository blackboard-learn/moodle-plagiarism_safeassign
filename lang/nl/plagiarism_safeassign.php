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

$string['pluginname'] = 'SafeAssign-plugin voor plagiaatdetectie';
$string['getscores'] = 'Scores voor inzendingen opvragen';
$string['getscoreslog'] = 'Logboek SafeAssign-scoretaken';
$string['getscoreslogfailed'] = 'Fout SafeAssign-scoretaken';
$string['getscoreslog_desc'] = 'SafeAssign-scoretaak met succes uitgevoerd.';
$string['servicedown'] = 'SafeAssign-service is niet beschikbaar.';
$string['studentdisclosuredefault'] = 'Alle geüploade bestanden worden aangeboden aan een service voor plagiaatdetectie';
$string['studentdisclosure'] = 'Release-opmerking van instelling';
$string['studentdisclosure_help'] = 'Deze tekst wordt aan alle studenten weergegeven op de pagina voor het uploaden van bestanden. Als dit veld leeg is, wordt de vertaalde standaardtekst (studentdisclosuredefault) gebruikt.';
$string['safeassignexplain'] = 'Zie het volgende voor meer informatie over deze plugin: ';
$string['safeassign'] = 'SafeAssign-plugin voor plagiaatdetectie';
$string['safeassign:enable'] = 'Docent toestaan om SafeAssign in-/uit te schakelen binnen een activiteit';
$string['safeassign:report'] = 'Weergave van originaliteitsrapport toestaan vanuit SafeAssign';
$string['usesafeassign'] = 'SafeAssign inschakelen';
$string['savedconfigsuccess'] = 'Plagiaatinstellingen opgeslagen';
$string['safeassign_additionalroles'] = 'Aanvullende rollen';
$string['safeassign_additionalroles_help'] = 'Gebruikers met deze rollen op systeemniveau worden als cursusleider toegevoegd
aan elke SafeAssign-cursus.';
$string['safeassign_api'] = 'URL voor SafeAssign-integratie';
$string['safeassign_api_help'] = 'Dit is het adres van de SafeAssign-API.';
$string['instructor_role_credentials'] = 'Referenties cursusleiderrol';
$string['safeassign_instructor_username'] = 'Gedeelde sleutel';
$string['safeassign_instructor_username_help'] = 'Gedeelde sleutel van cursusleider verstrekt door SafeAssign.';
$string['safeassign_instructor_password'] = 'Gedeeld geheim';
$string['safeassign_instructor_password_help'] = 'Gedeeld geheim van cursusleider verstrekt door SafeAssign.';
$string['student_role_credentials'] = 'Referenties studentenrol';
$string['safeassign_student_username'] = 'Gedeelde sleutel';
$string['safeassign_student_username_help'] = 'Gedeelde sleutel van student verstrekt door SafeAssign.';
$string['safeassign_student_password'] = 'Gedeeld geheim';
$string['safeassign_student_password_help'] = 'Gedeeld geheim van student verstrekt door SafeAssign.';
$string['safeassign_license_acceptor_givenname'] = 'Voornaam licentie-acceptant';
$string['safeassign_license_acceptor_surname'] = 'Achternaam licentie-acceptant';
$string['safeassign_license_acceptor_email'] = 'E-mail licentie-acceptant';
$string['safeassign_license_header'] = 'Voorwaarden SafeAssign&trade;-licentieovereenkomst';
$string['license_already_accepted'] = 'De huidige licentievoorwaarden zijn al geaccepteerd door de beheerder.';
$string['acceptlicense'] = 'SafeAssign-licentie accepteren';
$string['acceptlicenselog'] = 'Logboek SafeAssign-licentietaken';
$string['safeassign_license_warning'] = 'Er is een probleem met het valideren van de SafeAssign&trade;-licentiegegevens. Klik
op de knop Verbinding testen. Als de test lukt, probeer je het later opnieuw.';
$string['safeassign_enableplugin'] = 'SafeAssign inschakelen voor {$a}';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&nbsp Standaardwaarde: 0</div> <br>';
$string['safeassign_showid'] = 'Student-ID weergeven';
$string['safeassign_alloworganizations'] = 'SafeAssignments toestaan in organisaties';
$string['safeassign_referencedbactivity'] = 'Activiteit <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">algemene naslagddatabase</a>';
$string['safeassing_response_header'] = '<br>Reactie SafeAssign-server: <br>';
$string['safeassign_instructor_credentials'] = 'Referenties cursusleiderrol: ';
$string['safeassign_student_credentials'] = 'Referenties studentenrol: ';
$string['safeassign_credentials_verified'] = 'Verbinding geverifieerd.';
$string['safeassign_credentials_fail'] = 'Verbinding niet geverifieerd. Controleer sleutel, geheim en URL.';
$string['credentials'] = 'Referenties en service-URL';
$string['shareinfo'] = 'Info delen met SafeAssign';
$string['disclaimer'] = '<br>Als werkstukken worden verzonden naar de <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">algemene naslagdatabase</a> van SafeAssign, kunnen werkstukken van andere instellingen <br>
                        worden vergeleken met de werkstukken van jouw studenten om de herkomst van hun werk te beschermen.';
$string['settings'] = 'SafeAssign-instellingen';
$string['timezone_help'] = 'De tijdzone die is ingesteld in je Blackboard Open LMS-omgeving.';
$string['timezone'] = 'Tijdzone';
$string['safeassign_status'] = 'SafeAssign-status';
$string['status:pending'] = 'In behandeling';
$string['safeassign_score'] = 'SafeAssign-score';
$string['safeassign_reporturl'] = 'Rapport-URL';
$string['button_disabled'] = 'Formulier opslaan om verbinding te testen';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'Fout bij opvragen van json-bestand "{$a}" uit de map plagiarism/safeassign/tests/fixtures voor het simuleren van een aanroep van SafeAssign-webservices bij het uitvoeren van Behat-tests.';
$string['safeassign_curlcache'] = 'Time-out cache';
$string['safeassign_curlcache_help'] = 'Time-out cache webservice.';
$string['rest_error_nocurl'] = 'cURL-module moet aanwezig en ingeschakeld zijn!';
$string['rest_error_nourl'] = 'Je moet een URL opgeven!';
$string['rest_error_nomethod'] = 'Je moet een aanvraagmethode opgeven!';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'Verbinding testen';
$string['connectionfailed'] = 'Verbinding mislukt';
$string['connectionverified'] = 'Verbinding geverifieerd';
$string['cachedef_request'] = 'Cache met SafeAssign-aanvragen';
$string['error_behat_instancefail'] = 'Dit is een exemplaar dat is geconfigureerd om te mislukken met Behat-tests.';
$string['assignment_check_submissions'] = 'Inzendingen controleren met SafeAssign';
$string['assignment_check_submissions_help'] = 'SafeAssign-originaliteitsrapporten zijn niet beschikbaar voor docenten als anoniemen beoordeling
 is ingesteld, maar studenten kunnen hun eigen SafeAssign-originaliteitsrapporten inzien als "Studenten toestaan originaliteitsrapport in te zien" is geselecteerd.
<br><br>Hoewel SafeAssign officieel alleen Engels ondersteunt, kun je zonder problemen proberen om SafeAssign te gebruiken met andere talen.
SafeAssign kent geen technische beperkingen die het gebruik met andere talen voorkomen.
Zie de <a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">Blackboard Help</a> voor meer informatie.';
$string['students_originality_report'] = 'Studenten toestaan originaliteitsrapport in te zien';
$string['submissions_global_reference'] = 'Inzendingen niet opnemen in instellingsdatabase en <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">algemene naslagdatabase</a>';
$string['submissions_global_reference_help'] = 'Inzendingen worden dan nog wel verwerkt door SafeAssign, maar ze worden niet geregistreerd in databases. Je voorkomt zo dat bestanden als plagiaat worden aangemerkt wanneer je voor een bepaalde opdracht herinzendingen toestaat.';
$string['plagiarism_tools'] = 'Tools voor plagiaatdetectie';
$string['files_accepted'] = 'SafeAssign accepteert alleen bestanden in deze indelingen: .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf en .html. Bestanden met een andere indeling, waaronder .zip en andere gecomprimeerde bestandsindelingen, worden niet gecontroleerd door SafeAssign.
<br><br>Je gaat akkoord met het volgende wanneer je dit werkstuk indient:
 (1) dat je het werkstuk indient voor gebruik door de SafeAssign&trade;-services in overeenstemming met de <a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">gebruiksvoorwaarden</a> van Blackboard en het <a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">Blackboard-privacybeleid</a>;
 (2) dat je instellling je werkstuk mag gebruiken in overeenstemming met het beleid van de instelling; en
 (3) dat je SafeAssign zult gebruiken zonder een claim in te dienen bij Blackboard Inc. en haar dochterondernemingen.';
$string['agreement'] = 'Ik ga ermee akkoord dat mijn werkstuk(ken) worden opgeslagen in de <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">algemene naslagdatabase</a>.';
$string['error_api_generic'] = 'Er is een fout opgetreden bij het verwerken van je aanvraag';
$string['error_api_unauthorized'] = 'Er is een verificatiefout opgetreden bij het verwerken van je aanvraag';
$string['error_api_forbidden'] = 'Er is een autorisatiefout opgetreden bij het verwerken van je aanvraag';
$string['error_api_not_found'] = 'De gevraagde bron is niet gevonden';
$string['sync_assignments'] = 'Hiermee worden beschikbare gegevens naar de SafeAssign-server verzonden.';
$string['api_call_log_event'] = 'SafeAssign-logboek voor API-aanroepen.';
$string['course_error_sync'] = 'Er is een fout opgetreden bij het synchroniseren van de cursus met de ID: {$a} met SafeAssign: <br>';
$string['assign_error_sync'] = 'Er is een fout opgetreden bij het synchroniseren van de opdracht met de ID: {$a} met SafeAssign: <br>';
$string['submission_error_sync'] = 'Er is een fout opgetreden bij het synchroniseren van de inzending met de ID: {$a} met SafeAssign: <br>';
$string['submission_success_sync'] = 'Inzendingen zijn gesynchroniseerd';
$string['assign_success_sync'] = 'Opdrachten zijn gesynchroniseerd';
$string['course_success_sync'] = 'Cursussen zijn gesynchroniseerd';
$string['license_header'] = 'SafeAssign&trade;-licentieovereenkomst';
$string['license_title'] = 'SafeAssign-licentieovereenkomst';
$string['not_configured'] = 'SafeAssign&trade; is niet geconfigureerd. Vraag de systeembeheerder om op Behind the Blackboard een ticket aan te maken voor hulp.';
$string['agree_continue'] = 'Formulier opslaan';
$string['safeassign_file_not_supported'] = 'Niet ondersteund.';
$string['safeassign_file_not_supported_help'] = 'De bestandsextensie wordt niet ondersteund door SafeAssign of de bestandsgrootte overschrijdt de maximumcapaciteit.';
$string['safeassign_submission_not_supported'] = 'Deze inzending wordt niet gecontroleerd door SafeAssign.';
$string['safeassign_submission_not_supported_help'] = 'Inzendingen die zijn gemaakt door cursusleiders worden niet naar SafeAssign verzonden.';
$string['safeassign_file_in_review'] = 'SafeAssign-originaliteitsrapport wordt gemaakt...';
$string['safeassign_file_similarity_score'] = 'SafeAssign-score: {$a}%<br>';
$string['safeassign_link_originality_report'] = 'Originaliteitsrapport weergeven';
$string['safeassign_file_limit_exceeded'] = 'Deze inzending overschrijft de gecombineerde groottelimiet van 10 MB en wordt daarom niet verwerkt door SafeAssign';
$string['originality_report'] = 'SafeAssign-originaliteitsrapport';
$string['originality_report_unavailable'] = 'Het gevraagde originaliteitsrapport is niet beschikbaar. Kijk later nog eens of neem contact op met de systeembeheerder.';
$string['originality_report_error'] = 'Er is een fout geconstateerd met het originaliteitsrapport van SafeAssign. Neem contact op met de systeembeheerder.';
$string['safeassign_overall_score'] = '<b>Totaalscore SafeAssign: {$a}%</b>';
$string['messageprovider:safeassign_graded'] = 'SafeAssign verstuurt meldingen naar cursusleiders wanneer een inzendig is beoordeeld op plagiaat';
$string['safeassign_loading_settings'] = 'Instellingen worden geladen, even geduld';
$string['safeassign:get_messages'] = 'Meldingen van SafeAssign ontvangen';
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
$string['privacy:metadata:core_plagiarism'] = 'Deze plugin wordt aangeroepen door het subsysteem voor plagiaatdetectie van Moodle.';
$string['privacy:metadata:safeassign_service'] = 'Om over een originaliteitsrapport te beschikken, moeten bepaalde gebruikersgegevens worden verzonden naar de SafeAssign-service.';
$string['privacy:metadata:safeassign_service:adminemail'] = 'De beheerder moet een e-mailadres verzenden om de servicelicentie te accepteren.';
$string['privacy:metadata:safeassign_service:filecontent'] = 'We moeten de bestanden naar SafeAssign versturen om het originaliteitsrapport te genereren.';
$string['privacy:metadata:safeassign_service:filename'] = 'De bestandsnaam is vereist voor de SafeAssign-service.';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'Met behulp van de bestands-uuid kunnen Moodle-bestanden worden gerelateerd op de SafeAssign-server.';
$string['privacy:metadata:safeassign_service:fullname'] = 'De gebruikersnaam wordt verzonden naar SafeAssign om het verificatietoken op te vragen.';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'Deze inzendings-uuid is vereist om het originaliteitsrapport op te halen.';
$string['privacy:metadata:safeassign_service:userid'] = 'De gebruikers-ID die wordt verzonden vanuit Moodle om je toegang te bieden tot de SafeAssign-services.';
$string['privacy:metadata:plagiarism_safeassign_files'] = 'Informatie over de originaliteit van de bestanden die zijn geüpload door de gebruiker';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'De ID van de student die deze inzending heeft verstuurd.';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'Unieke bestands-ID in de SafeAssign-service.';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'URL naar het originaliteitsrapport.';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = 'Gelijksoortigheidsscore voor het ingediende bestand.';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'Het tijdstip waarop het bestand is ingediend.';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'Unieke inzendings-ID in de SafeAssign-service';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = 'De ID van het bestand dat is ingediend.';
$string['privacy:metadata:plagiarism_safeassign_course'] = 'Informatie over Moodle-cursussen waarvoor SafeAssign is ingeschakeld.';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'Unieke cursus-ID in de SafeAssign-service.';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'De cursus met een activiteit waarvoor SafeAssign is ingeschakeld.';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'De ID van de gebruiker die een docent is in deze cursus.';
$string['privacy:metadata:plagiarism_safeassign_subm'] = 'Informatie over inzendingen van studenten.';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'De opdracht-ID van deze inzending.';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'De gemiddelde gelijksoortigheidsscore voor alle ingediende bestanden.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = 'Vlag om te bepalen of er een bestand is bijgevoegd bij de inzending.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = 'Vlag om te bepalen of er online tekst is bijgevoegd bij de inzending.';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = 'De hoogste gelijksoortigheidsscore voor een ingediend bestand.';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'De ID van een inzending met een activiteit waarvoor SafeAssign is ingeschakeld.';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'Vlag om te bepalen of het bestand is verzonden naar SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = 'Tijdstip waarop de inzending is gemaakt.';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'Unieke inzendings-ID in de SafeAssign-service.';
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'Informatie over de docenten op het platform.';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = 'De ID van een gebruiker die een docent is in een cursus.';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'De ID van de cursus waarin de gebruiker een docent is.';
