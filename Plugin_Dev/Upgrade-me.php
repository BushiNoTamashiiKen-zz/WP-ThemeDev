<?php
/**
 * Plugin Name: Upgrade Me Baby 1.0
 * Description: A plugin to allow a user to change permission level.
 * Version: 1.0
 * Author: Thabo Mbuyisa
 * Last modified: 07-03-16
 * License: GPL2+
 */

// Successfully separated the logic
/*function send_that_shit_through() {

	$response = "";

  //function to generate response
  function my_contact_form_generate_response($type, $message){

    global $response;

    if($type == "success") $response = "<div class='success'>{$message}</div>";
    else $response = "<div class='error'>{$message}</div>";

  }

  //response messages
  $not_human       = "Human verification incorrect.";
  $missing_content = "Please supply all information.";
  $email_invalid   = "Email Address Invalid.";
  $message_unsent  = "Message was not sent. Try Again.";
  $message_sent    = "Thanks! Your message has been sent.";

  //user posted variables
  $name = $_POST['message_name'];
  $email = $_POST['message_email'];
  $message = $_POST['message_text'];
  $human = $_POST['message_human'];

  //php mailer variables
  $to = get_option('admin_email');
  $subject = "Someone sent a message from ".get_bloginfo('name');
  $headers = 'From: '. $email . "\r\n" .
    'Reply-To: ' . $email . "\r\n";

  if(!$human == 0){
    if($human != 2) my_contact_form_generate_response("error", $not_human); //not human!
    else {

      //validate email
      if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        my_contact_form_generate_response("error", $email_invalid);
      else //email is valid
      {
        //validate presence of name and message
        if(empty($name) || empty($message)){
          my_contact_form_generate_response("error", $missing_content);
        }
        else //ready to go!
        {
          $sent = wp_mail($to, $subject, strip_tags($message), $headers);
          if($sent) my_contact_form_generate_response("success", $message_sent); //message sent!
          else my_contact_form_generate_response("error", $message_unsent); //message wasn't sent
        }
      }
    }
  }
  else if ($_POST['submitted']) my_contact_form_generate_response("error", $missing_content);

}

/**
 * Fetches the form template from the /templates folder.
 *
 */
function ugme_upgrade_request_form() {
	require_once('templates/submit-entry.php'); 
}

/**
 * Generates email content.
 * Sanitizes input values.
 * @param $POST
 *
 */
function ugme_send_email_to_admin() { 

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
	$message = "The user : " . $current_user->display_name . "of User Role:" . $current_user_role . " has requested an account upgrade to Pro User \n";

	// PHP basic input validation if user clicks submit button
	// wp_nonce validation
	if(isset($_POST['ugme_submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

		if( !current_user_can('author') ) { 

			if( is_user_logged_in() ) {

				$NewUR = new WP_User($current_user_id); // Instantiation ritual

				// Set new user role
				$NewUR->set_role('author');

				// Set the mailer variable
				$sent = wp_mail($to, $subject, $message);

				// Redirect after successfully sending mail
				if($sent) {
					require_once('template-parts/submit-success.php'); // Fetch the submit success template
				}
			}
		}else{
			echo '<div>
					<p>Hang on a minute, you are already pro fam!</p>
				  </div>';
		}
	}

	// If user cancels request
	if(isset($_POST['ugme_cancelled']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce') ) {

		wp_redirect(home_url());
		exit;
	}
    /*if(wp_mail($to, $subject, $message)) {

				echo '<div>';
				echo '<p>Thanks for contacting me, expect a response soon.</p>';
				echo '</div>';

			}else{
				echo '<div>';
				echo '<p>Woah! what happened? Try that again.<p>';
				echo '</div>';
			}*/
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

function cf_shortcode() {
	ob_start();
	ugme_send_email_to_admin();
	ugme_upgrade_request_form();

	return ob_get_clean();
}

add_shortcode( 'go_pro_form', 'cf_shortcode' );

?>