<?php
/*
Plugin Name: Popup
Plugin URI: http://www.gopiplus.com/work/2011/01/14/wordpress-popup/
Description: One easy way to send your visitors a welcome message, notice or advertisement is to add this popup plugin to your site. 
Author: Gopi Ramasamy
Version: 11.8
Author URI: http://www.gopiplus.com/work/2011/01/14/wordpress-popup/
Donate link: http://www.gopiplus.com/work/2011/01/14/wordpress-popup/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb, $wp_version;

if (!session_id())
    session_start();

function PPOPOUPUP_activation()
{
	add_option('PPOPOUPUP_RANDOM', "YES");
	add_option('PPOPOUPUP_IMG1', "Sample text message 1");
	add_option('PPOPOUPUP_URL1', get_option('siteurl')."/wp-content/plugins/popup/images/336x280_1.jpg");
	add_option('PPOPOUPUP_LINK1', "http://www.gopiplus.com/work/");
	add_option('PPOPOUPUP_IMG2', "Sample text message 2");
	add_option('PPOPOUPUP_URL2', get_option('siteurl')."/wp-content/plugins/popup/images/336x280_2.jpg");
	add_option('PPOPOUPUP_LINK2', "http://www.gopiplus.com/work/");
	add_option('PPOPOUPUP_IMG3', "Sample text message 3");
	add_option('PPOPOUPUP_URL3', get_option('siteurl')."/wp-content/plugins/popup/images/336x280_3.jpg");
	add_option('PPOPOUPUP_LINK3', "http://www.gopiplus.com/work/");
	add_option('PPOPOUPUP_IMG4', "Sample text message 4");
	add_option('PPOPOUPUP_URL4', get_option('siteurl')."/wp-content/plugins/popup/images/336x280_1.jpg");
	add_option('PPOPOUPUP_LINK4', "http://www.gopiplus.com/work/");
	add_option('PPOPOUPUP_IMG5', "Sample text message 5");
	add_option('PPOPOUPUP_URL5', get_option('siteurl')."/wp-content/plugins/popup/images/336x280_2.jpg");
	add_option('PPOPOUPUP_LINK5', "http://www.gopiplus.com/work/");
	add_option('PPOPOUPUP_IMG6', "Sample text message 6");
	add_option('PPOPOUPUP_URL6', get_option('siteurl')."/wp-content/plugins/popup/images/336x280_3.jpg");
	add_option('PPOPOUPUP_LINK6', "http://www.gopiplus.com/work/");
	add_option('PPOPOUPUP_SESSION', "NO");
}

function mypopup()
{
	if(get_option('PPOPOUPUP_SESSION') == "YES")
	{
		if ( isset($_SESSION['popupsession']) <> "YES" )
		{
			$_SESSION['popupsession'] = "YES"; 
			g_popup();
		}
	}
	else
	{
		g_popup();
	}
}

function g_popup() 
{
	$txt = "";
	$url = "";
	$link = "";
	if(get_option('PPOPOUPUP_RANDOM') == "YES") 
	{
		$randomalert =  rand(1, 5);
		$mytxt= array("PPOPOUPUP_IMG1", "PPOPOUPUP_IMG2", "PPOPOUPUP_IMG3", "PPOPOUPUP_IMG4", "PPOPOUPUP_IMG5", "PPOPOUPUP_IMG6");
		$myurl= array("PPOPOUPUP_URL1", "PPOPOUPUP_URL2", "PPOPOUPUP_URL3", "PPOPOUPUP_URL4", "PPOPOUPUP_URL5", "PPOPOUPUP_URL6");
		$mylink= array("PPOPOUPUP_LINK1", "PPOPOUPUP_LINK2", "PPOPOUPUP_LINK3", "PPOPOUPUP_LINK4", "PPOPOUPUP_LINK5", "PPOPOUPUP_LINK6");
		$txt = get_option($mytxt[$randomalert]); 
		$url = get_option($myurl[$randomalert]); 
		$link = get_option($mylink[$randomalert]); 
	}
	else
	{
		$imgurl =  $matches[1];
		if($imgurl == "IMG1") { 
			$txt = get_option('PPOPOUPUP_IMG1'); 
			$url = get_option('PPOPOUPUP_URL1'); 
			$link = get_option('PPOPOUPUP_LINK1'); 
		} elseif($imgurl == "IMG2") { 
			$txt= get_option('PPOPOUPUP_IMG2'); 
			$url= get_option('PPOPOUPUP_URL2'); 
			$link = get_option('PPOPOUPUP_LINK2');
		} elseif($imgurl == "IMG3") { 
			$txt= get_option('PPOPOUPUP_IMG3'); 
			$url= get_option('PPOPOUPUP_URL3'); 
			$link = get_option('PPOPOUPUP_LINK3');
		} elseif($imgurl == "IMG4") { 
			$txt= get_option('PPOPOUPUP_IMG4'); 
			$url= get_option('PPOPOUPUP_URL4'); 
			$link = get_option('PPOPOUPUP_LINK4');
		} elseif($imgurl == "IMG5") { 
			$txt= get_option('PPOPOUPUP_IMG5'); 
			$url= get_option('PPOPOUPUP_URL5'); 
			$link = get_option('PPOPOUPUP_LINK5');
		} elseif($imgurl == "IMG6") { 
			$txt= get_option('PPOPOUPUP_IMG6'); 
			$url= get_option('PPOPOUPUP_URL6'); 
			$link = get_option('PPOPOUPUP_LINK6');
		} else {
			$txt= get_option('PPOPOUPUP_IMG1'); 
			$url= get_option('PPOPOUPUP_URL1'); 
			$link = get_option('PPOPOUPUP_LINK1');
		}
	}
	
	$PPOPOUPUP_siteurl = get_option('siteurl');
	$PPOPOUPUP_pluginurl = $PPOPOUPUP_siteurl . "/wp-content/plugins/popup/";
	
	if(get_option('PPOPOUPUP_SESSION') == "YES")
	{

		if ( isset($_SESSION['popupsession']) <> "YES" )
		{
			$_SESSION['popupsession'] = "YES"; 
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div id="PopupDiv">';
				$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div class="PopupDivBar"><a href="#">'.$txt.'</a><a href="#" onClick="PopupDivStop()" class="close">&nbsp;</a></div>';
				$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div class="PopupDivAds"><a href="'.$link.'"><img src="'.$url.'" alt="'.$txt.'" /></a></div>';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '</div>';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<script type="text/javascript">setTimeout("PopupDivFunc();", "3000");</script>';
			echo $PPOPOUPUP_txt;
		}

	}
	else
	{
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div id="PopupDiv">';
				$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div class="PopupDivBar"><a href="#">'.$txt.'</a><a href="#" onClick="PopupDivStop()" class="close">&nbsp;</a></div>';
				$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div class="PopupDivAds"><a href="'.$link.'"><img src="'.$url.'" alt="'.$txt.'" /></a></div>';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '</div>';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<script type="text/javascript">setTimeout("PopupDivFunc();", "3000");</script>';
			echo $PPOPOUPUP_txt;
	}
}

function PPOPOUPUP_deactivate() 
{
	// No action required
}

function PPOPOUPUP_add_to_menu() 
{
	add_options_page(__('Popup', 'popup'), __('Popup', 'popup'), 'manage_options', __FILE__, 'PPOPOUPUP_admin_options' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'PPOPOUPUP_add_to_menu');
}

function PPOPOUPUP_admin_options() 
{
	global $wpdb, $wp_version;
	?>
	<div class="wrap">
		<div class="form-wrap">
		<div id="icon-edit" class="icon32 icon32-posts-post"><br>
		</div>
		<h2><?php _e('Wordpress popup plugin', 'popup'); ?></h2>
		<h3><?php _e('Setting', 'popup'); ?></h3>
		<?php
		$PPOPOUPUP_RANDOM = get_option('PPOPOUPUP_RANDOM');
		
		$PPOPOUPUP_IMG1 = get_option('PPOPOUPUP_IMG1');
		$PPOPOUPUP_IMG2 = get_option('PPOPOUPUP_IMG2');
		$PPOPOUPUP_IMG3 = get_option('PPOPOUPUP_IMG3');
		$PPOPOUPUP_IMG4 = get_option('PPOPOUPUP_IMG4');
		$PPOPOUPUP_IMG5 = get_option('PPOPOUPUP_IMG5');
		$PPOPOUPUP_IMG6 = get_option('PPOPOUPUP_IMG6');
		
		$PPOPOUPUP_URL1 = get_option('PPOPOUPUP_URL1');
		$PPOPOUPUP_URL2 = get_option('PPOPOUPUP_URL2');
		$PPOPOUPUP_URL3 = get_option('PPOPOUPUP_URL3');
		$PPOPOUPUP_URL4 = get_option('PPOPOUPUP_URL4');
		$PPOPOUPUP_URL5 = get_option('PPOPOUPUP_URL5');
		$PPOPOUPUP_URL6 = get_option('PPOPOUPUP_URL6');
		
		$PPOPOUPUP_LINK1 = get_option('PPOPOUPUP_LINK1');
		$PPOPOUPUP_LINK2 = get_option('PPOPOUPUP_LINK2');
		$PPOPOUPUP_LINK3 = get_option('PPOPOUPUP_LINK3');
		$PPOPOUPUP_LINK4 = get_option('PPOPOUPUP_LINK4');
		$PPOPOUPUP_LINK5 = get_option('PPOPOUPUP_LINK5');
		$PPOPOUPUP_LINK6 = get_option('PPOPOUPUP_LINK6');
		
		$PPOPOUPUP_SESSION = get_option('PPOPOUPUP_SESSION');
		
		if (isset($_POST['PPOPOUPUP_submit'])) 
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('PPOPOUPUP_form_setting');
		
			$PPOPOUPUP_RANDOM = stripslashes(trim($_POST['PPOPOUPUP_RANDOM']));
			$PPOPOUPUP_IMG1 = stripslashes(trim($_POST['PPOPOUPUP_IMG1']));
			$PPOPOUPUP_IMG2 = stripslashes(trim($_POST['PPOPOUPUP_IMG2']));
			$PPOPOUPUP_IMG3 = stripslashes(trim($_POST['PPOPOUPUP_IMG3']));
			$PPOPOUPUP_IMG4 = stripslashes(trim($_POST['PPOPOUPUP_IMG4']));
			$PPOPOUPUP_IMG5 = stripslashes(trim($_POST['PPOPOUPUP_IMG5']));
			$PPOPOUPUP_IMG6 = stripslashes(trim($_POST['PPOPOUPUP_IMG6']));
			
			$PPOPOUPUP_URL1 = stripslashes(trim($_POST['PPOPOUPUP_URL1']));
			$PPOPOUPUP_URL2 = stripslashes(trim($_POST['PPOPOUPUP_URL2']));
			$PPOPOUPUP_URL3 = stripslashes(trim($_POST['PPOPOUPUP_URL3']));
			$PPOPOUPUP_URL4 = stripslashes(trim($_POST['PPOPOUPUP_URL4']));
			$PPOPOUPUP_URL5 = stripslashes(trim($_POST['PPOPOUPUP_URL5']));
			$PPOPOUPUP_URL6 = stripslashes(trim($_POST['PPOPOUPUP_URL6']));
			
			$PPOPOUPUP_LINK1 = stripslashes(trim($_POST['PPOPOUPUP_LINK1']));
			$PPOPOUPUP_LINK2 = stripslashes(trim($_POST['PPOPOUPUP_LINK2']));
			$PPOPOUPUP_LINK3 = stripslashes(trim($_POST['PPOPOUPUP_LINK3']));
			$PPOPOUPUP_LINK4 = stripslashes(trim($_POST['PPOPOUPUP_LINK4']));
			$PPOPOUPUP_LINK5 = stripslashes(trim($_POST['PPOPOUPUP_LINK5']));
			$PPOPOUPUP_LINK6 = stripslashes(trim($_POST['PPOPOUPUP_LINK6']));
			
			$PPOPOUPUP_SESSION = stripslashes(trim($_POST['PPOPOUPUP_SESSION']));
				
			update_option('PPOPOUPUP_RANDOM', $PPOPOUPUP_RANDOM );
			update_option('PPOPOUPUP_IMG1', $PPOPOUPUP_IMG1 );
			update_option('PPOPOUPUP_IMG2', $PPOPOUPUP_IMG2 );
			update_option('PPOPOUPUP_IMG3', $PPOPOUPUP_IMG3 );
			update_option('PPOPOUPUP_IMG4', $PPOPOUPUP_IMG4 );
			update_option('PPOPOUPUP_IMG5', $PPOPOUPUP_IMG5 );
			update_option('PPOPOUPUP_IMG6', $PPOPOUPUP_IMG6 );
			
			update_option('PPOPOUPUP_URL1', $PPOPOUPUP_URL1 );
			update_option('PPOPOUPUP_URL2', $PPOPOUPUP_URL2 );
			update_option('PPOPOUPUP_URL3', $PPOPOUPUP_URL3 );
			update_option('PPOPOUPUP_URL4', $PPOPOUPUP_URL4 );
			update_option('PPOPOUPUP_URL5', $PPOPOUPUP_URL5 );
			update_option('PPOPOUPUP_URL6', $PPOPOUPUP_URL6 );
			
			update_option('PPOPOUPUP_LINK1', $PPOPOUPUP_LINK1 );
			update_option('PPOPOUPUP_LINK2', $PPOPOUPUP_LINK2 );
			update_option('PPOPOUPUP_LINK3', $PPOPOUPUP_LINK3 );
			update_option('PPOPOUPUP_LINK4', $PPOPOUPUP_LINK4 );
			update_option('PPOPOUPUP_LINK5', $PPOPOUPUP_LINK5 );
			update_option('PPOPOUPUP_LINK6', $PPOPOUPUP_LINK6 );
			
			update_option('PPOPOUPUP_SESSION', $PPOPOUPUP_SESSION );	
			
			?>
			<div class="updated fade">
				<p><strong><?php _e('Details successfully updated.', 'popup'); ?></strong></p>
			</div>
			<?php
			
		}
		?>
		<?php
		wp_enqueue_script('jquery'); // jQuery
		wp_enqueue_media(); // This will enqueue the Media Uploader script
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($){
			$('#upload-btn1').click(function(e) {
				e.preventDefault();
				var image = wp.media({ 
					title: 'Upload Image',
					multiple: false
				}).open()
				.on('select', function(e){
					var uploaded_image = image.state().get('selection').first();
					console.log(uploaded_image);
					var PPOPOUPUP_URL1 = uploaded_image.toJSON().url;
					$('#PPOPOUPUP_URL1').val(PPOPOUPUP_URL1);
				});
			});
			$('#upload-btn2').click(function(e) {
				e.preventDefault();
				var image = wp.media({ 
					title: 'Upload Image',
					multiple: false
				}).open()
				.on('select', function(e){
					var uploaded_image2 = image.state().get('selection').first();
					console.log(uploaded_image2);
					var PPOPOUPUP_URL2 = uploaded_image2.toJSON().url;
					$('#PPOPOUPUP_URL2').val(PPOPOUPUP_URL2);
				});
			});
			$('#upload-btn3').click(function(e) {
				e.preventDefault();
				var image = wp.media({ 
					title: 'Upload Image',
					multiple: false
				}).open()
				.on('select', function(e){
					var uploaded_image3 = image.state().get('selection').first();
					console.log(uploaded_image3);
					var PPOPOUPUP_URL3 = uploaded_image3.toJSON().url;
					$('#PPOPOUPUP_URL3').val(PPOPOUPUP_URL3);
				});
			});
			$('#upload-btn4').click(function(e) {
				e.preventDefault();
				var image = wp.media({ 
					title: 'Upload Image',
					multiple: false
				}).open()
				.on('select', function(e){
					var uploaded_image4 = image.state().get('selection').first();
					console.log(uploaded_image4);
					var PPOPOUPUP_URL4 = uploaded_image4.toJSON().url;
					$('#PPOPOUPUP_URL4').val(PPOPOUPUP_URL4);
				});
			});
			$('#upload-btn5').click(function(e) {
				e.preventDefault();
				var image = wp.media({ 
					title: 'Upload Image',
					multiple: false
				}).open()
				.on('select', function(e){
					var uploaded_image5 = image.state().get('selection').first();
					console.log(uploaded_image5);
					var PPOPOUPUP_URL5 = uploaded_image5.toJSON().url;
					$('#PPOPOUPUP_URL5').val(PPOPOUPUP_URL5);
				});
			});
			$('#upload-btn6').click(function(e) {
				e.preventDefault();
				var image = wp.media({ 
					title: 'Upload Image',
					multiple: false
				}).open()
				.on('select', function(e){
					var uploaded_image6 = image.state().get('selection').first();
					console.log(uploaded_image6);
					var PPOPOUPUP_URL6 = uploaded_image6.toJSON().url;
					$('#PPOPOUPUP_URL6').val(PPOPOUPUP_URL6);
				});
			});
		});
		</script>
		<form name="form_PopUpFad" method="post" action="">
		<label for="tag-title"><?php _e('Random popup display', 'popup'); ?></label>
		<input name="PPOPOUPUP_RANDOM" id="PPOPOUPUP_RANDOM" type="text" value="<?php echo $PPOPOUPUP_RANDOM; ?>" />
		<p><?php _e('Enter YES (or) NO', 'popup'); ?></p>
		
		<label for="tag-title"><?php _e('Popup session option', 'popup'); ?></label>
		<input name="PPOPOUPUP_SESSION" id="PPOPOUPUP_SESSION" type="text" value="<?php echo $PPOPOUPUP_SESSION; ?>" size="20" />
		<p><?php _e('Enter YES (or) NO  (This option is to display the popup once per session)', 'popup'); ?></p>
		
		<h3><?php _e('Popup 1', 'popup'); ?></h3>
		<label for="tag-title"><?php _e('Popup image 1 text (IMG1)', 'popup'); ?></label>
		<input name="PPOPOUPUP_IMG1" id="PPOPOUPUP_IMG1" type="text" value="<?php echo $PPOPOUPUP_IMG1; ?>" size="80" />
		<p><?php _e('What is the text you want to display in popup window. (IMG1)', 'popup'); ?></p>	
		<label for="tag-title"><?php _e('Popup image 1 path (i.e image URL)', 'popup'); ?></label>
		<input name="PPOPOUPUP_URL1" id="PPOPOUPUP_URL1" type="text" value="<?php echo $PPOPOUPUP_URL1; ?>" size="80" />
		<input type="button" name="upload-btn1" id="upload-btn1" class="button-secondary" value="Upload Image">
		<p><?php _e('Where is the picture located on the internet', 'popup'); ?> (Example: http://www.gopiplus.com/work/wp-content/uploads/sample.jpg)</p>
		<label for="tag-title"><?php _e('Popup image 1 link', 'popup'); ?></label>
		<input name="PPOPOUPUP_LINK1" id="PPOPOUPUP_LINK1" type="text" value="<?php echo $PPOPOUPUP_LINK1; ?>" size="50" />
		<p><?php _e('When someone clicks on the picture, where do you want to send them. (Enter # if you dont have any link)', 'popup'); ?></p>
		
		<h3><?php _e('Popup 2', 'popup'); ?></h3>
		<label for="tag-title"><?php _e('Popup image 2 text (IMG2)', 'popup'); ?></label>
		<input name="PPOPOUPUP_IMG2" id="PPOPOUPUP_IMG2" type="text" value="<?php echo $PPOPOUPUP_IMG2; ?>" size="80" />
		<p><?php _e('What is the text you want to display in popup window.', 'popup'); ?></p>
		<label for="tag-title"><?php _e('Popup image 2 path (i.e image URL)', 'popup'); ?></label>
		<input name="PPOPOUPUP_URL2" id="PPOPOUPUP_URL2" type="text" value="<?php echo $PPOPOUPUP_URL2; ?>" size="80" />
		<input type="button" name="upload-btn2" id="upload-btn2" class="button-secondary" value="Upload Image">
		<p><?php _e('Where is the picture located on the internet', 'popup'); ?></p>
		<label for="tag-title"><?php _e('Popup image 2 link', 'popup'); ?></label>
		<input name="PPOPOUPUP_LINK2" id="PPOPOUPUP_LINK2" type="text" value="<?php echo $PPOPOUPUP_LINK2; ?>" size="50" />
		<p><?php _e('When someone clicks on the picture, where do you want to send them.', 'popup'); ?></p>
		
		<h3><?php _e('Popup 3', 'popup'); ?></h3>
		<label for="tag-title"><?php _e('Popup image 3 text (IMG3)', 'popup'); ?></label>
		<input name="PPOPOUPUP_IMG3" id="PPOPOUPUP_IMG3" type="text" value="<?php echo $PPOPOUPUP_IMG3; ?>" size="80" />
		<p><?php _e('What is the text you want to display in popup window.', 'popup'); ?></p>
		<label for="tag-title"><?php _e('Popup image 3 path (i.e image URL)', 'popup'); ?></label>
		<input name="PPOPOUPUP_URL3" id="PPOPOUPUP_URL3" type="text" value="<?php echo $PPOPOUPUP_URL3; ?>" size="80" />
		<input type="button" name="upload-btn3" id="upload-btn3" class="button-secondary" value="Upload Image">
		<p><?php _e('Where is the picture located on the internet', 'popup'); ?></p>
		<label for="tag-title"><?php _e('Popup image 3 link', 'popup'); ?></label>
		<input name="PPOPOUPUP_LINK3" id="PPOPOUPUP_LINK3" type="text" value="<?php echo $PPOPOUPUP_LINK3; ?>" size="50" />
		<p><?php _e('When someone clicks on the picture, where do you want to send them.', 'popup'); ?></p>
		
		<h3><?php _e('Popup 4', 'popup'); ?></h3>
		<label for="tag-title"><?php _e('Popup image 4 text (IMG4)', 'popup'); ?></label>
		<input name="PPOPOUPUP_IMG4" id="PPOPOUPUP_IMG4" type="text" value="<?php echo $PPOPOUPUP_IMG4; ?>" size="80" />
		<p><?php _e('What is the text you want to display in popup window.', 'popup'); ?></p>
		<label for="tag-title"><?php _e('Popup image 4 path (i.e image URL)', 'popup'); ?></label>
		<input name="PPOPOUPUP_URL4" id="PPOPOUPUP_URL4" type="text" value="<?php echo $PPOPOUPUP_URL4; ?>" size="80" />
		<input type="button" name="upload-btn4" id="upload-btn4" class="button-secondary" value="Upload Image">
		<p><?php _e('Where is the picture located on the internet', 'popup'); ?></p>
		<label for="tag-title"><?php _e('Popup image 4 link', 'popup'); ?></label>
		<input name="PPOPOUPUP_LINK4" id="PPOPOUPUP_LINK4" type="text" value="<?php echo $PPOPOUPUP_LINK4; ?>" size="50" />
		<p><?php _e('When someone clicks on the picture, where do you want to send them.', 'popup'); ?></p>
		
		<h3><?php _e('Popup 5', 'popup'); ?></h3>
		<label for="tag-title"><?php _e('Popup image 5 text (IMG5)', 'popup'); ?></label>
		<input name="PPOPOUPUP_IMG5" id="PPOPOUPUP_IMG5" type="text" value="<?php echo $PPOPOUPUP_IMG5; ?>" size="80" />
		<p><?php _e('What is the text you want to display in popup window.', 'popup'); ?></p>
		<label for="tag-title"><?php _e('Popup image 5 path (i.e image URL)', 'popup'); ?></label>
		<input name="PPOPOUPUP_URL5" id="PPOPOUPUP_URL5" type="text" value="<?php echo $PPOPOUPUP_URL5; ?>" size="80" />
		<input type="button" name="upload-btn5" id="upload-btn5" class="button-secondary" value="Upload Image">
		<p><?php _e('Where is the picture located on the internet', 'popup'); ?></p>
		<label for="tag-title"><?php _e('Popup image 5 link', 'popup'); ?></label>
		<input name="PPOPOUPUP_LINK5" id="PPOPOUPUP_LINK5" type="text" value="<?php echo $PPOPOUPUP_LINK5; ?>" size="50" />
		<p><?php _e('When someone clicks on the picture, where do you want to send them.', 'popup'); ?></p>
		
		<h3><?php _e('Popup 6', 'popup'); ?></h3>
		<label for="tag-title"><?php _e('Popup image 6 text (IMG6)', 'popup'); ?></label>
		<input name="PPOPOUPUP_IMG6" id="PPOPOUPUP_IMG6" type="text" value="<?php echo $PPOPOUPUP_IMG6; ?>" size="80" />
		<p><?php _e('What is the text you want to display in popup window.', 'popup'); ?></p>
		<label for="tag-title"><?php _e('Popup image 6 path (i.e image URL)', 'popup'); ?></label>
		<input name="PPOPOUPUP_URL6" id="PPOPOUPUP_URL6" type="text" value="<?php echo $PPOPOUPUP_URL6; ?>" size="80" />
		<input type="button" name="upload-btn6" id="upload-btn6" class="button-secondary" value="Upload Image">
		<p><?php _e('Where is the picture located on the internet', 'popup'); ?></p>
		<label for="tag-title"><?php _e('Popup image 6 link', 'popup'); ?></label>
		<input name="PPOPOUPUP_LINK6" id="PPOPOUPUP_LINK6" type="text" value="<?php echo $PPOPOUPUP_LINK6; ?>" size="50" />
		<p><?php _e('When someone clicks on the picture, where do you want to send them.', 'popup'); ?></p>
		
		<br />
		<input type="submit" id="PPOPOUPUP_submit" name="PPOPOUPUP_submit" lang="publish" class="button-primary" value="<?php _e('Update Setting', 'popup'); ?>" />
		<input type="button" id="help" name="help" lang="publish" class="button-primary" onClick="window.open('http://www.gopiplus.com/work/2011/01/14/wordpress-popup/');" value="<?php _e('Help', 'popup'); ?>" />
		<?php wp_nonce_field('PPOPOUPUP_form_setting'); ?>
		</form>
		</div>
		<h3><?php _e('Plugin configuration option', 'popup'); ?></h3>
		<ol>
			<li><?php _e('Option to add the popup into particular pages and posts.', 'popup'); ?></li>
			<li><?php _e('Option to add the popup window into all the pages in the website.', 'popup'); ?></li>
		</ol>
		<p class="description"><?php _e('Check official website for more information', 'popup'); ?>
		<a target="_blank" href="http://www.gopiplus.com/work/2011/01/14/wordpress-popup/"><?php _e('click here', 'popup'); ?></a></p>
	</div>
	<?php
}

add_shortcode( 'popup', 'PPOPOUPUP_shortcode' );

function PPOPOUPUP_shortcode( $atts ) 
{
	// [popup show="ALL"] 
	if ( ! is_array( $atts ) ) { return ''; }
	$imgurl = strtoupper($atts['show']);
	
	$txt = "";
	$url = "";
	$link = "";
	$PPOPOUPUP_txt = "";
	
	//if(get_option('PPOPOUPUP_RANDOM') == "YES") 
	if($imgurl == "ALL") 
	{
		$randomalert =  rand(1, 5);
		$mytxt= array("PPOPOUPUP_IMG1", "PPOPOUPUP_IMG2", "PPOPOUPUP_IMG3", "PPOPOUPUP_IMG4", "PPOPOUPUP_IMG5", "PPOPOUPUP_IMG6");
		$myurl= array("PPOPOUPUP_URL1", "PPOPOUPUP_URL2", "PPOPOUPUP_URL3", "PPOPOUPUP_URL4", "PPOPOUPUP_URL5", "PPOPOUPUP_URL6");
		$mylink= array("PPOPOUPUP_LINK1", "PPOPOUPUP_LINK2", "PPOPOUPUP_LINK3", "PPOPOUPUP_LINK4", "PPOPOUPUP_LINK5", "PPOPOUPUP_LINK6");
		$txt = get_option($mytxt[$randomalert]); 
		$url = get_option($myurl[$randomalert]); 
		$link = get_option($mylink[$randomalert]); 
	}
	else
	{
		//@$imgurl =  $matches[1];
		if($imgurl == "IMG1") { 
			$txt = get_option('PPOPOUPUP_IMG1'); 
			$url = get_option('PPOPOUPUP_URL1'); 
			$link = get_option('PPOPOUPUP_LINK1'); 
		} elseif($imgurl == "IMG2") { 
			$txt= get_option('PPOPOUPUP_IMG2'); 
			$url= get_option('PPOPOUPUP_URL2'); 
			$link = get_option('PPOPOUPUP_LINK2');
		} elseif($imgurl == "IMG3") { 
			$txt= get_option('PPOPOUPUP_IMG3'); 
			$url= get_option('PPOPOUPUP_URL3'); 
			$link = get_option('PPOPOUPUP_LINK3');
		} elseif($imgurl == "IMG4") { 
			$txt= get_option('PPOPOUPUP_IMG4'); 
			$url= get_option('PPOPOUPUP_URL4'); 
			$link = get_option('PPOPOUPUP_LINK4');
		} elseif($imgurl == "IMG5") { 
			$txt= get_option('PPOPOUPUP_IMG5'); 
			$url= get_option('PPOPOUPUP_URL5'); 
			$link = get_option('PPOPOUPUP_LINK5');
		} elseif($imgurl == "IMG6") { 
			$txt= get_option('PPOPOUPUP_IMG6'); 
			$url= get_option('PPOPOUPUP_URL6'); 
			$link = get_option('PPOPOUPUP_LINK6');
		} else {
			$txt= get_option('PPOPOUPUP_IMG1'); 
			$url= get_option('PPOPOUPUP_URL1'); 
			$link = get_option('PPOPOUPUP_LINK1');
		}
	}
	
	$PPOPOUPUP_siteurl = get_option('siteurl');
	$PPOPOUPUP_pluginurl = $PPOPOUPUP_siteurl . "/wp-content/plugins/popup/";
	
	if(get_option('PPOPOUPUP_SESSION') == "YES")
	{

		if ( isset($_SESSION['popupsession']) <> "YES" )
		{
			$_SESSION['popupsession'] = "YES"; 			
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div id="PopupDiv">';
				$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div class="PopupDivBar"><a href="#">'.$txt.'</a><a href="#" onClick="PopupDivStop()" class="close">&nbsp;</a></div>';
				$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div class="PopupDivAds"><a href="'.$link.'"><img src="'.$url.'" alt="'.$txt.'" /></a></div>';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '</div>';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<script type="text/javascript">setTimeout("PopupDivFunc();", "3000");</script>';
			return $PPOPOUPUP_txt;
		}

	}
	else
	{
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div id="PopupDiv">';
				$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div class="PopupDivBar"><a href="#">'.$txt.'</a><a href="#" onClick="PopupDivStop()" class="close">&nbsp;</a></div>';
				$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<div class="PopupDivAds"><a href="'.$link.'"><img src="'.$url.'" alt="'.$txt.'" /></a></div>';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '</div>';
			$PPOPOUPUP_txt = $PPOPOUPUP_txt . '<script type="text/javascript">setTimeout("PopupDivFunc();", "3000");</script>';
			return $PPOPOUPUP_txt;
	}
}

function PPOPOUPUP_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'popup', get_option('siteurl').'/wp-content/plugins/popup/popup.js');
		wp_enqueue_style( 'popup', get_option('siteurl').'/wp-content/plugins/popup/popup.css');
	}
}

function PPOPOUPUP_textdomain() 
{
	  load_plugin_textdomain( 'popup', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action('plugins_loaded', 'PPOPOUPUP_textdomain');
add_action('wp_enqueue_scripts', 'PPOPOUPUP_javascript_files');	
register_activation_hook(__FILE__, 'PPOPOUPUP_activation');
register_deactivation_hook( __FILE__, 'PPOPOUPUP_deactivate' );
?>