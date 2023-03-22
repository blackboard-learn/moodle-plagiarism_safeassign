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

$string['pluginname'] = 'SafeAssign-plagiointilisäosa';
$string['getscores'] = 'Hanki palautusten pisteytykset';
$string['getscoreslog'] = 'SafeAssignin pisteytystehtävän loki';
$string['getscoreslogfailed'] = 'SafeAssignin pisteytystehtävän epäonnistuminen';
$string['getscoreslog_desc'] = 'SafeAssignin pisteytystehtävä suoritus onnistui.';
$string['servicedown'] = 'SafeAssign-palvelu ei ole käytettävissä.';
$string['studentdisclosuredefault'] = 'Kaikki ladatut tiedostot lähetetään Turnitin.comin plagioinnintunnistuspalveluun';
$string['studentdisclosure'] = 'Oppilaitoksen julkaisulausunto';
$string['studentdisclosure_help'] = 'Tämä teksti näytetään kaikille opiskelijoille tiedoston lataussivulla. Jos tämä
kenttä jätetään tyhjäksi, lokalisoitu oletusmerkkijono (studentdisclosuredefault) näytetään sen sijaan.';
$string['safeassignexplain'] = 'Lisätietoja tästä lisäosasta on täällä:';
$string['safeassign'] = 'SafeAssign-plagiointilisäosa';
$string['safeassign:enable'] = 'Salli opettajan ottaa Safe Assign käyttöön / poistaa käytöstä aktiviteetin sisällä';
$string['safeassign:report'] = 'Salli SafeAssignin alkuperäraportin tarkastelu';
$string['usesafeassign'] = 'Ota käyttöön SafeAssign';
$string['savedconfigsuccess'] = 'Plagiointiasetukset tallennettu';
$string['safeassign_additionalroles'] = 'Lisätiedostot';
$string['safeassign_additionalroles_help'] = 'Käyttäjät, joilla on nämä roolit järjestelmätasolla, lisätään kuhunkin
SafeAssign-kurssiin ohjaajina.';
$string['safeassign_api'] = 'SafeAssign-integraation URL-osoite';
$string['safeassign_api_help'] = 'Tämä on SafeAssignin ohjelmointirajapinnan osoite.';
$string['instructor_role_credentials'] = 'Ohjaajaroolin tunnukset';
$string['safeassign_instructor_username'] = 'Jaettu avain';
$string['safeassign_instructor_username_help'] = 'Ohjaajien jaetun avaimen tarjoaa SafeAssign.';
$string['safeassign_instructor_password'] = 'Jaettu salaisuus';
$string['safeassign_instructor_password_help'] = 'Ohjaajien jaetun salaisuuden tarjoaa SafeAssign.';
$string['student_role_credentials'] = 'Opiskelijaroolin tunnukset';
$string['safeassign_student_username'] = 'Jaettu avain';
$string['safeassign_student_username_help'] = 'Opiskelijoiden jaetun avaimen tarjoaa SafeAssign.';
$string['safeassign_student_password'] = 'Jaettu salaisuus';
$string['safeassign_student_password_help'] = 'Opiskelijoiden jaetun salaisuuden tarjoaa SafeAssign.';
$string['safeassign_license_acceptor_givenname'] = 'Lisenssin hyväksyjän etunimi';
$string['safeassign_license_acceptor_surname'] = 'Lisenssin hyväksyjän sukunimi';
$string['safeassign_license_acceptor_email'] = 'Lisenssin hyväksyjän sähköpostiosoite';
$string['safeassign_license_header'] = 'SafeAssign&trade;-lisenssiehdot';
$string['license_already_accepted'] = 'Ylläpitäjä on jo hyväksynyt nykyiset lisenssiehdot.';
$string['acceptlicense'] = 'Hyväksy SafeAssign-lisenssi';
$string['acceptlicenselog'] = 'SafeAssign-lisenssin tehtäväloki';
$string['safeassign_license_warning'] = 'SafeAssign&trade;-lisenssitietojen tarkistuksessa oli ongelma.
Napsauta Testaa yhteys -painiketta. Jos testi onnistuu, yritä uudelleen myöhemmin.';
$string['safeassign_enableplugin'] = 'Ota käyttöön SafeAssign {$a}:lle';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&amp;nbsp Oletusarvo: 0</div> <br>';
$string['safeassign_showid'] = 'Näytä opiskelijatunnus';
$string['safeassign_alloworganizations'] = 'Salli SafeAssignments-toiminnot organisaatioissa';
$string['safeassign_referencedbactivity'] = '<a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a> -aktiviteetti';
$string['safeassing_response_header'] = '<br>SafeAssign-palvelimen vastaus:<br>';
$string['safeassign_instructor_credentials'] = 'Ohjaajaroolin tunnukset:';
$string['safeassign_student_credentials'] = 'Opiskelijaroolin tunnukset:';
$string['safeassign_credentials_verified'] = 'Yhteys vahvistettu.';
$string['safeassign_credentials_fail'] = 'Yhteyttä ei ole vahvistettu. Tarkista avain, salaisuus ja URL-osoite.';
$string['credentials'] = 'Tunnukset ja palvelun URL-osoite';
$string['shareinfo'] = 'Jaa tiedot SafeAssignin kanssa';
$string['disclaimer'] = '<br>Kun työ palautetaan SafeAssignin <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a> -tietokantaan, muiden oppilaitosten töitä<br>
voidaan verrata opiskelijasi työhön hänen työnsä alkuperän suojaamiseksi.';
$string['settings'] = 'SafeAssign-asetukset';
$string['timezone_help'] = 'Open LMS -ympäristön määritetty aikavyöhyke.';
$string['timezone'] = 'Aikavyöhyke';
$string['safeassign_status'] = 'SafeAssign-tila';
$string['status:pending'] = 'Odottaa';
$string['safeassign_score'] = 'SafeAssign-pisteet';
$string['safeassign_reporturl'] = 'SQL-raportti';
$string['button_disabled'] = 'Testaa yhteys tallentamalla lomake';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'Virhe json-tiedoston·{$a} noutamisessa kansiosta plagiarism/safeassign/tests/fixtures SafeAssign-verkkopalveluiden kutsun simuloinnissa behat-testejä suoritettaessa.';
$string['safeassign_curlcache'] = 'Välimuistin aikakatkaisu';
$string['safeassign_curlcache_help'] = 'Verkkopalvelun välimuistin aikakatkaisu.';
$string['rest_error_nocurl'] = 'cURL-moduulin on oltava asennettu ja käytössä!';
$string['rest_error_nourl'] = 'URL-osoite on määritettävä!';
$string['rest_error_nomethod'] = 'Pyyntömenetelmä on määritettävä!';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'Testaa yhteys';
$string['connectionfailed'] = 'Yhteys ei onnistunut';
$string['connectionverified'] = 'Yhteys vahvistettu';
$string['cachedef_request'] = 'SafeAssign-pyynnön välimuisti';
$string['error_behat_instancefail'] = 'Tämä esiintymä on määritetty epäonnistumaan behat-testeissä.';
$string['assignment_check_submissions'] = 'Tarkista palautukset SafeAssignilla';
$string['assignment_check_submissions_help'] = 'SafeAssign-alkuperäisyysraportteja ei ole saatavilla opettajille, jos nimetön arviointi
on valittu, mutta opiskelijat voivat katsella omia SafeAssign-alkuperäisyysraporttejaan, jos Salli opiskelijoiden tarkastella alkuperäisyysraporttia -asetus on valittu.
<br><br>SafeAssign palauttaa yksittäisen alkuperäisyysraportin, kun käyttäjät palauttavat useita tiedostoja. Voit valita tarkistettavan tiedoston raportista.
<br><br>Vaikka SafeAssign tukee virallisesti vain englantia, sen käyttöä voi kokeilla muillakin kielillä.
SafeAssignissa ei ole teknisiä rajoituksia, jotka estäisivät sen käytön muilla kielillä.
Katso lisätietoja <a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">Blackboardin ohjeesta</a>.';
$string['students_originality_report'] = 'Salli opiskelijoiden tarkastella alkuperäisyysraporttia';
$string['submissions_global_reference'] = 'Jätä palautukset pois <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a> -tietokannasta';
$string['submissions_global_reference_help'] = 'Palautukset käsitellään SafeAssignilla, mutta niitä ei rekisteröidä tietokantoihin. Tämä estää tiedostojen merkinnän plagioiduiksi, kun opettajat sallivat uudelleen palauttamisen tietyssä tehtävässä.';
$string['plagiarism_tools'] = 'Plagiointityökalut';
$string['files_accepted'] = 'SafeAssign hyväksyy vain seuraavat tiedostomuodot: .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf ja .html. SafeAssign ei tarkista missään muissa muodoissa olevia tiedostoja, kuten .zip-tiedostoja ja muita pakattuja tiedostomuotoja.
<br><br>Lähettämällä tämän työn hyväksyt seuraavat:
(1) että lähetät työsi käytettäväksi ja tallennettavaksi SafeAssign&trade;-palvelujen osana Blackboardin <a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">käyttöehtojen</a> ja <a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">Blackboardin tietosuojakäytännön</a> mukaisesti
(2) että oppilaitoksesi voi käyttää työtäsi oppilaitoksen käytäntöjen mukaisesti
(3) että SafeAssignin käytöstä ei aiheudu vaatimuksia Open LMS:lle tai sen kumppaneille.';
$string['agreement'] = 'Hyväksyn työni lähettämisen <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a> -tietokantaan.';
$string['error_api_generic'] = 'Pyyntösi käsittelyssä tapahtui virhe';
$string['error_api_unauthorized'] = 'Pyyntösi käsittelyssä tapahtui todennusvirhe';
$string['error_api_forbidden'] = 'Pyyntösi käsittelyssä tapahtui valtuutusvirhe';
$string['error_api_not_found'] = 'Pyydettyä resurssia ei löydetty';
$string['sync_assignments'] = 'Lähettää saatavana olevan tiedon SafeAssign-palvelimelle.';
$string['api_call_log_event'] = 'Ohjelmointirajapinnan kutsujen SafeAssign-loki.';
$string['course_error_sync'] = 'Tapahtui virhe kurssin {$a} synkronoinnissa SafeAssigniin:<br>';
$string['assign_error_sync'] = 'Tapahtui virhe tehtävän {$a} synkronoinnissa SafeAssigniin:<br>';
$string['submission_error_sync'] = 'Tapahtui virhe palautuksen {$a} synkronoinnissa SafeAssigniin:<br>';
$string['submission_success_sync'] = 'Palautuksien synkronointi onnistui';
$string['assign_success_sync'] = 'Tehtävien synkronointi onnistui';
$string['course_success_sync'] = 'Kurssien synkronointi onnistui';
$string['license_header'] = 'SafeAssign&trade;-lisenssisopimus';
$string['license_title'] = 'SafeAssignin lisenssisopimus';
$string['not_configured'] = 'SafeAssign&trade; on määrittämättä. Pyydä järjestelmänvalvojaa lähettämään tukipyyntö
<a href="https://support.openlms.net/" target="_blank" rel="noopener">Open LMS -tukeen</a>.';
$string['agree_continue'] = 'Tallenna lomake';
$string['safeassign_file_not_supported'] = 'Ei tueta.';
$string['safeassign_file_not_supported_help'] = 'SafeAssign ei tue tiedostomuotoa tai tiedostokoko ylittää enimmäiskapasiteetin.';
$string['safeassign_submission_not_supported'] = 'SafeAssign ei tarkista tätä palautusta.';
$string['safeassign_submission_not_supported_help'] = 'Kurssien ohjaajien luomia palautuksia ei lähetetä SafeAssigniin.';
$string['safeassign_file_in_review'] = 'SafeAssign-alkuperäisyysraportti käynnissä...';
$string['safeassign_file_similarity_score'] = 'SafeAssign-pisteet: {$a}%<br>';
$string['safeassign_link_originality_report'] = 'Alkuperäisyysraportin katselu';
$string['safeassign_file_limit_exceeded'] = 'Palautus ylittää yhdistetyn kokorajan 10 Mt. SafeAssign ei käsittele sitä';
$string['originality_report'] = 'SafeAssign-alkuperäisyysraportti';
$string['originality_report_unavailable'] = 'Pyydetty alkuperäisyysraportti ei ole saatavana. Tarkista myöhemmin tai ota yhteys järjestelmän ylläpitäjään.';
$string['originality_report_error'] = 'Virhe SafeAssign-alkuperäisyysraportissa. Ota yhteys järjestelmän ylläpitäjään.';
$string['safeassign_overall_score'] = '<b>SafeAssign-kokonaispisteet:·{$a}%</b>';
$string['messageprovider:safeassign_graded'] = 'SafeAssign lähettää ilmoitukset ohjaajille, kun palautuksen plagiointipisteytys on valmis';
$string['safeassign_loading_settings'] = 'Ladataan asetuksia, odota';
$string['safeassign:get_messages'] = 'Salli ilmoitusten vastaanottaminen SafeAssignilta';
$string['safeassign_notification_message'] = '"Plagiointipisteytykset on käsitelty kohteelle {$a->counter}·{$a->plural}·in·{$a->assignmentname}"';
$string['safeassign_notification_grading_link'] = 'Arviointisivu';
$string['safeassign_notification_message_hdr'] = 'SafeAssign-plagiointipisteytykset on tehty';
$string['safeassign_notification_subm_singular'] = 'palautus';
$string['safeassign_notification_subm_plural'] = 'Palautukset';
$string['messageprovider:safeassign_notification'] = 'SafeAssign lähettää ilmoitukset sivuston ylläpitäjille, kun uudet lisenssiehdot ovat saatavana';
$string['safeassign:get_notifications'] = 'Salli SafeAssign-ilmoitukset';
$string['license_agreement_notification_subject'] = 'Uudet SafeAssign-lisenssiehdot saatavana';
$string['license_agreement_notification_message'] = 'Voit hyväksyä uudet lisenssiehdot täällä: {$a}';
$string['settings_page'] = 'SafeAssign-asetukset -sivu';
$string['send_notifications'] = 'Lähetä uusien SafeAssign-lisenssiehtojen ilmoitukset.';
$string['privacy:metadata:core_files'] = 'Palautuksiin liitetyt tiedostot tai luotu verkkotekstien palautuksista.';
$string['privacy:metadata:core_plagiarism'] = 'Tätä lisäosaa kutsuu Moodlen plagiointialijärjestelmä.';
$string['privacy:metadata:safeassign_service'] = 'Jotta alkuperäisyysraportti saadaan, jotain käyttäjätietoja on lähetettävä SafeAssign-palveluun.';
$string['privacy:metadata:safeassign_service:adminemail'] = 'Ylläpitäjän tulis lähettää sähköpostiosoitteensa voidakseen hyväksyä palvelulisenssin.';
$string['privacy:metadata:safeassign_service:filecontent'] = 'Tiedostot on lähetettävä SafeAssigniin alkuperäisyysraportin luomista varten.';
$string['privacy:metadata:safeassign_service:filename'] = 'SafeAssign-palvelu edellyttää tiedostonimeä.';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'Tiedoston uuid sallii Moodle-tiedostojen liittämisen SafeAssign-palvelimella.';
$string['privacy:metadata:safeassign_service:fullname'] = 'Käyttäjän nimi lähetetään SafeAssigniin varmennusavainta varten.';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'Tämän palautuksen uuid vaaditaan alkuperäisyysraportin noutamista varten.';
$string['privacy:metadata:safeassign_service:userid'] = 'Moodlesta lähetetty userid-tunnus sallii SafeAssign-palvelun käyttämisen.';
$string['privacy:metadata:plagiarism_safeassign_files'] = 'Tietoja käyttäjän lataamien tiedostojen alkuperäisyydestä';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'Palautuksen tehneen opiskelijan tunnus.';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'Tiedoston yksilöivä tunniste SafeAssign-palvelussa.';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'Alkuperäisyysraportin URL-osoite.';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = 'Palautetun tiedoston samankaltaisuuspisteet.';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'Tiedoston palautusaika.';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'Palautuksen yksilöity tunniste SafeAssign-palvelussa';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = 'Tämä on käynnistetyn säännön tunnus.';
$string['privacy:metadata:plagiarism_safeassign_course'] = 'Tietoja Moodle-kursseista, joissa SafeAssign on käytössä.';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'Kurssin yksilöivä tunniste SafeAssign-palvelussa.';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'Kurssi, jossa on aktiviteetti, jossa SafeAssign on käytössä.';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'Käyttäjän, joka on kurssin opettaja, tunnus.';
$string['privacy:metadata:plagiarism_safeassign_subm'] = 'Tietoja opiskelijoiden palautuksista.';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'Tämän palautuksen tehtävätunnus.';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'Kaikkien tiedostojen samankaltaisuuspisteiden keskiarvo.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = 'Lippu ilmoittaa, onko palautuksessa tiedosto.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = 'Lippu ilmoittaa, onko palautuksessa verkkotekstiä.';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = 'Yhden tiedoston korkein samankaltaisuuspisteytys.';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'Aktiviteetin, jossa SafeAssign on käytössä, palautustunnus.';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'Lippu ilmoittaa, onko tiedosto lähetetty SafeAssigniin.';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = 'Version luontiaika';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'Palautuksen yksilöity tunniste SafeAssign-palvelussa.';
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'Tietoja alustan opettajista.';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = 'Kurssin opettajan käyttäjätunnus.';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'Sen kurssin tunnus, jossa käyttäjä on opettajana.';
