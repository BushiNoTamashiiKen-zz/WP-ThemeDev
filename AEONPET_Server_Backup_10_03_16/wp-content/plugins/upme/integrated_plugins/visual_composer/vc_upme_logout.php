<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class VCExtend_UPME_Logout extends VCExtend_UPME{
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );
 
        // Use this when creating a shortcode addon
        add_shortcode( 'upme_logout_vc', array( $this, 'renderLogout' ) );

    }
 
    public function integrateWithVC() {
        parent::integrateWithVC();
 
        /*
        Add your Visual Composer logic here.
        Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("UPME Logout", 'upme'),
            "description" => __("Logout button for UPME", 'upme'),
            "base" => "upme_logout_vc",
            "class" => "",
            "controls" => "full",
            "icon" => plugins_url('assets/upme-vc.png', __FILE__), 
            "category" => __('UPME', 'upme'),
            "params" => array(
               array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("CSS Class", 'upme'),
                  "param_name" => "class",
                  "value" => '', 
                  "description" => __("CSS class used for the logout link.", 'upme')
              ),
              array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Redirect URL", 'upme'),
                  "param_name" => "redirect_to",
                  "value" => '', 
                  "description" => __("Useres are redirected to the specified URL after logging out.", 'upme')
              ),
              
            )
        ) );
    }
    
    /*
    Shortcode logic how it should be rendered
    */
    public function renderLogout( $atts, $content = null ) {
      extract( shortcode_atts( array(
        'class'   => '',
        'redirect_to' => '',

      ), $atts ) );
      $content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content
     
      $params = '';
      if($class != ''){
          $params .= ' class="' . $class . '" ';
      }
      if($redirect_to != ''){
          $params .= ' redirect_to="' . $redirect_to . '" ';
      }        

      $output = do_shortcode('[upme_logout '. $params .' ]');
      return $output;
    }

}
// Finally initialize code
new VCExtend_UPME_Logout();