<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class VCExtend_UPME_NonMember extends VCExtend_UPME{
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );
 
        // Use this when creating a shortcode addon
        add_shortcode( 'upme_non_member_vc', array( $this, 'renderNonMemberContent' ) );

    }
 
    public function integrateWithVC() {
        parent::integrateWithVC();
 
        /*
        Add your Visual Composer logic here.
        Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("UPME Non Member Content", 'upme'),
            "description" => __("Content for Guests", 'upme'),
            "base" => "upme_non_member_vc",
            "class" => "",
            "controls" => "full",
            "icon" => plugins_url('assets/upme-vc.png', __FILE__), 
            "category" => __('UPME', 'upme'),
            "params" => array(
                array(
                    "type" => "textarea_html",
                    "holder" => "div",
                    "class" => "",
                    "heading" => __( "Content", "upme" ),
                    "param_name" => "content", 
                    "value" => "",
                    "description" => __( "Enter your content for non-members.", "upme" )
                 )
              
            )
        ) );
    }
    
    /*
    Shortcode logic how it should be rendered
    */
    public function renderNonMemberContent( $atts, $content = null ) {

      $content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content
      $output = do_shortcode('[upme_non_member]'. $content .'[/upme_non_member]');
      return $output;
    }

}
// Finally initialize code
new VCExtend_UPME_NonMember();