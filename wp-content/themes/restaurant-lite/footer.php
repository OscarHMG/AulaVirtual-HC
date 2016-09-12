<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package SKT Restaurant
 */
?>
<div id="footer-wrapper">
    	<div class="container">
             <div class="cols-4 widget-column-1">            	
               <h5><?php echo get_theme_mod('menu_title',__('Main Menu','restaurant')); ?></h5>
                <div class="menu">
                  <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
                </div>
            </div>                  
			         
             
             <div class="cols-4 widget-column-2">            	
               <h5><?php echo get_theme_mod('about_title',__('Our Philosophy','restaurant')); ?></h5>            	
				<p><?php echo get_theme_mod('about_description',__('Consectetur, adipisci velit, sed quiaony on numquam eius modi tempora incidunt, ut laboret dolore agnam aliquam quaeratine voluptatem. ut enim ad minima veniamting suscipit lab','restaurant')); ?></p>            	
              </div>     
                      
               <div class="cols-4 widget-column-3">
                   <h5><?php echo get_theme_mod('social_title',__('Follow Us','restaurant')); ?></h5>  
                             	
					<div class="clear"></div>                
                  <div class="social-icons">
					<?php if ( get_theme_mod('fb_link') != "") { ?>
                    <a title="facebook" class="fb" target="_blank" href="<?php echo esc_url(get_theme_mod('fb_link','#facebook')); ?>"></a> 
                    <?php } else { ?>
                    <?php echo '<a href="#" target="_blank" class="fb" title="facebook"></a>'; } ?>
                    
                    <?php if ( get_theme_mod('twitt_link') != "") { ?>
                    <a title="twitter" class="tw" target="_blank" href="<?php echo esc_url(get_theme_mod('twitt_link','#twitter')); ?>"></a>
                    <?php } else { ?>
                    <?php echo '<a href="#" target="_blank" class="tw" title="twitter"></a>'; } ?> 
                    
                    <?php if ( get_theme_mod('gplus_link') != "") { ?>
                    <a title="google-plus" class="gp" target="_blank" href="<?php echo esc_url(get_theme_mod('gplus_link','#gplus')); ?>"></a>
                    <?php } else { ?>
                    <?php echo '<a href="#" target="_blank" class="gp" title="google-plus"></a>'; } ?>
                    
                    <?php if ( get_theme_mod('linked_link') != "") { ?> 
                    <a title="linkedin" class="in" target="_blank" href="<?php echo esc_url(get_theme_mod('linked_link','#linkedin')); ?>"></a>
                    <?php } else { ?>
                    <?php echo '<a href="#" target="_blank" class="in" title="linkedin"></a>'; } ?>
                  </div>   
                </div>
                
                <div class="cols-4 widget-column-4">
                   <h5><?php echo get_theme_mod('contact_title',__('Contact Us','restaurant')); ?></h5> 
                   <p><?php echo get_theme_mod('contact_add',__('100 King St, Melbourne PIC 4000,<br /> Australia','restaurant')); ?></p>
              <div class="phone-no"><?php echo get_theme_mod('contact_no',__('<strong>Phone:</strong> +123 456 7890','restaurant')); ?> <br  />
             
           <strong> Email:</strong> <a href="mailto:<?php echo get_theme_mod('contact_mail','contact@company.com'); ?>"><?php echo get_theme_mod('contact_mail','contact@company.com'); ?></a></div>
              
                   
                </div><!--end .widget-column-4-->
                
                
            <div class="clear"></div>
        </div><!--end .container-->
        
        <div class="copyright-wrapper">
        	<div class="container">
            	<div class="copyright-txt"><?php echo restaurant_credit(); ?></div>
                <div class="design-by"><?php echo restaurant_themebytext(); ?></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
<?php wp_footer(); ?>

</body>
</html>