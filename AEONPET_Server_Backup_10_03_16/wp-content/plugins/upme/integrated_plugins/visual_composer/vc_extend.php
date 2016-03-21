<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class VCExtend_UPME {
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );


    }
 
    public function integrateWithVC() {
        // Check if Visual Composer is installed
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Visual Compser is required
            add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }        
    }
    
    

    /*
    Show notice if your plugin is activated but Visual Composer is not
    */
    public function showVcVersionNotice() {
        $plugin_data = get_plugin_data(__FILE__);
        echo '
        <div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'upme'), $plugin_data['Name']).'</p>
        </div>';
    }
}
// Finally initialize code
new VCExtend_UPME();


if( function_exists('add_shortcode_param')){
    add_shortcode_param( 'upme_multiple_select', 'upme_multiple_select_field' );
}
function upme_multiple_select_field( $settings, $value ) {

    $display = '<div class="my_param_block">'
             .'<select multiple name="' . esc_attr( $settings['param_name'] ) . '" class="chosen-admin_setting wpb_vc_param_value wpb-textinput ' .
             esc_attr( $settings['param_name'] ) . ' ' .
             esc_attr( $settings['type'] ) . '_field"  >';
                
    
                if(!is_array($value)){
                    $value = (array) explode(',',$value);
                }
                
                foreach($settings['value'] as $key => $v ){
                    $selected = '';
                    if( is_array($value) && in_array($v, $value)){
                        $selected = ' selected="selected" ';
                    }
                    $display .= '<option ' . $selected . 'value="'. $v .'" >'. $key .'</option>';
                }
    
    $display .= '</select>' .
             '</div>'; // This is html markup that will be outputted in content elements edit form
    return $display;
    
//   return '<div class="my_param_block">'
//             .'<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
//             esc_attr( $settings['param_name'] ) . ' ' .
//             esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />' .
//             '</div>'; // This is html markup that will be outputted in content elements edit form
}