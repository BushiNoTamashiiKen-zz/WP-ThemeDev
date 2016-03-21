<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class VCExtend_UPME_Member_List extends VCExtend_UPME{
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );

        // Use this when creating a shortcode addon
        add_shortcode( 'upme_member_list_vc', array( $this, 'renderMemberList' ) );

    }

    public function integrateWithVC() {
        global $upme_roles;
        parent::integrateWithVC();

        $users_ids = array( );

        $active_users = $upme_roles->get_active_users();

        foreach($active_users as $k=> $user ){
            $name = trim(get_user_meta($user->ID,'first_name',true) . ' ' . get_user_meta($user->ID,'last_name',true));
            $name = ($name == '') ? $user->data->user_login : $name;

            $users_ids[$name] = $user->ID;
        }


        $exclude_fields = array( 'user_pass','user_pass_confirm' );

        $profile_fields = get_option('upme_profile_fields');
        $group_meta_keys = array( __('Select','upme') => '0');
        $display_fields = array();
        $order_by_fields = array( __('Select','upme') => '0');
        foreach($profile_fields as $field){
            if(isset($field['type']) && $field['type'] == 'usermeta' && isset($field['field']) && !in_array($field['meta'],$exclude_fields)){
                $group_meta_keys[$field['name']] = $field['meta'];
                $display_fields[$field['name']] = $field['meta'];
                $order_by_fields[$field['name']] = $field['meta'];
            }
        }
        
        $user_roles = $upme_roles->upme_get_available_user_roles();
        $roles_vc = array();
        foreach($user_roles as $k=>$v){
            $roles_vc[$v] = $k;
        }
        
        $user_predefined_fields = array('ID' => 'ID', 'login' => 'Username', 'nicename' => 'Display Name', 'email' => 'Email', 'url' => 'URL', 'registered' => 'Registered Date', 'post_count' => 'Post Count' );
        
        $order_by_fields =  array_merge($user_predefined_fields,$order_by_fields);

        /*
        Add your Visual Composer logic here.
        Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("UPME Member List", 'upme'),
            "description" => __("Member List for UPME", 'upme'),
            "base" => "upme_member_list_vc",
            "class" => "",
            "controls" => "full",
            "icon" => plugins_url('assets/upme-vc.png', __FILE__), 
            "category" => __('UPME', 'upme'),
            "params" => array(

              array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Name for the Member List", 'upme'),
                  "param_name" => "name",
                  "value" => '', 
                  "description" => __("Add specific name to member list to load different filters on different member list shortcodes. If not specified, this will add a dynamic random string as the name.", 'upme'),
                  "group" => "Filtering"
              ),
              array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Profile Grouping Type", 'upme'),
                  "param_name" => "group",
                  "value" => array( __('Group All Users','upme') => 'all', __('Group Selected Users','upme') => 'users'),
                  "std" => '',
                  "description" => __("Select the grouping type for member list. Group All Users displays all active users in the site. Group Selected Users allows you to select the users to be displayed in member list..", 'upme'),
                  "group" => "Filtering"
                ),
                array(
                  "type" => "upme_multiple_select",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Users", 'upme'),
                  "param_name" => "id",
                  "value" => $users_ids,
                  "std" => '',
                  "description" => __("This is used to select user(s) to be displayed in the member list instead of all users. This is used when selected users option is selected for Profile Grouping Type.", 'upme'),
                  "group" => "Filtering"
                ),
                array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Group Meta Key", 'upme'),
                  "param_name" => "group_meta",
                  "value" => $group_meta_keys,
                  "std" => '',
                  "description" => __("This is used to group and filter users by custom field. Select the custom field used for grouping.", 'upme'),
                  "group" => "Filtering"
                ),
                array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Group Meta Value", 'upme'),
                  "param_name" => "group_meta_value",
                  "value" => '', 
                  "description" => __("This is used to specify the value of the custom field for grouping members.", 'upme'),
                  "group" => "Filtering"
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
                  "description" => __("Select the type of view profile. Full profile displays all fields. Compact profile only displays the profile header. Selected fields allows you to select the filds to be displayed in profile..", 'upme'),
                  "group" => "Filtering"
                ),
              array(
                  "type" => "upme_multiple_select",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Profile Fields", 'upme'),
                  "param_name" => "profile_fields",
                  "value" => $display_fields,
                  "std" => '',
                  "description" => __("You can use this option to include fields to be shown in the profile.This is optional and only used when you have the selected fields option for the Profile View Type.", 'upme'),
                  "group" => "Filtering"
                ),
                array(
                  "type" => "upme_multiple_select",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("User Roles", 'upme'),
                  "param_name" => "role",
                  "value" => $roles_vc,
                  "std" => '',
                  "description" => __("This is used to select the user roles to be allowed for the member list.", 'upme'),
                  "group" => "Filtering"
                ),
              array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Order by Field", 'upme'),
                  "param_name" => "order_by",
                  "value" => $order_by_fields,
                  "std" => '',
                  "description" => __("This is used to select the custom field used for ordering the results.", 'upme'),
                  "group" => "Filtering"
                ),
             array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Hide Admins", 'upme'),
                  "param_name" => "hide_admins",
                  "value" => array( __('No','upme') => 'no', __('Yes','upme') => 'yes'),
                  "std" => 'no',
                  "description" => __("This is used to group and filter users by custom field. Select the custom field used for grouping.", 'upme'),
                 "group" => "Filtering"
                ),  
                
                
            
            array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Order Results", 'upme'),
                  "param_name" => "order",
                  "value" => array( __('ASC','upme') => 'ASC', __('DESC','upme') => 'DESC'),
                  "std" => 'DESC',
                  "description" => __("Defines the Sort/order of the profiles displayed..", 'upme'),
                 "group" => "General"
                ),  
            array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Hide Until Search", 'upme'),
                  "param_name" => "hide_until_search",
                  "value" => array( __('No','upme') => 'no', __('Yes','upme') => 'yes'),
                  "std" => 'no',
                  "description" => __("Hides/Shows user list and only shows search results until search is completed.", 'upme'),
                 "group" => "General"
                ), 
            array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Users Per Page", 'upme'),
                  "param_name" => "users_per_page",
                  "value" => '', 
                  "description" => __("Number of user profiles to be displayed inside a single page..", 'upme'),
                  "group" => "General"
                ),
            array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Limit Results", 'upme'),
                  "param_name" => "limit_results",
                  "value" => array( __('No','upme') => 'no', __('Yes','upme') => 'yes'),
                  "std" => 'no',
                  "description" => __("Limit the member list to fixed number of users without pagination.", 'upme'),
                 "group" => "General"
                ),  
            array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Open Profiles In New Window", 'upme'),
                  "param_name" => "new_window",
                  "value" => array( __('No','upme') => 'no', __('Yes','upme') => 'yes'),
                  "std" => 'no',
                  "description" => __("Open profiles in a new window from the members list.", 'upme'),
                 "group" => "General"
                ),  
            array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Open Profiles In Modal Window", 'upme'),
                  "param_name" => "modal",
                  "value" => array( __('No','upme') => 'no', __('Yes','upme') => 'yes'),
                  "std" => 'no',
                  "description" => __("Open profiles in a modal window from a member list with compact view.", 'upme'),
                 "group" => "General"
                ),  
            array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Logout Redidirect URL", 'upme'),
                  "param_name" => "logout_redirect",
                  "value" => '', 
                  "description" => __("Used for specifying the redirect URL after the logout button is clicked from the profile..", 'upme'),
                  "group" => "General"
                ),    
                
                array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Show Result Count", 'upme'),
                  "param_name" => "show_result_count",
                  "value" => array( __("No", 'upme') => 'no' , __("Yes", 'upme') => 'yes' ),
                  "std" => 'no',
                  "description" => __("Display/Hide number of results generated from the user list.", 'upme'),
                  "group" => "Display Options"
                ),
                array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Number of Columns", 'upme'),
                  "param_name" => "width",
                  "value" => array( __("Select", 'upme') => '0' , __("2 Columns", 'upme') => '2' , __("3 Columns", 'upme') => '3'),
                  "std" => '0',
                  "description" => __("Number of user profiles to be displayed inside a single page..", 'upme'),
                  "group" => "Display Options"
                ),
            array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Display User ID", 'upme'),
                  "param_name" => "show_id",
                  "value" => array( __("No", 'upme') => 'no' , __("Yes", 'upme') => 'yes' ),
                  "std" => 'no',
                  "description" => __("This is used to display/hide user ID on profile.", 'upme'),
                  "group" => "Display Options"
                ),
                array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Display Profile Status", 'upme'),
                  "param_name" => "show_profile_status",
                  "value" => array( __("No", 'upme') => 'no' , __("Yes", 'upme') => 'yes' ),
                  "std" => 'no',
                  "description" => __("This is used to display/hide user profile status on profile.", 'upme'),
                  "group" => "Display Options"
                ),
                array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Display Profile Stats", 'upme'),
                  "param_name" => "show_stats",
                  "value" => array( __("Yes", 'upme') => 'yes' , __("No", 'upme') => 'no'  ),
                  "std" => 'yes',
                  "description" => __("This is used to display/hide posts and comments counts on profile.", 'upme'),
                  "group" => "Display Options"
                ),
                array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Display Social Bar", 'upme'),
                  "param_name" => "show_social_bar",
                  "value" => array( __("Yes", 'upme') => 'yes' , __("No", 'upme') => 'no'  ),
                  "std" => 'yes',
                  "description" => __("This is used to display/hide social icons on profile.", 'upme'),
                  "group" => "Display Options"
                ),
                array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Display User Role", 'upme'),
                  "param_name" => "show_role",
                  "value" => array( __("No", 'upme') => 'no' , __("Yes", 'upme') => 'yes' ),
                  "std" => 'no',
                  "description" => __("This is used to display/hide user role on profile.", 'upme'),
                  "group" => "Display Options"
                ),
                
                
            )
        ) );
    }

    /*
    Shortcode logic how it should be rendered
    */
    public function renderMemberList( $atts, $content = null ) {
      extract( shortcode_atts( array(
        'name'   => '',
        'id' => '',
        'view' => '',
        'show_id'   => 'no',
        'show_profile_status' => 'no',
        'show_stats' => 'yes',
        'show_social_bar'   => 'yes',
        'show_role' => 'no',
        'logout_redirect'   => '',
        'profile_fields' => '',
        'hide_admins' => 'no',
        'show_result_count' => 'no',
        'group' => 'all',
        'users_per_page' => '',
        'new_window' => 'no',
        'modal' => 'no',
        'hide_until_search' => '',
        'group_meta' => '',
        'group_meta_value' => '',
        'role' => '',
        'width' => '',
        'orderby_custom' => 'no',
        'order_by' => '',
        'order' => 'DESC',
        'limit_results' => 'no'

      ), $atts ) );
      $content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content

      $params = '';
      if($name != ''){
          $params .= ' name="'.$name.'" ';
      }

      if($logout_redirect != ''){
          $params .= ' logout_redirect="'.$logout_redirect.'" ';
      } 
 
      if($view == 'compact'){
        $params  .= ' view="compact" ';
      }else if($view == 'fields'){
         $params  .= ' view="' . $profile_fields . '" ';
      } 
        
      if($group == 'all'){
        $params  .= ' group="all" ';
      }else{
        
        if($id != ''){
            $params  .= ' group="' . $id . '" ';
        }else{
            $params  .= ' group="all" ';
        }            
      } 
        
        
      if($users_per_page != ''){
        $params  .= ' users_per_page="' . $users_per_page . '" ';
      }else{
        $params  .= ' users_per_page="20" '; 
      }
        
      if($new_window == 'yes'){
        $params  .= ' new_window="' . $new_window . '" '; 
      }
        
      if($modal == 'yes'){
        $params  .= ' modal="' . $modal . '" '; 
      }

      if($hide_until_search == 'yes'){
        $params  .= ' hide_until_search="' . $hide_until_search . '" ';
      }
        
      if(!($group_meta == '0' || $group_meta == '')){
        $params  .= ' group_meta="' . $group_meta . '" group_meta_value="' . $group_meta_value . '" ';
      }


      if($role != ''){
        $params  .= ' role="' . $role . '" ';
      }
        
        
      if($width == '2' || $width == '3'){
        $params  .= ' width="' . $width . '" ';
      }
        
      if($order_by != '' && $order_by != '0'){
        $preset_fields = array('ID', 'login', 'nicename', 'email', 'url', 'registered', 'post_count');
        if(!in_array($order_by,$preset_fields)){
            $params  .= ' orderby_custom="yes" ';
        }
        $params  .= ' orderby="' . $order_by . '" ';
      }
      $params  .= ' order="' . $order . '" ';

      $params .= ' show_id="'.$show_id.'" ';
      $params .= ' show_profile_status="'.$show_profile_status.'" ';
      $params .= ' show_stats="'.$show_stats.'" ';
      $params .= ' show_social_bar="'.$show_social_bar.'" ';
      $params .= ' show_role="'.$show_role.'" ';
      $params .= ' hide_admins="'.$hide_admins.'" ';
      $params .= ' show_result_count="'.$show_result_count.'" ';
      $params .= ' limit_results="' . $limit_results . '" ';

      $output = do_shortcode('[upme '.$params.' ]');
      return $output;
    }

}
// Finally initialize code
new VCExtend_UPME_Member_List();