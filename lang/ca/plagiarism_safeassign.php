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

$string['pluginname'] = 'Connector de plagi SafeAssign';
$string['getscores'] = 'Obté les puntuacions per les trameses';
$string['getscoreslog'] = 'Registre de la tasca de puntuació de SafeAssign';
$string['getscoreslogfailed'] = 'Error en la tasca de puntuació de SafeAssign';
$string['getscoreslog_desc'] = 'La tasca de puntuació de SafeAssign s\'ha executat correctament.';
$string['servicedown'] = 'El servei SafeAssign no està disponible.';
$string['studentdisclosuredefault'] = 'Tots els fitxers que es carreguin es trametran a un servei de detecció de plagi';
$string['studentdisclosure'] = 'Declaració d\'autorització de la institució';
$string['studentdisclosure_help'] = 'Aquest text es mostrarà a tots els estudiants a la pàgina de càrrega de fitxers. Si aquest
camp està en blanc, es farà servir la cadena traduïda predeterminada (studentdisclosuredefault).';
$string['safeassignexplain'] = 'Per més informació sobre aquest connector, consulteu:';
$string['safeassign'] = 'Connector de plagi SafeAssign';
$string['safeassign:enable'] = 'Permet que el professor pugui activar/desactivar SafeAssign dins les activitats';
$string['safeassign:report'] = 'Permet que es mostri l\'informe de SafeAssign';
$string['usesafeassign'] = 'Activa SafeAssign';
$string['savedconfigsuccess'] = 'S\'ha desat la configuració de plagi';
$string['safeassign_additionalroles'] = 'Rols addicionals';
$string['safeassign_additionalroles_help'] = 'Els usuaris amb aquests rols a nivell de sistema s\'afegiran a cada curs de SafeAssign
com instructors.';
$string['safeassign_api'] = 'URL d\'integració de SafeAssign';
$string['safeassign_api_help'] = 'Aquesta és l\'adreça de l\'API de SafeAssign.';
$string['instructor_role_credentials'] = 'Credencials del rol d\'instructor';
$string['safeassign_instructor_username'] = 'Clau compartida';
$string['safeassign_instructor_username_help'] = 'Clau compartida d\'instructor proporcionada per SafeAssign.';
$string['safeassign_instructor_password'] = 'Contrasenya secreta compartida';
$string['safeassign_instructor_password_help'] = 'Secret compartit d\'instructor proporcionat per SafeAssign.';
$string['student_role_credentials'] = 'Credencials del rol d\'estudiant';
$string['safeassign_student_username'] = 'Clau compartida';
$string['safeassign_student_username_help'] = 'Clau compartida d\'estudiant proporcionada per SafeAssign.';
$string['safeassign_student_password'] = 'Contrasenya secreta compartida';
$string['safeassign_student_password_help'] = 'Secret compartit d\'estudiant proporcionat per SafeAssign.';
$string['safeassign_license_acceptor_givenname'] = 'Nom de l\'acceptador de la llicència';
$string['safeassign_license_acceptor_surname'] = 'Cognoms de l\'acceptador de la llicència';
$string['safeassign_license_acceptor_email'] = 'Adreça electrònica de l\'acceptador de la llicència';
$string['safeassign_license_header'] = 'Termes i condicions de la llicència de SafeAssigntrade&trade;';
$string['license_already_accepted'] = 'El vostre administrador ja ha acceptat els termes de la llicència actual.';
$string['acceptlicense'] = 'Accepta la llicència de SafeAssign';
$string['acceptlicenselog'] = 'Registre de la tasca de llicència de SafeAssign';
$string['safeassign_license_warning'] = 'Hi ha un problema en la validació de les dades de la llicència de SafeAssign&trade;,
feu clic en el botó &quot;Prova la connexió&quot;. Si la prova surt bé, torneu-ho a intentar més tard.';
$string['safeassign_enableplugin'] = 'Activa SafeAssign per a {$a}';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&amp;nbsp Valor predefinit: 0</div> <br>';
$string['safeassign_showid'] = 'Mostra l\'ID de l\'estudiant';
$string['safeassign_alloworganizations'] = 'Permet SafeAssignments a les organitzacions';
$string['safeassign_referencedbactivity'] = 'Activitat de <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a>';
$string['safeassing_response_header'] = '<br>Resposta del servidor de SafeAssign:<br>';
$string['safeassign_instructor_credentials'] = 'Credencials del rol d\'instructor:';
$string['safeassign_student_credentials'] = 'Credencials del rol d\'estudiant:';
$string['safeassign_credentials_verified'] = 'Connexió verificada.';
$string['safeassign_credentials_fail'] = 'Connexió no verificada. Comproveu la clau, el secret i l\'URL.';
$string['credentials'] = 'URL de servei i credencials';
$string['shareinfo'] = 'Comparteix la informació amb SafeAssign';
$string['disclaimer'] = '<br>La tramesa a la <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">base de dades de referència global</a> de SafeAssign permet revisar documents d\'altres institucions<br>
i comparar-los amb els treballs dels vostres estudiants per protegir l’origen del seu treball.';
$string['settings'] = 'Configuració de SafeAssign';
$string['timezone_help'] = 'La zona horària que s\'ha configurat en el vostre entorn Open LMS.';
$string['timezone'] = 'Zona horària';
$string['safeassign_status'] = 'Estat de SafeAssign';
$string['status:pending'] = 'Pendents';
$string['safeassign_score'] = 'Puntuació de SafeAssign';
$string['safeassign_reporturl'] = 'URL de l\'informe';
$string['button_disabled'] = 'Desa el formulari per provar la connexió';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'Error d\'obtenció del fitxer json &quot;{$a}&quot; de la carpeta plagiarism/safeassign/tests/fixtures per simular una petició als serveis web de SafeAssign quan s\'executen les proves de behat.';
$string['safeassign_curlcache'] = 'Temps d\'espera de la memòria cau';
$string['safeassign_curlcache_help'] = 'Temps d\'espera de la memòria cau del servei web.';
$string['rest_error_nocurl'] = 'Hi ha d\'aver el mòdul cURL i ha d\'estar activat.';
$string['rest_error_nourl'] = 'Heu d\'especificar l\'URL.';
$string['rest_error_nomethod'] = 'Heu d\'especificar el mètode de sol·licitud.';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'Prova la connexió';
$string['connectionfailed'] = 'Error de connexió';
$string['connectionverified'] = 'Connexió verificada';
$string['cachedef_request'] = 'Memòria cau de la sol·licitud de SafeAssign';
$string['error_behat_instancefail'] = 'Aquesta és una instància configurada per fallar a les proves behat.';
$string['assignment_check_submissions'] = 'Comproveu les trameses amb SafeAssign';
$string['assignment_check_submissions_help'] = 'Els informes d’originalitat de SafeAssign no estan disponibles per als professors si s’estableix la qualificació anònima,
però els estudiants poden veure els seus propis informes d’originalitat de SafeAssign si es selecciona &quot;Permet que els estudiants puguin veure l\'informe d\'originalitat&quot;.
<br><br>SafeAssign retorna un únic informe d\'originalitat quan els usuaris envien diversos arxius. Podeu triar quin fitxer revisar des d’aquest informe.
<br><br>Tot i que SafeAssign oficialment només funciona en anglès, es pot intentar fer servir SafeAssign amb altres llengües.
SafeAssign no té limitacions tècniques que impedeixen utilitzar-lo amb altres idiomes.
Vegeu <a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">ajuda de Blackboard</a> per obtenir més informació.';
$string['students_originality_report'] = 'Permet que els estudiants puguin veure l\'informe d\'originalitat';
$string['submissions_global_reference'] = 'Exclou les presentacions de la <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">base de dades de referència global</a>';
$string['submissions_global_reference_help'] = 'SafeAssign encara processarà les trameses, però no es registraran a les bases de dades. D\'aquesta manera els fitxers no es marcaran com a plagis quan els professors permetin tornar a trametre una tasca específica.';
$string['plagiarism_tools'] = 'Eines de prevenció del plagi';
$string['files_accepted'] = 'SafeAssign només accepta fitxers en format .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf i .html. Els fitxers de qualsevol altre format, inclosos .zip i altres formats de fitxer comprimit, no es comprovaran a través de SafeAssign.
<br><br>En fer la tramesa d’aquest document, accepteu:
(1) que feu la tramesa del vostre article per ser utilitzat i emmagatzemat com a part dels serveis de SafeAssign&trade; d\'acord amb els <a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">termes i servei</a> i la <a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">política de privadesa de Blackboard</a>;
(2) que la vostra institució pugui utilitzar el vostre article d\'acord amb les seves polítiques; i
(3) que no podreu trametre un recurs pel vostre ús de SafeAssign front a Open LMS i les seves filials.';
$string['agreement'] = 'Accepto trametre els meus treballs a la <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">base de dades de referència global</a>.';
$string['error_api_generic'] = 'S\'ha produït un error en processar la vostra sol·licitud';
$string['error_api_unauthorized'] = 'S\'ha produït un error d\'autenticació en processar la vostra sol·licitud';
$string['error_api_forbidden'] = 'S\'ha produït un error d\'autorització en processar la vostra sol·licitud';
$string['error_api_not_found'] = 'No s\'ha trobat el recurs sol·licitat';
$string['sync_assignments'] = 'Envia la informació disponible al servidor de SafeAssign.';
$string['api_call_log_event'] = 'Registre de SafeAssign per a les peticions d\'API.';
$string['course_error_sync'] = 'S\'ha produït un error en intentar sincronitzar el curs amb l\'identificador: {$a} a SafeAssign:<br>';
$string['assign_error_sync'] = 'S\'ha produït un error en intentar sincronitzar la tasca amb l\'identificador: {$a} a SafeAssign:<br>';
$string['submission_error_sync'] = 'S\'ha produït un error en intentar sincronitzar la tramesa amb l\'identificador: {$a} a SafeAssign:<br>';
$string['submission_success_sync'] = 'Les trameses s\'han sincronitzat correctament';
$string['assign_success_sync'] = 'Les tasques s\'han sincronitzat correctament';
$string['course_success_sync'] = 'Els cursos s\'han sincronitzat correctament';
$string['license_header'] = 'Acord de llicència de SafeAssign&trade;';
$string['license_title'] = 'Acord de llicència de SafeAssign';
$string['not_configured'] = 'SafeAssign&trade; no està configurat. Demaneu-li al vostre administrador del sistema que enviï un ticket
a <a href="https://support.openlms.net/" target="_blank" rel="noopener">assistència tècnica d\'Open LMS</a> per obtenir ajuda.';
$string['agree_continue'] = 'Formulari per a desar';
$string['safeassign_file_not_supported'] = 'No és compatible.';
$string['safeassign_file_not_supported_help'] = 'L\'extensió del fitxer no és compatible amb SafeAssign o el fitxer és massa gran.';
$string['safeassign_submission_not_supported'] = 'SafeAssign no revisarà aquesta tramesa.';
$string['safeassign_submission_not_supported_help'] = 'Les trameses que han creat els instructors del curs no s\'envien a SafeAssign.';
$string['safeassign_file_in_review'] = 'Informe d\'originalitat de SafeAssign en curs...';
$string['safeassign_file_similarity_score'] = 'Puntuació de SafeAssign: {$a}%<br>';
$string['safeassign_link_originality_report'] = 'Mostra l\'informe d\'originalitat';
$string['safeassign_file_limit_exceeded'] = 'Aquesta tramesa supera la mida límit de 10 MB i SafeAssign no la processarà';
$string['originality_report'] = 'Informe d\'originalitat de SafeAssign';
$string['originality_report_unavailable'] = 'L\'informe d\'originalitat sol·licitat no està disponible. Torneu-ho a comprovar més tard o contacteu amb el vostre administrador del sistema.';
$string['originality_report_error'] = 'S\'ha produït un error amb l\'informe d\'originalitat de SafeAssign. Contacteu amb el vostre administrador del sistema.';
$string['safeassign_overall_score'] = '<b>Puntuació total de SafeAssign: {$a}%</b>';
$string['messageprovider:safeassign_graded'] = 'Quan una tramesa s\'avalua per plagi, SafeAssign envia notificacions als instructors';
$string['safeassign_loading_settings'] = 'S\'està carregant la configuració, espereu';
$string['safeassign:get_messages'] = 'Permet la recepció de notificacions de SafeAssign';
$string['safeassign_notification_message'] = 'S\'han processat les puntuacions de plagi per a {$a->counter} {$a->plural} en {$a->assignmentname}';
$string['safeassign_notification_grading_link'] = 'Pàgina de qualificació';
$string['safeassign_notification_message_hdr'] = 'S\'han processat les puntuacions de plagi de SafeAssign';
$string['safeassign_notification_subm_singular'] = 'tramesa';
$string['safeassign_notification_subm_plural'] = 'trameses';
$string['messageprovider:safeassign_notification'] = 'SafeAssign envia notificacions als administradors del lloc quan hi ha nous termes i condicions de llicència';
$string['safeassign:get_notifications'] = 'Permet les notificacions de SafeAssign';
$string['license_agreement_notification_subject'] = 'Nous termes i condicions de llicència de SafeAssign disponibles';
$string['license_agreement_notification_message'] = 'Podeu acceptar els nous termes i condicions de llicència aquí: {$a}';
$string['settings_page'] = 'Pàgina de configuració de SafeAssign';
$string['send_notifications'] = 'Envia notificacions de nous termes i condicions de llicència de SafeAssign.';
$string['privacy:metadata:core_files'] = 'Fitxers adjunts de les trameses o creats amb trameses de text en línia.';
$string['privacy:metadata:core_plagiarism'] = 'El subsistema de plagi de Moodle crida aquest connector.';
$string['privacy:metadata:safeassign_service'] = 'Per obtenir l\'informe d\'originalitat, s\'han d\'enviar al servei SafeAssign algunes dades de l\'usuari.';
$string['privacy:metadata:safeassign_service:adminemail'] = 'L\'administrador ha d\'enviar la seva adreça electrònica per acceptar la llicència del servei.';
$string['privacy:metadata:safeassign_service:filecontent'] = 'Hem d\'enviar els fitxers a SafeAssign per generar l\'informe d\'originalitat.';
$string['privacy:metadata:safeassign_service:filename'] = 'El servei SafeAssign necessita saber el nom del fitxer.';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'L\'uuid del fitxer permet relacionar els fitxers de Moodle al servidor de SafeAssign.';
$string['privacy:metadata:safeassign_service:fullname'] = 'El nom de l\'usuari s\'envia a SafeAssign per poder obtenir el testimoni d\'autenticació.';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'Aquest uuid de la tramesa és necessari per recuperar l\'informe d\'originalitat.';
$string['privacy:metadata:safeassign_service:userid'] = 'L\'ID de l\'usuari s\'envia des de Moodle perquè pugueu utilitzar els serveis de SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_files'] = 'Informació sobre l\'originalitat dels fitxers que ha carregat l\'usuari';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'L\'ID de l\'estudiant que ha fet aquesta tramesa.';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'Identificador únic del fitxer en el servei SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'URL de l\'informe d\'originalitat.';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = 'Puntuació de semblança del fitxer tramés.';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'Hora en què es va trametre el fitxer.';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'Identificador únic de la tramesa en el servei SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = 'L\'ID del fitxer que s\'ha tramès.';
$string['privacy:metadata:plagiarism_safeassign_course'] = 'Informació sobre els cursos Moodle que tenen integrat SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'Identificador únic del curs en el servei SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'El curs que té una activitat amb SafeAssign activat.';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'L\'ID de l\'usuari que és professor en el curs.';
$string['privacy:metadata:plagiarism_safeassign_subm'] = 'Informació sobre les trameses dels estudiants.';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'L\'ID de la tasca d\'aquesta tramesa.';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'La puntuació de semblança mitjana de tots els fitxers tramesos.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = 'Indicador per determinar si la tramesa conté fitxers.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = 'Indicador per determinar si la tramesa conté text en línia.';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = 'La puntuació de semblança més alta d\'un fitxer tramés.';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'L\'ID de la tramesa d\'una activitat que té habilitat SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'Indicador per determinar si el fitxer es va enviar a SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = 'La data/hora en què es va crear la tramesa.';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'Identificador únic de la tramesa en el servei SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'Informació sobre els professors en la plataforma.';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = 'L\'ID de l\'usuari que és professor en un curs.';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'L\'ID del curs on és professor l\'usuari.';
