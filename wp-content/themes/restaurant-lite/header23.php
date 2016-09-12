<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="container">
 *
 * @package SKT Restaurant
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>

</head>

<body <?php body_class(''); ?>>
<?php if ( is_front_page() && is_home() ) { ?>
<!-- Slider Section -->
<?php for($sld=7; $sld<10; $sld++) { ?>
<?php if( get_theme_mod('page-setting'.$sld)) { ?>
<?php $slidequery = new WP_query('page_id='.get_theme_mod('page-setting'.$sld,true)); ?>
<?php while( $slidequery->have_posts() ) : $slidequery->the_post();
$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
$img_arr[] = $image;
$id_arr[] = $post->ID;
endwhile;
}
}
?>
<?php if(!empty($id_arr)){ ?>
<section id="home_slider">
<div class="slider-wrapper theme-default">
<div id="slider" class="nivoSlider">
	<?php 
	$i=1;
	foreach($img_arr as $url){ ?>
    <img src="<?php echo $url; ?>" title="#slidecaption<?php echo $i; ?>" />
    <?php $i++; }  ?>
</div>   
<?php 
$i=1;
foreach($id_arr as $id){ 
$title = get_the_title( $id ); 
$post = get_post($id); 
$content = apply_filters('the_content', substr(strip_tags($post->post_content), 0, 380)); 
?>                 
<div id="slidecaption<?php echo $i; ?>" class="nivo-html-caption">
<div class="slide_info">
<?php if($title !== '') { ?>
<h2><?php echo $title; ?></h2>
<?php } ?>
<?php echo $content; ?>
<div class="clear"></div>
<a class="ReadMore" href="<?php the_permalink(); ?>">Read More</a>
</div>
</div>      
<?php $i++; } ?>       
 </div>
<div class="clear"></div>        
</section>
<?php } else { ?>
<section id="home_slider">
<div class="slider-wrapper theme-default"><div id="slider" class="nivoSlider">
<img src="<?php echo get_template_directory_uri(); ?>/images/slides/slider1.jpg" alt="Restaurant " title="#slidecaption1" /><img src="<?php echo get_template_directory_uri(); ?>/images/slides/slider2.jpg" alt="Restaurant & Cafe" title="#slidecaption2" /><img src="<?php echo get_template_directory_uri(); ?>/images/slides/slider3.jpg" alt="Cafe" title="#slidecaption3" /></div>                    
                <div id="slidecaption1" class="nivo-html-caption">
                    <div class="slide_info">
                            <h2><?php echo 'Restaurant & Cafe';?></h2>
                            <p><?php echo 'Phasellus viverra aliquet magna quis interduming. Sed quis fringilla massa. In ut porttitor felis necing iaculis mision'; ?> </p>
                            <div class="clear"></div>
                            <a class="ReadMore" href="#link1"><?php echo 'Read More'; ?></a>
                      </div>
                    </div>
                    <div id="slidecaption2" class="nivo-html-caption">
                    <div class="slide_info">
                            <h2><?php echo 'Restaurant & Cafe'; ?></h2>
                            <p><?php echo 'Phasellus viverra aliquet magna quis interduming. Sed quis fringilla massa. In ut porttitor felis necing iaculis mision';?></p>
                            <div class="clear"></div>
                            <a class="ReadMore" href="#link2"><?php echo 'Read More'; ?></a>
                      </div>
                    </div>
                    <div id="slidecaption3" class="nivo-html-caption">
                      <div class="slide_info">
                            <h2><?php echo 'Restaurant & Cafe';?></h2>
                            <p><?php echo 'Phasellus viverra aliquet magna quis interduming. Sed quis fringilla massa. In ut porttitor felis necing iaculis mision'; ?> </p>
                            <div class="clear"></div>
                            <a class="ReadMore" href="#link3"><?php echo 'Read More'; ?></a>
                    </div>
                   </div>
</div>
<div class="clear"></div>
</section>
<!-- Slider Section -->
<?php 
} 
  }
  elseif ( is_front_page() ) { 
?>
<!-- Slider Section -->
<?php for($sld=7; $sld<10; $sld++) { ?>
<?php if( get_theme_mod('page-setting'.$sld)) { ?>
<?php $slidequery = new WP_query('page_id='.get_theme_mod('page-setting'.$sld,true)); ?>
<?php while( $slidequery->have_posts() ) : $slidequery->the_post();
$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
$img_arr[] = $image;
$id_arr[] = $post->ID;
endwhile;
}
}
?>
<?php if(!empty($id_arr)){ ?>
<section id="home_slider">
<div class="slider-wrapper theme-default">
<div id="slider" class="nivoSlider">
	<?php 
	$i=1;
	foreach($img_arr as $url){ ?>
    <img src="<?php echo $url; ?>" title="#slidecaption<?php echo $i; ?>" />
    <?php $i++; }  ?>
</div>   
<?php 
$i=1;
foreach($id_arr as $id){ 
$title = get_the_title( $id ); 
$post = get_post($id); 
$content = apply_filters('the_content', substr(strip_tags($post->post_content), 0, 380)); 
?>                 
<div id="slidecaption<?php echo $i; ?>" class="nivo-html-caption">
<div class="slide_info">
<h1><?php echo $title; ?></h1>
<p><?php echo $content; ?></p>
<div class="clear"></div>
<a class="ReadMore" href="<?php the_permalink(); ?>">Read More</a>
</div>
</div>      
<?php $i++; } ?>       
 </div>
<div class="clear"></div>        
</section>
<?php } else { ?>
<section id="home_slider">
<div class="slider-wrapper theme-default"><div id="slider" class="nivoSlider">
<img src="<?php echo get_template_directory_uri(); ?>/images/slides/slider1.jpg" alt="We Create Delicious Memories " title="#slidecaption1" /><img src="<?php echo get_template_directory_uri(); ?>/images/slides/slider2.jpg" alt="Candle Ready Cakes " title="#slidecaption2" /><img src="<?php echo get_template_directory_uri(); ?>/images/slides/slider3.jpg" alt="Delicious Cookies " title="#slidecaption3" /></div>                    
                <div id="slidecaption1" class="nivo-html-caption">
                    <div class="slide_info">
                            <h2><?php echo 'Restaurant & Cafe';?></h2>
                            <p><?php echo 'Phasellus viverra aliquet magna quis interduming. Sed quis fringilla massa. In ut porttitor felis necing iaculis mision'; ?> </p>
                            <div class="clear"></div>
                            <a class="ReadMore" href="#link1"><?php echo 'Read More'; ?></a>
                      </div>
                    </div>
                    <div id="slidecaption2" class="nivo-html-caption">
                    <div class="slide_info">
                            <h2><?php echo 'Restaurant & Cafe'; ?></h2>
                            <p><?php echo 'Phasellus viverra aliquet magna quis interduming. Sed quis fringilla massa. In ut porttitor felis necing iaculis mision';?></p>
                            <div class="clear"></div>
                            <a class="ReadMore" href="#link2"><?php echo 'Read More'; ?></a>
                      </div>
                    </div>
                    <div id="slidecaption3" class="nivo-html-caption">
                      <div class="slide_info">
                            <h2><?php echo 'Restaurant & Cafe';?></h2>
                            <p><?php echo 'Phasellus viverra aliquet magna quis interduming. Sed quis fringilla massa. In ut porttitor felis necing iaculis mision'; ?> </p>
                            <div class="clear"></div>
                            <a class="ReadMore" href="#link3"><?php echo 'Read More'; ?></a>
                    </div>
                   </div>
</div>
<div class="clear"></div>
</section>
<!-- Slider Section -->
<?php }  } elseif ( is_home() ) { ?>
<?php /*?>        <div class="spacer40"></div>
        <div class="spacer80"></div><?php */?>
        <section id="home_slider" style="display:none;"></section>
        <?php } ?>
        <?php if ( is_front_page() && is_home() ) { ?>
		<div class="header">
        <?php }elseif ( is_front_page() ) { ?>
        <div class="header">
        <?php }else{?>
        <div class="header-innerpage">
        <?php
		}?>
        <div class="container">
            <div class="logo">
                        <h1><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
                        <span class="tagline"><?php bloginfo('description'); ?></span>
            </div><!-- logo -->
            <div class="toggle">
                <a class="toggleMenu" href="#"><?php _e('Menu','restaurant'); ?></a>
             </div><!-- toggle --> 
             <div class="sitenav">                  
                    <?php wp_nav_menu( array('theme_location' => 'primary')); ?>
              </div><!-- nav --><div class="clear"></div>
        </div><!-- container -->
 </div><!-- end.header -->
 
 <?php if ( is_front_page() && is_home() ) { ?>
<section id="wrapfirst">
            	<div class="container">
                    <div class="welcomewrap">
					<?php 
                    /*Home Section Content*/
                    if( get_theme_mod('page-setting1')) { ?>
                    <?php $queryvar = new WP_query('page_id='.get_theme_mod('page-setting1' ,true)); ?>
                    <?php while( $queryvar->have_posts() ) : $queryvar->the_post();?> 		
                     <?php the_post_thumbnail( array(570,380, true));?>
                     <h1><?php the_title(); ?></h1>         
                     <?php the_content(); ?>
                     <?php if(get_theme_mod('moreinfo_link', '#')) { ?>
                      <a class="MoreLink" href="<?php echo get_theme_mod('moreinfo_link',true); ?>"><?php _e('More Info','restaurant'); ?></a>
                      <?php } ?>
                     <div class="clear"></div>
                    <?php endwhile; } else { ?> 
                    <img src="<?php echo get_template_directory_uri(); ?>/images/welcome-restaurant.png" alt=""/>     
                    <h2><?php _e('Welcome to Our Restaurant','restaurant'); ?></h2>
                    <p><?php _e('Donec porta quis justo id pulvinar. Integer sed varius velit. Sed turpis nunc, imperdiet at mi nec, maximus maximus odio. Integer vel molestie ante. Curabitur blandit, purus id scelerisque posuere, enim diam mattis odio, vitae cursus nulla ex malesuada nisi. Pellentesque facilisis ullamcorper lacus, a lobortis urna porttitor eu. Suspendisse rutrum velit tellus, id volutpat risus condimentum non. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Ut sit amet aliquet metus, porttitor iaculis nunc. Quisque cursus ipsum ac lacinia faucibus. Quisque id dui vulputate, varius lectus vitae, congue purus Quisque id dui vulputate, varius lectus vitae, congue purus','restaurant'); ?></p>
                    <a class="MoreLink" href="#"><?php _e('More Info','restaurant'); ?></a> 
                    <?php } ?>
                      
               </div><!-- services-wrap-->
              <div class="clear"></div>
            </div><!-- container -->
       </section><div class="clear"></div>
		<section id="wrapsecond">
            	<div class="container">
                    <div class="services-wrap">
                        <h2><?php _e('Todays Special','restaurant'); ?></h2>
                     
                        <div class="space50"></div>
                        <?php for($p=1; $p<4; $p++) { ?>       
                        <?php if( get_theme_mod('page-column'.$p,false)) { ?>          
                            <?php $queryxxx = new WP_query('page_id='.get_theme_mod('page-column'.$p,true)); ?>				
                                    <?php while( $queryxxx->have_posts() ) : $queryxxx->the_post(); ?> 
                                    <div class="one_third <?php if($p % 3 == 0) { echo "last_column"; } ?>">                      
                                    <a href="<?php the_permalink(); ?>">
                                      <?php the_post_thumbnail( array(85,85, true) );?>
                                      <h4><?php the_title(); ?></h4>
                                    </a>                                              
                                    </div>
                                    <?php endwhile;
                                    wp_reset_query(); ?>
                                    
                        <?php } else { ?>
                                <div class="one_third <?php if($p % 3 == 0) { echo "last_column"; } ?>">                       
                                    <a href="#">
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/menu<?php echo $p; ?>.jpg" alt="" />
                                    <h4><?php _e('Special Menu','restaurant'); ?> <?php echo $p; ?></h4>
                                    </a>
                                 </div>
                        <?php }} ?>  
                    <div class="clear"></div>  
               </div><!-- services-wrap-->
              <div class="clear"></div>
            </div><!-- container -->
       </section><div class="clear"></div>
<?php } elseif ( is_front_page() ) {  ?>
<section id="wrapfirst">
            	<div class="container">
                    <div class="welcomewrap">
					<?php 
                    /*Home Section Content*/
                    if( get_theme_mod('page-setting1')) { ?>
                    <?php $queryvar = new WP_query('page_id='.get_theme_mod('page-setting1' ,true)); ?>
                    <?php while( $queryvar->have_posts() ) : $queryvar->the_post();?> 		
                     <?php the_post_thumbnail( array(570,380, true));?>
                     <h1><?php the_title(); ?></h1>         
                     <?php the_content(); ?>
                     <?php if(get_theme_mod('moreinfo_link', '#')) { ?>
                      <a class="MoreLink" href="<?php echo get_theme_mod('moreinfo_link',true); ?>"><?php _e('More Info','restaurant'); ?></a>
                      <?php } ?>
                     <div class="clear"></div>
                    <?php endwhile; } else { ?> 
                    <img src="<?php echo get_template_directory_uri(); ?>/images/welcome-restaurant.png" alt=""/>     
                    <h2><?php _e('Welcome to Our Restaurant','restaurant'); ?></h2>
                    <p><?php _e('Donec porta quis justo id pulvinar. Integer sed varius velit. Sed turpis nunc, imperdiet at mi nec, maximus maximus odio. Integer vel molestie ante. Curabitur blandit, purus id scelerisque posuere, enim diam mattis odio, vitae cursus nulla ex malesuada nisi. Pellentesque facilisis ullamcorper lacus, a lobortis urna porttitor eu. Suspendisse rutrum velit tellus, id volutpat risus condimentum non. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Ut sit amet aliquet metus, porttitor iaculis nunc. Quisque cursus ipsum ac lacinia faucibus. Quisque id dui vulputate, varius lectus vitae, congue purus Quisque id dui vulputate, varius lectus vitae, congue purus','restaurant'); ?></p>
                    <a class="MoreLink" href="#"><?php _e('More Info','restaurant'); ?></a> 
                    <?php } ?>
                      
               </div><!-- services-wrap-->
              <div class="clear"></div>
            </div><!-- container -->
       </section><div class="clear"></div>
		<section id="wrapsecond">
            	<div class="container">
                    <div class="services-wrap">
                        <h2><?php _e('Todays Special','restaurant'); ?></h2>
                     
                        <div class="space50"></div>
                        <?php for($p=1; $p<4; $p++) { ?>       
                        <?php if( get_theme_mod('page-column'.$p,false)) { ?>          
                            <?php $queryxxx = new WP_query('page_id='.get_theme_mod('page-column'.$p,true)); ?>				
                                    <?php while( $queryxxx->have_posts() ) : $queryxxx->the_post(); ?> 
                                    <div class="one_third <?php if($p % 3 == 0) { echo "last_column"; } ?>">                      
                                    <a href="<?php the_permalink(); ?>">
                                      <?php the_post_thumbnail( array(85,85, true) );?>
                                      <h4><?php the_title(); ?></h4>
                                    </a>                                              
                                    </div>
                                    <?php endwhile;
                                    wp_reset_query(); ?>
                                    
                        <?php } else { ?>
                                <div class="one_third <?php if($p % 3 == 0) { echo "last_column"; } ?>">                       
                                    <a href="#">
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/menu<?php echo $p; ?>.jpg" alt="" />
                                    <h4><?php _e('Special Menu','restaurant'); ?> <?php echo $p; ?></h4>
                                    </a>
                                 </div>
                        <?php }} ?>  
                    <div class="clear"></div>  
               </div><!-- services-wrap-->
              <div class="clear"></div>
            </div><!-- container -->
       </section><div class="clear"></div>
<?php } elseif ( is_home() ) { ?>
<div class="feature-box-main site-aligner" style="display:none;"></div>
<?php } ?>