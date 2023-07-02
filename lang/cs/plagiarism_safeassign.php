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

$string['pluginname'] = 'Plugin SafeAssign Plagiarism';
$string['getscores'] = 'Získat skóre odevzdaných prací';
$string['getscoreslog'] = 'Protokol úloh skóre SafeAssign';
$string['getscoreslogfailed'] = 'Selhání úlohy skóre SafeAssign';
$string['getscoreslog_desc'] = 'Úloha skóre SafeAssign proběhla úspěšně.';
$string['servicedown'] = 'Služba SafeAssign není dostupná.';
$string['studentdisclosuredefault'] = 'Všechny nahrané soubory budou odeslány do služby detekce plagiátorství.';
$string['studentdisclosure'] = 'Prohlášení o uvolnění instituce';
$string['studentdisclosure_help'] = 'Tento text se bude všem studentům zobrazovat na stránce nahrávání souborů. Pokud pole
ponecháte prázdné, použije se místo toho výchozí lokalizovaný řetězec (studentdisclosuredefault).';
$string['safeassignexplain'] = 'Další informace o tomto pluginu naleznete na:';
$string['safeassign'] = 'Plugin SafeAssign Plagiarism';
$string['safeassign:enable'] = 'Umožnit učiteli povolit/zakázat službu SafeAssign v rámci aktivity';
$string['safeassign:report'] = 'Povolit zobrazení sestavy původnosti ze služby SafeAssign';
$string['usesafeassign'] = 'Povolit službu SafeAssign';
$string['savedconfigsuccess'] = 'Nastavení plagiátorství uložena';
$string['safeassign_additionalroles'] = 'Další role';
$string['safeassign_additionalroles_help'] = 'Uživatelé s těmito rolemi na systémové úrovni budou přidáni do každého kurzu se službou SafeAssign
jako instruktoři.';
$string['safeassign_api'] = 'Adresa URL integrace služby SafeAssign';
$string['safeassign_api_help'] = 'Toto je adresa rozhraní API služby SafeAssign.';
$string['instructor_role_credentials'] = 'Přihlašovací údaje role instruktora';
$string['safeassign_instructor_username'] = 'Sdílený klíč';
$string['safeassign_instructor_username_help'] = 'Sdílený klíč instruktora poskytnutý službou SafeAssign';
$string['safeassign_instructor_password'] = 'Sdílený tajný klíč';
$string['safeassign_instructor_password_help'] = 'Sdílený tajný klíč instruktora poskytnutý službou SafeAssign';
$string['student_role_credentials'] = 'Přihlašovací údaje role studenta';
$string['safeassign_student_username'] = 'Sdílený klíč';
$string['safeassign_student_username_help'] = 'Sdílený klíč studenta poskytnutý službou SafeAssign';
$string['safeassign_student_password'] = 'Sdílený tajný klíč';
$string['safeassign_student_password_help'] = 'Sdílený tajný klíč studenta poskytnutý službou SafeAssign';
$string['safeassign_license_acceptor_givenname'] = 'Jméno člověka akceptujícího licenci';
$string['safeassign_license_acceptor_surname'] = 'Příjmení člověka akceptujícího licenci';
$string['safeassign_license_acceptor_email'] = 'E-mail člověka akceptujícího licenci';
$string['safeassign_license_header'] = 'Smluvní podmínky licence služby SafeAssign&trade;';
$string['license_already_accepted'] = 'Aktuální podmínky licence již akceptoval správce.';
$string['acceptlicense'] = 'Akceptovat licenci služby SafeAssign';
$string['acceptlicenselog'] = 'Protokol úloh licence služby SafeAssign';
$string['safeassign_license_warning'] = 'Při ověřování dat licence služby SafeAssign&trade; došlo k problému,
klikněte na tlačítko Otestovat připojení. Pokud bude test úspěšný, zkuste to znovu později.';
$string['safeassign_enableplugin'] = 'Povolit službu SafeAssign pro: {$a}';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&amp;nbsp Výchozí hodnota: 0</div> <br>';
$string['safeassign_showid'] = 'Zobrazit ID studenta';
$string['safeassign_alloworganizations'] = 'Povolit SafeAssignments v organizacích';
$string['safeassign_referencedbactivity'] = 'Aktivita <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">globální referenční databáze</a>';
$string['safeassing_response_header'] = '<br>Odpověď serveru služby SafeAssign:<br>';
$string['safeassign_instructor_credentials'] = 'Přihlašovací údaje role instruktora:';
$string['safeassign_student_credentials'] = 'Přihlašovací údaje role studenta:';
$string['safeassign_credentials_verified'] = 'Připojení bylo ověřeno.';
$string['safeassign_credentials_fail'] = 'Připojení nebylo ověřeno. Zkontrolujte klíč, tajný klíč a adresu URL.';
$string['credentials'] = 'Přihlašovací údaje a adresa URL služby';
$string['shareinfo'] = 'Sdílet informace se službou SafeAssign';
$string['disclaimer'] = '<br>Odeslání do <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">globální referenční databáze</a> SafeAssign umožňuje porovnat práce z jiných institucí<br>
s pracemi vašich studentů a chránit tak původ jejich práce.';
$string['settings'] = 'Nastavení služby SafeAssign';
$string['timezone_help'] = 'Časové pásmo nastavené ve vašem prostředí Open LMS.';
$string['timezone'] = 'Časové pásmo';
$string['safeassign_status'] = 'Stav služby SafeAssign';
$string['status:pending'] = 'Čeká na vyřízení';
$string['safeassign_score'] = 'Skóre služby SafeAssign';
$string['safeassign_reporturl'] = 'Adresa URL sestavy';
$string['button_disabled'] = 'Uložit formulář k otestování připojení';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'Nepodařilo se načíst soubor json "{$a}" ze složky plagiarism/safeassign/tests/fixtures sloužící k simulaci volání webových služeb SafeAssign během testování.';
$string['safeassign_curlcache'] = 'Časový limit mezipaměti';
$string['safeassign_curlcache_help'] = 'Časový limit mezipaměti webové služby';
$string['rest_error_nocurl'] = 'Musí být k dispozici a povolen modul cURL.';
$string['rest_error_nourl'] = 'Je nutné zadat adresu URL.';
$string['rest_error_nomethod'] = 'Je nutné zadat metodu požadavku.';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'Otestovat připojení';
$string['connectionfailed'] = 'Připojení se nezdařilo';
$string['connectionverified'] = 'Připojení bylo ověřeno';
$string['cachedef_request'] = 'Mezipaměť požadavku služby SafeAssign';
$string['error_behat_instancefail'] = 'Toto je instance konfigurovaná k selhání během testování.';
$string['assignment_check_submissions'] = 'Zkontrolovat odevzdání pomocí služby SafeAssign';
$string['assignment_check_submissions_help'] = 'Pokud je nastavena anonymní klasifikace, nejsou sestavy původnosti
SafeAssign pro učitele dostupné, ale studenti si mohou zobrazit své vlastní sestavy původnosti SafeAssign, pokud je vybrána možnost „Povolit studentům zobrazit sestavu původnosti“.
<br><br>Pokud uživatelé odešlou více souborů, SafeAssign vrátí jednu sestavu původnosti. V této sestavě si můžete vybrat, který soubor chcete zkontrolovat.
<br><br>Ačkoli SafeAssign oficiálně podporuje pouze angličtinu, můžete zkusit používat SafeAssign i v jiných jazycích.
SafeAssign nemá žádná technická omezení, která by bránila jeho použití s jinými jazyky.
Další informace najdete v <a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">nápovědě Blackboard</a>.';
$string['students_originality_report'] = 'Povolit studentům zobrazit sestavu původnosti';
$string['submissions_global_reference'] = 'Vyloučit příspěvky z <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">globální referenční databáze</a>';
$string['submissions_global_reference_help'] = 'Odevzdání budou stále zpracovávána službou SafeAssign, nebudou však registrována v databázích. Tím se vyhnete označování souborů jako plagiátorských v případě, že učitelé povolí u konkrétního úkolu opětovná odevzdání.';
$string['plagiarism_tools'] = 'Nástroje plagiátorství';
$string['files_accepted'] = 'SafeAssign přijímá pouze soubory ve formátech .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf a .html. Soubory v jakémkoli jiném formátu, včetně souborů .zip a jiných komprimovaných formátů, se nebudou prostřednictvím služby SafeAssign kontrolovat.
<br><br>Odesláním této práce souhlasíte s tím, že:
(1) odevzdáváte svou práci k použití a uložení v rámci služeb SafeAssign&trade; v souladu s <a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">podmínkami služby</a> Blackboard a <a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">zásadami ochrany osobních údajů Blackboard</a>;
(2) vaše instituce může použít váš dokument v souladu se zásadami vaší instituce; a
(3) vaše používání SafeAssign bude bez možnosti odvolání proti Open LMS a jejím přidruženým společnostem.';
$string['agreement'] = 'Souhlasím s odesláním své práce do <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">globální referenční databáze</a>.';
$string['error_api_generic'] = 'Při zpracovávání vašeho požadavku došlo k chybě';
$string['error_api_unauthorized'] = 'Při zpracovávání vašeho požadavku došlo k chybě ověření';
$string['error_api_forbidden'] = 'Při zpracovávání vašeho požadavku došlo k chybě autorizace';
$string['error_api_not_found'] = 'Požadovaný zdroj nebyl nalezen';
$string['sync_assignments'] = 'Odesílá dostupné informace na server služby SafeAssign.';
$string['api_call_log_event'] = 'Protokol služby SafeAssign pro volání rozhraní API';
$string['course_error_sync'] = 'Došlo k chybě při pokusu o synchronizaci kurzu s ID: {$a} do služby SafeAssign:<br>';
$string['assign_error_sync'] = 'Došlo k chybě při pokusu o synchronizaci úkolu s ID: {$a} do služby SafeAssign:<br>';
$string['submission_error_sync'] = 'Došlo k chybě při pokusu o synchronizaci odevzdání s ID: {$a} do služby SafeAssign:<br>';
$string['submission_success_sync'] = 'Odevzdání byla úspěšně synchronizována';
$string['assign_success_sync'] = 'Úkoly byly úspěšně synchronizovány';
$string['course_success_sync'] = 'Kurzy byly úspěšně synchronizovány';
$string['license_header'] = 'Licenční smlouva služby SafeAssign&trade;';
$string['license_title'] = 'Licenční smlouva služby SafeAssign';
$string['not_configured'] = 'Služba SafeAssign&trade; není konfigurována. Pokud potřebujete pomoc, požádejte správce systému, aby odeslal ticket
<a href="https://support.openlms.net/" target="_blank" rel="noopener">podpoře systému Open LMS</a>.';
$string['agree_continue'] = 'Uložit formulář';
$string['safeassign_file_not_supported'] = 'Není podporováno.';
$string['safeassign_file_not_supported_help'] = 'Přípona souboru není službou SafeAssign podporována nebo velikost souboru překračuje maximální kapacitu.';
$string['safeassign_submission_not_supported'] = 'Toto odevzdání nebude službou SafeAssign zkontrolováno.';
$string['safeassign_submission_not_supported_help'] = 'Odevzdání vytvořená instruktory kurzu nejsou odesílána do služby SafeAssign.';
$string['safeassign_file_in_review'] = 'Probíhá sestava původnosti SafeAssign…';
$string['safeassign_file_similarity_score'] = 'Skóre služby SafeAssign: {$a} %<br>';
$string['safeassign_link_originality_report'] = 'Zobrazit sestavu původnosti';
$string['safeassign_file_limit_exceeded'] = 'Toto odevzdání překračuje limit kombinované velikosti 10 MB a nebude službou SafeAssign zpracováno.';
$string['originality_report'] = 'Sestava původnosti SafeAssign';
$string['originality_report_unavailable'] = 'Požadovaná sestava původnosti není k dispozici. Zkuste to znovu později nebo se obraťte na správce systému.';
$string['originality_report_error'] = 'U sestavy původnosti SafeAssign došlo k chybě. Obraťte se na správce systému.';
$string['safeassign_overall_score'] = '<b>Celkové skóre služby SafeAssign: {$a} %</b>';
$string['messageprovider:safeassign_graded'] = 'Služba SafeAssign odešle instruktorům upozornění, pokud bylo odevzdání klasifikováno jako plagiátorské.';
$string['safeassign_loading_settings'] = 'Načítání nastavení, čekejte prosím';
$string['safeassign:get_messages'] = 'Povolit příjem upozornění ze služby SafeAssign';
$string['safeassign_notification_message'] = 'Skóre plagiátorství byla zpracována pro: {$a->counter} {$a->plural} v: {$a->assignmentname}';
$string['safeassign_notification_grading_link'] = 'Stránka klasifikace';
$string['safeassign_notification_message_hdr'] = 'Skóre plagiátorství služby SafeAssign byla zpracována';
$string['safeassign_notification_subm_singular'] = 'odevzdání';
$string['safeassign_notification_subm_plural'] = 'odevzdání';
$string['messageprovider:safeassign_notification'] = 'Služba SafeAssign odesílá upozornění správcům webu, když jsou k dispozici nové licenční smluvní podmínky';
$string['safeassign:get_notifications'] = 'Povolit upozornění ze služby SafeAssign';
$string['license_agreement_notification_subject'] = 'Nové licenční smluvní podmínky služby SafeAssign k dispozici';
$string['license_agreement_notification_message'] = 'Nové licenční smluvní podmínky můžete přijmout zde: {$a}';
$string['settings_page'] = 'Stránka nastavení služby SafeAssign';
$string['send_notifications'] = 'Odesílat upozornění na nové licenční smluvní podmínky služby SafeAssign';
$string['privacy:metadata:core_files'] = 'Soubory připojené k odevzdáním nebo vytvořené z online textových odevzdání';
$string['privacy:metadata:core_plagiarism'] = 'Tento plugin je volán podsystémem plagiátorství Moodlu.';
$string['privacy:metadata:safeassign_service'] = 'Aby bylo možné získat sestavu původnosti, je nutné odeslat některá uživatelská data do služby SafeAssign.';
$string['privacy:metadata:safeassign_service:adminemail'] = 'Správce musí odeslat e-mail, aby akceptoval licenci služby.';
$string['privacy:metadata:safeassign_service:filecontent'] = 'K vygenerování sestavy původnosti je potřeba odeslat soubory do služby SafeAssign.';
$string['privacy:metadata:safeassign_service:filename'] = 'Služba SafeAssign vyžaduje název souboru.';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'Uuid souboru umožňuje přiřadit soubory Moodlu na serveru služby SafeAssign.';
$string['privacy:metadata:safeassign_service:fullname'] = 'Uživatelské jméno se do služby SafeAssign odesílá proto, aby bylo možné získat ověřovací token.';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'Toto uuid odevzdání je potřeba k načtení sestavy původnosti.';
$string['privacy:metadata:safeassign_service:userid'] = 'ID uživatele odeslané z Moodlu za účelem umožnění použití služeb SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_files'] = 'Informace o původnosti souborů nahraných uživatelem';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'ID uživatele, který vytvořil toto odevzdání';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'Jedinečný identifikátor souboru ve službě SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'Adresa URL sestavy původnosti';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = 'Skóre podobnosti pro odeslaný soubor';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'Čas odeslání souboru';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'Jedinečný identifikátor odevzdání ve službě SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = 'ID odeslaného souboru';
$string['privacy:metadata:plagiarism_safeassign_course'] = 'Informace o kurzech Moodlu s povolenou službou SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'Jedinečný identifikátor kurzu ve službě SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'Kurz, který má aktivitu s povolenou službou SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'ID uživatele, který je učitelem v tomto kurzu';
$string['privacy:metadata:plagiarism_safeassign_subm'] = 'Informace o odevzdáních studentů';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'ID úkolu tohoto odevzdání';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'Průměrné skóre podobnosti pro všechny odeslané soubory';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = 'Příznak určující, zda má odevzdání soubor';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = 'Příznak určující, zda má odevzdání online text';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = 'Nejvyšší skóre podobnosti pro jeden odeslaný soubor';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'ID odevzdání aktivity s povolenou službou SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'Příznak určující, zda byl soubor odeslán do služby SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = 'Čas vytvoření odevzdání';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'Jedinečný identifikátor odevzdání ve službě SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'Informace o učitelích na platformě';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = 'ID jednoho uživatele, který je učitelem v jednom kurzu';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'ID kurzu, ve kterém je uživatel učitelem';
