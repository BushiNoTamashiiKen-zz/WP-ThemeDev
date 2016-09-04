<?php
add_action('init', 'register_bar_custom_post');

function register_bar_custom_post() {
	register_post_type('master', array(
		'labels' => array(
			'name' => 'マスター',
			'singular_name' => 'マスター',
		),
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'query_var' => true,
		'rewrite' => false,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 5,
		'supports' => array('title','editor'),
	));
};

?>
