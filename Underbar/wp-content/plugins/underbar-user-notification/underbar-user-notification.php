<?php 
/**
 * Plugin name: Underbar User Notifications
 * Description: Sends registration notifications to the app admin and user
 * Version 1.0
 * Author: Thabo Mbuyisa
 * Author URI: http://behance.net/MeatMan
 */

/*
 * redefine new user notification function
 *
 * emails new users their login info
 *
 * @author	Thabo Mbuyisa <thabo@caracri-works.com>
 * @param 	integer $user_id user id
 * @param 	string $plaintext_pass optional password
 */
if( !function_exists('wp_new_user_notification' ) ) {

	function wp_new_user_notification($user_id, $plaintext_pass = '') {

		// set content type to html
        add_filter( 'wp_mail_content_type', 'wpmail_content_type' );
 
        // user
        $user = new WP_User( $user_id );
        $userEmail = stripslashes( $user->user_email );
        $siteUrl = get_site_url();
        $logoUrl = plugin_dir_url( __FILE__ ).'/underbar-app-icon-green.svg';
 
        $subject = 'New Underbar user registration';
        $headers = 'From: Underbar <noreply@underbar.com>';
 
        // admin email
        $message  = "The user:" . ' ' . $user->first_name . ' ' . " has signed up to host an event at Underbar"."\r\n\r\n <br />";
        $message .= '';
        $message .= 'Email: '.$userEmail."\r\n";
        @wp_mail( get_option( 'admin_email' ), 'New Underbar User Sign Up', $message, $headers );
 
        ob_start();
        include plugin_dir_path( __FILE__ ).'/email_welcome.php';
        $message = ob_get_contents();
        ob_end_clean();
 
        @wp_mail( $userEmail, $subject, $message, $headers );
 
        // remove html content type
        remove_filter ( 'wp_mail_content_type', 'wpmail_content_type' );
	}	
}

/**
 * wpmail_content_type
 * allow html emails
 *
 * @author Thabo Mbuyisa <thabo@caracri-works.com>
 * @return string
 */
function wpmail_content_type() {
 
    return 'text/html';
}

/**
 * Registers new dashboard widget that shows a list of recently registered users
 * Renders a meta box in the wp dashboard
 * @param get_users();
 *
 */
function underbar_register_dashboard_widget(){

	global $wp_meta_boxes;

	wp_add_dashboard_widget(
		'urn_dashboard_widget',
		'Latest Bartending Requests',
		'underbar_dashboard_widget_display'
	);

 	$dashboard = $wp_meta_boxes['dashboard']['normal']['core'];	

	$my_widget = array( 'urn_dashboard_widget' => $dashboard['urn_dashboard_widget'] );
 	unset( $dashboard['urn_dashboard_widget'] );

 	$sorted_dashboard = array_merge( $my_widget, $dashboard );
 	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}

/**
 * Handles dashboard widget display.
 * Displays a list of users who have signed up on the site
 * @param get_users();
 *
 */
function underbar_dashboard_widget_display(){

	if(is_admin()){

		$single = true;// Only retrieve one element from the DB meta table

		// Displays a list of Author role users ordered by custom meta key 'user_last_login' (defined in blt_functions.php)
		$blogusers = get_users( 'blog_id=1&orderby=meta_value&meta_key=user_last_login&role=author&meta_key=first_name&order=DESC&number=4' );

		// Display an array of WP_User objects.
		foreach ( $blogusers as $user ){?>

			<ul style="margin: 3px 0; padding: 0 20px; background: #3c3f3e;">
				<a href="/underbar/wp-admin/users.php" style="display: inline-block; padding: 20px 10px; font-family: avenir-light, Helvetica, Arial, sans-serif; font-weight: normal; font-size: 16px; color: #fafafa;">
					<i style="padding-right: 10px;" class="fa fa-user" aria-hidden="true"></i>
					<span><?php echo $user->first_name ?><span>
					<p style="font-weight: normal; margin: 5px 0;"><i style="padding-right: 10px; color: #4dffbb;" class="fa fa-envelope-o" aria-hidden="true"></i><?php echo$user->user_email ?></p>
				</a>
				<p style="float: right; display: block; position: relative; top: 1.4em; color: #4dffbb;">
					<input type="hidden" name="action" value="1">
					<a style="background: #4dffbb; color: #3c3f3e; padding: 8px 10px;" href="/underbar/wp-admin/users.php" type="submit" name="bartender_approved" id="bartender_approved" value="/underbar/wp-admin/users.php">Approve</a>
					<br class="clear">
				</p>
			</ul><?php
		}
		if(empty( $blogusers ) ){?>

			<div style="background-color: #4dffbb; padding: 10px;">
				<p style="text-align: center; font-size: 15px; color: #3c3f3e;"><i style="padding-right: 10px;" class="fa fa-check" aria-hidden="true"></i>No bartending requests today</p>
			</div><?php
		}
	} else {

		echo "You don't have access to this data";
	}
}

// Hook for dashboard widget display
add_action( 'wp_dashboard_setup', 'underbar_register_dashboard_widget' );
