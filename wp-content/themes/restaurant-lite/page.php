<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package SKT Restaurant
 */

get_header(); ?>

	<div class="container">
      <div class="page_content">
    		 <section class="site-main" style = "width: 100%">               
            		<?php if( have_posts() ) :
							while( have_posts() ) : the_post(); ?>
                            	<h1 class="entry-title" style="font-family: 'Raleway', Arial, Tahoma, sans-serif;"><?php the_title(); ?></h1>
                                <div class="entry-content">
                                			<?php the_content(); ?>
                                            <?php
												//If comments are open or we have at least one comment, load up the comment template
												if ( comments_open() || '0' != get_comments_number() )
													comments_template();
												?>
                                </div><!-- entry-content -->
                      		<?php endwhile; else : endif; ?>
                    
            </section><!-- section-->
   
         
    <div class="clear"></div>
    </div><!-- .page_content --> 
 </div><!-- .container --> 
<?php get_footer(); ?>