<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package SKT Restaurant
 */

get_header(); ?>

<div class="container">
    <div class="page_content">
        <section class="site-main" id="sitemain">
            <header class="page-header">
                <h1 class="entry-title"><?php _e( '<strong>404</strong> NO SE ENCONTRÓ ESTA PÁGINA', 'restaurant' ); ?></h1>
            </header><!-- .page-header -->
            <div class="page-content">
                <p class="text-404"><?php _e( 'Al parecer hiciste algo mal o ingresaste una página que no existe.....<br />No te preocupes suele sucederle hasta a los mejores como nosotros', 'restaurant' ); ?></p>
               
            </div><!-- .page-content -->
        </section>
        <div class="clear"></div>
    </div>
</div>
<?php get_footer(); ?>