<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class VCExtend_UPME_Profile extends VCExtend_UPME{
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );

        // Use this when creating a shortcode addon
        add_shortcode( 'upme_profile_vc', array( $this, 'renderProfile' ) );

    }

    public function integrateWithVC() {
        global $upme_roles;
        parent::integrateWithVC();

        $users_ids = array( __('Display Profile Based on URL','upme') => '' );

        $active_users = $upme_roles->get_active_users();

        foreach($active_users as $k=> $user ){
            $name = trim(get_user_meta($user->ID,'first_name',true) . ' ' . get_user_meta($user->ID,'last_name',true));
            $name = ($name == '') ? $user->data->user_login : $name;

            $users_ids[$name] = $user->ID;
        }


        $exclude_fields = array( 'user_pass','user_pass_confirm' );

        $profile_fields = get_option('upme_profile_fields');
        $display_fields = array( );
        foreach($profile_fields as $field){
            if(isset($field['type']) && $field['type'] == 'usermeta' && isset($field['field']) && !in_array($field['meta'],$exclude_fields)){
                $display_fields[$field['name']] = $field['meta'];
            }
        }

        /*
        Add your Visual Composer logic here.
        Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("UPME Profile", 'upme'),
            "description" => __("User Profile for UPME", 'upme'),
            "base" => "upme_profile_vc",
            "class" => "",
            "controls" => "full",
            "icon" => plugins_url('assets/upme-vc.png', __FILE__), 
            "category" => __('UPME', 'upme'),
            "params" => array(

              array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Name for the Profile", 'upme'),
                  "param_name" => "name",
                  "value" => '', 
                  "description" => __("Add specific name to profile to load different filters on different profile shortcodes. If not specified, this will add a dynamic random string as the name.", 'upme')
              ),
              array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("User", 'upme'),
                  "param_name" => "id",
                  "value" => $users_ids,
                  "std" => '',
                  "description" => __("This is used to select user(s) to be displayed or display the profile based on current URL. This is optional.", 'upme')
                ),
              array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Profile View Type", 'upme'),
                  "param_name" => "view",
                  "value" => array( __('Display Full Profile','upme') => 'default' , 
                                  __('Display Compact Profile','upme') => 'compact',
                                  __('Display Selected Fields on Profile','upme') => 'fields'),
                  "std" => 'default',
                  "description" => __("Select the type of view profile. Full profile displays all fields. Compact profile only displays the profile header. Selected fields allows you to select the filds to be displayed in profile..", 'upme')
                ),
              array(
                  "type" => "upme_multiple_select",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Profile Fields", 'upme'),
                  "param_name" => "profile_fields",
                  "value" => $display_fields,
                  "std" => '',
                  "description" => __("You can use this option to include fields to be shown in the profile.This is optional and only used when you have the selected fields option for the Profile View Type.", 'upme')
                ),
              array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Display User ID", 'upme'),
                  "param_name" => "show_id",
                  "value" => array( __("No", 'upme') => 'no' , __("Yes", 'upme') => 'yes' ),
                  "std" => 'no',
                  "description" => __("This is used to display/hide user ID on profile.", 'upme')
                ),
                array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Display Profile Status", 'upme'),
                  "param_name" => "show_profile_status",
                  "value" => array( __("No", 'upme') => 'no' , __("Yes", 'upme') => 'yes' ),
                  "std" => 'no',
                  "description" => __("This is used to display/hide user profile status on profile.", 'upme')
                ),
                array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Display Profile Stats", 'upme'),
                  "param_name" => "show_stats",
                  "value" => array( __("Yes", 'upme') => 'yes' , __("No", 'upme') => 'no'  ),
                  "std" => 'yes',
                  "description" => __("This is used to display/hide posts and comments counts on profile.", 'upme')
                ),
                array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Display Social Bar", 'upme'),
                  "param_name" => "show_social_bar",
                  "value" => array( __("Yes", 'upme') => 'yes' , __("No", 'upme') => 'no'  ),
                  "std" => 'yes',
                  "description" => __("This is used to display/hide social icons on profile.", 'upme')
                ),
                array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Display User Role", 'upme'),
                  "param_name" => "show_role",
                  "value" => array( __("No", 'upme') => 'no' , __("Yes", 'upme') => 'yes' ),
                  "std" => 'no',
                  "description" => __("This is used to display/hide user role on profile.", 'upme')
                ),
                array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Use In Sidebar", 'upme'),
                  "param_name" => "use_in_sidebar",
                  "value" => array( __("No", 'upme') => 'no' , __("Yes", 'upme') => 'yes' ),
                  "std" => 'no',
                  "description" => __("This is entirely optional. You can decide to show the search inside a page
    or in the sidebar.", 'upme')
                ),

                array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Logout Redirect", 'upme'),
                  "param_name" => "logout_redirect",
                  "value" => '', 
                  "description" => __("Users will be redirected to this URL after logged out from profile.", 'upme')
                ),


            )
        ) );
    }

    /*
    Shortcode logic how it should be rendered
    */
    public function renderProfile( $atts, $content = null ) {
      extract( shortcode_atts( array(
        'name'   => '',
        'id' => '',
        'view' => '',
        'show_id'   => 'no',
        'show_profile_status' => 'no',
        'use_in_sidebar'   => '',
        'show_stats' => 'yes',
        'show_social_bar'   => 'yes',
        'show_role' => 'no',
        'logout_redirect'   => '',
        'profile_fields' => '',

      ), $atts ) );
      $content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content

      $params = '';
      if($name != ''){
          $params .= ' name="'.$name.'" ';
      }
      if($id != ''){
          $params .= ' id="'.$id.'" ';
      }
      if($use_in_sidebar == 'yes'){
          $params .= ' use_in_sidebar="'.$use_in_sidebar.'" ';
      }
      if($logout_redirect != ''){
          $params .= ' logout_redirect="'.$logout_redirect.'" ';
      } 
 
      if($view == 'compact'){
        $params  .= ' view="compact" ';
      }else if($view == 'fields'){
         $params  .= ' view="' . $profile_fields . '" ';
      } 

      $params .= ' profile_fields="" ';
      $params .= ' show_id="'.$show_id.'" ';
      $params .= ' show_profile_status="'.$show_profile_status.'" ';
      $params .= ' show_stats="'.$show_stats.'" ';
      $params .= ' show_social_bar="'.$show_social_bar.'" ';
      $params .= ' show_role="'.$show_role.'" ';

      $output = do_shortcode('[upme '. $params .' ]');
      return $output;
    }

}
// Finally initialize code
new VCExtend_UPME_Profile();