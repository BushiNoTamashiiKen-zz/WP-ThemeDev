<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class VCExtend_UPME_Registration extends VCExtend_UPME{
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );
 
        // Use this when creating a shortcode addon
        add_shortcode( 'upme_registration_vc', array( $this, 'renderRegistration' ) );

    }
 
    public function integrateWithVC() {
        global $upme_roles;
        $roles  = $upme_roles->upme_available_user_roles_registration();
        $roles_vc = array( __('Select','upme') => '');
        foreach($roles as $k=>$v){
            $roles_vc[$v] = $k;
        }
        
        parent::integrateWithVC();
 
        /*
        Add your Visual Composer logic here.
        Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("UPME Registration", 'upme'),
            "description" => __("Registration form for UPME", 'upme'),
            "base" => "upme_registration_vc",
            "class" => "",
            "controls" => "full",
            "icon" => plugins_url('assets/upme-vc.png', __FILE__), 
            "category" => __('UPME', 'upme'),
            "params" => array(
              array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Name for Registration Form", 'upme'),
                  "param_name" => "name",
                  "value" => '', 
                  "description" => __("Add specific name to registration form to load different fields on different registration forms. If not specified, this will add a dynamic random string as the name.", 'upme')
              ),
                array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Use In Sidebar", 'upme'),
                  "param_name" => "use_in_sidebar",
                  "value" => array( __("No", 'upme') => 'no'  , __("Yes", 'upme') => 'yes'),
                  "std" => 'no',
                  "description" => __("This will change the CSS styling to better fit inside a small width sidebar.", 'upme')
                ),
              array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Redirect URL", 'upme'),
                  "param_name" => "redirect_to",
                  "value" => '', 
                  "description" => __("Useres are redirected to the specified URL after logging in.", 'upme')
              ),
               array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Captcha", 'upme'),
                  "param_name" => "captcha",
                  "value" => array( __("No", 'upme')  =>  'no', 
                                   __("Yes", 'upme')  =>  'yes', 
                                   __("reCaptcha", 'upme')  => 'recaptcha', 
                                   __("FunCaptcha", 'upme')  => 'funcaptcha',
                                   __("Captcha", 'upme')  => 'captcha',
                                  ),
                  "std" => 'no',
                  "description" => __("Show the Login Form with captcha, uses the captcha plugn selected in UPME settings. You can specify the captcha to be used.", 'upme')
             ),
             array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Display Login Link", 'upme'),
                  "param_name" => "display_login",
                  "value" => array( __("Yes", 'upme')  => 'yes', __("No", 'upme')  => 'no'), 
                  "std" => 'yes',
                  "description" => __("Displays the login link on registration form for already registered users.", 'upme')
             ),
             array(
                  "type" => "dropdown",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("User Role", 'upme'),
                  "param_name" => "user_role",
                  "value" => $roles_vc, 
                  "std" => 'yes',
                  "description" => __("Add specific to the registration form. Once user_role attribute is added, all users registred with this form will get the defined user role instead of default user role.", 'upme')
             ),
             

              
            )
        ) );
    }
    
    /*
    Shortcode logic how it should be rendered
    */
    public function renderRegistration( $atts, $content = null ) {
      extract( shortcode_atts( array(
        'use_in_sidebar'   => 'no',
        'redirect_to' => '',
        'captcha' => 'no',
        'display_login'   => 'yes',
        'name' => '',
        'user_role' => '',
      ), $atts ) );
      $content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content
     
      $params = '';
      if($user_role != ''){
          $params .= ' user_role="'.$user_role.'" ';
      }
      if($name != ''){
          $params .= ' name="'.$name.'" ';
      }
      if($captcha != 'no'){
          $params .= ' captcha="'.$captcha.'" ';
      }
      if($redirect_to != ''){
          $params .= ' redirect_to="'.$redirect_to.'" ';
      }
      if($use_in_sidebar == 'yes'){
          $params .= ' use_in_sidebar="'.$use_in_sidebar.'" ';
      }

      $params .= ' display_login="'.$display_login.'" ';

      $output = do_shortcode('[upme_registration '. $params .' ]');
        //"<div style='color:{$color};' data-foo='${foo}'>{$content}</div>";
      return $output;
    }

}
// Finally initialize code
new VCExtend_UPME_Registration();