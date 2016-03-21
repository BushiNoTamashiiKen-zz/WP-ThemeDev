<?php
/**
 * Plugin name: Gorilla Restrict Plugin
 * Description: A plugin to toggle hide-show categories on
 * the user-submitted-posts template based on user roles
 *
 * Author: Thabo Mbuyisa
 * Version: 1.0.0
 * License: GPL2
 * Last modified: 02-03-16
 */

// Add actions
add_action('admin_menu', 'gr_plugin_menu');
add_action('admin_init', 'plugin_option');

// Add filters
add_filter('get_terms', 'restrict_categories_register_user');
/*add_filter('get_terms', 'restrict_categories');*/

// Adding Submenu to settings
function gr_plugin_menu() {
	add_options_page(
	'AeonPet Restrict Categories',
	'AeonPet Restrict Categories',
	'manage_options',
	'gr-restrict-content-user',
	'gr_categories_user_settings'
	);
}

// Set up user settings
function gr_categories_user_settings() {
	echo '<div class="wrap">';
	screen_icon();
	echo '<h2>Aeonpet Restrict Settings</h2>';
	echo '<form action="options.php" method="post">';
	do_settings_sections('gr-restrict-content-user');
	settings_fields('gr_settings_group');
	submit_button();
}

// plugin field and sections
function plugin_option() {
	add_settings_section(
	'gr_settings_section',
	'Plugin Options',
	null,
	'gr-restrict-content-user'
	);

	add_settings_field(
	'post-page-id',
	'<label for="post-page-id">Post and page ID to be restricted.</label>',
	'post_page_field',
	'gr-restrict-content-user',
	'gr_settings_section'
	);

	// register settings
	register_setting('gr_settings_group', 'gr_post-id-option');
}

function post_page_field() {
	echo "Enter Post or Page ID separated by comma. <br/>";
	echo '<input style="width:300px; height:40px" type="text" id="post-page-id" name="gr_post-id-option" value="' . get_option('gr_post-id-option') . '">';

}

// Restrict content on front-end
function restrict_categories_register_user($content) {
	global $post;
	$post_database = get_option('gr_post-id-option');
	$post_database = explode(',', $post_database);
	$current_user = wp_get_current_user();

	/* If there is no content, return. */
	if (is_null($content))
		return $content;

	foreach ($post_database as $posts) {
		$posts = trim($posts);
		if ($posts == $post -> ID) {
			if (current_user_can('revisor')) {

				/* Return the private content. */
				return $content;

			}else{

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
//

// add_filter('get_terms', 'restrict_categories');
/*function restrict_categories($categories) {	

	// If we are in the new/edit post page and not an admin, then restrict the categories
	$onPostPage = (strpos($_SERVER['PHP_SELF'], 'post.php') || strpos($_SERVER['PHP_SELF'], 'post-new.php'));	
	if (is_admin() && $onPostPage && !current_user_can('level_10')) {
		$size = count($categories);
		for ($i = 0; $i < $size; $i++) {			
			if ($categories[$i]->slug != 'site_news')
				 unset($categories[$i]);
		}
	}

	return $categories;
}*/


	//GorillArms_Plugin_Setup_Menu function Callback
	//Building my settings page
	/*function ga_settings_init(){

		echo '<div class="wrap">';
		screen_icon();
		echo '<h2>Restrict content to selected user role</h2>';
		echo '<form action="options.php" method="post">';
		do_settings_sections('GorillArms-plugin');
		settings_fields('ga_settings_group');
		submit_button();
		//
		?>
		<!--<div class="wrap">
		    <h2><?php _e('<strong>AeonPet Settings Page</strong>', 'textdomain'); ?></h2>
		    <form action="options.php" method="POST">
			    <?php settings_fields('my-plugin-settings-group'); ?>
			    <?php do_settings_sections('my-plugin-settings-group'); ?>
			    <p>
				Select user role
				<select name="userRolesToggle">
					<option value="">Select...</option>
					<option value="Administrator_role">Administrator</option>
					<option value="Revisor_role">Revisor</option>
					<option value="Editor_role">Editor</option>
				</select>
				</p>
			    <?php submit_button(); ?>
		    </form>
		</div>
		<?php
		/*$userRole = $_POST['Administrator_role'];
		update_option('my-plugin-settings-group', $userRole);
		?>
		</div>-->
		<?php
	}

	//Plugin field and sections
	function plugin_option() {
		add_settings_section(
			'ga_settings_section',
			'Plugin Options',
			null,
			'GorillArms-plugin'
			);

		add_settings_field(
			'post-page-id',
			'<label for="post-page-id">Post and Page ID to be restricted.</label>',
			'post_page_field',
			'GorillArms-plugin',
			'ga_settings_section'
			);

		//Register settings
		register_setting('ga_settings_group', 'ga_post-id-option');

		//Echo the form input field
		function post_page_field(){
			echo "Enter Post or Page ID separated by a comma. <br>";
			echo '<input style="width: 300px; height:80px" type="text" id="post-page-id" name="ga_post-id-option" value="' . get_option('ga_post-id-option') . '">';
		}
	}

	/**
	 * Function that retrieves data saved to database and uses it to restrict post
	 *
	 * @param $content (Manipulates Post or Page content)
	 *
	 *
	function ga_restrict_content_register_user($content){
		global $post; // Set $post as a global variable
		$post_database = get_option('ga_post-id-option');	
		$post_database = explode(',', $post_database);
		$current_user = wp_get_current_user(); //Retrieve the current user

		//If there is no content return
		if (is_null($content)) {
			return $content;

			foreach($post_database as $posts){
				$posts = trim($posts);
				if($posts == $post -> ID){
					if(current_user_can('revisor')){

						//Return the private content
						return $content;

					}else{

						//Return an alternate message
						return '<div align="center" style="color: #4cc2bf; padding: 20px; border: 1px solid border-color: rgd(221, 204, 119); background-color: #3B5998"> You must be a registered user to see this content. <br/>';

					}
				}

			}

			return $content;
		}
	}*/


	/**
	 * Creating the custom template tags for use elsewhere
	 * For use on the user-submited-posts page
	 *
	 */
	function ga_template_tag() {

			if (current_user_can('administrator')) { // check for the current user type (Need to turn this into a function)
					wp_dropdown_categories(array(
					'orderby'      => 'NAME',
					'hide_empty'   => 0, 
					'include'      => $include,
				    'selected'     => (isset($_POST['blt_post_category']) ? $_POST['blt_post_category'] : ''),
					'hierarchical' => 1, 
				    'name'         => 'blt_post_category',
					'id'           => 'blt_post_category',
					'class'        => 'form-control'
				));
			}
			elseif(current_user_can('revisor')){
					wp_dropdown_categories(array(
					'orderby'      => 'NAME',
					'hide_empty'   => 0, 
					'include'      => '30,35',
				    'selected'     => (isset($_POST['blt_post_category']) ? $_POST['blt_post_category'] : ''),
					'hierarchical' => 1, 
				    'name'         => 'blt_post_category',
					'id'           => 'blt_post_category',
					'class'        => 'form-control'
				));
			}
			elseif(current_user_can('editor')){
					wp_dropdown_categories(array(
					'orderby' => 'NAME',
					'hide-empty' => 0,
					'include' => '35,',
					'selected' => (isset($_POST['blt_post_category']) ? $_POST['blt_post_category'] : ''),
					'hierachical' => 1,
					'name' => 'blt_post_category',
					'id' => 'blt_post_category',
					'class' => 'form-control' 
				));
			}

	}