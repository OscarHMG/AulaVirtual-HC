<?php

class RegisterEntities {

    private $pluginBasename;

    private $metaBoxClass;

    public function __construct($pluginBasename, $metaBoxClass)
    {
        $this->pluginBasename = $pluginBasename;
        $this->metaBoxClass = $metaBoxClass;
    }

    public function registerTranslations() {
        load_plugin_textdomain( 'wp-find-your-nearest', false, $this->pluginBasename . '/languages' );
    }

    public function registerPostTypes()
    {
        $options =  get_option('aphs_FYN_options');
        $Item_Name = $options['Item_Name'];
        if (!$Item_Name) {
            $Item_Name = __('FYN Item', 'wp-find-your-nearest');
            $slug = 'find-your-nearest';
        } else {
            $slug = str_replace(' ', '-', strtolower($Item_Name));
        }

        $FYN_args  =  array(
            'public'  => true,
            'show_in_nav_menus'  => false,
            'query_var'  => 'find_your_nearest',
            'rewrite'  => array(
                'slug'  => $slug . '/%regional_category%',
                'with_front'  => false
            ),
            'has_archive' => true,
            'supports'  => array(
                'title',
                'editor',
                'thumbnail'
            ),
            'register_meta_box_cb'  => array($this->metaBoxClass, 'addFYNMetaBox'),
            'labels'  => array(
                'name'  => esc_html__('Find Your Nearest', 'wp-find-your-nearest'),
                'all_items' => sprintf(esc_html__('All %ss', 'wp-find-your-nearest'), $Item_Name),
                'singular_name'  => $Item_Name,
                'add_new'  => sprintf(esc_html__('Add New %s', 'wp-find-your-nearest'), $Item_Name),
                'add_new_item'  => sprintf(esc_html__('Add New %s', 'wp-find-your-nearest'), $Item_Name),
                'edit_item'  => sprintf(esc_html__('Edit %s', 'wp-find-your-nearest'), $Item_Name),
                'new_item'  => sprintf(esc_html__('New %s', 'wp-find-your-nearest'), $Item_Name),
                'view_item'  => sprintf(esc_html__('View %s', 'wp-find-your-nearest'), $Item_Name),
                'search_items'  => sprintf(esc_html__('Search %ss', 'wp-find-your-nearest'), $Item_Name),
                'not_found'  => sprintf(esc_html__('No %s Found', 'wp-find-your-nearest'), $Item_Name),
                'not_found_in_trash'  => sprintf(esc_html__('No %ss Found In Trash', 'wp-find-your-nearest'), $Item_Name),
            ),
        );

        /* Register the FYN post type. */
        register_post_type( 'find_your_nearest', $FYN_args );
    }

    /*Set up Regional taxonomy */

    public function registerRegionalCategories()
    {
        $options =  get_option('aphs_FYN_options');
        $Item_Name = $options['Item_Name'];
        if (!$Item_Name) {
            $Item_Name = 'FYN Item';
            $slug = 'regional_categories';
        } else {
            $slug = str_replace(' ','-',strtolower($Item_Name));
        }
        $regional_category_args  =  array(
            'hierarchical' =>true,
            'query_var' =>'regional_category',
            'show_tagcloud' =>false,
            'rewrite'  => array (
                'slug' =>$slug,
                'with_front' =>false
            ),
            'labels' =>array(
                'name' =>'Regional Category',
                'singular_name'  => esc_html__('Regional Category', 'wp-find-your-nearest'),
                'edit_item'  => esc_html__('Edit Regional Category', 'wp-find-your-nearest'),
                'update_item'  => esc_html__('Update Regional Category', 'wp-find-your-nearest'),
                'add_new_item'  => esc_html__('Add New Regional Category', 'wp-find-your-nearest'),
                'new_item_name'  => esc_html__('New Regional Category Name', 'wp-find-your-nearest'),
                'all_items'  => esc_html__('All Regional Categories', 'wp-find-your-nearest'),
                'search_items'  => esc_html__('Search Regional Categories', 'wp-find-your-nearest'),
                'popular_items'  => esc_html__('Popular Regional Categories', 'wp-find-your-nearest'),
                'separate_items_with_commas'  => esc_html__('Separate Regional Categories with commas', 'wp-find-your-nearest'),
                'add_or_remove_items'  => esc_html__('Add or remove Regional Categories', 'wp-find-your-nearest'),
                'choose_from_most_used'  => esc_html__('Choose from the most popular Regional Categories', 'wp-find-your-nearest'),
            ),
        );

        register_taxonomy( 'regional_category', array( 'find_your_nearest' ), $regional_category_args );
    }

    public function regionalCategoryLinkFilter($post_link, $id  =  0, $leavename  =  FALSE )
    {
        $options =  get_option('aphs_FYN_options');
        $Item_Name = $options['Item_Name'];
        if (!$Item_Name) {
            $Item_Name = 'FYN Item';
            $slug = 'regional_categories';
        } else {
            $slug = str_replace(' ','-',strtolower($Item_Name));
        }
        if (strpos('%regional_category%', $post_link)  === 'FALSE') {
            return $post_link;
        }
        $post  =  get_post($id);
        if (!is_object($post) || $post->post_type !==  'find_your_nearest') {
            return $post_link;
        }
        $terms  =  wp_get_object_terms($post->ID, 'regional_category');
        if (!$terms || is_wp_error($terms)) {
            return str_replace($slug . '/%regional_category%/', '', $post_link);
        }
        return str_replace('%regional_category%', $terms[0]->slug, $post_link);
    }

}
