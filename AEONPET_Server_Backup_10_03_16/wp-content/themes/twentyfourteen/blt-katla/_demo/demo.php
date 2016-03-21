<?php

function blt_demo_wp_footer(){

	wp_enqueue_script('blt-demo-plugins-scripts', get_template_directory_uri().'/_demo/assets/js/plugins.js', array('jquery'), BLT_THEME_VERSION, true);
	wp_enqueue_script('blt-demo-script', get_template_directory_uri().'/_demo/assets/js/script.js', array('jquery'), BLT_THEME_VERSION, true);
	wp_enqueue_style( 'blt-demo-plugins-styles', get_template_directory_uri() . '/_demo/assets/css/plugins.css', array(), BLT_THEME_VERSION, 'all' );

}
add_action('wp_footer', 'blt_demo_wp_footer');