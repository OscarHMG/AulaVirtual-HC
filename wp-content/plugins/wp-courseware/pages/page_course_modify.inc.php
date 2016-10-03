<?php
/**
 * WP Courseware
 * 
 * Functions relating to modifying a course.
 */


/**
 * Function that allows a course to be created or edited.
 */
function WPCW_showPage_ModifyCourse_load() 
{
	$page = new PageBuilder(true);
	
	$courseDetails = false;
	$courseID = false;
	
	// Trying to edit a course	
	if (isset($_GET['course_id'])) 
	{
		$courseID 		= $_GET['course_id'] + 0;
		$courseDetails 	= WPCW_courses_getCourseDetails($courseID);
		
		// Abort if course not found.
		if (!$courseDetails)
		{
			$page->showPageHeader(__('Editar Curso', 'wp_courseware'), '75%', WPCW_icon_getPageIconURL());
			$page->showMessage(__('Lo siento, pero este curso no fue encontrado.', 'wp_courseware'), true);
			$page->showPageFooter();
			return;
		}
		
		// Editing a course, and it was found
		else 
		{
			$page->showPageHeader(__('Editar Curso', 'wp_courseware'), '75%', WPCW_icon_getPageIconURL());
			
			// Check user is allowed to edit this course.
			$canEditCourse = apply_filters('wpcw_back_permissions_user_can_edit_course', true, get_current_user_id(), $courseDetails);
			if (!$canEditCourse)
			{
				$page->showMessage(apply_filters('wpcw_back_msg_permissions_user_can_edit_course', __('Actualmente no tienes permiso para editar este curso.', 'wp_courseware'), get_current_user_id(), $courseDetails), true);
				$page->showPageFooter();
				return;
			}
		}
	}
	
	// Adding course
	else 
	{
		$page->showPageHeader(__('Agregar Curso', 'wp_courseware'), '75%', WPCW_icon_getPageIconURL());
		
		// Check user is allowed to add another course.
		$canAddCourse = apply_filters('wpcw_back_permissions_user_can_add_course', true, get_current_user_id());
		if (!$canAddCourse)
		{
			$page->showMessage(apply_filters('wpcw_back_msg_permissions_user_can_add_course', __('Actualmente no tienes permiso para agregar un nuevo curso.', 'wp_courseware'), get_current_user_id()), true);
			$page->showPageFooter();
			return;
		}
	}
	
	
	
	
	
	// We've requested a course tool. Do the checks here...
	if ($courseDetails && $action = WPCW_arrays_getValue($_GET, 'action'))
	{
		switch($action)	
		{
			// Tool - reset progress for all users.
			case 'reset_course_progress':
				
					// Get a list of all users on this course.
					global $wpdb, $wpcwdb;
					$userList = $wpdb->get_col($wpdb->prepare("
						SELECT user_id 
						FROM $wpcwdb->user_courses
						WHERE course_id = %d 
					", $courseDetails->course_id));
				
					$unitList = false;
					
					// Get all units for a course
					$courseMap = new WPCW_CourseMap(); 
					$courseMap->loadDetails_byCourseID($courseDetails->course_id);
					$unitList = $courseMap->getUnitIDList_forCourse();
					
					// Reset all users for this course.
					WPCW_users_resetProgress($userList, $unitList, $courseDetails, $courseMap->getUnitCount());
					
					// Confirm it's complete.
					$page->showMessage(__('Progreso del usuario para este curso se ha restablecido.', 'wp_courseware'));
				break;
				
			// Access changes
			case 'grant_access_users_all':
			case 'grant_access_users_admins':
					WPCW_showPage_ModifyCourse_courseAccess_runAccessChanges($page, $action, $courseDetails);
				break;
		}
		
		// Add a link back to editing, as we've hidden that panel.
		printf('<p><a href="%s?page=WPCW_showPage_ModifyCourse&course_id=%d" class="button button-secondary">%s</a></p>', 
			admin_url('admin.php'), $courseDetails->course_id, __('&laquo; Volver a la edici&oacute;n de los ajustes del curso', 'wp_courseware')
		);		
	}
	
	// No course tool here...
	else
		{
		
		global $wpcwdb;
		
		$formDetails = array(
			'break_course_general' => array(
					'type'  	=> 'break',
					'html'  	=> WPCW_forms_createBreakHTML_tab(false),
				),	
		
			'course_title' => array(
					'label' 	=> __('T&iacute;tulo del Curso', 'wp_courseware'),
					'type'  	=> 'text',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_title',
					'desc'  	=> __('El t&iacute;tulo del Curso.', 'wp_courseware'),
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 150,
						'minlen'	=> 1,
						'regexp'	=> '/^[^<>]+$/',
						'error'		=> __('Por favor, especifique un nombre para su curso, hasta un m&aacute;ximo de 150 caracteres, s&oacute;lo hay par&eacute;ntesis angulares (&lt; o &gt;). Sus participantes ser&aacute;n capaces de ver este t&iacute;tulo del curso.', 'wp_courseware')
					)	
				),				
	
			'course_desc' => array(
					'label' 	=> __('Descripci&oacute;n del Curso', 'wp_courseware'),
					'type'  	=> 'textarea',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_desc',
					'desc'  	=> __('Descripci&oacute;n de este curso. Sus participantes ser&aacute;n capaces de ver esta descripci&oacute;n del curso..', 'wp_courseware'),
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 5000,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limitar la descripci&oacute;n de su curso con 5000 caracteres.', 'wp_courseware')
					)	 	
				),
				
			/* Maybe useful in future - descoped for now.
			'course_overview_page' => array(
					'label' 	=> __('Course Overview Page', 'wp_courseware'),
					'type'  	=> 'select',
					'required'  => false,
					'desc'  	=> __('The page that links to the list of all modules for the course.', 'wp_courseware'),
					'data'	 	=> WPCW_pages_getPageList() 	 	
				),*/
	
			'course_opt_completion_wall' => array(
					'label' 	=> __('Cuando los usuarios puede ver la siguiente Unidad', 'wp_courseware'),
					'type'  	=> 'radio',
					'required'  => true,
					'desc'  	=> __('Puede un usuario ver todas las posibles unidades de curso O deben completar las unidades anteriores antes de ver la siguiente unidad.', 'wp_courseware'),
					'data'		=> array(
						'all_visible' => __('<b>Todas las Unidades visible</b> - Todas las unidades son visibles independientemente del progreso finalizaci&oacute;n.', 'wp_courseware'),
						'completion_wall' => __('<b>Completar Unidad/Para ver la Unidad siguiente</b> - S&oacute;lo se muestran las unidades que se han completado, adem&aacute;s la unidad siguiente que el usuario pueda iniciar.', 'wp_courseware')
					)	 	
				),	
				
			// ###ÊUser Access - Courses
			'break_course_access' => array(
					'type'  	=> 'break',
					'html'  	=> WPCW_forms_createBreakHTML_tab(),
				),	
				
			'course_opt_user_access' => array(
					'label' 	=> __('Conceder acceso a este curso', 'wp_courseware'),
					'type'  	=> 'radio',
					'required'  => true,
					'desc'  	=> __('Esta configuraci&oacute;n le permite establecer c&oacute;mo los usuarios pueden acceder a este curso. O bien se puede tener acceso de forma autom&aacute;tica tan pronto como se crea el usuario, o se les puede dar acceso manualmente. Siempre se puede quitar manualmente el acceso si lo desea.', 'wp_courseware'),
					'data'		=> array(
						'default_show' => __('<b>Autom&aacute;tico</b> - Todos los nuevos usuarios tendr&aacute;n acceso este curso.', 'wp_courseware'),
						'default_hide' => __('<b>Manual</b> - Los usuarios s&oacute;lo pueden acceder, si les concede el acceso.', 'wp_courseware')
					)	 	
				),	
				
				
			// ###ÊUser Messages - Modules
			'break_course_messages' => array(
					'type'  	=> 'break',
					'html'  	=> WPCW_forms_createBreakHTML_tab(),
				),			
				
			'course_message_unit_complete' => array(
					'label' 	=> __('Mensaje - Unidad Completa', 'wp_courseware'),
					'type'  	=> 'textarea',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_message',
					'desc'  	=> __('El mensaje que se muestra a un aprendiz, una vez que hayan <b>completado una unidad</b>, que se muestra en la parte inferior de la p&aacute;gina unidad. HTML es OK.', 'wp_courseware'),
					'rows'		=> 2,
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 500,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limite el mensaje con 500 caracteres.', 'wp_courseware')
					)	 	
				),		
				
			'course_message_course_complete' => array(
					'label' 	=> __('Mensaje - Curso Completo', 'wp_courseware'),
					'type'  	=> 'textarea',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_message',
					'desc'  	=> __('El mensaje que se muestra a un aprendiz, una vez que hayan <b>completado todo el curso</b>, que se muestra en la parte inferior de la p&aacute;gina unidad. HTML es OK.', 'wp_courseware'),
					'rows'		=> 2,
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 500,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limite el mensaje a 500 caracteres.', 'wp_courseware')
					)	 	
				),
	
			'course_message_unit_pending' => array(
					'label' 	=> __('Mensaje - Unidad Pendiente', 'wp_courseware'),
					'type'  	=> 'textarea',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_message',
					'desc'  	=> __('El mensaje que se muestra a un aprendiz cuando a&uacute;n <b>tiene que completar una unidad</b>. Este mensaje se muestra en la parte inferior de la p&aacute;gina de la unidad, junto con un bot&oacute;n que dice "Marque esta Unidad de como COMPLETA". HTML es OK.', 'wp_courseware'),
					'rows'		=> 2,
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 500,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limite el mensaje a 500 caracteres.', 'wp_courseware')
					)	 	
				),	
				
			'course_message_unit_no_access' => array(
					'label' 	=> __('Mensaje - Acceso Denegado', 'wp_courseware'),
					'type'  	=> 'textarea',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_message',
					'desc'  	=> __('El mensaje que se muestra a un aprendiz que <b>no puede acceder a una unidad</b>, porque no se les permite <b>acceder el curso</b>.', 'wp_courseware'),
					'rows'		=> 2,
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 500,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limite el mensaje a 500 caracteres.', 'wp_courseware')
					)	 	
				),	
	
			'course_message_unit_not_yet' => array(
					'label' 	=> __('Mensaje - No disponible a&uacute;n', 'wp_courseware'),
					'type'  	=> 'textarea',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_message',
					'desc'  	=> __('El mensaje que se muestra a un aprendiz que <b>no puede acceder a una unidad</b>,  debido  que necesita completar una unidad anterior.', 'wp_courseware'),
					'rows'		=> 2,
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 500,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limite el mensaje a 500 caracteres.', 'wp_courseware')
					)	 	
				),	
	
			'course_message_unit_not_logged_in' => array(
					'label' 	=> __('Mensaje - Sin iniciar sesi&oacute;n', 'wp_courseware'),
					'type'  	=> 'textarea',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_message',
					'desc'  	=> __('El mensaje que se muestra a un aprendiz que <b>no est&aacute; en el sistema</b>, y por lo tanto no puede acceder a la unidad.', 'wp_courseware'),
					'rows'		=> 2,
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 500,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limite el mensaje a 500 caracteres.', 'wp_courseware')
					)	 	
				),			
				
			'course_message_quiz_open_grading_blocking' => array(
					'label' 	=> __('Mensaje - Evaluaci&oacute;n enviada - con bloqueo', 'wp_courseware'),
					'type'  	=> 'textarea',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_message',
					'desc'  	=> __('El mensaje que se muestra a un alumno cuando ha enviado una evaluacion. <b>Necesita que el instructor se la califique</b>, y podra <b>continuar</b>.', 'wp_courseware'),
					'rows'		=> 2,
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 500,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limite el mensaje a 500 caracteres.', 'wp_courseware')
					)	 	
				),	
				
			'course_message_quiz_open_grading_non_blocking' => array(
					'label' 	=> __('Mensaje - Evaluaci&oacute;n enviada - Sin bloqueo', 'wp_courseware'),
					'type'  	=> 'textarea',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_message',
					'desc'  	=> __('El mensaje que se muestra a un aprendiz que ha presentado una respuesta a una <b>pregunta abierta o cargada</b>, necesita que se la califiquen, pero puede <b>continuar de todos modos.</b.>', 'wp_courseware'),
					'rows'		=> 2,
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 500,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limite el mensaje a 500 caracteres.', 'wp_courseware')
					)	 	
				),	
				
				
	
			// ###ÊUser Notifications - From Email Address details
			'break_course_notifications_from_details' => array(
					'type'  	=> 'break',
					'html'  	=> WPCW_forms_createBreakHTML_tab(),
				),		
	
			'course_from_email' => array(
					'label' 	=> __('Direcci&oacute;n de correo electr&oacute;nico', 'wp_courseware'),
					'type'  	=> 'text',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_email',
					'desc'  	=> __('La direcci&oacute;n de correo electr&oacute;nico que se notificar&aacute;.<br/>Depende de su  protecci&oacuten de spam de su servidor, este correo podr&iacute;a no aparecer  en su bandeja de entrada.', 'wp_courseware'),
					'validate'	 	=> array(
						'type'		=> 'email',
						'maxlen'	=> 150,
						'minlen'	=> 1,
						'error'		=> __('Por favor, introduce una direcci&oacute;n de correo electr&oacute;nico v&aacute;lida', 'wp_courseware')
					)	
				),		
				
			'course_from_name' => array(
					'label' 	=> __('Nombre del correo electr&oacute;nico', 'wp_courseware'),
					'type'  	=> 'text',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_email',
					'desc'  	=> __('El nombre utilizado en las notificaciones por correo electr&oacute;nico, que se env&iacute;an a usted y sus alumnos. <br/>Depende de su  protecci&oacute;n de spam de su servidor, esto no puede aparecer en los correos electr&oacute;nicos salientes.', 'wp_courseware'),
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 150,
						'minlen'	=> 1,
						'regexp'	=> '/^[^<>]+$/',
						'error'		=> __('Por favor, especificar un nombre de hasta un m&aacute;ximo de 150 caracteres, s&oacute;lo hay par&eacute;ntesis angulares (&lt; or &gt;).', 'wp_courseware')			
					)	
				),	
	
			'course_to_email' => array(
					'label' 	=> __('Notificar a la direcci&oacute;n de correo electr&oacute;nico del administrador', 'wp_courseware'),
					'type'  	=> 'text',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_email',
					'desc'  	=> __('La direcci&oacute;n de correo electr&oacute;nico para enviar notificaciones al administrador.', 'wp_courseware'),
					'validate'	 	=> array(
						'type'		=> 'email',
						'maxlen'	=> 150,
						'minlen'	=> 1,
						'error'		=> __('Por favor, introduce una direcci&oacute;n de correo electr&oacute;nico v&aacute;lida.', 'wp_courseware')
					)	
				),	
			
			// ###ÊUser Notifications - Modules
			'break_course_notifications_user_module' => array(
					'type'  	=> 'break',
					'html'  	=> WPCW_forms_createBreakHTML_tab(),
				),
				
			'email_complete_module_option_admin' => array(
					'label' 	=> __('M&oacute;dulo completo - Notificarme?', 'wp_courseware'),
					'type'  	=> 'radio',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_email_template_option',
					'data'	 	=> array(
						'send_email'	=> __('<b>Enviarme un correo</b> - cuando uno de sus alumnos ha completado un m&oacute;dulo.', 'wp_courseware'),
						'no_email'	=> __('<b>No enviarme un correo</b> - cuando uno de sus alumnos ha completado un m&oacute;dulo.', 'wp_courseware')
					)
				),				
				
			'email_complete_module_option' => array(
					'label' 	=> __('M&oacute;dulo completo - Notificar al usuario?', 'wp_courseware'),
					'type'  	=> 'radio',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_email_template_option',
					'data'	 	=> array(
						'send_email'	=> __('<b>Enviar correo electr&oacute;nico</b> - para usuarios que han terminado su m&oacute;dulo.', 'wp_courseware'),
						'no_email'	=> __('<b>No enviar correo electr&oacute;nico</b> - para usuarios que han terminado su m&oacute;dulo.', 'wp_courseware')
					)
				),
				
			'email_complete_module_subject' => array(
					'label' 	=> __('M&oacute;dulo completo - Asunto del correo', 'wp_courseware'),
					'type'  	=> 'textarea',				
					'required'  => false,
					'cssclass'	=> 'wpcw_course_email_template_subject',
					'rows'		=> 2,
					'desc'  	=> __('La <b>linea del asunto</b> para un correo electr&oacute;nico cuando se complete un <b>m&oacute;dulo</b>.', 'wp_courseware'),
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 300,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limite el asunto del correo electr&oacute;nico con 300 caracteres.', 'wp_courseware')
					)	 	
				),		
							
			'email_complete_module_body' => array(
					'label' 	=> __('M&oacute;dulo completo - Cuerpo del correo', 'wp_courseware'),
					'type'  	=> 'textarea',
					'required'  => false,
					'cssclass'	=> 'wpcw_course_email_template',
					'desc'  	=> __('El cuerpo de la plantilla para el correo electr&oacute;nico enviado a un usuario cuando se completa un m&oacute;dulo.', 'wp_courseware'),
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 5000,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limite el cuerpo del correo electr&oacute;nico a 5000 con caracteres.', 'wp_courseware')
					)	 	
				),	
				
			// ###ÊUser Notifications - Courses			
			'break_course_notifications_user_course' => array(
					'type'  	=> 'break',
					'html'  	=> WPCW_forms_createBreakHTML_tab(),
				),
				
			'email_complete_course_option_admin' => array(
					'label' 	=> __('Curso completo - Notificarme?', 'wp_courseware'),
					'type'  	=> 'radio',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_email_template_option',
					'data'	 	=> array(
						'send_email'	=> __('<b>Enviarme un correo electr&oacute;nico</b> - cuando uno de sus alumnos ha completado todo el curso..', 'wp_courseware'),
						'no_email'	=> __('<b>No enviarme un correo electr&oacute;nico</b> - cuando uno de sus alumnos ha completado todo el curso.', 'wp_courseware')
					)
				),				
				
			'email_complete_course_option' => array(
					'label' 	=> __('Curso completo - Notificar al usuario?', 'wp_courseware'),
					'type'  	=> 'radio',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_email_template_option',
					'data'	 	=> array(
						'send_email'	=> __('<b>Enviar correo electr&oacute;nico</b> - al usuario cuando ha completado todo el curso', 'wp_courseware'),
						'no_email'	=> __('<b>No enviar correo electr&oacute;nico</b> - al usuario cuando ha completado todo el curso.', 'wp_courseware')
					)
				),
				
			'email_complete_course_subject' => array(
					'label' 	=> __('Curso completo - Asunto del correo electr&oacute;nico', 'wp_courseware'),
					'type'  	=> 'textarea',
					'required'  => false,
					'cssclass'	=> 'wpcw_course_email_template_subject',
					'rows'		=> 2,
					'desc'  	=> __('La <b>linea del asunto</b> para un correo electr&oacute;nico cuando se complete un <b>curso</b>..', 'wp_courseware'),
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 300,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limite el asunto del correo electr&oacute;nico con 300 caracteres.', 'wp_courseware')
					)	 	
				),		
							
			'email_complete_course_body' => array(
					'label' 	=> __('Curso completo - Cuerpo del correo electr&oacute;nico', 'wp_courseware'),
					'type'  	=> 'textarea',
					'required'  => false,
					'cssclass'	=> 'wpcw_course_email_template',
					'desc'  	=> __('El cuerpo de la plantilla para el correo electr&oacute;nico enviado a un usuario cuando completa un curso.', 'wp_courseware'),
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 5000,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limite el cuerpo del correo electr&oacute;nico a 5000 caracteres.', 'wp_courseware')
					)	 	
				),	
	
			// ###ÊUser Notifications - Quiz Grades			
			'break_course_notifications_user_grades' => array(
					'type'  	=> 'break',
					'html'  	=> WPCW_forms_createBreakHTML_tab(),
				),				
				
			'email_quiz_grade_option' => array(
					'label' 	=> __('Evaluaci&oacute;n del curso - Notificar al usuario?', 'wp_courseware'),
					'type'  	=> 'radio',
					'required'  => true,
					'cssclass'	=> 'wpcw_course_email_template_option',
					'data'	 	=> array(
						'send_email'	=> __('<b>Enviar correo electr&oacute;nico</b> ', 'wp_courseware'),
						'no_email'		=> __('<b>No enviar correo electr&oacute;nico</b> ', 'wp_courseware')
					),
				),
				
			'email_quiz_grade_subject' => array(
					'label' 	=> __('Evaluaci&oacute;n del curso - Asunto del correo electr&oacute;nico', 'wp_courseware'),
					'type'  	=> 'textarea',
					'required'  => false,
					'cssclass'	=> 'wpcw_course_email_template_subject',
					'rows'		=> 2,
					'desc'  	=> __('La <b>linea del asunto</b> para un correo electr&oacute;nico cuando se complete una <b>evaluaci&oacute;n</b>.', 'wp_courseware'),
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 300,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limite es de 300 caracteres.', 'wp_courseware')
					)	 	
				),		
							
			'email_quiz_grade_body' => array(
					'label' 	=> __('Evaluaci&oacute;n del curso - Cuerpo del correo electr&oacute;nico', 'wp_courseware'),
					'type'  	=> 'textarea',
					'required'  => false,
					'cssclass'	=> 'wpcw_course_email_template',
					'desc'  	=> __('El cuerpo de la plantilla para el correo electr&oacute;nico enviado a un usuario cuando completa una evaluaci&oacute;n.', 'wp_courseware'),
					'rows'		=> 20,
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 5000,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limite es de 5000 caracteres.', 'wp_courseware')
					)	 	
				),		
	
			// ###ÊUser Notifications - Final Summary Email			
			'break_course_notifications_user_final' => array(
					'type'  	=> 'break',
					'html'  	=> WPCW_forms_createBreakHTML_tab(),
				),				
				
			'email_complete_course_grade_summary_subject' => array(
					'label' 	=> __('Resumen Final - Asunto del correo electr&oacute;nico', 'wp_courseware'),
					'type'  	=> 'textarea',
					'required'  => false,
					'cssclass'	=> 'wpcw_course_email_template_subject',
					'rows'		=> 2,
					'desc'  	=> __('La <b>linea del asunto</b> para un correo electr&oacute;nico cuando se complete una <b>evaluaci&oacute;n final</b>.', 'wp_courseware'),
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 300,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limite es de 300 caracteres.', 'wp_courseware')
					)	 	
				),		
							
			'email_complete_course_grade_summary_body' => array(
					'label' 	=> __('Resumen Final - Cuerpo del correo electr&oacute;nico', 'wp_courseware'),
					'type'  	=> 'textarea',
					'required'  => false,
					'rows'		=> 20,
					'cssclass'	=> 'wpcw_course_email_template',
					'desc'  	=> __('El cuerpo de la plantilla para el correo electr&oacute;nico enviado a un usuario cuando completa una evaluaci&oacute;n final.', 'wp_courseware'),
					'validate'	 	=> array(
						'type'		=> 'string',
						'maxlen'	=> 5000,
						'minlen'	=> 1,
						'error'		=> __('Por favor, limite es de 5000 caracteres.', 'wp_courseware')
					)	 	
				),	
				
				
			// ###ÊCertificates - Courses			
			'break_course_certificates_user_course' => array(
					'type'  	=> 'break',
					'html'  	=> WPCW_forms_createBreakHTML_tab(),
				),
				
			'course_opt_use_certificate' => array(
					'label' 	=> __('Habilitar los certificados?', 'wp_courseware'),
					'type'  	=> 'radio',
					'required'  => true,
					'data'	 	=> array(
						'use_certs'	=> __('<b>Si</b> - Genera un certificado PDF al usuario complete este curso.','wp_courseware'),
						'no_certs'	=> __('<b>No</b> - No genera un certificado PDF al usuario complete este curso.','wp_courseware')
					)
				),

			// ###ÊCourse Tools			
			'break_course_certificates_user_tools' => array(
					'type'  	=> 'break',
					'html'  	=> WPCW_forms_createBreakHTML_tab(),
				),
				
			'course_tools_reset_all_users' => array(
					'label' 	=> __('Restablecer el Progreso de los usuarios para este curso', 'wp_courseware'),
					'type'  	=> 'custom',
					'html'		=> sprintf('<a href="%s?page=WPCW_showPage_ModifyCourse&course_id=%d&action=reset_course_progress" class="button-primary" id="wpcw_course_btn_progress_reset_whole_course">%s</a><p>%s</p>',
									admin_url('admin.php'), $courseID,
									__('Restablecer a todos los usuarios al inicio de este curso', 'wp_courseware'), 
									__('Este bot&oacute;n restablecer&aacute; todos los usuarios que han accedido a este curso. Esto elimina todos los datos de los cursos tambi&eacute;n.', 'wp_courseware')
								)
					),	
					
			'course_tools_user_access' => array(
					'label' 	=> __('Acceso masivo a este curso', 'wp_courseware'),
					'type'  	=> 'custom',
					'html'		=> sprintf('<a href="%s?page=WPCW_showPage_ModifyCourse&course_id=%d&action=grant_access_users_all" class="button-primary" id="wpcw_course_btn_access_all_existing_users">%s</a>&nbsp;&nbsp;
										    <a href="%s?page=WPCW_showPage_ModifyCourse&course_id=%d&action=grant_access_users_admins" class="button-primary" id="wpcw_course_btn_access_all_existing_admins">%s</a> 
										    <p>%s</p>',
									admin_url('admin.php'), $courseID,									
									__('Todos los usuarios existentes (incluidos los administradores)', 'wp_courseware'), 
									
									admin_url('admin.php'), $courseID,									
									__('S&oacute;lo los administradores que existen', 'wp_courseware'),
									
									__('Puede utilizar los botones de arriba para conceder a todos los usuarios el acceso a este curso. Dependiendo del n&uacute;mero de usuarios que tiene, esto puede ser un proceso lento.', 'wp_courseware')
								)
					),	
		);
		
		
		
		// Generate the tabs.
		$tabList = array( 
			'break_course_general' 						=> array('label' => __('Detalles Generales del Curso', 'wp_courseware')), 
			'break_course_access' 						=> array('label' => __('Acceso de Usuario', 'wp_courseware')), 
			'break_course_messages' 					=> array('label' => __('Mensaje de Usario', 'wp_courseware')),
			'break_course_notifications_from_details' 	=> array('label' => __('Detalles de la direcci&oacute;n de correo', 'wp_courseware')),
			'break_course_notifications_user_module' 	=> array('label' => __('Notificaciones de Correos - M&oacute;dulos', 'wp_courseware')),
			'break_course_notifications_user_course' 	=> array('label' => __('Notificaciones de Correos - Curso', 'wp_courseware')),
			'break_course_notifications_user_grades' 	=> array('label' => __('Notificaciones de Correos - Evaluaci&oacute;nes del Curso', 'wp_courseware')),
			'break_course_notifications_user_final' 	=> array('label' => __('Notificaciones de Correos - Promedios del curso', 'wp_courseware')),
			'break_course_certificates_user_course' 	=> array('label' =>  __('Certificados', 'wp_courseware')),
			'break_course_certificates_user_tools' 		=> array('label' => __('Herramientas de acceso a un curso', 'wp_courseware')),
		);
		
		// Remove reset fields if not appropriate.
		if (!$courseDetails)
		{
			// The tab
			unset($tabList['break_course_certificates_user_tools']);
			
			// The tool
			unset($formDetails['break_course_certificates_user_tools']);
			unset($formDetails['course_tools_reset_all_users']);
		}
		
		
		$form = new RecordsForm(
			$formDetails,			// List of form elements
			$wpcwdb->courses, 		// Table for main details
			'course_id', 			// Primary key column name
			false, 
			'wpcw_course_settings'
		);
		
		$form->customFormErrorMsg = __('Lo sentimos, pero por desgracia hab&iacute;a algunos errores al guardar los detalles del curso. Por favor corrija los errores e int&eacute;ntelo de nuevo.', 'wp_courseware');
		$form->setAllTranslationStrings(WPCW_forms_getTranslationStrings());
	
		// Set defaults if adding a new course
		if (!$courseDetails)
		{
			$form->loadDefaults(array(
			
				// Add basic Email Template to defaults when creating a new course.
				'email_complete_module_subject'					=> EMAIL_TEMPLATE_COMPLETE_MODULE_SUBJECT,
				'email_complete_course_subject'					=> EMAIL_TEMPLATE_COMPLETE_COURSE_SUBJECT,
				'email_quiz_grade_subject'						=> EMAIL_TEMPLATE_QUIZ_GRADE_SUBJECT,
				'email_complete_course_grade_summary_subject'	=> EMAIL_TEMPLATE_COURSE_SUMMARY_WITH_GRADE_SUBJECT,
			
				// Email bodies
				'email_complete_module_body'				=> EMAIL_TEMPLATE_COMPLETE_MODULE_BODY,
				'email_complete_course_body'				=> EMAIL_TEMPLATE_COMPLETE_COURSE_BODY,
				'email_quiz_grade_body'						=> EMAIL_TEMPLATE_QUIZ_GRADE_BODY,
				'email_complete_course_grade_summary_body'	=> EMAIL_TEMPLATE_COURSE_SUMMARY_WITH_GRADE_BODY,
			
				// Email address details
				'course_from_name'							=> get_bloginfo('name'),
				'course_from_email'							=> get_bloginfo('admin_email'),
				'course_to_email'							=> get_bloginfo('admin_email'),
			
				// Completion wall default (blocking mode)			
				'course_opt_completion_wall'				=> 'completion_wall',
				'course_opt_user_access'					=> 'default_hide',
			
				// Email notification defaults (yes to send email)
				'email_complete_course_option_admin'		=> 'no_email',
				'email_complete_course_option'				=> 'no_email',
				'email_complete_module_option_admin'		=> 'no_email',
				'email_complete_module_option'				=> 'no_email',
				'email_quiz_grade_option'					=> 'no_email',
						
				// Certificate defaults
				'course_opt_use_certificate'				=> 'no_certs',
			
				// User Messages
				'course_message_unit_not_yet'				=> __("Es necesario completar la unidad anterior.", 'wp_courseware'),		
				'course_message_unit_pending'				=> __("Ha completado esta unidad. A continuaci&oacute;n, marque esta unidad como completada.", 'wp_courseware'),			
				'course_message_unit_complete'				=> __("Ahora ha completado esta Unidad.", 'wp_courseware'),
				'course_message_course_complete'			=> __("FELICITACIONES, has completado el curso y puedes generar tu certificado, pero antes  VERIFICA tus datos personales.", 'wp_courseware'),
				'course_message_unit_no_access'				=> __("Lo sentimos, pero no se le permite acceder a este curso.", 'wp_courseware'),
				'course_message_unit_not_logged_in'			=> __('No se puede ver esta unidad, ya que no est&aacute; conectado todav&iacute;a.', 'wp_courseware'),
			
				// User Messages - quizzes
				'course_message_quiz_open_grading_blocking'		=> __('Su Evaluaci&oacute;n se ha enviado para ser calificada por su instructor.  Una vez que sea calificada, usted podr&aacute; ingresar a la siguiente Unidad.
', 'wp_courseware'),
				'course_message_quiz_open_grading_non_blocking'	=> __('Su Evaluaci&oacute;n se ha enviado para ser calificada por su instructor.  Ahora ha completado esta Unidad.', 'wp_courseware'),
			));
		}
		
		// Useful place to go
		$directionMsg = '<br/></br>' . sprintf(__('Do you want to return to the <a href="%s">course summary page</a>?', 'wp_courseware'),
			admin_url('admin.php?page=WPCW_wp_courseware')
		);	
		
		// Override success messages
		$form->msg_record_created = __('Los detalles del curso se crearon con exito. ', 'wp_courseware') . $directionMsg;
		$form->msg_record_updated = __('Los detalles del curso se han actualizado correctamente. ', 'wp_courseware') . $directionMsg;
	
		
		$form->setPrimaryKeyValue($courseID);	
		$form->setSaveButtonLabel(__('Guardar todos los detalles', 'wp_courseware'));
		
	
		// Process form	
		$formHTML = $form->getHTML();
	
	
			
		// Show message about this course having quizzes that require a pass mark.
		// Need updated details for this.
		$courseDetails = WPCW_courses_getCourseDetails($courseID);
		if ($courseDetails && $courseDetails->course_opt_completion_wall == 'all_visible')
		{
			$quizzes = WPCW_quizzes_getAllBlockingQuizzesForCourse($courseDetails->course_id);
			
			// Count how many blocking quizzes there are.
			if ($quizzes && count($quizzes) > 0) {
				$quizCountMessage = sprintf(__('Currently <b>%d of your quizzes</b> are blocking process based on a percentage score <b>in this course</b>.', 'wp_courseware'), count($quizzes));
			} else {
				$quizCountMessage = __('Actualmente no tiene ninguna secci&oacute;n de evaluaci&oacute;n para este curso.', 'wp_courseware');
			}
				
			printf('<div id="message" class="wpcw_msg_info wpcw_msg"><b>%s</b> - %s<br/><br/>
					%s				
					</div>', 
				__('Important Note', 'wp_courseware'),
				__('You have selected <b>All Units Visible</b>. If you create a quiz blocking progress based on a percentage score, students will have access to the entire course regardless of quiz score.', 'wp_courseware'),
				$quizCountMessage
			);
							
		}
		
		// Generate the tabs
		echo WPCW_tabs_generateTabHeader($tabList, 'wpcw_courses_tabs', false);

		// Show the form
		echo $formHTML;
		echo '</div>'; // .wpcw_tab_wrapper
		
	} // end if not doing a tool manipulation.		
	
	
	$page->showPageMiddle('20%');
	
		
	// Include a link to delete the course
	if ($courseDetails) 	
	{
		$page->openPane('wpcw-deletion-course', __('Eliminar este curso?', 'wp_courseware'));
			WPCW_showPage_ModifyCourse_deleteCourseButton($courseDetails);		
		$page->closePane();
	}	
	
	// Email template tags here...
	$page->openPane('wpcw_docs_email_tags', __('Plantilla de correo electr&oacute;nico', 'wp_courseware'));
	
	printf('<h4 class="wpcw_docs_side_mini_hdr">%s</h4>', __('Todas las notificaciones por correo electr&oacute;nico', 'wp_courseware'));
	printf('<dl class="wpcw_email_tags">');
		
		printf('<dt>{USER_NAME}</dt><dd>%s</dd>', 		__('El nombre del usuario', 'wp_courseware'));
		
		printf('<dt>{SITE_NAME}</dt><dd>%s</dd>', 		__('El nombre del sitio web.', 'wp_courseware'));
		printf('<dt>{SITE_URL}</dt><dd>%s</dd>', 		__('El url del sitio web.', 'wp_courseware'));
		
		printf('<dt>{COURSE_TITLE}</dt><dd>%s</dd>', 	__('El t&iacute;tulo del curso para la unidad que acaba de ser completado.', 'wp_courseware'));
		printf('<dt>{MODULE_TITLE}</dt><dd>%s</dd>', 	__('El t&iacute;tulo del m6oacute; para la unidad que acaba de ser completado.', 'wp_courseware'));
		printf('<dt>{MODULE_NUMBER}</dt><dd>%s</dd>', 	__('El numero del m6oacute; para la unidad que acaba de ser completado.', 'wp_courseware'));
		
		printf('<dt>{CERTIFICATE_LINK}</dt><dd>%s</dd>', __('Si el curso tiene habilitado los certificados PDF, este es el enlace del certificado PDF. (Si no tiene certificado o los certificados no est&aacute;n habilitados, simplemente esto estar&aacute; en blanco).', 'wp_courseware'));
		
	printf('</dl>');
	
	printf('<h4 class="wpcw_docs_side_mini_hdr">%s</h4>', __('Notificaciones de evaluaciones por correo', 'wp_courseware'));
	printf('<dl class="wpcw_email_tags">');
		printf('<dt>{QUIZ_TITLE}</dt><dd>%s</dd>', 			__('El t&iacute;tulo de la evaluaci&oacute;n que ha sido calificado.', 'wp_courseware'));
		printf('<dt>{QUIZ_GRADE}</dt><dd>%s</dd>', 			__('El porcentaje global de grado para un concurso.', 'wp_courseware'));
		printf('<dt>{QUIZ_GRADES_BY_TAG}</dt><dd>%s</dd>', 	__('Incluye un desglose de las puntuaciones por etiqueta si est&aacute; disponible.', 'wp_courseware'));
		printf('<dt>{QUIZ_TIME}</dt><dd>%s</dd>', 			__('Si se program&oacute; el cuestionario, muestra el tiempo utilizado para completar el cuestionario.', 'wp_courseware'));
		printf('<dt>{QUIZ_ATTEMPTS}</dt><dd>%s</dd>', 		__('Indica el n&uacute;mero de intentos para resolver el cuestionario.', 'wp_courseware'));
		printf('<dt>{CUSTOM_FEEDBACK}</dt><dd>%s</dd>', 	__('Incluye todos los mensajes de informaci&oacute;n personalizada que se han desencadenado en base a los resultados espec&iacute;ficos del usuario en el cuestionario.', 'wp_courseware'));
		printf('<dt>{QUIZ_RESULT_DETAIL}</dt><dd>%s</dd>', 	__('Cualquier informaci&oacute;n opcional relacionada con el resultado de la evaluaci&oacute;n, por ejemplo, informaci&oacute;n acerca de volver a tomar el examen.', 'wp_courseware'));
		printf('<dt>{UNIT_TITLE}</dt><dd>%s</dd>', 			__('El t&iacute;tulo de la unidad que est&aacute; asociada con el cuestionario.', 'wp_courseware'));
		printf('<dt>{UNIT_URL}</dt><dd>%s</dd>', 			__('La URL de la unidad que est&aacute; asociada con el cuestionario.', 'wp_courseware'));
	printf('</dl>');
	
	printf('<h4 class="wpcw_docs_side_mini_hdr">%s</h4>', __('Notificaciones del resumen final ', 'wp_courseware'));
	printf('<dl class="wpcw_email_tags">');
		printf('<dt>{CUMULATIVE_GRADE}</dt><dd>%s</dd>', 	__('La calificaci&oacute;n global acumulado que el usuario ha marcado de completar todas las evaluaci&oacute;nes en el curso.', 'wp_courseware'));
		printf('<dt>{QUIZ_SUMMARY}</dt><dd>%s</dd>', 		__('El resumen de cada evaluaci&oacute;n, y lo que el usuario anot&oacute; en cada uno.', 'wp_courseware'));
	printf('</dl>');
	
	
	$page->showPageFooter();
}



/**
 * Handles showing the delete course button on the course modification page.
 */
function WPCW_showPage_ModifyCourse_deleteCourseButton($courseDetails)
{
	$html = false;
	
	// Generate the URL that will handle the deletion for this course. Using the ID in the GET URL just in case the deletion fails.
	$html .= sprintf('<form method="POST" action="%s&action=delete_course&course_id=%d" id="wpcw_course_settings_delete_course">', admin_url('admin.php?page=WPCW_wp_courseware'), $courseDetails->course_id);
	
		// Radio option selection
		$html .= '<div class="wpcw_form_delete_options">';
			$html .= sprintf('<label><input type="radio" name="delete_course_type" value="course_and_module"/> %s <div class="wpcw_form_delete_options_desc">%s</div></label>', 
					__('S&oacute;lo configuraci&oacute;n del curso y el m&oacute;dulo', 'wp_courseware'),
					__('No se eliminar&aacute;n unidades y evaluaci&oacute;n, sino que simplemente se desvincularon del curso.', 'wp_courseware')
				);
				
			$html .= sprintf('<label><input type="radio" name="delete_course_type" value="complete" checked/> %s <div class="wpcw_form_delete_options_desc">%s</div></label>', 
					__('Eliminar todo', 'wp_courseware'),
					__('Esta opci&oacute;n eliminar&aacute; el curso, los m&oacute;dulos, todas las unidades y todos las evaluaciones.', 'wp_courseware')
				);
		$html .= '</div>';
	
		// Submit
		$html .= sprintf('<input type="submit" value="%s" class="button-primary wpcw_delete_item" title="%s" />', 
			__('Eliminar este curso', 'wp_courseware'),
			__("Seguro que quieres eliminar el este curso?\n\nEsto no se puede deshacer!", 'wp_courseware')
		); 	
	$html .= '</form>'; 
	
	echo $html;
} 



/**
 * Run the changes for the course access change.
 * @param Object $page The current page object for messages.
 * @param String $action The action that's been requested.
 * @param Object $userDetails The details of this course.
 */
function WPCW_showPage_ModifyCourse_courseAccess_runAccessChanges($page, $action, $courseDetails)
{
	$args = array(
		// No defaults actually, get_users() gets all users by default.
	);
	
	switch ($action)
	{
		case 'grant_access_users_all':
				$userType = false;
			break;
			
		case 'grant_access_users_admins':
				$args['role'] = 'administrator';
				$userType = __('admin', 'wp_courseware');
			break;
			
		default:
			$page->showMessage(__('Unknown access change was requested.', 'wp_courseware'), true);
			return;
			break;
	}
	
	// Kick of message to show we've started.
	WPCW_messages_showProgress(sprintf(__('Requesting a list of <b>all %s users</b> to update... (this make take a while)...', 'wp_courseware'), $userType), 0);
	$userList = get_users($args);
	
	// Report how many users we have to process.
	if (!empty($userList))
	{
		$userCount = count($userList);
		WPCW_messages_showProgress(sprintf(__('Found %d user(s), so now starting to add them to this course...', 'wp_courseware'), $userCount), 1);
		
		global $wpdb, $wpcwdb;
		$count = 0;
		
		// Each user has 2 DB accesses to update, so this may take a while.
		foreach ($userList as $userDetails)
		{
			
			WPCW_messages_showProgress(sprintf(__('Processing <b>%s</b>... ', 'wp_courseware'), $userDetails->data->user_login, $userDetails->data->display_name), 2);
			
			// See if the user already exists for this course.
			$entryExists = $wpdb->get_row($wpdb->prepare("
				SELECT * 
				 FROM $wpcwdb->user_courses 
				WHERE user_id = %d 
				  AND course_id = %d
				 ", $userDetails->ID, $courseDetails->course_id));
			
			// They already exist, nothing to do.
			if ($entryExists)
			{
				WPCW_messages_showProgress(__('User can already access this course. Skipping.', 'wp_courseware'), 3);
			}
			
			// Adding the user
			else
			{
				$wpdb->query($wpdb->prepare("
				INSERT INTO $wpcwdb->user_courses
				(user_id, course_id, course_progress, course_final_grade_sent) 
				VALUES(%d, %d, 0, '')
				 ", $userDetails->ID, $courseDetails->course_id));
				
				WPCW_messages_showProgress(__('Added.', 'wp_courseware'), 3);
			}
			
			$count++;
			WPCW_messages_showProgress(sprintf(__('Done. %.1f%% complete.', 'wp_courseware'), ($count/$userCount) * 100), 3);
		}
	}
	
	else {
		WPCW_messages_showProgress(__('No users found. Nothing to do.', 'wp_courseware'), 1);
	}
	
	WPCW_messages_showProgress('<b>' . __('All done.', 'wp_courseware') . '</b>', 0);
}



/**
 * Show user progress.
 * 
 * @param String $message The message to show.
 * @param Integer $indentLevel A number representing how many indent levels to add.
 */
function WPCW_messages_showProgress($message, $indentLevel)
{
	printf('<div class="wpcw_msg_progress wpcw_msg_progress_indent_%d">%s</div>', $indentLevel, $message);
	flush();
}


/**
 * Get a list of pages, with heirarchy, set as ID => Page Title in an array.
 * @return Array The page list as an array.
 */
function WPCW_pages_getPageList()
{
	$args= array(
		'echo' => 0 
	);
 
	// Find all values and options, and return as an array of IDs to Page Title with indents.
	if (preg_match_all('/<option(.+?)value="(.+?)">(.+?)<\/option>/i', wp_dropdown_pages($args), $matches)) 
	{
		$blank = array('' => __('---- No Page Selected ----', 'wp_courseware'));
		
		return array_merge($blank, array_combine ($matches[2], $matches[3]));
	}
	return false;
}

/**
 * Gets a list of all blocking courses for the specified course ID.
 * @param Integer $courseID The ID of the course to search.
 * @return Array A list of blocking quizzes for the specified course ID (or false if there are none).
 */
function WPCW_quizzes_getAllBlockingQuizzesForCourse($courseID)
{
	global $wpdb, $wpcwdb;
    $wpdb->show_errors();
    
    $SQL = $wpdb->prepare("
    	SELECT * 
    	FROM $wpcwdb->quiz 
    	WHERE parent_course_id = %d 
    	  AND quiz_type = 'quiz_block'
   	", $courseID);
    	
    
    return $wpdb->get_results($SQL);
}

?>