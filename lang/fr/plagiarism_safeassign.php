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

$string['pluginname'] = 'Plug-in de détection des plagiats SafeAssign';
$string['getscores'] = 'Obtenir des notes pour les travaux remis';
$string['getscoreslog'] = 'Journal des tâches liées aux notes SafeAssign';
$string['getscoreslogfailed'] = 'Échec de la tâche liée aux notes SafeAssign';
$string['getscoreslog_desc'] = 'La tâche liée aux notes SafeAssign s\'est exécutée correctement.';
$string['servicedown'] = 'Le service SafeAssign est indisponible.';
$string['studentdisclosuredefault'] = 'Tous les fichiers téléchargés seront envoyés à un service de détection des plagiats.';
$string['studentdisclosure'] = 'Annonce de publication de l\'établissement';
$string['studentdisclosure_help'] = 'Ce texte sera affiché pour tous les étudiants dans la page de téléchargement des fichiers. Si ce
champ est vide, la chaîne localisée par défaut (studentdisclosuredefault) sera utilisée.';
$string['safeassignexplain'] = 'Pour plus d\'informations sur ce plug-in, consultez :';
$string['safeassign'] = 'Plug-in de détection des plagiats SafeAssign';
$string['safeassign:enable'] = 'Autoriser le professeur à activer/désactiver SafeAssign dans une activité';
$string['safeassign:report'] = 'Autoriser l\'affichage du rapport de similitude depuis SafeAssign';
$string['usesafeassign'] = 'Activer SafeAssign';
$string['savedconfigsuccess'] = 'Paramètres de détection des plagiats enregistrés';
$string['safeassign_additionalroles'] = 'Rôles supplémentaires';
$string['safeassign_additionalroles_help'] = 'Les utilisateurs disposant de ces rôles au niveau système seront ajoutés à chaque cours SafeAssign
en tant qu\'enseignants.';
$string['safeassign_api'] = 'URL d\'intégration SafeAssign';
$string['safeassign_api_help'] = 'Il s\'agit de l\'adresse de l\'API SafeAssign.';
$string['instructor_role_credentials'] = 'Données d\'identification du rôle Enseignant';
$string['safeassign_instructor_username'] = 'Clé partagée';
$string['safeassign_instructor_username_help'] = 'Clé partagée de l\'enseignant générée par SafeAssign.';
$string['safeassign_instructor_password'] = 'Secret partagé';
$string['safeassign_instructor_password_help'] = 'Secret partagé de l\'enseignant généré par SafeAssign.';
$string['student_role_credentials'] = 'Données d\'identification du rôle Étudiant';
$string['safeassign_student_username'] = 'Clé partagée';
$string['safeassign_student_username_help'] = 'Clé partagée de l\'étudiant générée par SafeAssign.';
$string['safeassign_student_password'] = 'Secret partagé';
$string['safeassign_student_password_help'] = 'Secret partagé de l\'étudiant généré par SafeAssign.';
$string['safeassign_license_acceptor_givenname'] = 'Prénom de l\'accepteur de licence';
$string['safeassign_license_acceptor_surname'] = 'Nom de famille de l\'accepteur de licence';
$string['safeassign_license_acceptor_email'] = 'Adresse e-mail de l\'accepteur de licence';
$string['safeassign_license_header'] = 'Conditions générales du contrat de licence SafeAssign&trade;';
$string['license_already_accepted'] = 'Les conditions générales actuelles du contrat de licence ont déjà été acceptées par votre administrateur.';
$string['acceptlicense'] = 'Accepter la licence SafeAssign';
$string['acceptlicenselog'] = 'Journal des tâches liées à la licence SafeAssign';
$string['safeassign_license_warning'] = 'Un problème empêche la validation des données de licence SafeAssign&trade;.
Cliquez sur le bouton Tester la connexion. Si le test réussit, essayez à nouveau plus tard.';
$string['safeassign_enableplugin'] = 'Activer SafeAssign pour {$a}';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">Valeur par défaut : 0</div> <br>';
$string['safeassign_showid'] = 'Afficher l\'identifiant de l\'étudiant';
$string['safeassign_alloworganizations'] = 'Autoriser les détections SafeAssign dans les organisations';
$string['safeassign_referencedbactivity'] = 'Activité <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a>';
$string['safeassing_response_header'] = '<br>Réponse du serveur SafeAssign :<br>';
$string['safeassign_instructor_credentials'] = 'Données d\'identification du rôle Enseignant :';
$string['safeassign_student_credentials'] = 'Données d\'identification du rôle Étudiant :';
$string['safeassign_credentials_verified'] = 'Connexion vérifiée.';
$string['safeassign_credentials_fail'] = 'Connexion non vérifiée. Vérifiez la clé, le secret et l\'URL.';
$string['credentials'] = 'Données d\'identification et URL du service';
$string['shareinfo'] = 'Partager les informations avec SafeAssign';
$string['disclaimer'] = '<br>L\'envoi à SafeAssign <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a> permet de comparer des documents d\'autres institutions<br>
à ceux de vos étudiants afin de protéger l\'origine de leur travail.';
$string['settings'] = 'Paramètres de SafeAssign';
$string['timezone_help'] = 'Fuseau horaire défini dans votre environnement Open LMS.';
$string['timezone'] = 'Fuseau horaire';
$string['safeassign_status'] = 'Statut de SafeAssign';
$string['status:pending'] = 'En suspens';
$string['safeassign_score'] = 'Note de SafeAssign';
$string['safeassign_reporturl'] = 'URL du rapport';
$string['button_disabled'] = 'Enregistrer un formulaire pour tester la connexion';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'Erreur d\'obtention du fichier json « {$a} » depuis le dossier plagiarism/safeassign/tests/fixtures pour simuler un appel aux services Web SafeAssign lors de l\'exécution de tests Behat.';
$string['safeassign_curlcache'] = 'Délai d\'expiration du cache';
$string['safeassign_curlcache_help'] = 'Délai d\'expiration du cache du service Web.';
$string['rest_error_nocurl'] = 'Le module cURL doit être présent et activé.';
$string['rest_error_nourl'] = 'Vous devez indiquer une URL.';
$string['rest_error_nomethod'] = 'Vous devez indiquer une méthode de demande.';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'Tester la connexion';
$string['connectionfailed'] = 'Échec de la connexion';
$string['connectionverified'] = 'Connexion vérifiée';
$string['cachedef_request'] = 'Mise en cache de la demande SafeAssign';
$string['error_behat_instancefail'] = 'Il s\'agit d\'une instance configurée pour échouer aux tests Behat.';
$string['assignment_check_submissions'] = 'Vérifier les travaux remis avec SafeAssign';
$string['assignment_check_submissions_help'] = 'Les rapports de similitude SafeAssign ne sont pas disponibles pour les enseignants si la notation anonyme
est définie, mais les étudiants peuvent afficher leur propre rapport de similitude SafeAssign si l\'option « Autoriser les étudiants à afficher le rapport de similitudes » est sélectionnée.
<br><br>SafeAssign renvoie un rapport de similitude unique lorsque les utilisateurs envoient plusieurs fichiers. Vous pouvez choisir le fichier à examiner dans ce rapport.
<br><br>Bien que SafeAssign ne prenne officiellement en charge que l\'anglais, vous pouvez essayer d\'utiliser SafeAssign avec d\'autres langues.
SafeAssign n\'a pas de limitations techniques qui empêchent son utilisation avec d\'autres langues.
Contactez l\'<a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">assistance Blackboard</a> pour en savoir plus.';
$string['students_originality_report'] = 'Autoriser les étudiants à consulter le rapport de similitude';
$string['submissions_global_reference'] = 'Exclure les envois de <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a>';
$string['submissions_global_reference_help'] = 'Les travaux remis seront toujours traités par SafeAssign, mais ne seront pas enregistrés dans les bases de données. Ceci évite que des fichiers soient marqués comme plagiés lorsque des professeurs autorisent la nouvelle remise de travaux dans le cadre d\'un devoir spécifique.';
$string['plagiarism_tools'] = 'Outils de détection des plagiats';
$string['files_accepted'] = 'SafeAssign accepte les fichiers aux formats .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf et .html uniquement. Les fichiers de tout autre format, y compris .zip et d\'autres formats de fichiers compressés, ne seront pas vérifiés via SafeAssign.
<br><br>En envoyant ce document, vous acceptez :
(1) d\'envoyer votre document pour qu\'il soit utilisé et stocké dans le cadre des services SafeAssign&trade; conformément aux <a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">Conditions générales</a> et à la <a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">Politique de confidentialité</a> de Blackboard ;
(2) que votre établissement peut utiliser votre document conformément à ses politiques ; et
(3) que votre utilisation de SafeAssign se fera sans recours contre Open LMS et ses sociétés affiliées.';
$string['agreement'] = 'J\'accepte de soumettre mon ou mes documents à la base de données <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Global Reference Database</a>.';
$string['error_api_generic'] = 'Une erreur s\'est produite lors du traitement de votre demande.';
$string['error_api_unauthorized'] = 'Une erreur d\'authentification s\'est produite lors du traitement de votre demande.';
$string['error_api_forbidden'] = 'Une erreur d\'autorisation s\'est produite lors du traitement de votre demande.';
$string['error_api_not_found'] = 'La ressource demandée est introuvable.';
$string['sync_assignments'] = 'Envoie les informations disponibles au serveur SafeAssign.';
$string['api_call_log_event'] = 'Journal SafeAssign pour les appels d\'API.';
$string['course_error_sync'] = 'Une erreur s\'est produite lors de la tentative de synchronisation du cours avec l\'identifiant {$a} dans SafeAssign :<br>';
$string['assign_error_sync'] = 'Une erreur s\'est produite lors de la tentative de synchronisation du devoir avec l\'identifiant {$a} dans SafeAssign :<br>';
$string['submission_error_sync'] = 'Une erreur s\'est produite lors de la tentative de synchronisation du travail remis avec l\'identifiant {$a} dans SafeAssign :<br>';
$string['submission_success_sync'] = 'Synchronisation des travaux remis réussie';
$string['assign_success_sync'] = 'Synchronisation des devoirs réussie';
$string['course_success_sync'] = 'Synchronisation des cours réussie';
$string['license_header'] = 'Contrat de licence SafeAssign&trade;';
$string['license_title'] = 'Contrat de licence SafeAssign';
$string['not_configured'] = 'SafeAssign&trade; n\'est pas configuré. Demandez à votre administrateur système de créer un ticket
auprès de l\'<a href="https://support.openlms.net/" target="_blank" rel="noopener">assistance Open LMS</a> pour obtenir de l\'aide.';
$string['agree_continue'] = 'Enregistrer un formulaire';
$string['safeassign_file_not_supported'] = 'Non pris en charge.';
$string['safeassign_file_not_supported_help'] = 'L\'extension du fichier n\'est pas prise en charge par SafeAssign ou la taille du fichier dépasse la capacité maximale.';
$string['safeassign_submission_not_supported'] = 'Ce travail remis ne sera pas vérifié par SafeAssign.';
$string['safeassign_submission_not_supported_help'] = 'Les travaux remis créés par des enseignants ne sont pas envoyés à SafeAssign.';
$string['safeassign_file_in_review'] = 'Rapport de similitude SafeAssign en cours...';
$string['safeassign_file_similarity_score'] = 'Note SafeAssign : {$a}%<br>';
$string['safeassign_link_originality_report'] = 'Afficher le rapport de similitude';
$string['safeassign_file_limit_exceeded'] = 'Le travail remis dépasse la taille limite combinée de 10 Mo et ne sera pas traité par SafeAssign.';
$string['originality_report'] = 'Rapport de similitude SafeAssign';
$string['originality_report_unavailable'] = 'Le rapport de similitude demandé n\'est pas disponible. Vérifiez à nouveau plus tard ou contactez votre administrateur système.';
$string['originality_report_error'] = 'Une erreur s\'est produite avec le rapport de similitude SafeAssign. Contactez votre administrateur système.';
$string['safeassign_overall_score'] = '<b>Score global SafeAssign : {$a}%</b>';
$string['messageprovider:safeassign_graded'] = 'SafeAssign envoie des notifications aux enseignants lorsqu\'un travail remis est classé comme plagiat.';
$string['safeassign_loading_settings'] = 'Chargement des paramètres en cours. Veuillez patienter.';
$string['safeassign:get_messages'] = 'Autoriser la réception de notifications de SafeAssign';
$string['safeassign_notification_message'] = 'Les scores de plagiat ont été traités pour {$a->counter} {$a->plural} dans {$a->assignmentname}.';
$string['safeassign_notification_grading_link'] = 'Page de notation';
$string['safeassign_notification_message_hdr'] = 'Les scores de plagiat SafeAssign ont été traités.';
$string['safeassign_notification_subm_singular'] = 'travail remis';
$string['safeassign_notification_subm_plural'] = 'travaux remis';
$string['messageprovider:safeassign_notification'] = 'SafeAssign envoie des notifications aux administrateurs de sites dès que de nouvelles conditions générales du contrat de licence sont disponibles.';
$string['safeassign:get_notifications'] = 'Autoriser les notifications de SafeAssign';
$string['license_agreement_notification_subject'] = 'Nouvelles conditions générales du contrat de licence SafeAssign disponibles';
$string['license_agreement_notification_message'] = 'Vous pouvez accepter les nouvelles conditions générales du contrat de licence ici : {$a}';
$string['settings_page'] = 'Page des paramètres de SafeAssign';
$string['send_notifications'] = 'Envoyer des notifications relatives aux nouvelles conditions générales du contrat de licence SafeAssign.';
$string['privacy:metadata:core_files'] = 'Pièces jointes aux travaux remis ou créées à partir de travaux remis (texte) en ligne.';
$string['privacy:metadata:core_plagiarism'] = 'Ce plug-in est appelé par le sous-système de détection des plagiats de Moodle.';
$string['privacy:metadata:safeassign_service'] = 'Pour générer un rapport de similitude, des données utilisateur doivent être envoyées au service SafeAssign.';
$string['privacy:metadata:safeassign_service:adminemail'] = 'L\'administrateur doit envoyer un e-mail pour accepter la licence de service.';
$string['privacy:metadata:safeassign_service:filecontent'] = 'Nous devons envoyer les fichiers à SafeAssign pour générer le rapport de similitude.';
$string['privacy:metadata:safeassign_service:filename'] = 'Le nom de fichier est requis pour le service SafeAssign.';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'L\'UUID du fichier permet de faire le lien entre les fichiers Moodle sur le serveur SafeAssign.';
$string['privacy:metadata:safeassign_service:fullname'] = 'Le nom d\'utilisateur est envoyé à SafeAssign pour permettre l\'obtention du jeton d\'authentification.';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'L\'UUID du travail remis est requis pour récupérer le rapport de similitude.';
$string['privacy:metadata:safeassign_service:userid'] = 'Identifiant utilisateur envoyé depuis Moodle pour vous permettre d\'utiliser les services SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_files'] = 'Informations sur l\'originalité des fichiers téléchargés par l\'utilisateur';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'Identifiant de l\'étudiant qui a remis le travail.';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'Identifiant unique du fichier dans le service SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'URL du rapport de similitude.';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = 'Score de similitude du fichier soumis.';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'Heure à laquelle le fichier a été soumis.';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'Identifiant unique du travail remis dans le service SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = 'Identifiant du fichier ayant été soumis.';
$string['privacy:metadata:plagiarism_safeassign_course'] = 'Informations sur les cours Moodle pour lesquels SafeAssign est activé.';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'Identifiant unique du cours dans le service SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'Cours associé à une activité pour laquelle SafeAssign est activé.';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'Identifiant de l\'utilisateur étant professeur dans ce cours.';
$string['privacy:metadata:plagiarism_safeassign_subm'] = 'Informations sur les travaux remis par les étudiants.';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'Identifiant du devoir associé au travail remis.';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'Score moyen de similitude pour tous les fichiers soumis.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = 'Indicateur indiquant si le travail remis comporte un fichier.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = 'Indicateur indiquant si le travail remis comporte un texte en ligne.';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = 'Score de similitude le plus élevé pour un fichier soumis.';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'ID du travail remis d\'une activité pour laquelle SafeAssign est activé.';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'Indicateur indiquant si le fichier a été envoyé à SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = 'Heure à laquelle le travail remis a été créé.';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'Identifiant unique du travail remis dans le service SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'Informations sur les enseignants sur la plateforme.';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = 'Identifiant d\'un utilisateur qui est enseignant dans un cours.';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'Identifiant du cours dans lequel l\'utilisateur est enseignant.';
