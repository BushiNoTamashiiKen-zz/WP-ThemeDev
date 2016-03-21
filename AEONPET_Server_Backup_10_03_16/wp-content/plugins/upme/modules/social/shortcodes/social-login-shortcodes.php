<?php
/**
 * Display social login button for specified networks
 *
 * @access public
 * @since 1.0
 * @param array $atts Shortcode attributes
 * @param string $content
 * @return string $html HTML button for social login 
 */
add_shortcode( 'upme_social_login_button', 'upme_social_login_button' );
function upme_social_login_button( $atts, $content = null ){

	/* Merge the default and provided paramneters for shortcodes */
	extract(shortcode_atts(array(
		'network'	=>	'',
		'design'	=>  '',
        'upme_user_role' =>  '',
     	), $atts));
    
    $user_role_param = '';
    if($upme_user_role != ''){
        $user_role_param = '&upme_user_role='.$upme_user_role;
    }

	$link = '?upme_social_login='.$network.'&upme_social_action=login'.$user_role_param;

	$social_network_keys = array('Facebook' => 'facebook','Linkedin' => 'linkedin','Twitter' => 'twitter', 'Google' => 'google');

	/* Include styles for social icon fonts */
	wp_register_style('upme-social-icons', upme_url . 'modules/social/css/zocial/zocial.css');
    wp_enqueue_style('upme-social-icons');


	$html = '<a href="' . $link . '" class="zocial '. $design .' '. $social_network_keys[$network] .' '. $design . '">
			   '. __('Login with ','upme'). ucfirst($network) .'
			  </a>';

	return $html;
}


/**
 * Display social login buttons independetly from UPME registration
 *
 * @access public
 * @since 1.0
 * @param array $atts Shortcode attributes
 * @param string $content
 * @return string $html HTML button for social login 
 */
add_shortcode( 'upme_social_login_panel', 'upme_social_login_panel' );
function upme_social_login_panel( $atts, $content = null ){

	extract(shortcode_atts(array(
		'class'	=>	'',
		'design'	=>  '',
     	), $atts));

	$upme_settings = get_option('upme_options');
	$allowed_networks = isset($upme_settings['social_login_allowed_networks']) ? $upme_settings['social_login_allowed_networks'] :array();
	
	if (get_option('users_can_register') == '1') {

		$html = '<div align="center" style="margin:10px">';
		$html .= '<div align="center" class="upme-social-header"  >'. $upme_settings['social_login_display_message'] .'</div>';
		foreach ($allowed_networks as $key => $network) {
			$network = ucfirst($network);
			$html .= do_shortcode('[upme_social_login_button class="' . $class . '" network="'.$network.'" design="'.$design.'" ]');
			
		}

		$html .= '</div>';
	}

    return $html;
}
