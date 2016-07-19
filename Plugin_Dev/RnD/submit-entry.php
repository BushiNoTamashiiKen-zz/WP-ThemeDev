<?php 
/**
 * Generic front-end form submission template.
 * Customizable to fit theme styling by referrencing theme classes
 * @param $POST
 *
 */

/*$missing_username = '';
$current_user = wp_get_current_user();
$username = sanitize_text_field($_POST['username']);

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
}*/
?>
<form class="blt_posts_form" action="<?php the_permalink(); ?>" method="post">

	<fieldset class="form-group">
		<h3>Your User Role will be upgraded to pro status</h3>
		<p>Submit to proceed otherwise cancel</p>

		<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>

		<input type="hidden" name="action" value="1">
		<button class="btn btn-theme btn-lg" name="ugme_submitted" id="ugme_submitted" type="submit"><?php _e('Submit request', 'bluthemes'); ?>
		</button>
		<span></span>
		<button class="btn btn-theme btn-lg" name="ugme_cancelled" id="ugme_submitted" type="submit"><?php _e('Cancel', 'bluthemes'); ?>
		</button>
	</fieldset>
</form>