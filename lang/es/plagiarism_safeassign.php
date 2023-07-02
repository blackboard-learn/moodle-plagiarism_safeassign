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

$string['pluginname'] = 'Complemento SafeAssign para la detección de plagio';
$string['getscores'] = 'Obtener puntuaciones para las entregas';
$string['getscoreslog'] = 'Registro de tarea de puntuación de SafeAssign';
$string['getscoreslogfailed'] = 'Error de tarea de puntuación de SafeAssign';
$string['getscoreslog_desc'] = 'La tarea de puntuación de SafeAssign se ejecutó correctamente.';
$string['servicedown'] = 'El servicio de SafeAssign no está disponible.';
$string['studentdisclosuredefault'] = 'Todos los archivos cargados se enviarán al servicio de detección de plagio';
$string['studentdisclosure'] = 'Comunicado de la institución';
$string['studentdisclosure_help'] = 'Todos los estudiantes podrán ver este texto en la página de carga de archivos. Si este
campo se deja vacío, se utilizará la cadena localizada predeterminada (studentdisclosuredefault) en su lugar.';
$string['safeassignexplain'] = 'Para obtener más información acerca de este complemento, consulte:';
$string['safeassign'] = 'complemento SafeAssign para la detección de plagio';
$string['safeassign:enable'] = 'Permitir que el profesor habilite o deshabilite SafeAssign dentro de una actividad';
$string['safeassign:report'] = 'Permitir la visualización del Originality Report de SafeAssign';
$string['usesafeassign'] = 'Habilitar SafeAssign';
$string['savedconfigsuccess'] = 'Ajustes de plagio guardados';
$string['safeassign_additionalroles'] = 'Roles adicionales';
$string['safeassign_additionalroles_help'] = 'Los usuarios con estos roles a nivel de sistema se agregarán a cada curso de SafeAssign
como profesores.';
$string['safeassign_api'] = 'URL para la integración de SafeAssign';
$string['safeassign_api_help'] = 'Esta es la dirección de la API de SafeAssign.';
$string['instructor_role_credentials'] = 'Credenciales para el rol de profesor';
$string['safeassign_instructor_username'] = 'Clave compartida';
$string['safeassign_instructor_username_help'] = 'Clave compartida del profesor que brinda SafeAssign.';
$string['safeassign_instructor_password'] = 'Secreto compartido';
$string['safeassign_instructor_password_help'] = 'Secreto compartido del profesor que brinda SafeAssign.';
$string['student_role_credentials'] = 'Credenciales para el rol de estudiante';
$string['safeassign_student_username'] = 'Clave compartida';
$string['safeassign_student_username_help'] = 'Clave compartida del estudiante que brinda SafeAssign.';
$string['safeassign_student_password'] = 'Secreto compartido';
$string['safeassign_student_password_help'] = 'Secreto compartido del estudiante que brinda SafeAssign.';
$string['safeassign_license_acceptor_givenname'] = 'Nombre de quien acepta las licencias';
$string['safeassign_license_acceptor_surname'] = 'Apellido de quien acepta las licencias';
$string['safeassign_license_acceptor_email'] = 'Correo electrónico de quien acepta las licencias';
$string['safeassign_license_header'] = 'Términos y condiciones de licencia de SafeAssign&trade;';
$string['license_already_accepted'] = 'El administrador ya aceptó los términos de licencia actuales.';
$string['acceptlicense'] = 'Aceptar licencia de SafeAssign';
$string['acceptlicenselog'] = 'Registro de tarea de licencia de SafeAssign';
$string['safeassign_license_warning'] = 'Se ha producido un problema al validar los datos de licencia de SafeAssign&trade;.
Haga clic en el botón "Probar conexión". Si la prueba se realiza con éxito, vuelva a intentarlo más tarde.';
$string['safeassign_enableplugin'] = 'Habilitar SafeAssign para {$a}';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&amp;nbsp Valor predeterminado: 0</div> <br>';
$string['safeassign_showid'] = 'Mostrar ID del estudiante';
$string['safeassign_alloworganizations'] = 'Permitir SafeAssignments en las organizaciones';
$string['safeassign_referencedbactivity'] = 'Actividad en la <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">base de datos de referencia global</a>';
$string['safeassing_response_header'] = '<br>Respuesta del servidor de SafeAssign:<br>';
$string['safeassign_instructor_credentials'] = 'Credenciales para el rol de profesor:';
$string['safeassign_student_credentials'] = 'Credenciales para el rol de estudiante:';
$string['safeassign_credentials_verified'] = 'Conexión verificada.';
$string['safeassign_credentials_fail'] = 'Conexión no verificada. Verifique la clave, el secreto y la URL.';
$string['credentials'] = 'URL de las credenciales y el servicio';
$string['shareinfo'] = 'Compartir información con SafeAssign';
$string['disclaimer'] = '<br>Con el envío de entregas a la <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">base de datos de referencia global</a> de SafeAssign, se pueden comparar trabajos de otras instituciones<br>
con los trabajos de sus estudiantes para proteger su origen.';
$string['settings'] = 'Ajustes de SafeAssign';
$string['timezone_help'] = 'Zona horaria establecida en su entorno de Open LMS.';
$string['timezone'] = 'Zona horaria';
$string['safeassign_status'] = 'Estado de SafeAssign';
$string['status:pending'] = 'Pendiente';
$string['safeassign_score'] = 'Puntuación de SafeAssign';
$string['safeassign_reporturl'] = 'URL del informe';
$string['button_disabled'] = 'Guardar formulario para probar la conexión';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'Error al obtener el archivo JSON "{$a}" de la carpeta plagiarism/safeassign/tests/fixtures para simular una llamada a los servicios web de SafeAssign cuando se ejecutan las pruebas Behat.';
$string['safeassign_curlcache'] = 'Tiempo de espera de la memoria caché';
$string['safeassign_curlcache_help'] = 'Tiempo de espera de la memoria caché del servicio web.';
$string['rest_error_nocurl'] = 'El módulo cURL debe estar presente y activado.';
$string['rest_error_nourl'] = 'Debe espeficificar una URL.';
$string['rest_error_nomethod'] = 'Debe especificar un método de solicitud.';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'Probar conexión';
$string['connectionfailed'] = 'Error de conexión';
$string['connectionverified'] = 'Conexión verificada';
$string['cachedef_request'] = 'Caché de solicitud de SafeAssign';
$string['error_behat_instancefail'] = 'Esta es una instancia configurada para fallar en las pruebas Behat.';
$string['assignment_check_submissions'] = 'Verificar entregas con SafeAssign';
$string['assignment_check_submissions_help'] = 'Si se establece el método de calificación anónima, los informes de originalidad de SafeAssign no estarán disponibles para los profesores.
Sin embargo, los estudiantes sí podrán ver sus propios informes de originalidad de SafeAssign si se selecciona la opción "Permitir que los estudiantes vean los informes de originalidad".
<br><br>SafeAssign devuelve un único informe de originalidad cuando los usuarios envían varios archivos. Puede elegir el archivo que desea revisar en este informe.
<br><br>Si bien SafeAssign solo admite el idioma inglés de forma oficial, los clientes pueden intentar utilizar SafeAssign con otros idiomas.
SafeAssign no tiene limitaciones técnicas que impidan su uso con otros idiomas.
Consulte la <a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">ayuda de Blackboard</a> para obtener más información.';
$string['students_originality_report'] = 'Permitir que los estudiantes vean los Originality Reports';
$string['submissions_global_reference'] = 'Excluir las entregas de la <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">base de datos de referencia global</a>';
$string['submissions_global_reference_help'] = 'SafeAssign aún procesará las entregas, pero estas no se registrarán en las bases de datos. Esto impide que los archivos se marquen como plagio cuando los profesores permiten que se vuelvan a realizar entregas de una actividad específica.';
$string['plagiarism_tools'] = 'Herramientas de plagio';
$string['files_accepted'] = 'SafeAssign solo admite archivos con formato .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf y .html. Por otro lado, SafeAssign no verificará archivos con otros formatos, incluidos .zip y otros formatos de archivos comprimidos.
<br><br>Al enviar este trabajo, acepta lo siguiente:
(1) que este se utilice y almacene como parte de los servicios de SafeAssign&trade;, de acuerdo con los <a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">Términos y servicios</a> y con la <a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">Política de privacidad de Blackboard</a>;
(2) que su institución utilice su trabajo de acuerdo con sus políticas;
(3) que su uso de SafeAssign no dará lugar a recursos contra Blackboard Inc. y sus filiales.';
$string['agreement'] = 'Acepto enviar mi(s) trabajo(s) a la <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">base de datos de referencia global</a>.';
$string['error_api_generic'] = 'Se produjo un error al procesar su solicitud';
$string['error_api_unauthorized'] = 'Se produjo un error de autenticación al procesar su solicitud';
$string['error_api_forbidden'] = 'Se produjo un error de autorización al procesar su solicitud';
$string['error_api_not_found'] = 'No se encontró el recurso solicitado';
$string['sync_assignments'] = 'Se envía la información disponible al servidor de SafeAssign.';
$string['api_call_log_event'] = 'Registro de SafeAssign para las llamadas a la API.';
$string['course_error_sync'] = 'Se ha producido un error al intentar sincronizar el Curso con el ID {$a} en SafeAssign:<br>';
$string['assign_error_sync'] = 'Se ha producido un error al intentar sincronizar la Tarea con el ID {$a} en SafeAssign:<br>';
$string['submission_error_sync'] = 'Se ha producido un error al intentar sincronizar la Entrega con el ID {$a} en SafeAssign:<br>';
$string['submission_success_sync'] = 'Las entregas se sincronizaron correctamente';
$string['assign_success_sync'] = 'Las actividades se sincronizaron correctamente';
$string['course_success_sync'] = 'Los cursos se sincronizaron correctamente';
$string['license_header'] = 'Contrato de licencia de SafeAssign&trade;';
$string['license_title'] = 'Contrato de Licencia de SafeAssign';
$string['not_configured'] = 'SafeAssign&trade; no está configurado. Solicite al administrador del sistema que envíe un ticket al
<a href="https://support.openlms.net/" target="_blank" rel="noopener">servicio de asistencia de Open LMS</a> para obtener ayuda.';
$string['agree_continue'] = 'Guardar formulario';
$string['safeassign_file_not_supported'] = 'No es compatible.';
$string['safeassign_file_not_supported_help'] = 'La extensión del archivo no es compatible con SafeAssign o el tamaño del archivo excede la capacidad máxima.';
$string['safeassign_submission_not_supported'] = 'SafeAssign no verificará esta entrega.';
$string['safeassign_submission_not_supported_help'] = 'Las entregas que crean los profesores del curso no se envían a SafeAssign.';
$string['safeassign_file_in_review'] = 'Originality Report de SafeAssign en curso...';
$string['safeassign_file_similarity_score'] = 'Puntuación de SafeAssign: {$a} %<br>';
$string['safeassign_link_originality_report'] = 'Ver Originality Report';
$string['safeassign_file_limit_exceeded'] = 'Esta entrega excede el límite de tamaño combinado de 10 MB y SafeAssign no la procesará';
$string['originality_report'] = 'Originality Report de SafeAssign';
$string['originality_report_unavailable'] = 'El Originality Report solicitado no se encuentra disponible. Vuelva a verificarlo más tarde o comuníquese con el Administrador del sistema.';
$string['originality_report_error'] = 'Se produjo un error con el Originality Report de SafeAssign. Comuníquese con el Administrador del sistema.';
$string['safeassign_overall_score'] = '<b>Puntuación general de SafeAssign: {$a} %</b>';
$string['messageprovider:safeassign_graded'] = 'SafeAssign envía notificaciones a los profesores cuando se califica una entrega por plagio';
$string['safeassign_loading_settings'] = 'Cargando ajustes. Espere.';
$string['safeassign:get_messages'] = 'Permitir recibir notificaciones de SafeAssign';
$string['safeassign_notification_message'] = 'Las puntuaciones por plagio se procesaron por {$a->counter} {$a->plural} en {$a->assignmentname}';
$string['safeassign_notification_grading_link'] = 'Página Calificación';
$string['safeassign_notification_message_hdr'] = 'Se procesaron las puntuaciones por plagio de SafeAssign';
$string['safeassign_notification_subm_singular'] = 'entrega';
$string['safeassign_notification_subm_plural'] = 'entregas';
$string['messageprovider:safeassign_notification'] = 'SafeAssign envía notificaciones a los administradores del sitio cuando hay disponibles nuevos Términos y condiciones de licencia disponible';
$string['safeassign:get_notifications'] = 'Permitir notificaciones de SafeAssign';
$string['license_agreement_notification_subject'] = 'Nuevos Términos y condiciones de licencia de SafeAssign disponibles';
$string['license_agreement_notification_message'] = 'Puede aceptar los nuevos términos y condiciones de licencia aquí: {$a}';
$string['settings_page'] = 'Página Ajustes de SafeAssign';
$string['send_notifications'] = 'Enviar notificaciones de los nuevos Términos y condiciones de licencia de SafeAssign.';
$string['privacy:metadata:core_files'] = 'Archivos adjuntos a las entregas o creados a partir de entregas de texto en línea.';
$string['privacy:metadata:core_plagiarism'] = 'Este complemento obtiene su nombre del subsistema de plagio de Moodle.';
$string['privacy:metadata:safeassign_service'] = 'Para obtener un Originality Report, algunos datos del usuario deberán enviarse al servicio de SafeAssign.';
$string['privacy:metadata:safeassign_service:adminemail'] = 'El administrador debe proporcionar su correo electrónico para aceptar la licencia de servicio.';
$string['privacy:metadata:safeassign_service:filecontent'] = 'Para generar el Originality Report, debemos enviar los archivos a SafeAssign.';
$string['privacy:metadata:safeassign_service:filename'] = 'Se necesita el nombre del archivo para el servicio de SafeAssign.';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'El UUID de archivo permite identificar los archivos de Moodle en el servidor de SafeAssign.';
$string['privacy:metadata:safeassign_service:fullname'] = 'El nombre de usuario se envía a SafeAssign para poder obtener el token de autenticación.';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'Se necesita el UUID de esta entrega para recuperar el Originality Report.';
$string['privacy:metadata:safeassign_service:userid'] = 'El ID de usuario se envía desde Moodle para que pueda utilizar los servicios de SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_files'] = 'Información acerca de la originalidad de los archivos que carga el usuario';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'ID del estudiante que realizó esta entrega.';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'Identificador único de archivos en el servicio de SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'URL del Originality Report.';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = 'Puntuación de similitud para el archivo enviado.';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'Hora en la que se envió el archivo.';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'Identificador único de entrega en el servicio de SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = 'ID del archivo enviado.';
$string['privacy:metadata:plagiarism_safeassign_course'] = 'Información acerca de los cursos de Moodle con SafeAssign habilitado.';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'Identificador único de curso en el servicio de SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'El curso que tiene una actividad con SafeAssign habilitado.';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'ID del usuario correspondiente al profesor de este curso.';
$string['privacy:metadata:plagiarism_safeassign_subm'] = 'Información acerca de las entregas de los estudiantes.';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'ID de la actividad para esta entrega.';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'Puntuación de similitud promedio para todos los archivos enviados.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = 'Marca para determinar si la entrega contiene un archivo.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = 'Marca para determinar si la entrega contiene un texto en línea.';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = 'La puntuación de similitud más alta para un archivo enviado.';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'ID de la entrega de una actividad con SafeAssign habilitado.';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'Marca para determinar si el archivo se envió a SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = 'Hora en la que se creó la entrega.';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'Identificador único de entrega en el servicio de SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'Información acerca de los profesores de la plataforma.';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = 'ID de un usuario correspondiente al profesor de un curso.';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'ID del curso en el que el usuario es profesor.';
