<?php
/**
 * Plugin Name: Upgrade Me Baby 1.0
 * Description: A plugin that allows a user to request a permission level upgrade.
 * Version: 1.0
 * Author: Thabo Mbuyisa
 * Last modified: 09-03-16
 * License: GPL2+
 */


/**
 * Fetches the form template from the /templates folder.
 *
 */
function ugme_upgrade_request_form() {
	require_once('template-parts/submit-entry.php'); 
}

/**
 * Generates email content.
 * updates user role
 * @param $POST
 *
 */
function ugme_process_upgrade_request() { 

	// Get the current user
	$current_user = wp_get_current_user();

	// Get the current logged in user's ID to pass to the WP_User instance
	$current_user_id = get_current_user_id(); 

	// Get current user role to pass as a string
	$current_user_role = wp_get_current_user()->roles;

	// PHP mailer variables
	$to = get_option('admin_email');
	$subject = 'User role upgrade request';

	// User posted variables
	$message = "The user : " . $current_user->display_name . " has requested an account upgrade to Pro User \n";


	// PHP basic input validation if user clicks submit button
	// wp_nonce validation
	if(isset($_POST['ugme_submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

		if( is_user_logged_in() ) {


			$NewUR = new WP_User($current_user_id); // The usual instantiation ritual referencing WP_USER

			// Set new user role
			$NewUR->set_role('author');

			// Set the mailer variable
			$sent = wp_mail($to, $subject, $message);

			// Redirect after successfully sending mail
			if($sent) {?>

				<div class="page-body">
					<h1 class="page-title"><i class="fa fa-check" style="color:#A5D535"></i> <?php _e('Go Pro Request has been Submitted!', 'bluthemes'); ?></h1>
					<div class="page-text">
						<hr>
						<p class="lead"><?php _e('Thank you for submitting your request!<br>After our staff has reviewed your request your profile will be upgraded', 'bluthemes'); ?></p>
					</div>
				</div>
				<?php

				$key = nickname;

				// The new updated value
				$update = 'Ta2Kick';

				$updated_user = get_current_user_id();

				$upgrade_status = update_user_meta($updated_user, $key, $update );

				// Add new user meta for retrieval later
				//add_user_meta( $current_user_id, 'Upgrade_Awsomeness_Status', $upgrade_status );

				exit; // Exit the session after processing request
				}
			}
		}

	// If user cancels request
	if(isset($_POST['ugme_cancelled']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce') ) {

		wp_redirect(home_url());
		exit;
	}
}

// Registers new dashboard widget
function urn_register_dashboard_widget() {
	global $wp_meta_boxes;

	wp_add_dashboard_widget(
		'urn_dashboard_widget',
		'Go pro requests',
		'urn_dashboard_widget_display'
	);

 	$dashboard = $wp_meta_boxes['dashboard']['normal']['core'];	

	$my_widget = array( 'urn_dashboard_widget' => $dashboard['urn_dashboard_widget'] );
 	unset( $dashboard['urn_dashboard_widget'] );

 	$sorted_dashboard = array_merge( $my_widget, $dashboard );
 	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;

}

// Renders dashboard widget display
function urn_dashboard_widget_display() {

	// Retrieve user metadata key defined in the Upgrade-me 1.0 plugin
	$key = nickname;

	// Only retrieve one element from the DB meta table
	$single = true;

	// Fetch the user in question
	$updated_user = 3;

	// Fetch the updated user meta value to display
	$upgrade_status = get_user_meta($updated_user, $key, $single );

	//require_once('templates/urn-widget-display.php'); // Fetch widget display template
	$blogusers = get_users( 'blog_id=1&orderby=registered&order=DESC&role=author&number=4' );

	// Set up get_user parameter array
	/*$args = array(
		'role' => ''
		'meta_key' => $key,
		'meta_value' => 
		);*/

	// Array of WP_User objects.
	foreach ( $blogusers as $user ) {
		echo '<ul style="margin: 0px 0px 5px;">
				<div style="padding: 6px 12px; border-radius: 2px; color: #38aeab; font-weight: bold;">' . $upgrade_status . '</div>
			</ul>';
		}
	if(empty( $blogusers ) ) {
		echo '<div style="background-color: #2ea2cc; padding: 10px;">
				<p>No Go Pro user requests today</p>
			</div>';
	}
}

/**
 * Generates error responses if input values don't pass validation test
 *
 */
function ugme_generate_response() {
	//global $response; //Set a global variable $respons
}
	/**
	 * Sanitize $POST variable
	 * Generate email content
	 * Process submission form
	 *
	$name    = sanitize_text_field( $_POST["ug_upgrade_name"] );
	$email   = sanitize_email( $_POST["ug_user_email"] );

	if(isset($POST['upgrade_request_submitted'])) {

		$current_user = wp_get_current_user(); // Set the current user variable

		if(!current_user_can('administrator')) { // Check that Admins doesn't get emails from other Admins

			$to = get_option('admin_email'); // Set the admin email variable
			$subject = 'User role upgrade'; // Hard-code mail subject
			$message = "the user : " .$current_user->display_name . " has requested a role upgrade\n";

			if(wp_mail($to, $subject, $message)) {

				echo '<div>';
				echo '<p>Thanks for contacting me, expect a response soon.</p>';
				echo '</div>';

			}else{
				echo '<div>';
				echo '<p>Woah! what happened? Try that again.<p>';
				echo '</div>';
			}
		}
	}

	/*if(isset($POST['upgrade_request_submitted'])) {
		$redirect_url = blt_get_option('user_posts_redirect_page'); // Set page redirect URL

		//Sanitize input values

		$admins = get_users( array(
			'role' => 'administrator'
			));

		// loop through admins and send them emails
		foreach($admins as $admin){

			$message = __('New Post Submitted & Pending Review: ', 'bluthemes') . $title;
			wp_mail($admin->user_email, __('New Post by User'), $message);
			// wp_mail($admin->user_email, __('New Post by User'), sprintf(__('%s has posted a new article titled %s. You can view it %s here %s', 'bluthemes'), $current_user->user_login, wp_strip_all_tags($title), '<a href="'.get_permalink($post_id).'">', '</a>'));

		}
		wp_redirect( $redirect_url );
		exit;
	}*

}*/

// Add actions
//add_action('admin_post_nopriv_contact_form', 'ug_send_email_to_admin');
//add_action('admin_post_contact_form', 'ug_send_email_to_admin');

/*function deliver_mail() {

	// if the submit button is clicked, send the email
	if ( isset( $_POST['cf-submitted'] ) ) {

		// sanitize form values
		$name    = sanitize_text_field( $_POST["cf-name"] );
		$email   = sanitize_email( $_POST["cf-email"] );

		// get the blog administrator's email address
		$to = get_option( 'admin_email' );

		$headers = "From: $name <$email>" . "\r\n";

		// If email is in the process of being sent, display a success message
		if ( wp_mail( $to, $subject, $message, $headers ) ) {
			echo '<div>';
			echo '<p>Thanks for contacting me, expect a response soon.</p>';
			echo '</div>';
		} else {
			echo 'An unexpected error occurred';
		}
	}
}*/
// Enqueues stylsheet for widget display content
function urn_dashboard_widget_display_enqueues() {

	//wp_enqueue_style('urn-dashboard-widget-styles', plugins_url('', __FILE__ ) . 'templates/urn-dashboard-widget-styles.css');
}

// Add actions and filters
add_action( 'wp_dashboard_setup', 'urn_register_dashboard_widget' );
add_action('admin_enqueue_scripts','urn_dashboard_widget_display_enqueues'); // Enqueue stylesheet for display template

// Creates a shortcode to call the template within a post
function cf_shortcode() {
	ob_start();
	ugme_process_upgrade_request();
	ugme_upgrade_request_form();

	return ob_get_clean();
}

add_shortcode( 'go_pro_form', 'cf_shortcode' );

?>