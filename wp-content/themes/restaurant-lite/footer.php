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
           <!--  <div class="cols-4 widget-column-1">            	
               <h5><?php echo get_theme_mod('menu_title',__('Menú Principal','restaurant')); ?></h5>
                <div class="menu">
                  <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
                </div>
            </div>                  -->
			         
             
             <div class="cols-4 widget-column-2">            	
               <h5><?php echo get_theme_mod('about_title',__('¿Qué Hacemos?','restaurant')); ?></h5>            	
				<p><?php echo get_theme_mod('about_description',__('Vivienda Social y Hábitat</br>
Economía Popular Solidaria</br>
Desarrollo Comunitario</br>
Pastoral</br>
Salud y Seguridad Alimentaria</br>
Aulas del Conocimiento</br>
Acuicultura y Agricultura Social</br>
Banco de Materiales</br>
Casa de Acogida</br>
Voluntariado','restaurant')); ?></p>            	
              </div>     
                      
               <div class="cols-4 widget-column-3">
                   <h5><?php echo get_theme_mod('social_title',__('Síguenos','restaurant')); ?></h5>  
                             	
					<div class="clear"></div>                
                  <div class="social-icons">
					<?php if ( get_theme_mod('fb_link') != "") { ?>
                    <a title="facebook" class="fb" target="_blank" href="<?php echo esc_url(get_theme_mod('fb_link','#facebook')); ?>"></a> 
                    <?php } else { ?>
                    <?php echo '<a href="https://www.facebook.com/HogardeCristoEcuador" target="_blank" class="fb" title="facebook"></a>'; } ?>
                    
                    <?php if ( get_theme_mod('twitt_link') != "") { ?>
                    <a title="twitter" class="tw" target="_blank" href="<?php echo esc_url(get_theme_mod('twitt_link','#twitter')); ?>"></a>
                    <?php } else { ?>
                    <?php echo '<a href="https://twitter.com/HogardeCristoEc" target="_blank" class="tw" title="twitter"></a>'; } ?> 
                    <!--<?php if ( get_theme_mod('gplus_link') != "") { ?>
                    <a title="google-plus" class="gp" target="_blank" href="<?php echo esc_url(get_theme_mod('gplus_link','#gplus')); ?>"></a>
                    <?php } else { ?>
                    <?php echo '<a href="https://www.youtube.com/user/HogardeCristoGye1" target="_blank" class="gp" title="google-plus"></a>'; } ?>-->
                    <!--<?php if ( get_theme_mod('linked_link') != "") { ?> 
                    <a title="Linked-link" class="in" target="_blank" href="<?php echo esc_url(get_theme_mod('Linked-link','#linkedin')); ?>"></a>
                    <?php } else { ?>
                    <?php echo '<a href="https://www.instagram.com/hogardecristoec/" target="_blank" class="in" title="Instagram"></a>'; } ?>-->
                    <?php if ( get_theme_mod('linked_link') != "") { ?> 
                    <a title="Instagram" class="in" target="_blank" href="<?php echo esc_url(get_theme_mod('linked_link','#linkedin')); ?>"></a>
                    <?php } else { ?>
                    <?php echo '<a href="https://www.instagram.com/hogardecristoec/" target="_blank" class="in" title="Instagram"></a>'; } ?>
                  </div>   
                </div>
                
                <div class="cols-4 widget-column-4">
                   <h5><?php echo get_theme_mod('contact_title',__('Contáctanos','restaurant')); ?></h5> 
                   <p><?php echo get_theme_mod('contact_add',__('Av. Casuarina. Coop. Sergio Toral, Mz. 101/Bloque 1,<br />Ecuador ','restaurant')); ?></p>
              <div class="phone-no"><?php echo get_theme_mod('contact_no',__('<strong>Teléfono:</strong> +593 4 390 4449 Ext. 101','restaurant')); ?> <br  />
             
           <strong> Email:</strong> <a href="mailto:<?php echo get_theme_mod('contact_mail','soportehogarcristo@gmail.com'); ?>"><?php echo get_theme_mod('contact_mail','soportehogarcristo@gmail.com'); ?></a></div>
              
                   
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