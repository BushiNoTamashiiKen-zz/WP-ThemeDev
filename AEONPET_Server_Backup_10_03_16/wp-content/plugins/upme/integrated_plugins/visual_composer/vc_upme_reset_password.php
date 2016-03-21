<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class VCExtend_UPME_ResetPassword extends VCExtend_UPME{
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );
 
        // Use this when creating a shortcode addon
        add_shortcode( 'upme_reset_password_vc', array( $this, 'renderResetPassword' ) );

    }
 
    public function integrateWithVC() {
        parent::integrateWithVC();
 
        /*
        Add your Visual Composer logic here.
        Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("UPME Reset Password", 'upme'),
            "description" => __("Reset Password form for UPME", 'upme'),
            "base" => "upme_reset_password_vc",
            "class" => "",
            "controls" => "full",
            "icon" => plugins_url('assets/upme-vc.png', __FILE__), 
            "category" => __('UPME', 'upme'),
            "params" => array(
                array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Name for the Reset Password Form", 'upme'),
                  "param_name" => "name",
                  "value" => '', 
                  "description" => __("Add specific name to reset password form.", 'upme')
              ) )
        ) );
    }
    
    /*
    Shortcode logic how it should be rendered
    */
    public function renderResetPassword( $atts, $content = null ) {
      extract( shortcode_atts( array( ), $atts ) );
      $content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content

      $output = do_shortcode('[upme_reset_password ]');
      return $output;
    }

}
// Finally initialize code
new VCExtend_UPME_ResetPassword();