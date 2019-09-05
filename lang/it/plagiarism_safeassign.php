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

$string['pluginname'] = 'Plugin per controllo di plagio SafeAssign';
$string['getscores'] = 'Ottieni i punteggi per le consegne';
$string['getscoreslog'] = 'Registro dei punteggi SafeAssign';
$string['getscoreslogfailed'] = 'Errore nei punteggi SafeAssign';
$string['getscoreslog_desc'] = 'Punteggi SafeAssign calcolati correttamente.';
$string['servicedown'] = 'Il servizio SafeAssign non è disponibile.';
$string['studentdisclosuredefault'] = 'Tutti i file caricati verranno consegnati a un servizio di identificazione di plagio';
$string['studentdisclosure'] = 'Dichiarazione di rilascio dell\'istituto';
$string['studentdisclosure_help'] = 'Il testo verrà visualizzato da tutti gli studenti sulla pagina di caricamento dei file. Se questo
campo rimane vuoto, al suo posto verrà utilizzata la stringa predefinita localizzata (studentdisclosuredefault).';
$string['safeassignexplain'] = 'Per ulteriori informazioni su questo plugin, vedere: ';
$string['safeassign'] = 'Plugin SafeAssign per l\'identificazione del plagio';
$string['safeassign:enable'] = 'Consenti al docente di attivare/disattivare SafeAssign da un\'attività';
$string['safeassign:report'] = 'Consenti di visualizzare il report di originalità da SafeAssign';
$string['usesafeassign'] = 'Attiva SafeAssign';
$string['savedconfigsuccess'] = 'Impostazioni plagio salvate';
$string['safeassign_additionalroles'] = 'Ruoli aggiuntivi';
$string['safeassign_additionalroles_help'] = 'Gli utenti con questi ruoli a livello di sistema saranno aggiunti a ciascun corso
SafeAssign come docenti.';
$string['safeassign_api'] = 'URL di integrazione SafeAssign';
$string['safeassign_api_help'] = 'Questo indirizzo corrisponde all\'API SafeAssign.';
$string['instructor_role_credentials'] = 'Credenziali per il ruolo di docente';
$string['safeassign_instructor_username'] = 'Chiave condivisa';
$string['safeassign_instructor_username_help'] = 'La chiave condivisa del docente fornita da SafeAssign.';
$string['safeassign_instructor_password'] = 'Segreto condivisio';
$string['safeassign_instructor_password_help'] = 'Il segreto condiviso del docente fornito da SafeAssign.';
$string['student_role_credentials'] = 'Credenziali del ruolo di studente';
$string['safeassign_student_username'] = 'Chiave condivisa';
$string['safeassign_student_username_help'] = 'La chiave condivisa dello studente fornita da SafeAssign.';
$string['safeassign_student_password'] = 'Shared secret';
$string['safeassign_student_password_help'] = 'Il segreto condiviso dello studente fornito da SafeAssign.';
$string['safeassign_license_acceptor_givenname'] = 'Nome di chi accetta la licenza';
$string['safeassign_license_acceptor_surname'] = 'Cognome di chi accetta la licenza';
$string['safeassign_license_acceptor_email'] = 'E-mail di chi accetta la licenza';
$string['safeassign_license_header'] = 'Termini e condizioni della licenza SafeAssign&trade;';
$string['license_already_accepted'] = 'I termini della licenza in vigore sono già stati accettati dall\'amministratore.';
$string['acceptlicense'] = 'Accetta la licenza SafeAssign';
$string['acceptlicenselog'] = 'Registro attività licenza SafeAssig';
$string['safeassign_license_warning'] = 'Si è verificato un problema durante la convalida dei dati della licenza SafeAssign&trade;. Fare
clic sul pulsante \'Test connessione\'. Se il test viene eseguito correttamente, provare di nuovo.';
$string['safeassign_enableplugin'] = 'Attiva SafeAssign per {$a}';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&nbsp Valore predefinito: 0</div> <br>';
$string['safeassign_showid'] = 'Visualizza ID studente';
$string['safeassign_alloworganizations'] = 'Consenti SafeAssignments in Organizzazioni';
$string['safeassign_referencedbactivity'] = '<a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">Global Reference Database</a> Attività';
$string['safeassing_response_header'] = '<br>Risposta del server SafeAssign: <br>';
$string['safeassign_instructor_credentials'] = 'Credenziali ruolo docente: ';
$string['safeassign_student_credentials'] = 'Credenziali ruolo studente: ';
$string['safeassign_credentials_verified'] = 'Connessione verificata.';
$string['safeassign_credentials_fail'] = 'Connessione non verificata. Controllare la chiave, il segreto e l\'URL.';
$string['credentials'] = 'Credenziali e URL servizio';
$string['shareinfo'] = 'Condividi informazioni con SafeAssign';
$string['disclaimer'] = '<br>La consegna al SafeAssign <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">Global Reference Database</a> consente di controllare i compiti degli altri istituti <br>
                        confrontandoli con quelli dei tuoi studenti per proteggere l\'origine del loro lavoro.';
$string['settings'] = 'Impostazioni SafeAssign';
$string['timezone_help'] = 'Il fuso orario impostato nel tuo ambiente Blackboard Open LMS';
$string['timezone'] = 'Fuso orario';
$string['safeassign_status'] = 'Stato SafeAssign';
$string['status:pending'] = 'In attesa';
$string['safeassign_score'] = 'Punteggio SafeAssign';
$string['safeassign_reporturl'] = 'URL report';
$string['button_disabled'] = 'Salva modulo per testare la connessione';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'Errore durante il recupero del file json "{$a}"dalla cartella plagiarism/safeassign/tests/fixtures per simulare una chiamata dei webservice SafeAssign durante l\'esecuzione dei test behat';
$string['safeassign_curlcache'] = 'Timeout cache';
$string['safeassign_curlcache_help'] = 'Timeout cache di web service';
$string['rest_error_nocurl'] = 'Il modulo cURL deve essere presente e abilitato!';
$string['rest_error_nourl'] = 'È necessario specificare l\'URL!';
$string['rest_error_nomethod'] = 'È necessario specificare il metodo di richiesta!';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'Test connessione';
$string['connectionfailed'] = 'Connessione non riuscita';
$string['connectionverified'] = 'Connessione verificata';
$string['cachedef_request'] = 'Cache richieste SafeAssign';
$string['error_behat_instancefail'] = 'Questa istanza è configurata per fallire con i test behat.';
$string['assignment_check_submissions'] = 'Controlla consegne con SafeAssign';
$string['assignment_check_submissions_help'] = 'I report sull\'originalità SafeAssign non saranno disponibili per i docenti se è stata impostata la
valutazione anonima, tuttavia  gli studenti saranno in grado di visualizzare i report sull\'originalità SafeAssign se l\'opzione "Consenti agli studenti di visualizzare i report sull\'originalità" è selezionata.
<br><br>Sebbene ufficialmente SafeAssign supporti solo la lingua inglese, i clienti possono provare a utilizzare SafeAssign in lingue diverse.
SafeAssign non presenta limiti tecnici che ne precludono l\'utilizzo in altre lingue.
Vedere <a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">assistenza Blackboard</a> per ulteriori informazioni.';
$string['students_originality_report'] = 'Consenti agli studenti di visualizzare il report sull\'originalità';
$string['submissions_global_reference'] = 'Escludi le consegne da parte del database dell\'istituto e del <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">Global Reference Database</a>';
$string['submissions_global_reference_help'] = 'Le consegne saranno sempre elaborate da SafeAssign ma non saranno registrate in un database. In questo modo i file non verranno contrassegnati come plagio quando i docenti consentiranno nuovamente le consegne per un compito specifico.';
$string['plagiarism_tools'] = 'Strumenti plagio';
$string['files_accepted'] = 'SafeAssign accetta solo file nei formati .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf e .html. I file di qualsiasi altro formato, compresi i file .zip e in altri formati compressi, non saranno verificati da SafeAssign.
<br><br>Consegnando questo compito accetti:
 (1) che il compito consegnato venga utilizzato e archiviato presso i servizi SafeAssign&trade; in accordo con i <a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">Termini di servizio Blackboard</a> e la <a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">Politica sulla privacy di Blackboard</a>;
 (2) che l\'istituto possa utilizzare il tuo compito in accordo con le politiche dell\'istituto; e
 (3) che il tuo utilizzo di SafeAssign non comporti un ricorso contro Blackboard Inc. e le sue affiliate.';
$string['agreement'] = 'Accetto di consegnare il mio compito/i miei compiti a <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign#global_reference" target="_blank">Global Reference Database</a>.';
$string['error_api_generic'] = 'Si è verificato un errore nell\'elaborazione della richiesta';
$string['error_api_unauthorized'] = 'Si è verificato un errore di autenticazione nell\'elaborazione della richiesta';
$string['error_api_forbidden'] = 'Si è verificato un errore di autorizzazione nell\'elaborazione  della richiesta';
$string['error_api_not_found'] = 'La risorsa richiesta non è stata trovata';
$string['sync_assignments'] = 'Invia le informazioni disponibili al server SafeAssign.';
$string['api_call_log_event'] = 'Registro di SafeAssign per le chiamate API.';
$string['course_error_sync'] = 'Si è verificato un errore durante il tentativo di sincronizzazione del corso con l\'ID: {$a} in SafeAssign: <br>';
$string['assign_error_sync'] = 'Si è verificato un errore durante il tentativo di sincronizzazione del compito con l\'ID: {$a} in SafeAssign: <br>';
$string['submission_error_sync'] = 'Si è verificato un errore durante il tentativo di sincronizzazione della consegna con l\'ID: {$a} in SafeAssign: <br>';
$string['submission_success_sync'] = 'Consegne sincronizzate correttamente';
$string['assign_success_sync'] = 'Compiti sincronizzati correttamente';
$string['course_success_sync'] = 'Corsi sincronizzati correttamente';
$string['license_header'] = 'Contratto di licenza SafeAssign&trade;';
$string['license_title'] = 'Contratto di licenza SafeAssign';
$string['not_configured'] = 'SafeAssign&trade; non è configurato. Invitare l\'amministratore di sistema a inviare un ticket a Behind the Blackboard per ricevere assistenza.';
$string['agree_continue'] = 'Salva modulo';
$string['safeassign_file_not_supported'] = 'Non supportato.';
$string['safeassign_file_not_supported_help'] = 'L\'estensione del file non è supportata da SafeAssign oppure le dimensioni del file superano la massima capacità.';
$string['safeassign_submission_not_supported'] = 'La consegna non sarà analizzata da SafeAssign.';
$string['safeassign_submission_not_supported_help'] = 'Le consegne create dai docenti del corso non vengono inviate a SafeAssign.';
$string['safeassign_file_in_review'] = 'Report sull\'originalità di SafeAssign in corso...';
$string['safeassign_file_similarity_score'] = 'Punteggio SafeAssign: {$a}%<br>';
$string['safeassign_link_originality_report'] = 'Visualizza report sull\'originalità';
$string['safeassign_file_limit_exceeded'] = 'Questa consegna supera il limite di dimensioni combinato di 10 MB e pertanto non verrà elaborato da SafeAssign';
$string['originality_report'] = 'Report sull\'originalità di SelfAssign';
$string['originality_report_unavailable'] = 'Il report sull\'originalità richiesto non è disponibile. Controllare più tardi o contattare l\'amministratore di sistema.';
$string['originality_report_error'] = 'Si è verificato un errore con il report sull\'originalità di SafeAssign. Contattare l\'amministratore di sistema.';
$string['safeassign_overall_score'] = '<b>Punteggio generale SafeAssign: {$a}%</b>';
$string['messageprovider:safeassign_graded'] = 'SafeAssign invia una notifica ai docenti quando una consegna viene contrassegnata come plagio';
$string['safeassign_loading_settings'] = 'Caricamento delle opzioni in corso, attendere';
$string['safeassign:get_messages'] = 'Consenti la ricezione di notifiche da SafeAssign';
$string['safeassign_notification_message'] = 'I punteggi di plagio sono stati elaborati per {$a->counter} {$a->plural} in {$a->assignmentname}';
$string['safeassign_notification_grading_link'] = 'Pagina della valutazione';
$string['safeassign_notification_message_hdr'] = 'Sono stati elaborati i punteggi di plagio di SafeAssign';
$string['safeassign_notification_subm_singular'] = 'consegna';
$string['safeassign_notification_subm_plural'] = 'consegne';
$string['messageprovider:safeassign_notification'] = 'SafeAssign invia notifiche agli amministratori del sito quando sono disponibili nuovi Termini e condizioni di licenza';
$string['safeassign:get_notifications'] = 'Consenti notifiche da SafeAssign';
$string['license_agreement_notification_subject'] = 'Nuovi Termini e condizioni di licenza SafeAssign disponibili';
$string['license_agreement_notification_message'] = 'È possibile accettare i nuovi Termini e condizioni di licenza qui: {$a}';
$string['settings_page'] = 'Pagina delle impostazioni SafeAssign';
$string['send_notifications'] = 'Invia notifiche per nuovi Termini e condizioni di licenza SafeAssign';
$string['privacy:metadata:core_files'] = 'File delle consegne allegati o creati da consegne di testo online';
$string['privacy:metadata:core_plagiarism'] = 'Questo plugin viene chiamato dal sottosistema di individuazione di plagio di Moodle.';
$string['privacy:metadata:safeassign_service'] = 'Per ottenere un report sull\'originalità, alcuni dati degli utenti devono essere inviati al servizio SafeAssign.';
$string['privacy:metadata:safeassign_service:adminemail'] = 'Per accettare la licenza di servizio, l\'amministratore deve inviare la sua e-mail.';
$string['privacy:metadata:safeassign_service:filecontent'] = 'Per generare il report sull\'originalità, dobbiamo inviare i file a SafeAssign.';
$string['privacy:metadata:safeassign_service:filename'] = 'Il nome del file è obbligatorio per utilizzare il servizio SafeAssign.';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'L\'uuid del file consente di relazionare i file Moodle nel server SafeAssign.';
$string['privacy:metadata:safeassign_service:fullname'] = 'Il nome utente viene inviato a SafeAssign per consentire di ottenere il token di autenticazione.';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'La consegna uuid è necessaria per recuperare il report sull\'originalità.';
$string['privacy:metadata:safeassign_service:userid'] = 'L\'id dell\'utente viene inviato da Moodle per consentire l\'utilizzo dei servizi SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_files'] = 'Le informazioni sull\'originalità dei file caricati dall\'utente';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'L\'ID dello studente che ha effettuato questa consegna.';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'Identificatore univoco del file nel servizio SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'URL per il report di originalità.';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = 'Punteggio di similarità del file inviato.';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'Ora di consegna del file.';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'Identificatore univoco della consegna nel servizio SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = 'L\'ID del file consegnato.';
$string['privacy:metadata:plagiarism_safeassign_course'] = 'Informazioni sui corsi Moodle nei quali è attivato SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'Identificatore univoco del corso nel servizio SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'Il corso che ha un\'attività in cui è attivato SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'L\'ID dell\'utente che è il docente di questo corso.';
$string['privacy:metadata:plagiarism_safeassign_subm'] = 'Informazioni sulle consegne degli studenti.';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'L\'ID del compito di questa consegna.';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'Il punteggio medio di similarità tra tutti i file consegnati.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = 'Contrassegna per determinare se la consegna ha un file.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = 'Contrassegna per determinare se la consegna ha del testo online.';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = 'Il punteggio di similarità più alto per un file consegnato.';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'L\'ID della consegna di un\'attività in cui è attivato SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'Contrassegna per determinare se il file è stato inviato in SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = 'L\'ora di creazione della consegna.';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'Identificatore univoco della consegna nel servizio SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'Informazioni sui docenti presenti sulla piattaforma.';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = 'L\'ID di un utente che è il docente di questo corso.';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'L\'ID del corso di cui l\'utente è il docente.';
