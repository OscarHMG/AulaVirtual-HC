<?php
/**
 * SKT Restaurant Theme Customizer
 *
 * @package SKT Restaurant
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function restaurant_customize_register( $wp_customize ) {
	
	//Add a class for titles
    class restaurant_Info extends WP_Customize_Control {
        public $type = 'info';
        public $label = '';
        public function render_content() {
        ?>
			<h3 style="text-decoration: underline; color: #DA4141; text-transform: uppercase;"><?php echo esc_html( $this->label ); ?></h3>
        <?php
        }
    }
	
	class WP_Customize_Textarea_Control extends WP_Customize_Control {
    public $type = 'textarea';
 
    public function render_content() {
        ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
            </label>
        <?php
    }
}
	
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->remove_control('header_textcolor');
	$wp_customize->remove_control('display_header_text');
	
	
	$wp_customize->add_section(
        'logo_sec',
        array(
            'title' => __('Logo (PRO Version)', 'restaurant'),
            'priority' => 1,
            'description' => sprintf( __( 'Logo Settings available in %s.', 'restaurant' ), sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( '"'.SKT_PRO_THEME_URL.'"' ), __( 'PRO Version', 'restaurant' ))),			
        )
    );
	
	  
    $wp_customize->add_setting('restaurant_options[logo-info]',array(
            'type' => 'info_control',
            'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
        )
    );
    $wp_customize->add_control( new restaurant_Info( $wp_customize, 'logo_section', array(
        'section' => 'logo_sec',
        'settings' => 'restaurant_options[logo-info]',
        'priority' => null
        ) )
    );
	$wp_customize->add_setting('color_scheme',array(
			'default'	=> '#ffa200',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'sanitize_hex_color'
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'color_scheme',array(
			'label' => __('Color Scheme','restaurant'),			
			 'description' => sprintf( __( 'More color options in %s.', 'restaurant' ), sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( '"'.SKT_PRO_THEME_URL.'"' ), __( 'PRO Version', 'restaurant' ))),			
			'section' => 'colors',
			'settings' => 'color_scheme'
		))
	);
	
	// Slider Section	
	$wp_customize->add_section('slider_section',array(
		'title'	=> __('Slider Settings','restaurant'),
		'description' => sprintf( __( 'Slider Image Dimensions ( 1400 X 648 )<br/> Select Featured Image for these pages <br /> How to set featured image <a href="%1$s" target="_blank">Click Here ?</a><br/><br/> More slider settings available in <a href="%2$s" target="_blank">PRO Version</a>', 'restaurant' ),
			esc_url( '"'.SKT_THEME_FEATURED_SET_VIDEO_URL.'"' ),
			esc_url( '"'.SKT_PRO_THEME_URL.'"' )
		),			   	
		'priority'		=> null
	));
	
	$wp_customize->add_setting('page-setting7',array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'restaurant_sanitize_integer'
	));
	
	$wp_customize->add_control('page-setting7',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for slide one:','restaurant'),
			'section'	=> 'slider_section'
	));	
	
	$wp_customize->add_setting('page-setting8',array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'restaurant_sanitize_integer'
	));
	
	$wp_customize->add_control('page-setting8',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for slide two:','restaurant'),
			'section'	=> 'slider_section'
	));	
	
	$wp_customize->add_setting('page-setting9',array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'restaurant_sanitize_integer'
	));
	
	$wp_customize->add_control('page-setting9',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for slide three:','restaurant'),
			'section'	=> 'slider_section'
	));	// Slider Section
	
	// Home Welcome Section 	
	$wp_customize->add_section('section_first',array(
		'title'	=> __('Homepage Welcome Section','restaurant'),
		'description'	=> __('Select Page from the dropdown for first section','restaurant'),
		'priority'	=> null
	));
	
	$wp_customize->add_setting('page-setting1',	array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'restaurant_sanitize_integer',
		));
 
	$wp_customize->add_control(	'page-setting1',array('type' => 'dropdown-pages',
			'label' => __('','restaurant'),
			'section' => 'section_first',
	));
	
	$wp_customize->add_setting('moreinfo_link',array(
			'capability' => 'edit_theme_options',
			'default'	=> '#',
			'sanitize_callback'	=> 'esc_url_raw'
	));
	
	$wp_customize->add_control('moreinfo_link',array(
			'label'	=> __('Add link more info button for welcome section','restaurant'),
			'section'	=> 'section_first',
			'setting'	=> 'moreinfo_link'
	));		

	

// Home Three Boxes Section 	
	$wp_customize->add_section('section_second', array(
		'title'	=> __('Homepage Three Boxes Section','restaurant'),
 			'description' => sprintf( __( 'Select Pages from the dropdown.<br/><br/>Featured Image Dimensions : ( 343 X 297 )<br/> Select Featured Image for these pages. <br /><br /> How to set featured image %s', 'restaurant' ), sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( '"'.SKT_THEME_FEATURED_SET_VIDEO_URL.'"' ), __( 'Click Here ?', 'restaurant' )
						)
					),
		'priority'	=> null
	));	
	
	
	$wp_customize->add_setting('page-column1',	array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'restaurant_sanitize_integer',
		));
 
	$wp_customize->add_control(	'page-column1',array('type' => 'dropdown-pages',
			'label' => __('','restaurant'),
			'section' => 'section_second',
	));	
	
	
	$wp_customize->add_setting('page-column2',	array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'restaurant_sanitize_integer',
		));
 
	$wp_customize->add_control(	'page-column2',array('type' => 'dropdown-pages',
			'label' => __('','restaurant'),
			'section' => 'section_second',
	));
	
	$wp_customize->add_setting('page-column3',	array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'restaurant_sanitize_integer',
		));
 
	$wp_customize->add_control(	'page-column3',array('type' => 'dropdown-pages',
			'label' => __('','restaurant'),
			'section' => 'section_second',
	));	//end three column part
	
	
	$wp_customize->add_section('social_sec',array(
			'title'	=> __('Social Settings','restaurant'),				
			'description' => sprintf( __( 'More social icon available in %s.', 'restaurant' ), sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( '"'.SKT_PRO_THEME_URL.'"' ), __( 'PRO Version', 'restaurant' ))),			
			'priority'		=> null
	));
	
	
	$wp_customize->add_setting('fb_link',array(
			'capability' => 'edit_theme_options',
			'default'	=> '#facebook',
			'sanitize_callback'	=> 'esc_url_raw'	
	));
	
	$wp_customize->add_control('fb_link',array(
			'label'	=> __('Add facebook link here','restaurant'),
			'section'	=> 'social_sec',
			'setting'	=> 'fb_link'
	));	
	$wp_customize->add_setting('twitt_link',array(
			'capability' => 'edit_theme_options',
			'default'	=> '#twitter',
			'sanitize_callback'	=> 'esc_url_raw'
	));
	
	$wp_customize->add_control('twitt_link',array(
			'label'	=> __('Add twitter link here','restaurant'),
			'section'	=> 'social_sec',
			'setting'	=> 'twitt_link'
	));
	$wp_customize->add_setting('gplus_link',array(
			'capability' => 'edit_theme_options',
			'default'	=> '#gplus',
			'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('gplus_link',array(
			'label'	=> __('Add google plus link here','restaurant'),
			'section'	=> 'social_sec',
			'setting'	=> 'gplus_link'
	));
	$wp_customize->add_setting('linked_link',array(
			'capability' => 'edit_theme_options',
			'default'	=> '#linkedin',
			'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('linked_link',array(
			'label'	=> __('Add linkedin link here','restaurant'),
			'section'	=> 'social_sec',
			'setting'	=> 'linked_link'
	));
	
	
	
	
	$wp_customize->add_section('footer_area',array(
			'title'	=> __('Footer Area','restaurant'),
			'priority'	=> null,
			'description'	=> __('Add footer copyright text','restaurant')
	));
	$wp_customize->add_setting('restaurant_options[credit-info]', array(
            'type' => 'info_control',
            'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
        )
    );
    $wp_customize->add_control( new restaurant_Info( $wp_customize, 'cred_section', array(
		'label'	=> __('To remove credit &amp; copyright text upgrade to PRO version','restaurant'),
        'section' => 'footer_area',
        'settings' => 'restaurant_options[credit-info]'
        ) )
    );
	$wp_customize->add_setting('menu_title',array(
			'default'	=> __('Main Menu','restaurant'),
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('menu_title',array(
			'label'	=> __('Add title for menu','restaurant'),
			'section'	=> 'footer_area',
			'setting'	=> 'menu_title'
	));	
	
	$wp_customize->add_setting('about_title',array(
			'default'	=> __('Our Philosophy','restaurant'),
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('about_title',array(
			'label'	=> __('Add title for our philosophy','restaurant'),
			'section'	=> 'footer_area',
			'setting'	=> 'about_title'
	));
	
	$wp_customize->add_setting('about_description',array(
			'default'	=> __('Consectetur, adipisci velit, sed quiaony on numquam eius modi tempora incidunt, ut laboret dolore agnam aliquam quaeratine voluptatem. ut enim ad minima veniamting suscipit lab','restaurant'),
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'wp_htmledit_pre'
	));
	
	$wp_customize->add_control(	new WP_Customize_Textarea_Control( $wp_customize,'about_description', array(	
			'label'	=> __('Add description for our philosophy','restaurant'),
			'section'	=> 'footer_area',
			'setting'	=> 'about_description'
	)) );
	
	$wp_customize->add_setting('social_title',array(
			'default'	=> __('Follow Us','restaurant'),
			'capability' => 'edit_theme_options',	
			'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('social_title',array(
			'label'	=> __('Add title for footer social icons','restaurant'),
			'section'	=> 'footer_area',
			'setting'	=> 'social_title'
	));	
	
	$wp_customize->add_setting('contact_title',array(
			'default'	=> __('Contact Us','restaurant'),
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('contact_title',array(
			'label'	=> __('Add title for footer contact info','restaurant'),
			'section'	=> 'footer_area',
			'setting'	=> 'contact_title'
	));	
	
	
	$wp_customize->add_section('contact_sec',array(
			'title'	=> __('Contact Details','restaurant'),
			'description'	=> __('Add you contact details here','restaurant'),
			'priority'	=> null
	));	
	
	
	$wp_customize->add_setting('contact_add',array(
			'default'	=> __('100 King St, Melbourne PIC 4000, Australia','restaurant'),
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'wp_htmledit_pre'
	));
	
	$wp_customize->add_control(	new WP_Customize_Textarea_Control( $wp_customize, 'contact_add', array(
				'label'	=> __('Add contact address here','restaurant'),
				'section'	=> 'contact_sec',
				'setting'	=> 'contact_add'
			)
		)
	);
	$wp_customize->add_setting('contact_no',array(
			'default'	=> __('Phone: +123 456 7890','restaurant'),
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('contact_no',array(
			'label'	=> __('Add contact number here.','restaurant'),
			'section'	=> 'contact_sec',
			'setting'	=> 'contact_no'
	));
	$wp_customize->add_setting('contact_mail',array(
			'default'	=> 'contact@company.com',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'sanitize_email'
	));
	
	$wp_customize->add_control('contact_mail',array(
			'label'	=> __('Add you email here','restaurant'),
			'section'	=> 'contact_sec',
			'setting'	=> 'contact_mail'
	));
	
	$wp_customize->add_section( 'theme_layout_sec', array(
            'title' => __('Layout Settings (PRO Version)', 'restaurant'),
            'priority' => null,			
			'description' => sprintf( __( 'Layout Settings available in   %s.', 'restaurant' ), sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( '"'.SKT_PRO_THEME_URL.'"' ), __( 'PRO Version', 'restaurant' ))),
			
        )
    );
	
	
	 
    $wp_customize->add_setting('restaurant_options[layout-info]', array(
            'type' => 'info_control',
            'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
        )
    );
    $wp_customize->add_control( new restaurant_Info( $wp_customize, 'layout_section', array(
        'section' => 'theme_layout_sec',
        'settings' => 'restaurant_options[layout-info]',
        'priority' => null
        ) )
    );
	
	$wp_customize->add_section('theme_font_sec', array(
            'title' => __('Fonts Settings (PRO Version)', 'restaurant'),
            'priority' => null,			
			'description' => sprintf( __( 'Font Settings available in   %s.', 'restaurant' ), sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( '"'.SKT_PRO_THEME_URL.'"' ), __( 'PRO Version', 'restaurant' ))),		
			
        )
    );  
	
	
	  
    $wp_customize->add_setting('restaurant_options[font-info]', array(
            'type' => 'info_control',
            'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
        )
    );
    $wp_customize->add_control( new restaurant_Info( $wp_customize, 'font_section', array(
        'section' => 'theme_font_sec',
        'settings' => 'restaurant_options[font-info]',
        'priority' => null
        ) )
    );
	
	$wp_customize->add_section( 'theme_doc_sec', array(
            'title' => __('Documentation &amp; Support', 'restaurant'),
            'priority' => null,
            'description' => sprintf( __( 'For documentation and support check this link %s.', 'restaurant' ), sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( '"'.SKT_THEME_DOC.'"' ), __( 'Restaurant Documentation', 'restaurant' )
						)
					),
        )
    );
	
	  
    $wp_customize->add_setting('restaurant_options[info]', array(
            'type' => 'info_control',
            'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
        )
    );
    $wp_customize->add_control( new restaurant_Info( $wp_customize, 'doc_section', array(
        'section' => 'theme_doc_sec',
        'settings' => 'restaurant_options[info]',
        'priority' => 10
        ) )
    );		
}
add_action( 'customize_register', 'restaurant_customize_register' );

//Integer
function restaurant_sanitize_integer( $input ) {
    if( is_numeric( $input ) ) {
        return intval( $input );
    }
}

function restaurant_custom_css(){
		?>
        	<style type="text/css"> 
					
					a, .blog_lists h2 a:hover,
					#sidebar ul li a:hover,								
					.cols-4 ul li a:hover, .cols-4 ul li.current_page_item a,					
					.phone-no strong,					
					.header .header-inner .nav ul li a:hover, .header .header-inner .nav ul li.current_page_item a,
					.slide_info h2 a,
					.social-icons a:hover
					{ color:<?php echo get_theme_mod('color_scheme','#ffa200'); ?>;}
					 
					
					.pagination ul li .current, .pagination ul li a:hover, 
					#commentform input#submit:hover,					
					.nivo-controlNav a.active,				
					h3.widget-title,
					.MoreLink,
					.wpcf7 input[type='submit']
					{ background-color:<?php echo get_theme_mod('color_scheme','#ffa200'); ?>;}
					
						
					.header .header-inner .nav ul li a:hover, .header .header-inner .nav ul li.current_page_item a,
					.social-icons a:hover
					{ border-color:<?php echo get_theme_mod('color_scheme','#ffa200'); ?>;}
					
			</style> 
<?php      
}
         
add_action('wp_head','restaurant_custom_css');	

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function restaurant_customize_preview_js() {
	wp_enqueue_script( 'restaurant_customizer', get_template_directory_uri() . '/js/customize-preview.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'restaurant_customize_preview_js' );


function restaurant_custom_customize_enqueue() {
	wp_enqueue_script( 'restaurant-custom-customize', get_template_directory_uri() . '/js/custom.customize.js', array( 'jquery', 'customize-controls' ), false, true );
}
add_action( 'customize_controls_enqueue_scripts', 'restaurant_custom_customize_enqueue' );