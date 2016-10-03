<?php
/**
 * WP Courseware
 * 
 * Functions relating to allowing you to modify the settings and details of a module.
 */


/**
 * Function that allows a module to be created or edited.
 */
function WPCW_showPage_ModifyModule_load() 
{
	$page = new PageBuilder(true);
	
	$moduleDetails 	= false;
	$moduleID 		= false;
	$adding			= false;
	
	// Trying to edit a course	
	if (isset($_GET['module_id'])) 
	{
		$moduleID 		= $_GET['module_id'] + 0;
		$moduleDetails 	= WPCW_modules_getModuleDetails($moduleID);
		
		// Abort if module not found.
		if (!$moduleDetails)
		{
			$page->showPageHeader(__('Editar M&oacute;dulo', 'wp_courseware'), '75%', WPCW_icon_getPageIconURL());
			$page->showMessage(__('Lo sentimos, pero ese m&oacute;dulo no se pudo encontrar.', 'wp_courseware'), true);
			$page->showPageFooter();
			return;
		}
		
		// Editing a module, and it was found
		else {
			$page->showPageHeader(__('Editar M&oacute;dulo', 'wp_courseware'), '75%', WPCW_icon_getPageIconURL());
		}
	}
	
	// Adding module
	else {
		$page->showPageHeader(__('Agregar M&oacute;dulo', 'wp_courseware'), '75%', WPCW_icon_getPageIconURL());
		
		$adding = true;
	}
	
	
	global $wpcwdb;
	
	$formDetails = array(
		'module_title' => array(
				'label' 	=> __('T&iacute;tulo del M&oacute;dulo', 'wp_courseware'),
				'type'  	=> 'text',
				'required'  => true,
				'cssclass'	=> 'wpcw_module_title',
				'desc'  	=> __('T&iacute;tulo del m&oacute;dulo. <b>No es necesario numerar los m&oacute;dulos</b> - esto se hace autom&aacute;ticamente en funci&oacute;n del orden en el que se disponen.', 'wp_courseware'),
				'validate'	 	=> array(
					'type'		=> 'string',
					'maxlen'	=> 150,
					'minlen'	=> 1,
					'regexp'	=> '/^[^<>]+$/',
					'error'		=> __('Por favor, especifique un nombre para el m&oacute;dulo, hasta un m&aacute;ximo de 150 caracteres, s&oacute;lo hay par&eacute;ntesis angulares (&lt; o &gt;). Sus participantes ser&aacute;n capaces de ver este t&iacute;tulo m&oacute;dulo.', 'wp_courseware')
				)	
			),				
			
		'parent_course_id' => array(
				'label' 	=> __('Curso Asociado', 'wp_courseware'),
				'type'  	=> 'select',
				'required'  => true,
				'cssclass'	=> 'wpcw_associated_course',
				'desc'  	=> __('Curso que pertenece este M&oacute;dulo.', 'wp_courseware'),
				'data'		=> WPCW_courses_getCourseList(__('-- Seleccionar un curso de capacitaci&oacute;n --', 'wp_courseware'))	
			),	

		'module_desc' => array(
				'label' 	=> __('Descripci&oacute;n del M&oacute;dulo', 'wp_courseware'),
				'type'  	=> 'textarea',
				'required'  => true,
				'cssclass'	=> 'wpcw_module_desc',
				'desc'  	=> __('La descripci&oacute;n de este m&oacute;dulo. Sus participantes ser&aacute;n capaces de ver esta descripci&oacute;n del m&oacute;dulo.', 'wp_courseware'),
				'validate'	 	=> array(
					'type'		=> 'string',
					'maxlen'	=> 5000,
					'minlen'	=> 1,
					'error'		=> __('Por favor, limitar la descripci&oacute;n de su m&oacute;dulo 5000 caracteres.', 'wp_courseware')
				)	 	
			),		
	);
		
	
	$form = new RecordsForm(
		$formDetails,			// List of form elements
		$wpcwdb->modules, 		// Table for main details
		'module_id' 			// Primary key column name
	);	
	
	$form->customFormErrorMsg = __('Lo sentimos, pero por desgracia hab&iacute;a algunos errores al guardar los detalles del m&oacute;dulo. Por favor corrija los errores e int&eacute;ntelo de nuevo.', 'wp_courseware');
	$form->setAllTranslationStrings(WPCW_forms_getTranslationStrings());
	
	// Useful place to go
	$directionMsg = '<br/></br>' . sprintf(__('Quieres volver a la p&aacute;gina de <a href="%s">p&aacute;gina del progreso del curso</a>?', 'wp_courseware'),
		admin_url('admin.php?page=WPCW_wp_courseware')
	);	
	
	// Override success messages
	$form->msg_record_created = __('Detalles del m&oacute;dulo creado correctamente.', 'wp_courseware') . $directionMsg;
	$form->msg_record_updated = __('Detalles del m&oacute;dulo actualizado satisfactoriamente.', 'wp_courseware') . $directionMsg;

	$form->setPrimaryKeyValue($moduleID);	
	$form->setSaveButtonLabel(__('Guardar todos los detalles', 'wp_courseware'));
		
	
	// See if we have a course ID to pre-set.
	if ($adding && $courseID = WPCW_arrays_getValue($_GET, 'course_id')) {
		$form->loadDefaults(array(
			'parent_course_id' => $courseID			
		));
	}
	
	// Call to re-order modules once they've been created
	$form->afterSaveFunction = 'WPCW_actions_modules_afterModuleSaved_formHook'; 
	
	$form->show();
	
	$page->showPageMiddle('20%');
	
	// Editing a module?
	if ($moduleDetails) 	
	{
		// ### Include a link to delete the module
		$page->openPane('wpcw-deletion-module', __('Eliminar M&oacute;dulo?', 'wp_courseware'));
		
		printf('<a href="%s&action=delete_module&module_id=%d" class="wpcw_delete_item" title="%s">%s</a>',
			admin_url('admin.php?page=WPCW_wp_courseware'),
			$moduleID,
			__("Seguro que quieres eliminar el este m&oacute;dulo?\n\nEsto no se puede deshacer", 'wp_courseware'),			 
			__('Eliminar este M&oacute;dulo', 'wp_courseware')
		);	
		
		printf('<p>%s</p>', __('Las unidades <b>no</b> se borran, <b>s&oacute;lo se desvinculan</b> de este m&oacute;dulo.', 'wp_courseware'));
		
		$page->closePane();
		
		
		// #### Show a list of all sub-units 
		$page->openPane('wpcw-units-module', __('Unidades en este M&oacute;dulo', 'wp_courseware'));
		
		$unitList = WPCW_units_getListOfUnits($moduleID);
		if ($unitList)
		{
			printf('<ul class="wpcw_unit_list">');
			foreach ($unitList as $unitID => $unitObj)
			{
				printf('<li>%s %d - %s</li>',
					__('Unidad', 'wp_courseware'),
					$unitObj->unit_meta->unit_number,
					$unitObj->post_title
				);
			}
			printf('</ul>');
		}
		
		else {
			printf('<p>%s</p>', __('Actualmente no hay unidades de este m&oacute;dulo.', 'wp_courseware'));
		}
	}
	
	$page->showPageFooter();
}




?>