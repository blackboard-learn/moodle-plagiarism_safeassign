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

$string['pluginname'] = 'SafeAssign-plagieringsplugin';
$string['getscores'] = 'Hent pointresultater for besvarelser';
$string['getscoreslog'] = 'Logfil for opgaven SafeAssign-pointresultater';
$string['getscoreslogfailed'] = 'Opgaven SafeAssign-pointresultater mislykkedes';
$string['getscoreslog_desc'] = 'Opgaven SafeAssign-pointresultater lykkedes.';
$string['servicedown'] = 'SafeAssign-tjenesten er ikke tilgængelig.';
$string['studentdisclosuredefault'] = 'Alle uploadede filer indsendes til plagieringskontrol';
$string['studentdisclosure'] = 'Frigivelseserklæring';
$string['studentdisclosure_help'] = 'Denne tekst vises til alle studerende på siden for upload af filer. Hvis dette
felt står tomt, vil den lokaliserede standardstreng (studentdisclosuredefault) blive anvendt i stedet.';
$string['safeassignexplain'] = 'For at få mere information om dette plugin, kan du se:';
$string['safeassign'] = 'SafeAssign-plagieringsplugin';
$string['safeassign:enable'] = 'Tillad underviseren at aktivere/deaktivere SafeAssign i en aktivitet';
$string['safeassign:report'] = 'Tillad adgang til originalitetsrapporten fra SafeAssign';
$string['usesafeassign'] = 'Aktivér SafeAssign';
$string['savedconfigsuccess'] = 'Indstillinger for plagiering gemt';
$string['safeassign_additionalroles'] = 'Yderligere roller';
$string['safeassign_additionalroles_help'] = 'Brugere med disse roller på systemniveau vil blive føjet til hvert SafeAssign-
kursus som undervisere.';
$string['safeassign_api'] = 'URL til SafeAssign-integration';
$string['safeassign_api_help'] = 'Dette er adressen for SafeAssign-API&apos;en.';
$string['instructor_role_credentials'] = 'Legitimationsoplysninger for underviserrolle';
$string['safeassign_instructor_username'] = 'Delt nøgle';
$string['safeassign_instructor_username_help'] = 'Underviserens delte nøgle angivet af SafeAssign.';
$string['safeassign_instructor_password'] = 'Delt hemmelighed';
$string['safeassign_instructor_password_help'] = 'Underviserens delte hemmelighed angivet af SafeAssign.';
$string['student_role_credentials'] = 'Legitimationsoplysninger for rollen studerende';
$string['safeassign_student_username'] = 'Delt nøgle';
$string['safeassign_student_username_help'] = 'Den studerendes delte nøgle angivet af SafeAssign.';
$string['safeassign_student_password'] = 'Delt hemmelighed';
$string['safeassign_student_password_help'] = 'Den studerendes delte hemmelighed angivet af SafeAssign.';
$string['safeassign_license_acceptor_givenname'] = 'Licensaftalens acceptants fornavn';
$string['safeassign_license_acceptor_surname'] = 'Licensaftalens acceptants efternavn';
$string['safeassign_license_acceptor_email'] = 'Licensaftalens acceptants e-mail';
$string['safeassign_license_header'] = 'Vilkår og betingelser for SafeAssign&trade;-licens';
$string['license_already_accepted'] = 'De nuværende licensvilkår er allerede blevet accepteret af din administrator.';
$string['acceptlicense'] = 'Acceptér SafeAssign-licensaftalen';
$string['acceptlicenselog'] = 'Logfil for opgaven SafeAssign-licensaftale';
$string['safeassign_license_warning'] = 'Der er problemer med at validere SafeAssign&trade;-licensoplysningerne.
Klik venligst på knappen &quot;Test forbindelse&quot;. Hvis testen lykkes, kan du prøve at validere igen senere.';
$string['safeassign_enableplugin'] = 'Aktivér SafeAssign for {$a}';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&amp;nbsp Standardværdi: 0</div> <br>';
$string['safeassign_showid'] = 'Vis ID for studerende';
$string['safeassign_alloworganizations'] = 'Tillad SafeAssign i organisationer';
$string['safeassign_referencedbactivity'] = 'Aktivitet for den <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Globale referencedatabase</a>';
$string['safeassing_response_header'] = '<br>SafeAssign-serversvar:<br>';
$string['safeassign_instructor_credentials'] = 'Legitimationsoplysninger for rollen underviser:';
$string['safeassign_student_credentials'] = 'Legitimationsoplysninger for rollen studerende:';
$string['safeassign_credentials_verified'] = 'Forbindelse bekræftet.';
$string['safeassign_credentials_fail'] = 'Forbindelse ikke bekræftet. Kontrollér nøgle, hemmelighed og URL.';
$string['credentials'] = 'URL til legimitationsoplysninger og tjenester';
$string['shareinfo'] = 'Del oplysninger med SafeAssign';
$string['disclaimer'] = '<br>Indsendelse til SafeAssigns <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Globale referencedatabase</a> gør det muligt at tillade, at opgaver fra andre institutioner<br>
bliver kontrolleret i forhold til din studerendes opgave for at beskytte arbejdets originalitet.';
$string['settings'] = 'Indstillinger for SafeAssign';
$string['timezone_help'] = 'Den indstillede tidszone i dit Open LMS-miljø.';
$string['timezone'] = 'Tidszone';
$string['safeassign_status'] = 'SafeAssign-status';
$string['status:pending'] = 'Afventer';
$string['safeassign_score'] = 'SafeAssign-pointresultat';
$string['safeassign_reporturl'] = 'Rapportér URL';
$string['button_disabled'] = 'Gem formular til test af forbindelse';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'Fejl under hentning af json-fil &quot;{$a}&quot; fra mappen plagiarism/safeassign/tests/fixtures til at simulere et opkald til SafeAssign-webstjenester når behat-tests køres.';
$string['safeassign_curlcache'] = 'Cachelager-timeout';
$string['safeassign_curlcache_help'] = 'Timeout for cachelager for webtjenesten.';
$string['rest_error_nocurl'] = 'cURL-modulet skal være til stede og aktiveret!';
$string['rest_error_nourl'] = 'Du skal angive en URL!';
$string['rest_error_nomethod'] = 'Du skal angive en metode til anmodning!';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'Test forbindelse';
$string['connectionfailed'] = 'Forbindelsen mislykkes';
$string['connectionverified'] = 'Forbindelse bekræftet';
$string['cachedef_request'] = 'SafeAssign-anmodningscachelager';
$string['error_behat_instancefail'] = 'Dette er en forekomst, der er indstillet til at fejle under behat-tests.';
$string['assignment_check_submissions'] = 'Tjek besvarelser med SafeAssign';
$string['assignment_check_submissions_help'] = 'SafeAssign-originalitetsrapporter er ikke tilgængelige for undervisere, hvis der er angivet anonym karaktergivning,
men de studerende kan se deres egne SafeAssign-originalitetsrapporter, hvis &quot;Tillad, at de studerende kan se originalitetsrapporten&quot; er markeret.
<br><br>SafeAssign laver en enkelt originalitetsrapport, når brugerne indsender flere filer. Du kan vælge, hvilken fil, der skal gennemgås gennem denne rapport.
<br><br>Selvom SafeAssign officielt kun understøtter engelsk, kan du prøve at bruge SafeAssign sammen med andre sprog.
SafeAssign indeholder ingen tekniske begrænsninger, der skulle udelukke at bruge programmet sammen med andre sprog.
Der henvises til <a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">Blackboard-hjælp</a> for at få flere oplysninger.';
$string['students_originality_report'] = 'Tillad studerende at se originalitetsrapporten';
$string['submissions_global_reference'] = 'Udelad besvarelser fra den <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Globale referencedatabase</a>';
$string['submissions_global_reference_help'] = 'Besvarelser vil stadig blive behandlet af SafeAssign, men vil ikke blive registreret i databasen. På den måde undgås det, at filer bliver markeret som plagieret, når undervisere tillader genindsendelse af en bestemt opgave.';
$string['plagiarism_tools'] = 'Værktøjer for plagiering';
$string['files_accepted'] = 'SafeAssign accepterer kun filer i formaterne .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf og .html. Filer i andre formater, herunder .zip og andre komprimerede filformater, kan ikke kontrolleres gennem SafeAssign.
<br><br>Ved at indsende denne opgavebesvarelse accepterer du:
(1) At du indsender din opgavebesvarelse til brug og opbevaring som en del af SafeAssign&trade;-tjenesterne i overensstemmelse med Blackboards <a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">Vilkår og servicebetingelser</a> og <a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">Blackboards privatlivspolitik</a>
(2) At din institution må bruge din opgavebesvarelse i overensstemmelse med din institutions politikker, og
(3) At din brug af SafeAssign vil være uden regres mod Open LMS og dets associerede selskaber.';
$string['agreement'] = 'Jeg accepterer at indsende min(e) opgavebesvarelse(r) til den <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Globale referencedatabase</a>.';
$string['error_api_generic'] = 'Der opstod en fejl under behandling af din anmodning';
$string['error_api_unauthorized'] = 'Der opstod en godkendelsesfejl under behandling af din anmodning';
$string['error_api_forbidden'] = 'Der opstod en autorisationsfejl under behandling af din anmodning';
$string['error_api_not_found'] = 'Ressourcen, der blev anmodet om, blev ikke fundet';
$string['sync_assignments'] = 'Sender de tilgængelige oplysninger til SafeAssign-serveren.';
$string['api_call_log_event'] = 'SafeAssign-logfil for API-opkald.';
$string['course_error_sync'] = 'Der opstod en fejl ved forsøg på synkronisering af kurset med id: {$a} i SafeAssign:<br>';
$string['assign_error_sync'] = 'Der opstod en fejl ved forsøg på synkronisering af opgaven med id: {$a} i SafeAssign:<br>';
$string['submission_error_sync'] = 'Der opstod en fejl ved forsøg på synkronisering af afleveringen med id: {$a} i SafeAssign:<br>';
$string['submission_success_sync'] = 'Besvarelserne blev synkroniseret';
$string['assign_success_sync'] = 'Opgaverne blev synkroniseret';
$string['course_success_sync'] = 'Kurserne blev synkroniseret';
$string['license_header'] = 'SafeAssign&trade;-licensaftale';
$string['license_title'] = 'SafeAssign-licensaftale';
$string['not_configured'] = 'SafeAssign&trade; er ikke konfigureret. Få venligst din systemadministrator til at oprette en sag
til <a href="https://support.openlms.net/" target="_blank" rel="noopener">Open LMS-support</a> for at få hjælp.';
$string['agree_continue'] = 'Gem formular';
$string['safeassign_file_not_supported'] = 'Ikke understøttet.';
$string['safeassign_file_not_supported_help'] = 'Filtypen understøttes ikke af SafeAssign, eller filstørrelsen overstiger den maksimale kapacitet.';
$string['safeassign_submission_not_supported'] = 'Denne besvarelse vil ikke blive gennemset af SafeAssign.';
$string['safeassign_submission_not_supported_help'] = 'Besvarelser oprettet af kursusundervisere sendes ikke til SafeAssign.';
$string['safeassign_file_in_review'] = 'SafeAssign-originalitetsrapport er i gang ...';
$string['safeassign_file_similarity_score'] = 'SafeAssign-pointresultat: {$a} %<br>';
$string['safeassign_link_originality_report'] = 'Se originalitetsrapport';
$string['safeassign_file_limit_exceeded'] = 'Denne besvarelse overstiger den kombinerede størrelsesgrænse på 10 MB og vil ikke blive behandlet af SafeAssign';
$string['originality_report'] = 'SafeAssign-originalitetsrapport';
$string['originality_report_unavailable'] = 'Den originalitetsrapport, som der blev anmodet om, er ikke tilgængelig. Tjek igen senere, eller kontakt din systemadministrator.';
$string['originality_report_error'] = 'Der opstod en fejl med SafeAssigns originalitetsrapport. Kontakt din systemadministrator.';
$string['safeassign_overall_score'] = '<b>Overordnet SafeAssign-pointresultat: {$a} %</b>';
$string['messageprovider:safeassign_graded'] = 'SafeAssign sender notifikationer til undervisere, når en besvarelse er blevet evalueret som plagiering';
$string['safeassign_loading_settings'] = 'Indlæser indstillinger, vent venligst';
$string['safeassign:get_messages'] = 'Tillad modtagelse af notifikationer fra SafeAssign';
$string['safeassign_notification_message'] = 'Pointresultat for plagiering er blevet behandlet for {$a->counter} {$a->plural} i {$a->assignmentname}';
$string['safeassign_notification_grading_link'] = 'Side for karaktergivning';
$string['safeassign_notification_message_hdr'] = 'SafeAssign-pointresultat for plagiering er blevet behandlet';
$string['safeassign_notification_subm_singular'] = 'aflevering';
$string['safeassign_notification_subm_plural'] = 'besvarelser';
$string['messageprovider:safeassign_notification'] = 'SafeAssign sender notifikationer til webstedsadministratorer, når nye vilkår og betingelser for licensen er tilgængelige';
$string['safeassign:get_notifications'] = 'Tillad notifikationer fra SafeAssign';
$string['license_agreement_notification_subject'] = 'Der findes nye vilkår og betingelser for SafeAssign-licensen';
$string['license_agreement_notification_message'] = 'Du kan acceptere de nye vilkår og betingelser for licensen her: {$a}';
$string['settings_page'] = 'Indstillingsside for SafeAssign';
$string['send_notifications'] = 'Send notifikationer om nye vilkår og betingelser for SafeAssign-licensen.';
$string['privacy:metadata:core_files'] = 'Filer vedhæftet til besvarelser eller skabt fra onlinetekst.';
$string['privacy:metadata:core_plagiarism'] = 'Dette plugin blev kaldt af Moodles undersystem for plagiering.';
$string['privacy:metadata:safeassign_service'] = 'For at få en originalitetsrapport skal der sendes brugerdata til SafeAssign-tjenesten.';
$string['privacy:metadata:safeassign_service:adminemail'] = 'Administratoren skal sende denne e-mail for at acceptere licensaftalen for tjenesten.';
$string['privacy:metadata:safeassign_service:filecontent'] = 'Vi skal sende filerne til SafeAssign for at generere originalitetsrapporten.';
$string['privacy:metadata:safeassign_service:filename'] = 'Filnavnet er påkrævet for SafeAssign-tjenesten.';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'Filens UUID tillader at relatere til Moodle-filer i SafeAssigns server.';
$string['privacy:metadata:safeassign_service:fullname'] = 'Brugernavnet sendes til SafeAssign for at hente godkendelsestokenet.';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'Denne besvarelses UUID er påkrævet for at hente originalitetsrapporten.';
$string['privacy:metadata:safeassign_service:userid'] = 'Bruger-ID sendes fra Moodle for at tillade dig at bruge SafeAssign-tjenester.';
$string['privacy:metadata:plagiarism_safeassign_files'] = 'Oplysninger om originaliteten af filen, som er uploadet af brugeren';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'ID for den studerende, der indsendte denne besvarelse.';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'Filens unikke ID i SafeAssign-tjenesten.';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'URL til originalitetsrapporten.';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = 'Lighedspointresultat for den afleverede fil.';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'Tidspunktet, hvor filen blev indsendt.';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'Besvarelsens unikke ID i SafeAssign-tjenesten';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = 'ID for den indsendte fil.';
$string['privacy:metadata:plagiarism_safeassign_course'] = 'Oplysninger om Moodle-kurser, der har SafeAssign aktiveret.';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'Kursets unikke ID i SafeAssign-tjenesten';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'Kurset, der har en aktivitet med SafeAssign aktiveret.';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'ID for brugeren, der er underviser på dette kursus.';
$string['privacy:metadata:plagiarism_safeassign_subm'] = 'Information om studerendes besvarelser.';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'Opgave-ID for denne besvarelse.';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'Det gennemsnitlige lighedsresultat for alle indsendte filer.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = 'Markering, der angiver, om besvarelsen har en fil vedhæftet.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = 'Markering, der angiver, om besvarelsen har en onlinetekst vedhæftet.';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = 'Den højeste lighedsscore for en indsendt fil.';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'Besvarelses-ID for en aktivitet med SafeAssign aktiveret.';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'Markering der angiver, om filen blev sendt til SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = 'Tidspunktet, hvor besvarelsen blev oprettet.';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'Besvarelsens unikke ID i SafeAssign-tjenesten.';
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'Oplysninger om underviserne på platformen.';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = 'ID for én bruger, der er underviser på ét kursus.';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'ID for det kursus, som brugeren underviser på.';
