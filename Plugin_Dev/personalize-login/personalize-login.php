<?php
/*--- WP Login Mod PHP file *---/
Last modified: 02/02/16 18:44
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
     * hooks in the constructor.
     */
    public function __construct() {
      //Empty constructor for now :)
    }

      // Call the function when plugin activation hook is switched on
      public static function plugin_activated() {
      // Information needed for creating the plugin's pages
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
   
      foreach ( $page_definitions as $slug => $page ) {
          // Check that the page doesn't exist already
          $query = new WP_Query( 'pagename=' . $slug );
          if ( ! $query->have_posts() ) {
              // Add the page using the data from the array above
              wp_insert_post(
                  array(
                      'post_content'   => $page['content'],
                      'post_name'      => $slug,
                      'post_title'     => $page['title'],
                      'post_status'    => 'publish',
                      'post_type'      => 'page',
                      'ping_status'    => 'closed',
                      'comment_status' => 'closed',
                  )
              );
          }
      }
  }
}
 
// Initialize the plugin
$personalize_login_pages_plugin = new Personalize_Login_Plugin();

// Create the custom pages at plugin activation
register_activation_hook( __FILE__, array( 'Personalize_Login_Plugin', 'plugin_activated' ) );




 
