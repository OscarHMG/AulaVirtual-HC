<?php
/**
 * WP Courseware
 * 
 * Code relating to converting an existing post or page to a WP Courseware unit.
 */


/**
 * Convert page/post to a course unit 
 */
function WPCW_showPage_ConvertPage_load()
{
	$page = new PageBuilder(false);
	$page->showPageHeader(__('Convertir P&aacute;gina/Post a una Unidad de Curso', 'wp_courseware'), '75%', WPCW_icon_getPageIconURL());
	
	// Future Feature - Check user can edit other people's pages - use edit_others_pages or custom capability.
	if (!current_user_can('manage_options')) {
		$page->showMessage(__('Lo sentimos, pero no es&aacute; autorizado a editar esta P&aacute;gina/Post.', 'wp_courseware'), true);
		$page->showPageFooter();
		return false;
	}
	
	// Check that post ID is valid
	$postID = WPCW_arrays_getValue($_GET, 'postid') + 0;
	$convertPost = get_post($postID);
	if (!$convertPost) {
		$page->showMessage(__('Lo sentimos, pero la P&aacute;gina/Post especificado no parece existir.', 'wp_courseware'), true);
		$page->showPageFooter();
		return false;
	}
	
	// Check that post isn't already a course unit before trying change. 
	// This is where the conversion takes place.	
	if ('course_unit' != $convertPost->post_type)
	{
		// Confirm we want to do the conversion
		if (!isset($_GET['confirm']))
		{
			$message = sprintf(__('Seguro que desea convertir la <em>%s</em>  a una Unidad de Curso?', 'wp_courseware'), $convertPost->post_type);
			$message .= '<br/><br/>';
			
			// Yes Button
			$message .= sprintf('<a href="%s&postid=%d&confirm=yes" class="button-primary">%s</a>', 
				admin_url('admin.php?page=WPCW_showPage_ConvertPage'), 
				$postID,
				__('S&iacute;, convertirlo', 'wp_courseware')
			);
			
			// Cancel
			$message .= sprintf('&nbsp;&nbsp;<a href="%s&postid=%d&confirm=no" class="button-secondary">%s</a>', 
				admin_url('admin.php?page=WPCW_showPage_ConvertPage'), 
				$postID,
				__('No, no convertirlo', 'wp_courseware')
			);
			
			
			$page->showMessage($message);
			$page->showPageFooter();
			return false;
		}
		
		
		// Handle the conversion confirmation
		else 
		{
			// Confirmed conversion
			if ($_GET['confirm'] == 'yes')
			{
				$postDetails 				= array();
  				$postDetails['ID'] 			= $postID;
  				$postDetails['post_type'] 	= 'course_unit';
  				
  				// Update the post into the database
  				wp_update_post($postDetails);
			}
			
			// Cancelled conversion
			if ($_GET['confirm'] != 'yes') 
			{
				$page->showMessage(__('La conversi&oacute;n a una unidad de curso cancelado.', 'wp_courseware'), false);
				$page->showPageFooter();
				return false;
			}
		}
  		
	}
	
	// Check conversion happened
	$convertedPost = get_post($postID);
	if ('course_unit' == $convertedPost->post_type)
	{
		$page->showMessage(sprintf(__('La P&aacute;gina/Post fue convertido con &eacute;xito a una unidad de curso.  <a href="%s">Ahora puede editar la unidad del curso</a>.', 'wp_courseware'),
			admin_url(sprintf('post.php?post=%d&action=edit', $postID))
		));
	}
	
	else {
		$page->showMessage(__('Por desgracia, hab&iacute;a un error al intentar convertir la P&aacute;gina/Post para una unidad del curso. Tal vez podr&iacute;a intentarlo de nuevo?', 'wp_courseware'), true);
	}
	
	$page->showPageFooter();
}


?>