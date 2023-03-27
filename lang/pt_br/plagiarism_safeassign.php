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

$string['pluginname'] = 'Plug-in de plágio do SafeAssign';
$string['getscores'] = 'Obter pontuações para envios';
$string['getscoreslog'] = 'Registro de tarefa de pontuação do SafeAssign';
$string['getscoreslogfailed'] = 'Falha na tarefa de pontuação do SafeAssign';
$string['getscoreslog_desc'] = 'A tarefa de pontuação do SafeAssign foi realizada com sucesso.';
$string['servicedown'] = 'O serviço SafeAssign está indisponível.';
$string['studentdisclosuredefault'] = 'Todos os arquivos carregados serão enviados ao serviço de detecção de plágio';
$string['studentdisclosure'] = 'Declaração de Anuência da Instituição';
$string['studentdisclosure_help'] = 'Este texto será exibido para todos os alunos na página de upload do arquivo. Se este
campo for deixado em branco, a string localizada padrão (studentdisclosuredefault) será usada.';
$string['safeassignexplain'] = 'Para obter mais informações sobre este plug-in, consulte:';
$string['safeassign'] = 'Plug-in de plágio do SafeAssign';
$string['safeassign:enable'] = 'Permitir que o professor habilite/desabilite o SafeAssign dentro de uma atividade';
$string['safeassign:report'] = 'Permitir visualização do Originality Report do SafeAssign';
$string['usesafeassign'] = 'Habilitar o SafeAssign';
$string['savedconfigsuccess'] = 'Configurações de plágio salvas';
$string['safeassign_additionalroles'] = 'Funções adicionais';
$string['safeassign_additionalroles_help'] = 'Os usuários com essas funções no nível do sistema serão adicionados a cada curso do SafeAssign
como instrutores.';
$string['safeassign_api'] = 'URL de integração do SafeAssign';
$string['safeassign_api_help'] = 'Este é o endereço da API SafeAssign.';
$string['instructor_role_credentials'] = 'Credenciais de função do instrutor';
$string['safeassign_instructor_username'] = 'Chave compartilhada';
$string['safeassign_instructor_username_help'] = 'Chave compartilhada do instrutor fornecida pelo SafeAssign.';
$string['safeassign_instructor_password'] = 'Segredo compartilhado';
$string['safeassign_instructor_password_help'] = 'Segredo compartilhado do instrutor fornecido pelo SafeAssign.';
$string['student_role_credentials'] = 'Credenciais de função do aluno';
$string['safeassign_student_username'] = 'Chave compartilhada';
$string['safeassign_student_username_help'] = 'Chave compartilhada do aluno fornecida pelo SafeAssign.';
$string['safeassign_student_password'] = 'Segredo compartilhado';
$string['safeassign_student_password_help'] = 'Segredo compartilhado do aluno fornecido pelo SafeAssign.';
$string['safeassign_license_acceptor_givenname'] = 'Nome do aceitador da licença';
$string['safeassign_license_acceptor_surname'] = 'Sobrenome do aceitador da licença';
$string['safeassign_license_acceptor_email'] = 'E-mail do aceitador da licença';
$string['safeassign_license_header'] = 'Termos e Condições da Licença do SafeAssign&trade;';
$string['license_already_accepted'] = 'Os termos da licença atual já foram aceitos pelo seu administrador.';
$string['acceptlicense'] = 'Aceitar licença do SafeAssign';
$string['acceptlicenselog'] = 'Registro de tarefa da licença do SafeAssign';
$string['safeassign_license_warning'] = 'Ocorreu um problema ao validar os dados da licença do SafeAssign&trade;
Clique no botão &quot;Testar conexão&quot;. Se o teste for bem-sucedido, tente novamente mais tarde.';
$string['safeassign_enableplugin'] = 'Habilitar o SafeAssign para {$a}';
$string['safeassign_cachedefault'] = '<div class="form-defaultinfo text-muted">&amp;nbsp Valor padrão: 0</div> <br>';
$string['safeassign_showid'] = 'Mostrar código do aluno';
$string['safeassign_alloworganizations'] = 'Permitir SafeAssignments nas organizações';
$string['safeassign_referencedbactivity'] = 'Atividade do <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">banco de dados de referência global</a>';
$string['safeassing_response_header'] = '<br>Resposta do servidor SafeAssign:<br>';
$string['safeassign_instructor_credentials'] = 'Credenciais de função do instrutor:';
$string['safeassign_student_credentials'] = 'Credenciais de função do aluno:';
$string['safeassign_credentials_verified'] = 'Conexão verificada.';
$string['safeassign_credentials_fail'] = 'Conexão não verificada. Verificar chave, segredo e URL.';
$string['credentials'] = 'Credenciais e URL do serviço';
$string['shareinfo'] = 'Compartilhar informações com o SafeAssign';
$string['disclaimer'] = '<br>O envio para o <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">banco de dados de referência global</a> do SafeAssign permite que documentos de outras instituições<br>
sejam verificados em relação aos documentos de seus alunos para proteger a origem de seus trabalhos.';
$string['settings'] = 'Configurações do SafeAssign';
$string['timezone_help'] = 'O fuso horário definido no ambiente do Open LMS.';
$string['timezone'] = 'Fuso horário';
$string['safeassign_status'] = 'Status do SafeAssign';
$string['status:pending'] = 'Pendente';
$string['safeassign_score'] = 'Pontuação do SafeAssign';
$string['safeassign_reporturl'] = 'URL do relatório';
$string['button_disabled'] = 'Salvar formulário para testar conexão';
$string['error_generic'] = '{$a}';
$string['error_behat_getjson'] = 'Erro ao obter o arquivo json &quot;{$a}&quot; da pasta plágio/safeassign/testes/acessórios para simular uma chamada para serviços da Web do SafeAssign ao realizar testes behat.';
$string['safeassign_curlcache'] = 'Tempo limite do cache';
$string['safeassign_curlcache_help'] = 'Tempo limite do cache de serviço da Web.';
$string['rest_error_nocurl'] = 'O módulo cURL deve estar presente e habilitado!';
$string['rest_error_nourl'] = 'É necessário especificar o URL!';
$string['rest_error_nomethod'] = 'É necessário especificar o método de solicitação!';
$string['rest_error_server'] = '{$a}';
$string['rest_error_curl'] = '{$a}';
$string['test_credentials'] = 'Testar conexão';
$string['connectionfailed'] = 'Falha na conexão';
$string['connectionverified'] = 'Conexão verificada';
$string['cachedef_request'] = 'Cache de solicitação do SafeAssign';
$string['error_behat_instancefail'] = 'Esta é uma instância configurada para falhar com testes behat.';
$string['assignment_check_submissions'] = 'Verificar envios com o SafeAssign';
$string['assignment_check_submissions_help'] = 'Os Originality Report do SafeAssign não estarão disponíveis para professores se a avaliação anônima
estiver definida, mas os alunos podem visualizar seus próprios Originality Report do SafeAssign se a opção &quot;Permitir que os alunos visualizem o Originality Report&quot; estiver selecionada.
<br><br>O SafeAssign retorna um único Originality Report quando os usuários enviam vários arquivos. Você pode escolher o arquivo a ser revisado neste relatório.
<br><br>Embora o SafeAssign seja compatível oficialmente apenas em inglês, você pode tentar usar o SafeAssign com outros idiomas.
O SafeAssign não tem limitações técnicas que impeçam seu uso em outros idiomas.
Consulte a <a href="http://www.blackboard.com/docs/documentation.htm?DocID=191SafeAssign001en_US" target="_blank">ajuda do Blackboard</a> para obter mais informações.';
$string['students_originality_report'] = 'Permitir que os alunos vejam o Originality Report';
$string['submissions_global_reference'] = 'Excluir envios do <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Banco de dados de referência global</a>';
$string['submissions_global_reference_help'] = 'Os envios ainda serão processados pelo SafeAssign, mas não serão registrados em bancos de dados. Isso impede que os arquivos sejam marcados como plagiados quando os professores permitirem o reenvio em um exercício específico.';
$string['plagiarism_tools'] = 'Ferramentas de plágio';
$string['files_accepted'] = 'O SafeAssign aceita arquivos apenas nos formatos .doc, .docx, .docm, .ppt, .pptx, .odt, .txt, .rtf, .pdf e .html. Arquivos de qualquer outro formato, incluindo .zip e outros formatos de arquivo compactado, não serão verificados pelo SafeAssign.
<br><br>Ao enviar este documento, você concorda:
(1) que você está enviando seu papel para ser usado e armazenado como parte dos serviços SafeAssign&trade; de acordo com os <a href="http://www.blackboard.com/safeassign/tos.htm" target="_blank">Termos e serviços</a> e <a href="http://blackboard.com/footer/privacy-policy.aspx" target="_blank">Política de Privacidade do Blackboard</a>;
(2) que a sua instituição possa usar seu papel de acordo com as políticas da sua instituição; e
(3) que seu uso do SafeAssign será sem recurso contra o Open LMS e suas afiliadas.';
$string['agreement'] = 'Concordo em enviar meu(s) documento(s) para o <a href="https://help.blackboard.com/Learn/Instructor/Assignments/SafeAssign" target="_blank">Banco de dados de referência global</a>.';
$string['error_api_generic'] = 'Ocorreu um erro ao processar sua solicitação';
$string['error_api_unauthorized'] = 'Ocorreu um erro de autenticação ao processar sua solicitação';
$string['error_api_forbidden'] = 'Ocorreu um erro de autorização ao processar sua solicitação';
$string['error_api_not_found'] = 'O recurso solicitado não foi encontrado';
$string['sync_assignments'] = 'Envia as informações disponíveis para o servidor SafeAssign.';
$string['api_call_log_event'] = 'Registro do SafeAssign para chamadas de API.';
$string['course_error_sync'] = 'Ocorreu um erro ao tentar sincronizar o Curso com o ID: {$a} no SafeAssign:<br>';
$string['assign_error_sync'] = 'Ocorreu um erro ao tentar sincronizar a tarefa com o ID: {$a} no SafeAssign:<br>';
$string['submission_error_sync'] = 'Ocorreu um erro ao tentar sincronizar o Envio com ID: {$a} no SafeAssign:<br>';
$string['submission_success_sync'] = 'Envios sincronizados com êxito';
$string['assign_success_sync'] = 'Exercícios sincronizados com êxito';
$string['course_success_sync'] = 'Cursos sincronizados com êxito';
$string['license_header'] = 'Contrato de licença do SafeAssign&trade;';
$string['license_title'] = 'Contrato de licença do SafeAssign';
$string['not_configured'] = 'O SafeAssign&trade; não está configurado. Solicite ao administrador do sistema para enviar um tíquete
para <a href="https://support.openlms.net/" target="_blank" rel="noopener">Suporte do Open LMS</a> para obter assistência.';
$string['agree_continue'] = 'Salvar formulário';
$string['safeassign_file_not_supported'] = 'Não compatível.';
$string['safeassign_file_not_supported_help'] = 'A extensão do arquivo não é compatível com o SafeAssign ou o tamanho do arquivo excede a capacidade máxima.';
$string['safeassign_submission_not_supported'] = 'Este envio não será revisado pelo SafeAssign.';
$string['safeassign_submission_not_supported_help'] = 'Os envios criados por instrutores do curso não são enviados para o SafeAssign.';
$string['safeassign_file_in_review'] = 'Originality Report do SafeAssign em andamento...';
$string['safeassign_file_similarity_score'] = 'Pontuação do SafeAssign: {$a}%<br>';
$string['safeassign_link_originality_report'] = 'Ver o Originality Report';
$string['safeassign_file_limit_exceeded'] = 'Este envio excede o limite de tamanho combinado de 10 MB e não será processado pelo SafeAssign';
$string['originality_report'] = 'Originality Report do SafeAssign';
$string['originality_report_unavailable'] = 'O Originality Report solicitado está indisponível. Verifique novamente mais tarde ou entre em contato com o Administrador do sistema.';
$string['originality_report_error'] = 'Ocorreu um erro com o Originality Report do SafeAssign. Entre em contato com o Administrador do sistema.';
$string['safeassign_overall_score'] = '<b>Pontuação geral do SafeAssign: {$a}%</b>';
$string['messageprovider:safeassign_graded'] = 'O SafeAssign envia notificações aos instrutores quando um envio foi avaliado como plágio';
$string['safeassign_loading_settings'] = 'Carregando configurações, aguarde';
$string['safeassign:get_messages'] = 'Permitir o recebimento de notificações do SafeAssign';
$string['safeassign_notification_message'] = 'As pontuações de plágio foram processadas para {$a->counter} {$a->plural} em {$a->assignmentname}';
$string['safeassign_notification_grading_link'] = 'Página de avaliação';
$string['safeassign_notification_message_hdr'] = 'Foram processadas as pontuações do SafeAssign de plágio';
$string['safeassign_notification_subm_singular'] = 'envio';
$string['safeassign_notification_subm_plural'] = 'envios';
$string['messageprovider:safeassign_notification'] = 'O SafeAssign envia notificações aos Administradores do site quando novos Termos e Condições de Licença estão disponíveis';
$string['safeassign:get_notifications'] = 'Permitir notificações do SafeAssign';
$string['license_agreement_notification_subject'] = 'Novos Termos e Condições de Licença do SafeAssign disponíveis';
$string['license_agreement_notification_message'] = 'Você pode aceitar os novos Termos e Condições de licença aqui: {$a}';
$string['settings_page'] = 'Página de configurações do SafeAssign';
$string['send_notifications'] = 'Enviar notificações sobre novos Termos e Condições de Licença do SafeAssign.';
$string['privacy:metadata:core_files'] = 'Arquivos anexados aos envios ou criados a partir de envios de textos on-line.';
$string['privacy:metadata:core_plagiarism'] = 'Este plug-in é chamado pelo subsistema de plágio do Moodle.';
$string['privacy:metadata:safeassign_service'] = 'Para obter um Originality Report, alguns dados do usuário devem ser enviados para o serviço do SafeAssign.';
$string['privacy:metadata:safeassign_service:adminemail'] = 'É necessário que o administrador tenha enviado o e-mail para aceitar a licença de serviço.';
$string['privacy:metadata:safeassign_service:filecontent'] = 'Precisamos ter enviado os arquivos para o SafeAssign para gerar o Originality Report.';
$string['privacy:metadata:safeassign_service:filename'] = 'O nome do arquivo é obrigatório para o serviço do SafeAssign.';
$string['privacy:metadata:safeassign_service:fileuuid'] = 'O UUID do arquivo permite relacionar os arquivos do Moodle no servidor SafeAssign.';
$string['privacy:metadata:safeassign_service:fullname'] = 'O nome do usuário é enviado para o SafeAssign para permitir a obtenção do código de autenticação.';
$string['privacy:metadata:safeassign_service:submissionuuid'] = 'Este UUID de envio é obrigatório para recuperar o Originality Report.';
$string['privacy:metadata:safeassign_service:userid'] = 'O código do usuário enviado do Moodle para permitir que você use os serviços do SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_files'] = 'Informações sobre a originalidade dos arquivos carregados pelo usuário';
$string['privacy:metadata:plagiarism_safeassign_files:userid'] = 'O código do aluno que fez este envio.';
$string['privacy:metadata:plagiarism_safeassign_files:uuid'] = 'O identificador exclusivo de arquivo no serviço do SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_files:reporturl'] = 'URL para o Originality Report.';
$string['privacy:metadata:plagiarism_safeassign_files:similarityscore'] = 'Pontuação de semelhança para o arquivo enviado.';
$string['privacy:metadata:plagiarism_safeassign_files:timesubmitted'] = 'Hora em que o arquivo foi enviado.';
$string['privacy:metadata:plagiarism_safeassign_files:submissionid'] = 'Identificador exclusivo de envio no serviço do SafeAssign';
$string['privacy:metadata:plagiarism_safeassign_files:fileid'] = 'O código do arquivo que foi enviado.';
$string['privacy:metadata:plagiarism_safeassign_course'] = 'Informações sobre cursos do Moodle com o SafeAssign habilitado neles.';
$string['privacy:metadata:plagiarism_safeassign_course:uuid'] = 'Identificador exclusivo do curso no serviço do SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_course:courseid'] = 'O curso que tem uma atividade com a habilitação do SafeAssign nele.';
$string['privacy:metadata:plagiarism_safeassign_course:instructorid'] = 'O código do usuário que é um professor neste curso.';
$string['privacy:metadata:plagiarism_safeassign_subm'] = 'Informações sobre envios de alunos.';
$string['privacy:metadata:plagiarism_safeassign_subm:assignmentid'] = 'O código do exercício deste envio.';
$string['privacy:metadata:plagiarism_safeassign_subm:avgscore'] = 'A pontuação média de semelhança para todos os arquivos enviados.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasfile'] = 'Sinalização para determinar se o envio tem um arquivo nele.';
$string['privacy:metadata:plagiarism_safeassign_subm:hasonlinetext'] = 'Sinalização para determinar se o envio tem um texto on-line nele.';
$string['privacy:metadata:plagiarism_safeassign_subm:highscore'] = 'A pontuação mais alta de semelhança para um arquivo enviado.';
$string['privacy:metadata:plagiarism_safeassign_subm:submissionid'] = 'O código de envio de uma atividade com o SafeAssign habilitado nela.';
$string['privacy:metadata:plagiarism_safeassign_subm:submitted'] = 'Sinalização para determinar se o arquivo foi enviado para o SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_subm:timecreated'] = 'Hora em que o envio foi criado.';
$string['privacy:metadata:plagiarism_safeassign_subm:uuid'] = 'Identificador exclusivo de envio no serviço do SafeAssign.';
$string['privacy:metadata:plagiarism_safeassign_instr'] = 'Informações sobre os professores na plataforma.';
$string['privacy:metadata:plagiarism_safeassign_instr:instructorid'] = 'O código de um usuário que é um professor em um curso.';
$string['privacy:metadata:plagiarism_safeassign_instr:courseid'] = 'O código do curso no qual o usuário é professor.';
