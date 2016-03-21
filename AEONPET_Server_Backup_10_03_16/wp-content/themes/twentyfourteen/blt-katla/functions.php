<?php

error_reporting(-1);
ini_set('display_errors', 'On');

// Define the version so we can easily replace it throughout the theme
define( 'BLT_THEME_VERSION', 2.3 );
define( 'BLT_THEME_NAME', 'Katla' );
define( 'BLT_THEME_NAMESPACE', 'katla' );

// load language
load_theme_textdomain( 'bluthemes', get_template_directory() . '/inc/lang' );

// Set the content width based on the theme's design and stylesheet 
if(!isset($content_width)){$content_width = 700;}

// Load Custom Functions
require_once 'inc/blt-functions.php';
require_once 'inc/blt-template-tags.php';
// Load Advanced Custom Fields
require_once 'inc/acf/acf-init.php';
// Load Custom Assets (CSS & JS)
require_once 'inc/blt-assets.php';
// Load Widgets
require_once 'inc/widgets/widgets.php';
// Load Shortcodes
require_once 'inc/shortcodes.php';
// Load Custom CSS Made by the Theme Options framework
require_once 'inc/blt-custom-css.php';
// Load Bootstrap type menu 
require_once 'inc/bootstrap-walker.php';


add_action('acf/validate_save_post', 'blt_clear_options_cached');
function blt_clear_options_cached(){
	delete_transient( 'blt_options' );
}


if(defined('BLT_DEMO')){
	require_once '_demo/demo.php';
	require_once '_demo/customizer.php';
}

if(is_admin() and current_user_can('administrator')){

	// Plugin Activation
	require_once 'inc/classes/tgm-plugin-activation.php';

	// Admin Notices
	require_once 'inc/classes/notifications.php';
	new BluNotifications(BLT_THEME_NAMESPACE);
	
	// Theme Updates
	require_once 'inc/classes/update.php';
	new BluUpdate(BLT_THEME_NAMESPACE, blt_get_option('field_5513f0517426d'));

	$user_posts_page_id = get_option('blt_user_posts_page_id');
	if(empty($user_posts_page_id)){

		$post_id = wp_insert_post(array(
			
		  'post_content'   => '',
		  'post_name'      => 'Submit a Post',
		  'post_title'     => 'Submit a Post',
		  'post_status'    => 'publish',
		  'post_type'      => 'page',
		  'ping_status'    => 'closed',
		  'post_date'      => date('Y-m-d H:i:s'),
		  'post_date_gmt'  => date('Y-m-d H:i:s'),
		  'comment_status' => 'closed',
		  'page_template'  => 'user-posts-template.php'
		  
		));

		update_option('blt_user_posts_page_id', $post_id);
		update_field( 'user_posts_page_id', $post_id );

	}

	$user_profile_page_id = get_option('blt_user_profile_page_id');
	if(empty($user_profile_page_id)){

		$post_id = wp_insert_post(array(

		  'post_content'   => '',
		  'post_name'      => 'User Profile',
		  'post_title'     => 'User Profile',
		  'post_status'    => 'publish',
		  'post_type'      => 'page',
		  'ping_status'    => 'closed',
		  'post_date'      => date('Y-m-d H:i:s'),
		  'post_date_gmt'  => date('Y-m-d H:i:s'),
		  'comment_status' => 'closed',
		  'page_template'  => 'user-profile.php'

		));

		update_option('blt_user_profile_page_id', $post_id);
		update_field( 'user_profile_page_id', $post_id );

	}

}


// Login / Register Modal
require_once 'inc/user-login.php';

// User Submitted Posts
require_once 'inc/user-posts.php';