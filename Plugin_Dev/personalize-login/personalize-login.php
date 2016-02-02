<?php 
/*--- Login Mod PHP file *---/
Last modified: 25/01/16 15:07
/*---
 /**
  * Plugin Name: Personalize login
  * Description: A plugin that replaces the WP login
  * Version: 1.0.0
  * Author: Thabo Mbuyisa
  * License: GPL -2.0+
  * Text Domain: Personalize login
  */

 class Personalize_Login_Plugin {

 	/**
 	 * Initializes the plugin.
 	 *
 	 * To keep the initialization fast, only add filter and action
 	 * in the constructor.
 	 */
 	Public function __construct() {

 	}
 }

 //Initialize the plugin 
 $Personalize_login_pages_plugin = new Personalize_Login_Plugin();

 //Create the custom pages at plugin activation
 register_activation_hook(__FILE__, array('Personalize_Login_Plugin', 'Plugin_activated'));

 /**
 * Plugin activation hook
 * 
 * Creates all Wordpress pages needed for login page swap
 */
 plugin static function plugin_activauted() {
 // Info needed to create the plugin's pages
 	$page_definitions = array(
 		'member-login' => array(
            'title' => __( 'Sign In', 'personalize-login' ),
            'content' => '[custom-login-form]'
        ),
        'member-account' => array(
            'title' => __( 'Your Account', 'personalize-login' ),
            'content' => '[account-info]'
        ),
 	);

// Loop through the page renders
	foreach($page_definitions as $slug => $page){
		// Check that the page doesn't exist already
        $query = new WP_Query( 'pagename=' . $slug );
        if ( ! $query->have_posts()){
        // Add the page using the data from the array above
        wp_insert_post(
        	array(
        		'post_content' => $page['content'],
        		'post_title' => $page['title'],
        		'post_name' => $slug,
        		'post_status' => 'publish',
        		'post_type' => 'page',
        		'ping_status' => 'closed',
        		'comment_status' => 'closed',
        		)
        	);
    	}
    }
}
//loop end
