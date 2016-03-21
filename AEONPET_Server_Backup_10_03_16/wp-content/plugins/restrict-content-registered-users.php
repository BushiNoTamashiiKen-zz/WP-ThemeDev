<?php
/*
 Plugin Name: Restrict Content to registered users
 Plugin URI: http://hongkiat.com
 Description: Restricing content to registered users only
 Version: 0.1
 Author: Agbonghama Collins
 Author URI: http://tech4sky.com
 */

add_action('admin_menu', 'rcru_plugin_menu');

// Adding Submenu to settings
function rcru_plugin_menu() {
	add_options_page(
	'Restrict content To Registered User',
	'Restrict content To Registered User',
	'manage_options',
	'rcru-restrict-content-user',
	'rcru_content_user_settings'
	);
}

function rcru_content_user_settings() {
	echo '<div class="wrap">';
	screen_icon();
	echo '<h2>Restrict content To Registered User</h2>';
	echo '<form action="options.php" method="post">';
	do_settings_sections('rcru-restrict-content-user');
	settings_fields('rcru_settings_group');
	submit_button();
}

// plugin field and sections
function plugin_option() {
	add_settings_section(
	'rcru_settings_section',
	'Plugin Options',
	null,
	'rcru-restrict-content-user'
	);

	add_settings_field(
	'post-page-id',
	'<label for="post-page-id">Post and page ID to be restricted.</label>',
	'post_page_field',
	'rcru-restrict-content-user',
	'rcru_settings_section'
	);

	// register settings
	register_setting('rcru_settings_group', 'rcru_post-id-option');
}

function post_page_field() {
	echo "Enter Post or Page ID separated by comma. <br/>";
	echo '<input style="width:300px; heigh:8	0px" type="text" id="post-page-id" name="rcru_post-id-option" value="' . get_option('rcru_post-id-option') . '">';

}

add_action('admin_init', 'plugin_option');

function restrict_content_register_user($content) {
	global $post;
	$post_database = get_option('rcru_post-id-option');
	$post_database = explode(',', $post_database);
	$current_user = wp_get_current_user();

	/* If there is no content, return. */
	if (is_null($content))
		return $content;

	foreach ($post_database as $posts) {
		$posts = trim($posts);
		if ($posts == $post -> ID) {
			if (username_exists($current_user -> user_login)) {

				/* Return the private content. */
				return $content;
			} else {

				/* Return an alternate message. */
				return '<div align="center" style="color: #fff; padding: 20px; border: 1px solid border-color: rgb(221, 204, 119); background-color: #3B5998">
	You must be a registered user to read this content.
	<br/>
	<a style="font-size: 20px;" href="' . get_site_url() . '/wp-login.php?action=register">Register Here</a>
</div>';
			}

		}
	}
	return $content;
}

add_filter('the_content', 'restrict_content_register_user');


/**
 * Shortcode 
 */
 
 
/* Register shortcodes in 'init'. */
add_action('init', 'rcru_user_shortcodes');

/* Function for registering the shortcode. */
function rcru_user_shortcodes() {

	/* Adds the [rcru-private] shortcode. */
	add_shortcode('rcru-private', 'rcru_shortcode');
}

/* Function for handling shortcode output. */
function rcru_shortcode($attr, $content = '') {

	/* Check if the current user has the 'read_private_content' capability. */
	$current_reader = wp_get_current_user();
	if (!username_exists($current_reader -> user_login)) {

		/* Return an alternate message. */
		return '<div align="center" style="color: #fff; padding: 20px; border: 1px solid border-color: rgb(221, 204, 119); background-color: #3B5998">
	You must be a registered user to read this content.
	<br/>
	<a style="font-size: 20px;" href="' . get_site_url() . '/wp-login.php?action=register">Register Here</a>
</div>';

	}
}


/**
 * Meta box
 */

function rcru_mb_create() {
	/**
	 * @array $screens Write screen on which to show the meta box
	 * @values post, page, dashboard, link, attachment, custom_post_type
	 */
	$screens = array(
	'post',
	'page'
	);

	foreach ($screens as $screen) {

		add_meta_box('rcru-meta',
		'Restrict Post / Page',
		'rcru_mb_function',
		$screen,
		'normal',
		'high');
	}
}

add_action('add_meta_boxes', 'rcru_mb_create');

function rcru_mb_function($post) {

	//retrieve the metadata values if they exist
	$restrict_post = get_post_meta($post -> ID, '_rcru_restrict_content', true);

	// Add an nonce field so we can check for it later when validating
	wp_nonce_field('rcru_inner_custom_box', 'rcru_inner_custom_box_nonce');

	echo '<div style="margin: 10px 100px; text-align: center">
    <table>
		<tr>
		<th scope="row"><label for="rcru-restrict-content">Restrict content?</label></th>
			<td>
                        <input type="checkbox" value="1" name="rcru_restrict_content" id="rcru-restrict-content"' . checked($restrict_post, 1, false) . '>
                        <span class="description">Checking this setting will restrict this post to only registered users.</span>
                    </td>
                    </tr>
	</table>
</div>';
}

function rcru_mb_save_data($post_id) {
	/*
	 * We need to verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times.
	 */

	// Check if our nonce is set.
	if (!isset($_POST['rcru_inner_custom_box_nonce']))
		return $post_id;

	$nonce = $_POST['rcru_inner_custom_box_nonce'];

	// Verify that the nonce is valid.
	if (!wp_verify_nonce($nonce, 'rcru_inner_custom_box'))
		return $post_id;

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $post_id;

	// Check the user's permissions.
	if ('page' == $_POST['post_type']) {

		if (!current_user_can('edit_page', $post_id))
			return $post_id;
	} else {

		if (!current_user_can('edit_post', $post_id))
			return $post_id;
	}

	/* OK, its safe for us to save the data now. */

	// If old entries exist, retrieve them
	$old_restrict_post = get_post_meta($post_id, '_rcru_restrict_content', true);

	// Sanitize user input.
	$restrict_post = sanitize_text_field($_POST['rcru_restrict_content']);

	// Update the meta field in the database.
	update_post_meta($post_id, '_rcru_restrict_content', $restrict_post, $old_restrict_post);

}

//hook to save the meta box data
add_action('save_post', 'rcru_mb_save_data');

function restrict_content_metabox($content) {
	global $post;
	//retrieve the metadata values if they exist
	$post_restricted = get_post_meta($post -> ID, '_rcru_restrict_content', true);
	
	// if the post or page has restriction and the user isn't registered
	// display the error notice
	if ($post_restricted == 1 &&  (!username_exists(wp_get_current_user()->user_login)) ) {

		return '<div align="center" style="color: #fff; padding: 20px; border: 1px solid border-color: rgb(221, 204, 119); background-color: #3B5998">
	You must be a registered user to read this content.
	<br/>
	<a style="font-size: 20px;" href="' . get_site_url() . '/wp-login.php?action=register">Register Here</a>
</div>';
	}
	
	return $content;

}

// hook the function to the post content to effect the change
add_filter('the_content', 'restrict_content_metabox');
