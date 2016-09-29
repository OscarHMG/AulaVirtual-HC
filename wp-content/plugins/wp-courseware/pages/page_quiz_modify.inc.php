<?php
/**
 * WP Courseware
 * 
 * Functions relating to modifying a quiz.
 */


include_once WPCW_plugin_getPluginDirPath() . 'classes/class_custom_feedback.inc.php';


/**
 * Function that allows a quiz to be created or edited.
 */
function WPCW_showPage_ModifyQuiz_load() 
{
	// Thickbox needed for random and quiz windows.
	add_thickbox();
	
	$page = new PageBuilder(true); 
	
	$quizDetails 	= false;
	$adding			= false;

	$quizID 		= false;
	
	// Check POST and GET
	if (isset($_GET['quiz_id'])) {
		$quizID = $_GET['quiz_id'] + 0;
	} 
	else if (isset($_POST['quiz_id'])) {
		$quizID = $_POST['quiz_id'] + 0;
	}
	

	// Trying to edit a quiz	
	if ($quizDetails = WPCW_quizzes_getQuizDetails($quizID, false, false, false)) 
	{
		// Abort if quiz not found.
		if (!$quizDetails)
		{
			$page->showPageHeader(__('Editar Evaluaci&oacute;n/Encuesta', 'wp_courseware'), '70%', WPCW_icon_getPageIconURL());
			$page->showMessage(__('Lo sentimos, pero eso evaluaci&oacute;n/encuesta no se pudo encontrar.', 'wp_courseware'), true);
			$page->showPageFooter();
			return;
		}
		
		// Editing a quiz, and it was found
		else 
		{
			// Start form prolog - with quiz ID
			printf('<form method="POST" action="%s&quiz_id=%d" name="wpcw_quiz_details_modify" id="wpcw_quiz_details_modify">', admin_url('admin.php?page=WPCW_showPage_ModifyQuiz'), $quizDetails->quiz_id);
			
			$page->showPageHeader(__('Editar Evaluaci&oacute;n/Encuesta ', 'wp_courseware'), '70%', WPCW_icon_getPageIconURL());
		}
	}
	
	// Adding quiz
	else
	{
		// Start form prolog - no quiz ID
		printf('<form method="POST" action="%s" name="wpcw_quiz_details_modify" id="wpcw_quiz_details_modify">', admin_url('admin.php?page=WPCW_showPage_ModifyQuiz'));
		
		$page->showPageHeader(__('Agregar Evaluaci&oacute;n/Encuesta', 'wp_courseware'), '70%', WPCW_icon_getPageIconURL());
		$adding = true;
	}	
	
	
	// Generate the tabs.
	$tabList = array( 
		'wpcw_section_break_quiz_general' 			=> array('label' => __(' Configuraci&oacute;n General', 'wp_courseware')),
		'wpcw_section_break_quiz_logic' 			=> array('label' => __('Configuraciones del comportamiento de las Evaluaciones/Encuestas', 'wp_courseware')), 
		'wpcw_section_break_quiz_results' 			=> array('label' => __('Resultados de Configuraci&oacute;n', 'wp_courseware')), 
		'wpcw_section_break_quiz_custom_feedback' 	=> array('label' => __('Evaluaci&oacute;n Personalizada', 'wp_courseware'), 'cssclass' => 'wpcw_quiz_only_tab'),
		'wpcw_section_break_quiz_questions' 		=> array('label' => __('Administrar preguntas', 'wp_courseware')),
	);
	
	global $wpcwdb;
		
	$formDetails = array(
		'wpcw_section_break_quiz_general' => array(
				'type'  	=> 'break',
				'html'  	=> WPCW_forms_createBreakHTML_tab(false),
			),	
	
		'quiz_title' => array(
				'label' 	=> __('T&iacute;tulo de la Evaluaci&oacute;n', 'wp_courseware'),
				'type'  	=> 'text',
				'required'  => true,
				'cssclass'	=> 'wpcw_quiz_title',
				'desc'  	=> __('T&iacute;tulo de su examen o cuestionario. Sus participantes ser&aacute;n capaces de ver este t&iacute;tulo de la evaluaci&oacute;n.', 'wp_courseware'),
				'validate'	 	=> array(
					'type'		=> 'string',
					'maxlen'	=> 150,
					'minlen'	=> 1,
					'regexp'	=> '/^[^<>]+$/',
					'error'		=> __('Please specify a name for your quiz or survey, up to a maximum of 150 characters, just no angled brackets (&lt; or &gt;).', 'wp_courseware')
				)	
			),		
			
		'quiz_desc' => array(
				'label' 	=> __('Descripci&oacute;n de la Evaluaci&oacute;n', 'wp_courseware'),
				'type'  	=> 'textarea',
				'required'  => false,
				'cssclass'	=> 'wpcw_quiz_desc',
				'rows'		=> 2,
				'desc'  	=> __('(Opcional) La descripci&oacute;n de esta evaluaci&oacute;n. Sus alumnos no ver&aacute;n esta descripci&oacute;n. Es s&oacute;lo para su referencia.', 'wp_courseware'),
				'validate'	 	=> array(
					'type'		=> 'string',
					'maxlen'	=> 5000,
					'minlen'	=> 1,
					'error'		=> __('Por favor, limitar la descripci&oacute;n de su evaluaci&oacute;n a 5000 caracteres.', 'wp_courseware')
				)	 	
			),		

		'quiz_type' => array(
				'label' 	=> __('Tipo de Evaluaci&oacute;n', 'wp_courseware'),
				'type'  	=> 'radio',
				'required'  => true,
				'cssclass'	=> 'wpcw_quiz_type wpcw_quiz_type_hide_pass',
				'data'		=> array(
					'survey'		=> __('<b>Modo encuesta</b> - No hay respuestas correctas, simplemente recoger informaci&oacute;n.', 'wp_courseware'),
					'quiz_block'	=> __('<b>Modo de Evaluaci&oacute;n - con bloqueo </b> - Requiere de estudio de la unidad previa. <b>Minima nota </b> para pasar a la siguiente unidad.', 'wp_courseware'),
					'quiz_noblock'	=> __('<b>Modo de Evaluaci&oacute;n - sin bloqueo </b> - Requiere de estudio de la unidad previa. <b>No necesita haber aprobado </b> para pasar a la siguiente unidad.', 'wp_courseware'),
				)	
			),

		'wpcw_section_break_quiz_logic' => array(
				'type'  	=> 'break',
				'html'  	=> WPCW_forms_createBreakHTML_tab(false),
			),
			
		'quiz_pass_mark' => array(
				'label' 	=> __('Pass Mark', 'wp_courseware'),
				'type'  	=> 'select',
				'required'  => true,
				'cssclass'	=> 'wpcw_quiz_block_only wpcw_quiz_only',
				'data'		=> WPCW_quizzes_getPercentageList(__('-- Seleccione una calificaci&oacute;n de aprobaci&oacute;n --', 'wp_courseware')),
				'desc'  	=> __('La nota m&iacute;nima que sus alumnos necesitan para lograr progresar a la siguiente unidad.', 'wp_courseware'),
			),
			
		'quiz_attempts_allowed' => array(
				'label' 	=> __('N&uacute;mero de intentos permitidos?', 'wp_courseware'),
				'type'  	=> 'select',
				'required'  => true,
				'cssclass'	=> 'wpcw_quiz_block_only wpcw_quiz_only',
				'data'		=> WPCW_quizzes_getAttemptList(),
				'desc'  	=> __('El n&uacute;mero m&aacute;ximo de intentos que un estudiante tiene para completar una evaluaci&oacute;n para el bloqueo de las preguntas.', 'wp_courseware'),
			),
			
		'quiz_show_survey_responses' => array(
				'label' 	=> __('Mostrar respuesta de las Encuestas', 'wp_courseware'),
				'type'  	=> 'radio',
				'required'  => true,
				'cssclass'	=> 'wpcw_survey_only',
				'data'		=> array(
						'show_responses' 	=> __('<b>Mostrar respuestas</b> - Mostrar al estudiante sus respuestas a la encuesta.', 'wp_courseware'),
						'no_responses' 	=> __('<b>Sin respuestas</b> - No mostrar al estudiante sus respuestas a la encuesta.', 'wp_courseware'),
					),
				'desc'  	=> __('Esta configuraci&oacute;n le permite elegir si desea o no que los estudiantes sean capaces de revisar sus respuestas en las evaluaci&oacute;n anteriores cuando vuelven a las unidades.', 'wp_courseware'),
			),

			
			
		'quiz_paginate_questions' => array(
				'label' 	=> __('Paginaci&oacute;n de preguntas?', 'wp_courseware'),
				'type'  	=> 'radio',
				'required'  => true,
				'cssclass'	=> 'wpcw_quiz_paginate_questions',
				'data'		=> array(
					'use_paging' 	=> __('<b>Use Paginaci&oacute;n</b> - Este ajuste mostrar&aacute; las preguntas una a la vez que permite a los estudiantes progresar a trav&eacute;s de preguntas individualmente dentro de un marco en su p&aacute;gina unidad.', 'wp_courseware'),
					'no_paging' 	=> __('<b>No Paginaci&oacute;n</b> - No utiliza ning&uacute;na paginaci&oacute;n. Muestra todas las preguntas a la vez en la p&aacute;gina de la unidad.', 'wp_courseware'),
				),
				
				// The list of subitems for each radio button
				'suffix_subitems' => array(
				
					// Show these options when 'use_paging' is selected
					'use_paging' 	=> array(
				
						'quiz_paginate_questions_settings' => array(
							'type'  		=> 'checkboxlist',
							'required'		=> false,
							'cssclass'		=> 'wpcw_quiz_paginate_questions_group',
								'data'	=> array(
									'allow_review_before_submission'  	=> '<b>' . __('Permitir revisados antes de aprobarlos final', 'wp_courseware') . '</b> - ' . __('Si se selecciona, los estudiantes ser&aacute;n presentados con la oportunidad de revisar una lista editable de todas las respuestas antes de la presentaci&oacute;n final.', 'wp_courseware'),
									'allow_students_to_answer_later'  	=> '<b>' . __('Permitir a los alumnos que respondan tarde', 'wp_courseware') . '</b> - ' . __('Si se selecciona, los estudiantes ser&aacute;n capaz de hacer clic en el bot&oacute;n "Responder tarde" y continuar a la siguiente pregunta. La pregunta se presentar&aacute; de nuevo al final de la prueba.', 'wp_courseware'),
									'allow_nav_previous_questions'  	=> '<b>' . __('Permitir la navegaci&oacute;n a las preguntas anteriores', 'wp_courseware') . '</b> - ' . __('Si se selecciona, se mostrar&aacute; un bot&oacute;n "Pregunta anterior", lo que permite a los estudiantes navegar libremente hacia atr&aacute;s y hacia delante a trav&eacute;s de preguntas.', 'wp_courseware'),
								),
						),
					), // end of quiz_paginate_questions array
				), // end suffix_subitems
			),
			
			'quiz_timer_mode' => array(
				'label' 	=> __('Plazo concedido para las evaluaciones?', 'wp_courseware'),
				'type'  	=> 'radio',
				'required'  => true,
				'cssclass'	=> 'wpcw_quiz_timer_mode wpcw_quiz_block_only',
				'data'		=> array(
					'use_timer' 	=> __('<b>Especificar un l&iacute;mite de tiempo la evaluaci&oacute;n</b> - Dar al alumno una cantidad fija de tiempo para completar la evaluaci&oacute;n.', 'wp_courseware'),
					'no_timer' 	=> __('<b>Sin l&iacute;mite de tiempo</b> - el estudiante puede tomar todo el tiempo que desee para completar la evaluaci&oacute;n', 'wp_courseware'),
				),
			),
			
			'quiz_timer_mode_limit' => array(
				'label' 	=> __('Tiempo Limite (en minutos)', 'wp_courseware'),
				'type'  	=> 'text',
				'required'  => false,
				'cssclass'	=> 'wpcw_quiz_timer_mode_limit wpcw_quiz_timer_mode_active_only wpcw_quiz_block_only',
				'extrahtml' => __('Minutos', 'wp_courseware'),
				'validate'	=> array(
					'type'	=> 'number',
					'max'	=> 1000,
					'min'	=> 1,
					'error'	=> 'Por favor, elija l&iacute;mite de tiempo entre 1 y 1000 minutos.'
				) 
			),
			
		'wpcw_section_break_quiz_results' => array(
				'type'  	=> 'break',
				'html'  	=> WPCW_forms_createBreakHTML_tab(false),
			),

		'quiz_show_answers' => array(
				'label' 	=> __('Mostrar respuestas?', 'wp_courseware'),
				'type'  	=> 'radio',
				'required'  => true,
				'cssclass'	=> 'wpcw_quiz_show_answers wpcw_quiz_only',
				'data'		=> array(
					'show_answers' 	=> __('<b>Mostrar Respuestas</b> - Mostrar al estudiante las respuestas correctas antes de continuar.', 'wp_courseware'),
					'no_answers' 	=> __('<b>No mostrar Respuestas</b> - No mostrar al estudiante las respuestas correctas antes de continuar..', 'wp_courseware'),
				),
				'extrahtml'	=> '<div class="wpcw_msg_info wpcw_msg wpcw_msg_in_form wpcw_msg_error_no_answers_selected" style="display: none">' . __('Si se selecciona esta opci&oacute;n, los estudiantes no podr&acute;n ver las respuestas correctas o incorrectas.', 'wp_courseware')  . '</div>',
				
				// The list of subitems for each radio button
				'suffix_subitems' => array(
				
					// Show these options when 'show_answers' is selected
					'show_answers' 	=> array(
				
						'show_answers_settings' => array(
							'type'  		=> 'checkboxlist',
							'required'		=> false,
							'cssclass'		=> '',
							'errormsg'			=> __('Please choose at least one option when showing correct answers.', 'wp_courseware'),
								'data'	=> array(
									'show_correct_answer' 		=> '<b>' . __('Mostrar  respuestas correctas', 'wp_courseware') . '</b> - ' . __('Mostrar al estudiante las respuestas correctas antes de continuar.', 'wp_courseware'),
									'show_user_answer' 			=> '<b>' . __('Mostrar todas las repuestas del usuario', 'wp_courseware') . '</b> - ' . __('Mostrar al estudiante todas sus respuestas antes de enviarlas.', 'wp_courseware'),
									'show_explanation' 			=> '<b>' . __('Mostrar explicaci&oacute;n', 'wp_courseware') . 	'</b> - ' . __('Muestre al estudiante una explicaci&oacute;n de la respuesta correcta (si la hay).', 'wp_courseware'),
									'mark_answers' 				=> '<b>' . __('Marcar Respuestas', 'wp_courseware') . 		'</b> - ' . __('Esta opci&oacute;n mostrar&aacute; respuestas correctas con una marca verde, y las respuestas incorrectas con una "X" roja.', 'wp_courseware'),
									'show_results_later' 		=> '<b>' . __('Deja resultados de la prueba disponible para verlo en otro momento?', 'wp_courseware') . '</b> - ' . __('Esta configuraci&oacute;n le permite elegir si desea o no que los estudiantes sean capaces de revisar sus &uacute;ltimas respuestas de la evaluaci&oacute;n cuando vuelven a las unidades.', 'wp_courseware'),
								),
							'extrahtml'	=> '<div class="wpcw_msg_error wpcw_msg wpcw_msg_in_form wpcw_msg_error_show_answers_none_selected" style="display: none">' . __('Para hacer uso de esta opci&oacute;n que muestra las respuestas, al menos uno de los ajustes anteriores se deber&aacute; marcar. De lo contrario, no hay respuestas para mostrar', 'wp_courseware')  . '</div>',
						),
					), // end of show_answers array
					
					/*'no_answers' 	=> array(
						'no_answers_settings' => array(
							'type'  		=> 'checkboxlist',
							'cssclass'		=> '',
								'data'	=> array(
									'no_show_explanation' 	=> '<b>' . __('Show explanation', 'wp_courseware') . '</b> - ' . __('Show the trainee an explanation for the correct answer (if there is one).', 'wp_courseware'),
								),
						),						
					), // end of show_answers array*/
					
				),
			),
			
			
			'quiz_results_by_tag' => array(
				'label' 		=> __('Mostrar resultados por tag?', 'wp_courseware'),
				'type'  		=> 'checkbox',
				'required'  	=> false,
				'extralabel'	=> __('<b>Mostrar resultados por etiqueta de preguntas</b> - Adem&aacute;s de la puntuaci&oacute;n total, indicar un desglose de los resultados para cada etiqueta que se trate.', 'wp_courseware'),
			),
			
			'quiz_results_by_timer' => array(
				'label' 		=> __('Mostrar Tiempo de terminaci&oacute;n?', 'wp_courseware'),
				'type'  		=> 'checkbox',
				'required'  	=> false,
				'cssclass'		=> 'wpcw_quiz_timer_mode_active_only wpcw_quiz_block_only',
				'extralabel'	=> __('<b>Mostrar el tiempo de finalizaci&oacute;n dentro de la evaluaci&oacute;n</b> - Si la evaluaci&oacute;n tiene tiempo, esta opci&oacute;n se mostrar&aacute; el tiempo total del estudiante utilizado para completar el cuestionario.', 'wp_courseware'),
			),
			
	);
		
	
	$form = new RecordsForm(
		$formDetails,			// List of form elements
		$wpcwdb->quiz, 			// Table for main details
		'quiz_id', 				// Primary key column name
		false,
		'wpcw_quiz_details_modify'	// Name of the form.
	);	
	
	$form->customFormErrorMsg = __('Lo sentimos, pero por desgracia hab&iacute;a algunos errores al guardar los detalles de la Evaluaci&oacute;n. Por favor corrija los errores e int&eacute;ntelo de nuevo.', 'wp_courseware');
	$form->setAllTranslationStrings(WPCW_forms_getTranslationStrings());
	
	// Got to summary of quizzes
	$directionMsg = '<br/></br>' . sprintf(__('Quieres volver a la <a href="%s">quiz summary page</a>?', 'wp_courseware'),
		admin_url('admin.php?page=WPCW_showPage_QuizSummary')
	);	
	
	// Override success messages
	$form->msg_record_created = __('Detalles de la Evaluaci&oacute;n creada con &eacute;xito..', 'wp_courseware') . $directionMsg;
	$form->msg_record_updated = __('Detalles de la Evaluaci&oacute;n se han actualizado correctamente..', 'wp_courseware') . $directionMsg;

	$form->setPrimaryKeyValue($quizID);	
	$form->setSaveButtonLabel(__('Guardar todos los ajuste de la Evaluaci&oacute;n', 'wp_courseware'));
	
	// Do default checking based on quiz type.
	$form->filterBeforeSaveFunction = 'WPCW_actions_quizzes_beforeQuizSaved';
	$form->afterSaveFunction 		= 'WPCW_actions_quizzes_afterQuizSaved';
		
	
	// Set defaults when creating a new one
	if ($adding) 
	{
		$form->loadDefaults(array(
			'quiz_pass_mark' 			 => 50,				
			'quiz_type'					 => 'quiz_block',
			'quiz_show_survey_responses' => 'no_responses',
		
			// Show answers
			'quiz_show_answers'			 => 'show_answers',
			'show_answers_settings' => array(
				'show_correct_answer' 	=> 'on',
				'show_user_answer' 		=> 'on',
				'show_explanation' 		=> 'on',
				'mark_answers' 			=> 'on',
				'show_results_later' 	=> 'on'
			),
			
			// Paging
			'quiz_paginate_questions'	 => 'no_paging',			
			'quiz_paginate_questions_settings' => array(
				'allow_review_before_submission' 	=> 'on',
				'allow_students_to_answer_later' 	=> 'on',
				'allow_nav_previous_questions' 		=> 'on',
			),
			
			// Time Limit
			'quiz_timer_mode'			=> 'no_timer',
			'quiz_timer_mode_limit'		=> '15',
			
			// Result settings
			'quiz_results_by_tag'		=> 'on',
			'quiz_results_by_timer'		=> 'on'
			
		));
	}
	
		

	// Get the rendered form, extract the start and finish form tags so that
	// we can use the form across multiple panes, and submit other data such
	// as questions along with the quiz itself. We're doing this because the RecordsForm
	// object actually does a really good job, so we don't want to refactor to remove it.
	$formHTML = $form->getHTML();
	$formHTML = preg_replace('/^(\s*?)(<form(.*?>))/', '', $formHTML);
	$formHTML = preg_replace('/<\/form>(\s*?)$/', '', $formHTML);
	
	// Need to move the submit button to before the closing form tag to allow it
	// to render on the page as expected after the questions drag-n-drop section.
	// Don't bother if we're not showing any questions yet though.
	$buttonHTML = false;
	if ($form->primaryKeyValue > 0)
	{
		$pattern = '/<p class="submit">(\C*?)<\/p>/';   
		if (preg_match($pattern, $formHTML, $matches))
		{
			// Found it, so add to variable to show later, and strip
			// it from the HTML so far.
			$buttonHTML = $matches[0];
			$formHTML = str_replace($buttonHTML, false, $formHTML);
		}
	}
	
	
	// Not got any questions to show yet, so hide questions tab.
	if ($form->primaryKeyValue <= 0) {
		unset($tabList['wpcw_section_break_quiz_questions']);
		unset($tabList['wpcw_section_break_quiz_custom_feedback']);
	}
	
	// Show a placeholder for an error message that may occur within tabs.
	printf('<div class="wpcw_msg wpcw_msg_error wpcw_section_error_within_tabs">%s</div>', __('Por desgracia, hay algunos detalles que faltan que necesitan ser a&ntilde;adido antes de que esta evaluaci&oacute;n pueda ser guardada. Por favor, resolverlos y vuelve a intentarlo.', 'wp_courseware'));
	
	// Render the tabs	
	echo WPCW_tabs_generateTabHeader($tabList, 'wpcw_quizzes_tabs');
	
	// The main quiz settings
	echo  $formHTML;
	
	
	// Try to see if we've got an ID having saved the form from a first add
	// or we're editing the form
	if ($form->primaryKeyValue > 0)
	{
		$quizID = $form->primaryKeyValue;
		
		// Top for jumps.
		printf('<a name="top"></a>');
		
		// ### 1) Custom Feedback Messages
		printf('<div class="wpcw_form_break_tab"></div>');
				
		printf('<div class="form-table" id="wpcw_section_break_quiz_custom_feedback">');
			WPCW_showPage_customFeedback_showEditForms($quizID, $page);
		printf('</div>');
		
		
		// ### 2) Question Settings
		printf('<div class="wpcw_form_break_tab"></div>');
				
		printf('<div class="form-table" id="wpcw_section_break_quiz_questions">');
			WPCW_showPage_ModifyQuiz_showQuestionEntryForms($quizID, $page);
		printf('</div>');		
	}
	
	// Reshow the button here
	echo $buttonHTML;
	
	// The closing form tag
	echo '</form>';
	
	// .wpcw_tab_wrapper
	echo '</div>'; 
	
	// The thickboxes for the page
	WPCW_showPage_thickbox_questionPool();	
	WPCW_showPage_thickbox_randomQuestion();
	
	$page->showPageFooter();
}



/**
 * Render the placeholder for the AJAX-loading Thickbox that allows the user to add questions
 * to a quiz directly from a thickbox.
 */
function WPCW_showPage_thickbox_questionPool()
{
	printf('<div id="wpcw_tb_question_pool" style="display: none">');					
		printf('<div id="wpcw_tb_question_pool_inner">');
				
			echo WPCW_questionPool_showPoolTable(20, $_GET, 'ajax');
			
		printf('</div>'); // #wpcw_tb_question_pool_inner		
	printf('</div>'); // #wpcw_tb_question_pool
}




/**
 * Render the placeholder for the AJAX-loading Thickbox that allows the user to add 
 * a selection of random questions using a tag.
 */
function WPCW_showPage_thickbox_randomQuestion()
{	
	$defaultQuestions = 10;
	
	printf('<div id="wpcw_tb_random_question" style="display: none">');					
		printf('<div id="wpcw_tb_random_question_inner">');		

			// ### Choice A - Whole Quiz Pool
			printf('<div class="wpcw_tb_option_wrap" id="wpcw_tb_option_wrap_whole_pool">');
				
				// Label
				printf('<label class="wpcw_bold"><input type="radio" name="wpcw_tb_random_question_type" value="whole_pool" /> %s</label>', 
					__('Seleccionar al azar de todo el grupo de preguntas', 'wp_courseware')
				);
				printf('<div class="wpcw_tb_description">%s</div>', __('Si se selecciona esta opci&oacute;n, entonces el n&uacute;mero de preguntas que elija ser&aacute; elegido al azar de todo el grupo de preguntas, independientemente de la etiqueta que se trate.', 'wp_courseware'));
				
				// How many questions are there?
				$questionCount = WPCW_questions_getQuestionCount();
				
				// Wraps the selection in a grey box
				printf('<div class="wpcw_tb_option_selection">');
					printf('<label>%s&nbsp;&nbsp;</label>', __('Show a total of ', 'wp_courseware'));
					printf('<input type="text" class="wpcw_spinner" value="%d" data-wpcw-max="%d"/>', $defaultQuestions, $questionCount);
					printf('<label>&nbsp;&nbsp;%s</label>', __('questions', 'wp_courseware'));
					
					// Shows how many questions are available.
					printf('<div class="wpcw_tb_random_question_count">%s <b>%d</b> %s</div>',
						__('There are a total of ', 'wp_courseware'), 
						$questionCount, 
						__('questions available in the question pool.', 'wp_courseware')
					);
				printf('</div>');
				
				
				
		
			printf('</div>'); // .wpcw_tb_option_wrap
		 
			
			// ### Choice B - Select using question tags...
			printf('<div class="wpcw_tb_option_wrap wpcw_tb_option_wrap_active" id="wpcw_tb_option_wrap_question_tags">');
				
				// Label
				printf('<label class="wpcw_bold"><input type="radio" name="wpcw_tb_random_question_type" value="question_tags" checked/> %s</label>', 
					__('Randomly Select using Question Tags', 'wp_courseware')
				);
				printf('<div class="wpcw_tb_description">%s</div>', __('Si se selecciona esta opci&oacute;n, se mostrar&aacute; al azar a continuaci&oacute;n, el ´n&uacute;mero de preguntas que elija para cada etiqueta.', 'wp_courseware'));
				
				// Wraps the selection in a grey box
				printf('<div class="wpcw_tb_option_selection">');
					
					// List the main ones.
					printf('<div id="wpcw_tb_option_wrap_question_tags_list">');
						WPCW_showPage_thickbox_randomQuestion_tagSelectionLine(10, true);
					printf('</div>');
					
					
					// Create the add new line
					printf('<hr/>');
					printf('<a href="#" id="wpcw_tb_option_wrap_question_tags_add">%s</a>',  __('+ agregar otra', 'wp_courseware'));
					
				printf('</div>');
	
		
			printf('</div>'); // .wpcw_tb_option_wrap
			
			// Insert button
			printf('<br/><div class="wpcw_button_group">');
				printf('<a href="#new_question" class="button-primary" id="wpcw_tb_random_question_inner_insert">%s</a>',  __('Inserte Selecci&oacute;n de Preguntas aleatorias ', 'wp_courseware'));
			printf('</div>');
			
		printf('</div>'); // #wpcw_tb_random_question_inner			
	printf('</div>'); // #wpcw_tb_random_question
}

/**
 * Creates a simple spinner line with delete/tag selection.
 * 
 * @param Integer $defaultCount The default count on the spinner.
 */
function WPCW_showPage_thickbox_randomQuestion_tagSelectionLine($defaultCount = 10, $isFirst = false)
{
	printf('<div class="wpcw_tb_option_wrap_question_tags_row">');
	
		// Label prefix
		printf('<label>%s&nbsp;&nbsp;</label>', __('  ', 'wp_courseware'));
		
		// Input box
		printf('<input type="text" class="wpcw_spinner" value="%d" />', $defaultCount);
		
		// Label suffix
		printf('<label>&nbsp;&nbsp;%s&nbsp;&nbsp;</label>', __('preguntas de', 'wp_courseware'));
		
		// The tag dropdown
		echo WPCW_questions_tags_getTagDropdown(__('--- Seleccionar Tag ---', 'wp_courseware'), 'tag_selection', false, 'wpcw_tb_option_tag_select', true);
		
		// The deletion link.
		printf('<a href="#" class="wpcw_delete_icon" rel="%s" %s>%s</a>',
			__('Est&aacute; seguro de que desea eliminar esta selecci&oacute;n?', 'wp_courseware'),
			($isFirst ? 'style="display: none;"' : false), // Hide deletion link if this is the first item
			__('Eliminar', 'wp_courseware') 
		);
		
		// Marker to show that a tag needs selection.
		printf('<div class="wpcw_missing_tag">%s</div>',
			__('Por favor, seleccione una etiqueta', 'wp_courseware') 
		);
		
	printf('</div>');
}





/**
 * Show the forms where the quiz answers can be edited.
 * 
 * @param Integer $quizID the ID of the quiz to be edited.
 * @param Object $page The associated page object for showing messages.
 */
function WPCW_showPage_ModifyQuiz_showQuestionEntryForms($quizID, $page)
{
	global $wpdb, $wpcwdb;
	$wpdb->show_errors();
	
	// Work out if we need correct answers or not. And what the pass mark is.
	$quizDetails = WPCW_quizzes_getQuizDetails($quizID, true, false, false);
	$needCorrectAnswers  = ('survey' != $quizDetails->quiz_type);
	 	
	// Show the existing quiz questions as a series of forms.
	$quizItems = WPCW_quizzes_getListOfQuestions($quizID);
	
	
	// Show the number of correct answers the user must get in order to pass.
	if ('quiz_block' == $quizDetails->quiz_type) 
	{
		$totalQs = WPCW_quizzes_calculateActualQuestionCount($quizID);
		$passQs  = ceil(($quizDetails->quiz_pass_mark / 100) * $totalQs);
		
		printf('<div class="wpcw_msg wpcw_msg_info">');
		printf(__('Se le requerir&aacute; al estudiante a responder correctamente al menos <b>%d de las %d</b> siguientes preguntas (<b>al menos %d%%</b>) para el progreso.', 'wp_courseware'),
			$passQs, $totalQs, $quizDetails->quiz_pass_mark
		);
		printf('</div>');
	}
	
	// Got a  quiz, and trainer is requiring to show answers. Tell them we can't show answers
	// as this quiz contains open-ended questions that need grading.
	if ($needCorrectAnswers && 'show_answers' == $quizDetails->quiz_show_answers && WPCW_quizzes_containsQuestionsNeedingManualGrading($quizItems) )
	{
		printf('<div class="wpcw_msg wpcw_msg_error">');
		
			printf(
			__('Esta evaluaci&oacute;n contiene preguntas que necesitan de <b>clasificaci&oacute;n manual</b>, y que ha seleccionado \'<b>Mostrar respuestas\' cuando el usuario complete esta evaluaci&oacute;n. ', 'wp_courseware') . '<br/><br/>' .
			__('Dado que las respuestas no pueden ser mostrados al usuario, ya que no se conocen en esa etapa, las respuestas no pueden ser mostrados. Para ocultar este mensaje, seleccione No hay respuestas anteriores.', 'wp_courseware')								
			);
		printf('</div>');
	}
	
		
	$errorCount = 0;
	global $errorCount;
		
	// Wrapper for questions
	printf('<ol class="wpcw_dragable_question_holder">');

	if ($quizItems)
	{
		// Render edit form for each of the quizzes that already exist
		foreach ($quizItems as $quizItem)
		{
			switch ($quizItem->question_type)
			{
				case 'multi':
					$quizObj = new WPCW_quiz_MultipleChoice($quizItem);
					break;
					
				case 'truefalse':
					$quizObj = new WPCW_quiz_TrueFalse($quizItem);					
					break;
					
				case 'open':
					$quizObj = new WPCW_quiz_OpenEntry($quizItem);
					break;
					
				case 'upload':
					$quizObj = new WPCW_quiz_FileUpload($quizItem);
					break;
					
				case 'random_selection':
					$quizObj = new WPCW_quiz_RandomSelection($quizItem);
					break;
					
				default:
					die(__('Unknown quiz type: ', 'wp_courseware') . $quizItem->question_type);
					break;
			}
			
			$quizObj->showErrors = true;
			$quizObj->needCorrectAnswers = $needCorrectAnswers;
			
			// Keep track of errors
			if ($quizObj && $quizObj->gotError) {
				$errorCount++;
			}

			echo $quizObj->editForm_toString();
		}
	}
		
	printf('</ol>');
	
	// Do any of the questions have residual errors? Tell the user.
	if ($errorCount > 0) 
	{
		$page->showMessage(sprintf(__('%d de las preguntas a continuaci&oacute;n tienen errores. Por favor, hacer correcciones y luego guardar los cambios.', 'wp_courseware'), $errorCount), true);
	}
	
	
	$page->showPageMiddle('35%');

	
	// Show the menu for saving and adding new items.
	WPCW_showPage_ModifyQuiz_FloatMenu($page);
	
	// Flag to indicate that questions have been updated.
	printf('<input type="hidden" name="survey_updated" value="survey_updated" />');
	
	printf('<a name="new_question"></a>');
	
	// The empty forms for adding a new question		
	$quizItemDummy = new stdClass();
	$quizItemDummy->question_question 			= '';	
	$quizItemDummy->question_correct_answer 	= false;
	$quizItemDummy->question_order 				= 0;
	$quizItemDummy->question_answer_type 		= false;
	$quizItemDummy->question_answer_hint 		= false;
	$quizItemDummy->question_answer_explanation = false;
	$quizItemDummy->question_answer_file_types 	= 'doc, pdf, jpg, png, jpeg, gif';
	$quizItemDummy->question_image				= false;
	$quizItemDummy->question_usage_count		= 0;
	
	$quizItemDummy->question_multi_random_enable = 0;
	$quizItemDummy->question_multi_random_count	 = 5;
	
	// Create some dummy answers.
	$quizItemDummy->question_data_answers 		= serialize(array(
		1 => array('answer' => ''),
		2 => array('answer' => ''),
		3 => array('answer' => '')
	));
	
	
	$quizFormsToCreate = array (
		'new_multi' 			=> 'WPCW_quiz_MultipleChoice',
		'new_tf' 				=> 'WPCW_quiz_TrueFalse',
		'new_open' 				=> 'WPCW_quiz_OpenEntry',
		'new_upload' 			=> 'WPCW_quiz_FileUpload',
		'new_random_selection' 	=> 'WPCW_quiz_RandomSelection',
	);
	
	// Create the dummy quiz objects
	foreach ($quizFormsToCreate as $dummyid => $objClass)
	{
		// Set placeholder class
		$quizItemDummy->question_id  = $dummyid;
		
		// Create new object and set it up with defaults
		$quizObj = new $objClass($quizItemDummy);
		
		$quizObj->cssClasses .= ' wpcw_question_template';
		$quizObj->showErrors = false;
		$quizObj->needCorrectAnswers = $needCorrectAnswers;
		$quizObj->editForm_questionNotSavedYet = true;
		
		echo $quizObj->editForm_toString();
	}

}


/**
 * Creates the floating menu for adding quiz items.
 */
function WPCW_showPage_ModifyQuiz_FloatMenu($page)
{
	?>
	<div class="wpcw_floating_menu" id="wpcw_add_quiz_menu">
		
		<div class="wpcw_add_quiz_block">
			<div class="wpcw_add_quiz_title"><?php _e('Question Tools', 'wp_courseware'); ?></div>
			<div class="wpcw_add_quiz_options"><ul>
				<li><a href="#new_question" class="button-secondary" id="wpcw_add_question_multi"><?php _e('Agregar opci&oacute;n m&uacute;ltiple', 'wp_courseware'); ?></a></li>
				<li><a href="#new_question" class="button-secondary" id="wpcw_add_question_truefalse"><?php _e('Agregar Verdadero/Falso', 'wp_courseware'); ?></a></li>
				<li><a href="#new_question" class="button-secondary" id="wpcw_add_question_open"><?php _e('Agregar pregunta abierta', 'wp_courseware'); ?></a></li>
				<li><a href="#new_question" class="button-secondary" id="wpcw_add_question_upload"><?php _e('Agregar archivo de preguntas', 'wp_courseware'); ?></a></li>
				
				<li class="wpcw_add_quiz_spacer"><hr/></li>
				
				<li><a href="#TB_inline?width=1200&height=800&inlineId=wpcw_tb_question_pool" title="<?php _e('Insertar pregunta del Grupo de preguntas', 'wp_courseware'); ?>" class="button-secondary thickbox" id="wpcw_add_question_from_pool"><?php _e('Agregar pregunta - grupo de preguntas', 'wp_courseware'); ?></a></li>
				<li><a href="#TB_inline?width=1200&height=800&inlineId=wpcw_tb_random_question" title="<?php _e('Inserte preguntas al azar', 'wp_courseware'); ?>" class="button-secondary thickbox" id="wpcw_add_question_random"><?php _e('Agregar preguntas al azar', 'wp_courseware'); ?></a></li>
				
				<li class="wpcw_add_quiz_spacer"><hr/></li>
				
				<li class="wpcw_quiz_tool_compact">
					<a href="#top" class="wpcw_quiz_tool_compact_compact" title="<?php _e('Utilice esta opci&oacute;n para compactar las preguntas de manera que sean m&aacute;s f&aacute;ciles de organizar.', 'wp_courseware'); ?>">[-] <?php _e('Compactar preguntas', 'wp_courseware'); ?></a>
					<a href="#top" class="wpcw_quiz_tool_compact_expand" title="<?php _e('Utilice esta opci&oacute;n para ampliar las preguntas para la edici&oacute;n.', 'wp_courseware'); ?>">[+] <?php _e('Expandir preguntas', 'wp_courseware'); ?></a>
				</li>
			</ul></div>
		</div>
		
		<?php
			// Keep track of new questions so that they all get a new ID. 
			printf('<div id="wpcw_question_template_count" class="wpcw_question_template">0</div>'); 
		?>
		
		<div class="wpcw_add_quiz_save">
			<input type="submit" class="button-primary" value="<?php _e('Guardar Evaluaci&oacute;n y Preguntas', 'wp_courseware'); ?>" />
		</div>
		
		<div class="wpcw_quiz_tool_compact">
			
		</div>
	</div>
	
	<?php 
}


/**
 * Show the forms where the quiz custom feedback can be edited.
 * 
 * @param Integer $quizID the ID of the quiz to be edited.
 * @param Object $page The associated page object for showing messages.
 */
function WPCW_showPage_customFeedback_showEditForms($quizID, $page)
{
	// ### 1 - Heading Messages
	printf('<h3>%s</h3>', __('Retroalimentaci&oacute;n de los mensajes', 'wp_courseware'));
	printf('<p>(%s) %s</p>', 
		__('Optional', 'wp_courseware'),
		__('Si est&aacute; utilizando etiquetas de preguntas dentro de tu evaluaci&oacute;n, puede crear mensajes personalizados para mostrar a la presentaci&oacute;n de la prueba sobre la base de un resultado para los estudiantes una etiqueta en particular.', 'wp_courseware')
	);
	
	printf('<p><em>%s</em></p>', 
		__('Tenga en cuenta: Los mensajes de informaci&oacute;n personalizada no muestran ninguna informaci&oacute;n de grado. Utilice la configuraci&oacute;n en la pesta&ntilde;a <b>Configuraci&oacute;n de resultados</b>, a continuaci&oacute;n, <b>Mostrar respuestas</b> - para personalizar la presentaci&oacute;n de los resultados.', 'wp_courseware')
	);
	
	// ### 2 - Button to add a new message
	printf('<div class="wpcw_button_group"><a href="#" class="button-secondary" id="wpcw_quiz_custom_feedback_add_new">%s</a></div><br/>', __('Agregar nuevo comentario', 'wp_courseware'));
	
	// ### 3 - Keep track of new messages and deletions
	printf('<div id="wpcw_quiz_custom_feedback_add_new_count">0</div>'); 
	printf('<div id="wpcw_quiz_custom_feedback_deletion_holder"></div>');
	
	
	// ### 4 - Holder for new messages
	printf('<div id="wpcw_quiz_custom_feedback_holder">');
	
		// ### 5 - Render the existing forms to modify the custom messages.
		$feedbackList = WPCW_quizzes_feedback_getFeedbackMessagesForQuiz($quizID);
		if (!empty($feedbackList))
		{
			// Show an edit form for each custom feedback item.
			foreach ($feedbackList as $feedbackItem)
			{
				$fb = new WPCW_quiz_CustomFeedback($quizID, $feedbackItem);
				echo $fb->generate_editForm();
			}
		}
	
	// ### 6 - Close holder
	printf('</div>');
	
	// ### 7 - Render a hidden form that's used for a placeholder.
	$fb = new WPCW_quiz_CustomFeedback($quizID, false);
	echo $fb->generate_editForm();
}





/**
 * Handle saving a feedback message to the database.
 * 
 * @param Integer $quizID The quiz for which the questions apply to. 
 */
function WPCW_showPage_customFeedback_processSave($quizID)
{
	global $wpdb, $wpcwdb;
	$wpdb->show_errors();
	
	$msgToSave     = array();
	$msgToSave_New = array();	
		
	// Check $_POST data for the 
	foreach ($_POST as $key => $value)
	{
		// ### 1) - Check if we're deleting a custom feedback message
		if (preg_match('/^delete_wpcw_qcfm_sgl_wrapper_([0-9]+)$/', $key, $matches))
		{
			// Delete the message from the message table
			$SQL = $wpdb->prepare("
				DELETE FROM $wpcwdb->quiz_feedback
				WHERE qfeedback_id = %d
			", $matches[1]);
						
			$wpdb->query($SQL);
		}
		
		// #### 2 - See if we have a custom feedback message to add or update
		// Checking for wpcw_qcfm_sgl_wrapper_1 or wpcw_qcfm_sgl_wrapper_new_message_1
		if (preg_match('/^wpcw_qcfm_sgl_summary(_new_message)?_([0-9]+)$/', $key, $matches))		
		{
			// Got the ID of the message we're updating or adding.
			$messageID = $matches[2];
			
			// Store the extra string if we're adding a new message.
			$newMessagePrefix = $matches[1]; 
			
			$fieldSuffix = $newMessagePrefix . '_' . $messageID;
			
			// Fetch each field we need that will be saved
			$messageFields = array(
				// Already have this, so not fetching from POST
				'qfeedback_quiz_id' 	=> $quizID,
			
				// Risk of slashes, hence removign them
				'qfeedback_summary' 	=> stripslashes(WPCW_arrays_getValue($_POST, 'wpcw_qcfm_sgl_' . 'summary' . $fieldSuffix)),
				'qfeedback_message' 	=> stripslashes(WPCW_arrays_getValue($_POST, 'wpcw_qcfm_sgl_' . 'message' . $fieldSuffix)),
			
				// Numbers
				'qfeedback_tag_id' 		=> intval(WPCW_arrays_getValue($_POST, 'wpcw_qcfm_sgl_' . 'tag' . $fieldSuffix)),
				'qfeedback_score_grade' => intval(WPCW_arrays_getValue($_POST, 'wpcw_qcfm_sgl_' . 'score_grade' . $fieldSuffix)),
								
				// Fixed-width strings
				'qfeedback_score_type' 	=> WPCW_arrays_getValue($_POST, 'wpcw_qcfm_sgl_' . 'score_type' . $fieldSuffix),
			);
			
			// Check we have a valid score type.
			if ('below' != $messageFields['qfeedback_score_type'] && 'above' != $messageFields['qfeedback_score_type']) {
				$messageFields['qfeedback_score_type'] = 'below';
			}

			
			// #### 3) - Not a new message - so add to list of new messages to add.
			if ($newMessagePrefix) {				
				$msgToSave_New[] = $messageFields;
			}
			
			// Existing message - so keep the message ID we have so we can update it.
			else 
			{
				$messageFields['qfeedback_id']	= $messageID;
				$msgToSave[] = $messageFields;
			}
		} // end of preg_match check
	} // each of $_POST foreach.
	
	// #### 4) Add new messages
	if (!empty($msgToSave_New))
	{
		foreach ($msgToSave_New as $messageDetails) { 
			$wpdb->query(arrayToSQLInsert($wpcwdb->quiz_feedback, $messageDetails));
		}
	}
	
	// #### 5) Update existing messages
	if (!empty($msgToSave))
	{
		foreach ($msgToSave as $messageDetails) { 
			$wpdb->query(arrayToSQLUpdate($wpcwdb->quiz_feedback, $messageDetails, 'qfeedback_id'));
		}
	}
}




/**
 * Function called before a quiz is being saved.
 * 
 * @param Array $originalFormValues The raw form values.
 * @param RecordsForm $thisObject The reference to the form object doing the saving. 
 */ 
function WPCW_actions_quizzes_beforeQuizSaved($originalFormValues, $thisObject)
{
	// Ensure if survey is selected, that no answers are set up.
	if ('survey' == $originalFormValues['quiz_type']) {	
		$originalFormValues['quiz_show_answers'] = 'no_answers';
	}
	
	// Ensure that if we're not in blocking mode, that we can't use the timer.
	if ('quiz_block' != $originalFormValues['quiz_type']) {	
		$originalFormValues['quiz_timer_mode'] = 'no_timer';
	}
	
	// Ensure if no timer is selected, that the time is set to a useful time other than 0.
	if ('no_timer' == $originalFormValues['quiz_timer_mode'] && intval($originalFormValues['quiz_timer_mode_limit']) <= 0) {	
		$originalFormValues['quiz_timer_mode_limit'] = '15';
	}
	
	return $originalFormValues;
}


/**
 * Function called after a quiz is being saved.
 *
 * @param Array $originalFormValues The form values after filtering that were used to save. 
 * @param Array $originalFormValues The raw form values that were unfiltered.
 * @param RecordsForm $thisObject The reference to the form object doing the saving. 
 */ 
function WPCW_actions_quizzes_afterQuizSaved($formValues, $originalFormValues, $thisObject)
{
	// Handle the saving of quiz questions
	WPCW_handler_questions_processSave($thisObject->primaryKeyValue);
	
	// Handle the saving of custom feedback
	WPCW_showPage_customFeedback_processSave($thisObject->primaryKeyValue);
}


/**
 * Fetch a list of the attempts that a user is allowed for completing a quiz.
 * @return Array The list of attempts as Counts => Names.
 */
function WPCW_quizzes_getAttemptList()
{
	$attemptList = array(
		'-1'	=> __('Intentos ilimitadaos', 'wp_courseware'),
		'1'		=> __('1 Intento', 'wp_courseware'),
	);
	
	for ($i = 2; $i <= 30; $i++) {
		$attemptList[$i] = sprintf(__('%d Intentos', 'wp_courseware'), $i);
	}
	
	return $attemptList;
}


?>