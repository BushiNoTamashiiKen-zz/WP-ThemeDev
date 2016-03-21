<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class VCExtend_UPME_Search extends VCExtend_UPME{
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );
 
        // Use this when creating a shortcode addon
        add_shortcode( 'upme_search_vc', array( $this, 'renderSearch' ) );

    }
 
    public function integrateWithVC() {
        parent::integrateWithVC();
        
        $fields         = array( __('Select', 'upme') => '' );
        $filters        = array( __('Select', 'upme') => '' );
        $exclude_fields = array( __('Select', 'upme') => '' );
        
        $supported_fields =  array('text','textarea','date');
        
        $profile_fields = get_option('upme_profile_fields');
        foreach($profile_fields as $field){
            if(isset($field['type']) && $field['type'] == 'usermeta' && isset($field['field']) && in_array($field['field'],$supported_fields)){
                $fields[$field['name']] = $field['meta'];
                $exclude_fields[$field['name']] = $field['meta'];
            }
            if(isset($field['type']) && $field['type'] == 'usermeta'){
                $filters[$field['name']] = $field['meta'];
            }
        }
 
        /*
        Add your Visual Composer logic here.
        Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("UPME Search", 'upme'),
            "description" => __("Search form for UPME", 'upme'),
            "base" => "upme_search_vc",
            "class" => "",
            "controls" => "full",
            "icon" => plugins_url('assets/upme-vc.png', __FILE__), 
            "category" => __('UPME', 'upme'),
            "params" => array(
                
              array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Name for the Search Form", 'upme'),
                  "param_name" => "name",
                  "value" => '', 
                  "description" => __("Add specific name to search form to load different filters on different search
	forms. If not specified, this will add a dynamic random string as the name.", 'upme')
              ),
              array(
                  "type" => "upme_multiple_select",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Fields", 'upme'),
                  "param_name" => "fields",
                  "value" => $fields,
                  "std" => '',
                  "description" => __("This is used to match the specified criteria using AND/OR operator. This is optional.", 'upme')
                ),
              array(
                  "type" => "upme_multiple_select",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Filters", 'upme'),
                  "param_name" => "filters",
                  "value" => $filters,
                  "std" => '',
                  "description" => __("This is used to match the specified criteria using AND/OR operator. This is optional.", 'upme')
                ),
              array(
                  "type" => "upme_multiple_select",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Exclude Fields", 'upme'),
                  "param_name" => "exclude_fields",
                  "value" => $exclude_fields,
                  "std" => '',
                  "description" => __("You can use this option to exclude some text fields from the default search.This is optional.", 'upme')
                ),
              array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Operator", 'upme'),
                  "param_name" => "operator",
                  "value" => array( __("AND", 'upme') => 'AND'  , __("OR", 'upme') => 'OR'),
                  "std" => 'no',
                  "description" => __("This is used to match the specified criteria using AND/OR operator.", 'upme')
                ),
              array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Use In Sidebar", 'upme'),
                  "param_name" => "use_in_sidebar",
                  "value" => array( __("No", 'upme') => 'no'  , __("Yes", 'upme') => 'yes'),
                  "std" => 'no',
                  "description" => __("This is entirely optional. You can decide to show the search inside a page
	or in the sidebar.", 'upme')
                ),

              array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Show Combined Search", 'upme'),
                  "param_name" => "show_combined_search",
                  "value" => array( __("Yes", 'upme')  => 'yes', __("No", 'upme')  => 'no'),
                  "std" => 'yes',
                  "description" => __("Used to hide combined search option and only use the search on search filters.", 'upme')
             ),
             array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Reset Button Text", 'upme'),
                  "param_name" => "reset_button_text",
                  "value" => '', 
                  "description" => __("Used to change the text displayed inside the search reset button.", 'upme')
              ),
              array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Search Button Text", 'upme'),
                  "param_name" => "button_text",
                  "value" => '', 
                  "description" => __("Used to change the text displayed on search button.", 'upme')
              ),
              array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Combined Search Text", 'upme'),
                  "param_name" => "combined_search_text",
                  "value" => '', 
                  "description" => __("Used to change the text displayed inside the search box.", 'upme')
              ),
              array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Users are Called as", 'upme'),
                  "param_name" => "users_are_called",
                  "value" => '', 
                  "description" => __("Used change the text display for users.", 'upme')
              ),
              
            )
        ) );
    }
    
    /*
    Shortcode logic how it should be rendered
    */
    public function renderSearch( $atts, $content = null ) {
      extract( shortcode_atts( array(
        'name'   => '',
        'fields' => '',
        'filters' => '',
        'exclude_fields'   => 'yes',
        'operator' => 'AND',
        'use_in_sidebar'   => '',
        'users_are_called' => '',
        'combined_search_text'   => '',
        'button_text' => '',
        'reset_button_text'   => '',
        'show_combined_search' => '',

          
      ), $atts ) );
      $content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content
     
      $params = '';
      if($name != ''){
          $params .= ' name="'.$name.'" ';
      }
      if($use_in_sidebar != '' && $use_in_sidebar == 'yes'){
          $params .= ' use_in_sidebar="'.$use_in_sidebar.'" ';
      }
      if($users_are_called != ''){
          $params .= ' users_are_called="'.$users_are_called.'" ';
      }
      if($combined_search_text != ''){
          $params .= ' combined_search_text="'.$combined_search_text.'" ';
      }
      if($button_text != ''){
          $params .= ' button_text="'.$button_text.'" ';
      }
      if($reset_button_text != ''){
          $params .= ' reset_button_text="'.$reset_button_text.'" ';
      }
      if($show_combined_search != ''){
          $params .= ' show_combined_search="'.$show_combined_search.'" ';
      }
      if($fields != ''){
          $params .= ' fields="'. $fields .'" ';
      }
      if($filters != ''){
          $params .= ' filters="' .$filters. '" ';
      }
      if($exclude_fields != ''){
          $params .= ' exclude_fields="' .$exclude_fields. '" ';
      }

      $params .= ' operator="'.$operator.'" ';

        

      $output = do_shortcode('[upme_search '. $params .' ]');
      return $output;
    }

}
// Finally initialize code
new VCExtend_UPME_Search();