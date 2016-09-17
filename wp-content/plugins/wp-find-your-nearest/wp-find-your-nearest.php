<?php
/*
* Plugin Name: WP Find Your Nearest
* Plugin URI: http://www.sargant.net/projects/wordpress-plug-ins/wp-find-your-nearest/
* Description: "Find Your Nearest" creates a custom post type which can be associated with a latitude and longitude calculated from your local postal code, which can then be sorted by distance from a postal code entered into a search field.
* Version: 0.3.1
* Author: SocialEvolution
* Text Domain: wp-find-your-nearest
* Domain Path: /languages
* Author URI: http://www.socialevolution.co.uk/
* License: GPL2
* Copyright 2015  Adam Sargant  (email : aphsargant@socialevolution.co.uk)
*
*     This program is free software; you can redistribute it and/or modify
*     it under the terms of the GNU General Public License, version 2, as
*     published by the Free Software Foundation.
*
*     This program is distributed in the hope that it will be useful,
*     but WITHOUT ANY WARRANTY; without even the implied warranty of
*     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*     GNU General Public License for more details.
*
*     You should have received a copy of the GNU General Public License
*     along with this program; if not, write to the Free Software
*     Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$options= get_option('aphs_FYN_options');
$options['version'] = '0.3.1';
update_option('aphs_FYN_options', $options);

require_once 'gateway.php';

add_action('plugins_loaded', array(WPFindYourNearest::registerEntities(), 'registerTranslations'));
add_action('init', array(WPFindYourNearest::registerEntities(), 'registerPostTypes'));
add_action('init', array(WPFindYourNearest::registerEntities(), 'registerRegionalCategories'));
add_filter('post_type_link', array(WPFindYourNearest::registerEntities(), 'regionalCategoryLinkFilter'), 1, 3);

add_action('admin_menu', array(WPFindYourNearest::customOptionsPanels(), 'addMenuEntry'));
add_action('admin_init', array(WPFindYourNearest::customOptionsPanels(), 'addOptionsFields'));

add_action( 'add_meta_boxes', array(WPFindYourNearest::metaBoxes(), 'addFYNMetaBox'));
add_action( 'add_meta_boxes', array(WPFindYourNearest::metaBoxes(), 'addFYNPremiumPromoMetaBox'));
add_action( 'save_post', array(WPFindYourNearest::metaBoxes(), 'processMetaData'));

add_action( 'wp_dashboard_setup', array(WPFindYourNearest::metaBoxes(), 'addFYNDashboardWidget'));

add_action( 'admin_enqueue_scripts', array(WPFindYourNearest::manageScripts(), 'enqueuePostCodeFinder'));
add_action( 'wp_enqueue_scripts', array(WPFindYourNearest::manageScripts(), 'enqueuePostCodeSearcher'));

add_filter ( 'the_content', array(WPFindYourNearest::searchPage(), 'replaceWithSearchResults'));

foreach (glob(dirname(__FILE__) . '/lib/widgets/*.php') as $widgetfilename) {
    include_once $widgetfilename;
}

//to be refactored before release!!
add_action('wp_ajax_return_search_results', array(WPFindYourNearest::ajaxFunctions(), 'returnSearchResults'));
add_action('wp_ajax_nopriv_return_search_results', array(WPFindYourNearest::ajaxFunctions(), 'returnSearchResults'));


add_action('wp_ajax_return_post_data', array(WPFindYourNearest::ajaxFunctions(), 'returnPostData'));
add_action('wp_ajax_nopriv_return_post_data', array(WPFindYourNearest::ajaxFunctions(), 'returnPostData'));
