<?php
/**
 * WP Courseware
 * 
 * Functions relating to changing the access for a specific user.
 */


/** 
 * Page where the site owner can choose which courses a user is allowed to access.
 */
function WPCW_showPage_UserCourseAccess_load()
{
	global $wpcwdb, $wpdb;
	$wpdb->show_errors();
	
	$page = new PageBuilder(false);
	$page->showPageHeader(__('Actualizar permisos de acceso a usuarios de los cursos', 'wp_courseware'), '75%', WPCW_icon_getPageIconURL());
	
	
	// Check passed user ID is valid
	$userID = WPCW_arrays_getValue($_GET, 'user_id');
	$userDetails = get_userdata($userID); 
	if (!$userDetails) 
	{
		$page->showMessage(__('Lo siento, pero ese usuario no se pudo encontrar.', 'wp_courseware'), true);
		$page->showPageFooter();
		return false;		
	}

	printf(__('<p>Aqu&iacute; puede cambiar que cursos el usuario <b>%s</b> (Usuario: <b>%s</b>) puede acceder.</p>', 'wp_courseware'), $userDetails->data->display_name, $userDetails->data->user_login);
		
	
	// Check to see if anything has been submitted?
	if (isset($_POST['wpcw_course_user_access'])) 
	{
		$subUserID = WPCW_arrays_getValue($_POST, 'user_id')+0;
		$userSubDetails = get_userdata($subUserID); 
		
		// Check that user ID is valid, and that it matches user we're editing.
		if (!$userSubDetails || $subUserID != $userID) {
			$page->showMessage(__('Lo sentimos, pero ese usuario no se pudo encontrar. Los cambios no se han guardado.', 'wp_courseware'), true);
		}
		
		// Continue, as things appear to be fine
		else 
		{
			// Get list of courses that user is allowed to access from the submitted values.
			$courseAccessIDs = array();
			foreach ($_POST as $key => $value)
			{
				// Check for course ID selection
				if (preg_match('/^wpcw_course_(\d+)$/', $key, $matches)) {
					$courseAccessIDs[] = $matches[1];					
				}				
			}
			
			// Sync courses that the user is allowed to access
			WPCW_courses_syncUserAccess($subUserID, $courseAccessIDs, 'sync');

			// Final success message	
			$message = sprintf(__('Los cursos para el usuario <em>%s</em> ahora han sido actualizados.', 'wp_courseware'), $userDetails->data->display_name);			
			$page->showMessage($message, false);
		}
	}
	

	
	$SQL = "SELECT * 
			FROM $wpcwdb->courses
			ORDER BY course_title ASC 
			";
	
	$courses = $wpdb->get_results($SQL);
	if ($courses)  
	{
		$tbl = new TableBuilder();
		$tbl->attributes = array(
			'id' 	=> 'wpcw_tbl_course_access_summary',
			'class'	=> 'widefat wpcw_tbl'
		);
		
		$tblCol = new TableColumn(__('Acceso permitido', 'wp_courseware'), 'allowed_access');		
		$tblCol->cellClass = "allowed_access";
		$tbl->addColumn($tblCol);
		
		$tblCol = new TableColumn(__('T&iacute;tulo del curso', 'wp_courseware'), 'course_title');
		$tblCol->cellClass = "course_title";
		$tbl->addColumn($tblCol);
		
		$tblCol = new TableColumn(__('Descripci&oacute;n', 'wp_courseware'), 'course_desc');
		$tblCol->cellClass = "course_desc";
		$tbl->addColumn($tblCol);
		
		// Format row data and show it.
		$odd = false;
		foreach ($courses as $course)
		{
			$data = array();
			
			// Basic details					
			$data['course_desc']  	= $course->course_desc;
			
			$editURL = admin_url('admin.php?page=WPCW_showPage_ModifyCourse&course_id=' . $course->course_id);
			$data['course_title']  	= sprintf('<a href="%s">%s</a>', $editURL, $course->course_title);
						
			// Checkbox if enabled or not
			$userAccess = WPCW_courses_canUserAccessCourse($course->course_id, $userID);
			$checkedHTML = ($userAccess ? 'checked="checked"' : '');
			 
			$data['allowed_access'] = sprintf('<input type="checkbox" name="wpcw_course_%d" %s/>', $course->course_id, $checkedHTML);
			
			// Odd/Even row colouring.
			$odd = !$odd;
			$tbl->addRow($data, ($odd ? 'alternate' : ''));
		}
		
		// Create a form so user can update access.
		?>
		<form action="<?php str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
			<?php 
			
			// Finally show table
			echo $tbl->toString();		
			
			?>
			<input type="hidden" name="user_id" value="<?php echo $userID; ?>"> 
			<input type="submit" class="button-primary" name="wpcw_course_user_access" value="<?php _e('Guardar cambios', 'wp_courseware'); ?>" />
		</form>
		<?php 
	}
	
	else {
		printf('<p>%s</p>', __('Actualmente no hay cursos para mostrar. Por qu&eacute; no crea uno?', 'wp_courseware'));
	}
	
	
	
	$page->showPageFooter();
}





?>