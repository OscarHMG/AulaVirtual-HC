<?php
/**
 * WP Courseware
 * 
 * Functions relating to showing the settings page.
 */


/**
 * Shows the settings page for the plugin.
 */
function WPCW_showPage_Settings_load()
{
	$page = new PageBuilder(true);
	$page->showPageHeader(__('Cursos de capacitaci&oacute;n - Configuraci&oacute;n', 'wp_courseware'), '75%', WPCW_icon_getPageIconURL());
	
	// Check for update flag
	if (isset($_POST['update']) && $_POST['update'] == 'tables_force_upgrade')
	{
		$page->showMessage(__('Actualizaci&oacute;n de las Tablas WP Courseware...', 'wp_courseware'));
		flush();		

		$installed_ver  = get_option(WPCW_DATABASE_KEY) + 0;
		
		WPCW_database_upgradeTables($installed_ver, true, true); 
		$page->showMessage(sprintf(__('%s tablas se han actualizado con &eacute;xito.', 'wp_courseware'), 'WP Courseware') );
	}
	
	
	
	$settingsFields = array(
		'section_access_key' 	=> array(
				'type'	  	=> 'break',
				'html'	   	=> WPCW_forms_createBreakHTML(__(' Configuraci&oacute;n Licence Key', 'wp_courseware')),
			),			
			
		'licence_key' => array(
				'label' 	=> __('Licence Key', 'wp_courseware'),
				'type'  	=> 'text',
				'desc'  	=> __('La clave de licencia para el plugin WP Courseware.', 'wp_courseware'), 
				'validate'	 	=> array(
					'type'		=> 'string',
					'maxlen'	=> 32,
					'minlen'	=> 32,
					'regexp'	=> '/^[A-Za-z0-9]+$/',
					'error'		=> __('Por favor, introduzca su clave de licencia de 32 caracteres, que contiene s&oacute;lo letras y n&uacute;meros.', 'wp_courseware'),
				)	
			), 	

		'license_activation' => array(
			'label' 	=> __('Activaci&oacute;n de licencia', 'wp_courseware'),
			'type'  	=> 'radio',
			'required'	=> 'true',
			'data'		=> array(
				'activate_license' 	=> sprintf('<b>%s</b>', __('Activada', 'wp_courseware')),
				'deactivate_license' 	=> sprintf('<b>%s</b>', __('Desactivada', 'wp_courseware')),
			),
			'desc'  	=> __('Si desea recibir las actualizaciones de este plugin, seleccione "Activar". De lo contrario, seleccione "Desactivar" para desactivar la licencia. Seleccionando la opci&oacute;n "Desactivar" inhabilitar&aacute; las actualizaciones futuras. La desactivaci&oacute;n de su licencia le permite mover su plugin a otro sitio.', 'wp_courseware'),
		),		
			
		// Section that deals with CSS
		'section_default_css' 	=> array(
				'type'	  	=> 'break',
				'html'	   	=> WPCW_forms_createBreakHTML(__('Estilo y Dise&ntilde;o de configuraciones', 'wp_courseware')),
			),
			
			
		'use_default_css' => array(
				'label' 	=> __('Usar CSS por defecto?', 'wp_courseware'),
				'type'  	=> 'radio',
				'required'	=> 'true',
				'data'		=> array(
					'show_css' 	=> sprintf('<b>%s</b> - %s', __('Si', 'wp_courseware'), __('Utilice hojas de estilo por defecto para la interfaz de la p&aacute;gina web.', 'wp_courseware')),
					'hide_css' 	=> sprintf('<b>%s</b> - %s', __('No', 'wp_courseware'), __('No utilice la hoja de estilo por defecto para la interfaz de la p&aacute;gina web (voy a escribir mi propio CSS)', 'wp_courseware')),
				),
				'desc'  	=> __('Si desea el estilo de su material del curso de formaci&oacute;n a ti mismo, puede desactivar la hoja de estilo por defecto. En caso de duda, seleccione <b>Si</b>.', 'wp_courseware'),
			),	
			
		'section_link' 	=> array(
				'type'	  	=> 'break',
				'html'	   	=> WPCW_forms_createBreakHTML(__('Powered By Link', 'wp_courseware')),
			),			
			
		'show_powered_by' => array(
				'label' 	=> __('Mostrar Powered By Link?', 'wp_courseware'),
				'type'  	=> 'radio',
				'required'	=> 'true',
				'data'		=> array(
					'show_link' 	=> sprintf('<b>%s</b> - %s', __('Yes', 'wp_courseware'), __('Show the <em>\'Powered By WP Courseware\'</em> link.', 'wp_courseware')),
					'hide_link' 	=> sprintf('<b>%s</b> - %s', __('No', 'wp_courseware'), __('Don\'t show any powered-by links.', 'wp_courseware')),
				),
				'desc'  	=> __("Quieres mostrar 'Powered By WP Courseware' en la parte final del curso?", 'wp_courseware'),
			),

		'affiliate_id' => array(
				'label' 	=> __('Your Affiliate ID', 'wp_courseware'),
				'type'  	=> 'text',
				'desc'  	=> __("(Opcional) ganar algo de dinero, proporcionando su nombre de usuario, la cual a su vez, la <b>Powered By WP Courseware</b>  en un afiliado enlace que gana un porcentaje de cada venta! Si no es un afiliado, iniciar sesi&oacute;n en el portal de miembros para registrarse y obtener su ID.", 'wp_courseware'), 
				'validate'	 	=> array(
					'type'		=> 'string',
					'maxlen'	=> 15,
					'minlen'	=> 1,
					'regexp'	=> '/^[A-Za-z0-9\-_]+$/',
					'error'		=> __('Por favor, introduzca su nombre de usuario, que es s&oacute;lo un n&uacute;mero ..', 'wp_courseware'),
				)	
			),
		);
		
	
	// Remove licence key for child multi-sites
	if (!WPCW_plugin_hasAdminRights()) 
	{
		unset($settingsFields['section_access_key']);
		unset($settingsFields['licence_key']);	
	}
				
	$settings = new SettingsForm($settingsFields, WPCW_DATABASE_SETTINGS_KEY, 'wpcw_form_settings_general');
	$settings->setSaveButtonLabel(__('Guardar todas las configuraciones', 'wp_courseware'));
	
	// Update messages for translation
	$settings->msg_settingsSaved   	= __('La configuraci&oacute;n se guard&oacute; con &eacute;xito.', 'wp_courseware');
	$settings->msg_settingsProblem 	= __('Hubo un problema al guardar la configuraci&oacute;n.', 'wp_courseware'); 	
	$settings->customFormErrorMsg = __('Lo sentimos, pero por desgracia hab&iacute;a algunos errores al guardar los detalles del curso. Por favor corrija los errores e int&eacute;ntelo de nuevo.', 'wp_courseware');
	$settings->setAllTranslationStrings(WPCW_forms_getTranslationStrings());
	
	// Form event handlers - processes the saved settings in some way 
	$settings->afterSaveFunction = 'WPCW_showPage_Settings_afterSave';
	$settings->afterSaveFunction = 'edd_activate_license_WPCW'; 
		
	$settings->show();	
	
	
	// Create little form to force upgrading tables if something went wrong during update.
	echo WPCW_forms_createBreakHTML(__("Actualizaci&oacute;n de Tablas", 'wp_courseware'), false, true, 'wpcw_upgrade_tables');
	?>	
	<p><?php _e("Si usted est&aacute; recibiendo los errores con el WP pedag&oacute;gico en relaci&oacute;n con las tablas de base de datos cuando se ha informado, puede forzar una actualizaci&oacute;n de las tablas de base de datos utilizando el bot&oacute;n de abajo.", 'wp_courseware'); ?></p>
	<?php
	
	$form = new FormBuilder('tables_force_upgrade');
	$form->setSubmitLabel(__('Forzar actualizaci&oacute;n de tablas', 'wp_courseware'));	
	echo $form->toString();

	
	
	// RHS Support Information
	$page->showPageMiddle('23%');	
	WPCW_docs_showSupportInfo($page);
	WPCW_docs_showSupportInfo_News($page);	
	WPCW_docs_showSupportInfo_Affiliate($page);
	
	$page->showPageFooter();
}


/**
 * Function called after settings are saved.
 * 
 * @param String $formValuesFiltered The data values actually saved to the database after filtering.
 * @param String $originalFormValues The original data values before filtering.
 * @param Object $formObj The form object thats doing the saving.
 */
function WPCW_showPage_Settings_afterSave($formValuesFiltered, $originalFormValues, $formObj)
{
	// Can't update licence key unless admin for site.
	if (!WPCW_plugin_hasAdminRights()) {
		return false;
	}
	
	// Update the licence key for the plugin, in case it's changed.
	// global $updater_wpcw;
	// $updater_wpcw->setAccessKey($formValuesFiltered['licence_key'], true);
}


/**
 * Shows the settings page for the plugin, shown just for the network page.
 */
function WPCW_showPage_Settings_Network_load()
{
	$page = new PageBuilder(true);
	$page->showPageHeader(__('WP Courseware - Configuraciones', 'wp_courseware'), '75%', WPCW_icon_getPageIconURL());
	
	
	$settingsFields = array(
		'section_access_key' 	=> array(
				'type'	  	=> 'break',
				'html'	   	=> WPCW_forms_createBreakHTML(__('Clave de licencia Configuraciones', 'wp_courseware'), false, true),
			),			
			
		'licence_key' => array(
				'label' 	=> __('Clave de licencia', 'wp_courseware'),
				'type'  	=> 'text',
				'desc'  	=> __('La clave de licencia para el plugin WP pedag&oacute;gico..', 'wp_courseware'), 
				'validate'	 	=> array(
					'type'		=> 'string',
					'maxlen'	=> 32,
					'minlen'	=> 32,
					'regexp'	=> '/^[A-Za-z0-9]+$/',
					'error'		=> __('Por favor, introduzca su clave de licencia de 32 caracteres, que contiene s&oacute;lo letras y n&uacute;meros.', 'wp_courseware'),
				)	
			), 		
		);
		
				
	$settings = new SettingsForm($settingsFields, WPCW_DATABASE_SETTINGS_KEY, 'wpcw_form_settings_general');
	
	// Set strings and messages
	$settings->setAllTranslationStrings(WPCW_forms_getTranslationStrings());
	$settings->setSaveButtonLabel('Guardar todas las configuraciones', 'wp_courseware');
	
	// Form event handlers - processes the saved settings in some way 
	$settings->afterSaveFunction = 'WPCW_showPage_Settings_afterSave';
		
	$settings->show();	
	
	
	// RHS Support Information
	$page->showPageMiddle('23%');	
	WPCW_docs_showSupportInfo($page);
	WPCW_docs_showSupportInfo_News($page);	
	WPCW_docs_showSupportInfo_Affiliate($page);
	
	$page->showPageFooter();
}

?>