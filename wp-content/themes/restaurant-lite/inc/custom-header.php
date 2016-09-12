<?php
/**
 * @package SKT Restaurant
 * Setup the WordPress core custom header feature.
 *
 * @uses restaurant_header_style()
 * @uses restaurant_admin_header_style()
 * @uses restaurant_admin_header_image()

 */
function restaurant_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'restaurant_custom_header_args', array(
		//'default-image'          => get_template_directory_uri().'/images/banner_bg.jpg',
		'default-text-color'     => 'fff',
		'width'                  => 1600,
		'height'                 => 200,
		'wp-head-callback'       => 'restaurant_header_style',
		'admin-head-callback'    => 'restaurant_admin_header_style',
		'admin-preview-callback' => 'restaurant_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'restaurant_custom_header_setup' );

if ( ! function_exists( 'restaurant_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see restaurant_custom_header_setup().
 */
function restaurant_header_style() {
	$header_text_color = get_header_textcolor();
	?>
	<style type="text/css">
	<?php
		//Check if user has defined any header image.
		if ( get_header_image() ) :
	?>
		.headerxxx {
			background: url(<?php echo get_header_image(); ?>) no-repeat;
			background-position: center top;
		}
	<?php endif; ?>	
	</style>
	<?php
}
endif; // restaurant_header_style

if ( ! function_exists( 'restaurant_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see restaurant_custom_header_setup().
 */
function restaurant_admin_header_style() {?>
	<style type="text/css">
	.appearance_page_custom-header #headimg { border: none; }
	</style><?php
}
endif; // restaurant_admin_header_style


add_action( 'admin_head', 'admin_header_css' );
function admin_header_css(){ ?>
	<style>pre{white-space: pre-wrap;}</style><?php
}


if ( ! function_exists( 'restaurant_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see restaurant_custom_header_setup().
 */
function restaurant_admin_header_image() {
	$style = sprintf( ' style="color:#%s;"', get_header_textcolor() );
?>
	<div id="headimg">
		<?php if ( get_header_image() ) : ?>
		<img src="<?php header_image(); ?>" alt="">
		<?php endif; ?>
	</div>
<?php    
}
endif; // restaurant_admin_header_image 