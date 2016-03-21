<?php


# 	
# 	USER REGISTRATION/LOGIN MODAL
# 	========================================================================================
#   Attach this function to the footer if the user isn't logged in
# 	========================================================================================
# 		

	function blt_login_register_modal() {

		// only show the registration/login form to non-logged-in members
		if(!is_user_logged_in()){ 


			$captcha_enabled = blt_get_option('enable_captcha', false);
			$show_terms_conditions = blt_get_option('show_terms_conditions', false);

			?>
			<div class="modal fade modal-narrow" id="blt-user-modal" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog" data-active-tab="">
					<div class="modal-content">
						<div class="modal-body">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php

							if(get_option('users_can_register')){ ?>

								<!-- Register form -->
								<div class="blt-register">
							 
									<h3><?php _e('Register New Account', 'bluthemes'); ?></h3>
									<hr>

									<form id="blt_registration_form" action="<?php echo home_url( '/' ); ?>" method="POST">
										<?php do_action( 'wordpress_social_login' ); ?>
										<div class="form-group">
											<label><?php _e('Username', 'bluthemes'); ?></label>
											<input class="form-control input-lg required" name="blt_user_login" type="text"/>
										</div>
										<div class="form-group">
											<label for="blt_user_email"><?php _e('Email', 'bluthemes'); ?></label>
											<input class="form-control input-lg required" name="blt_user_email" id="blt_user_email" type="email"/>
										</div><?php

										if($captcha_enabled){ ?>
											<div class="form-group">
												<div class="g-recaptcha" data-sitekey="<?php echo blt_get_option('google_api_public_key') ?>"></div>
											</div><?php
										}

										if($show_terms_conditions){ ?>
											<div class="form-group">
												<div class="checkbox">
													<label><input name="blt_terms" type="checkbox"> <?php echo sprintf( __( 'I accept the <a target="_blank" href="%s">Terms & Conditions</a>', 'bluthemes' ), blt_get_option('terms_conditions_page') ) ?></label>
												</div>
											</div><?php
										} ?>

										<div class="form-group">
											<input type="hidden" name="action" value="blt_register_member"/>
											<button class="btn btn-theme btn-lg" data-loading-text="<?php _e('Loading...', 'bluthemes') ?>" type="submit"><?php _e('Sign up', 'bluthemes'); ?></button>
										</div>
										<?php wp_nonce_field( 'ajax-login-nonce', 'register-security' ); ?>
									</form>
									<div class="blt-errors"></div>
								</div>

								<!-- Login form -->
								<div class="blt-login">
							 
									<h3><?php _e('Login', 'bluthemes'); ?></h3>
									<hr>
							 
									<form id="blt_login_form" action="<?php echo home_url( '/' ); ?>" method="post">
										<?php do_action( 'wordpress_social_login' ); ?>
										<div class="form-group">
											<label><?php _e('Username', 'bluthemes') ?></label>
											<input class="form-control input-lg required" name="blt_user_login" type="text"/>
										</div>
										<div class="form-group">
											<label for="blt_user_pass"><?php _e('Password', 'bluthemes')?> <a class="pull-right" href="#blt-reset-password"><?php _e('Lost Password?', 'bluthemes') ?></a></label>
											<input class="form-control input-lg required" name="blt_user_pass" id="blt_user_pass" type="password"/>
										</div>
										<div class="form-group">
											<input type="hidden" name="action" value="blt_login_member"/>
											<button class="btn btn-theme btn-lg" data-loading-text="<?php _e('Loading...', 'bluthemes') ?>" type="submit"><?php _e('Login', 'bluthemes'); ?></button>
										</div>
										<?php wp_nonce_field( 'ajax-login-nonce', 'login-security' ); ?>
									</form>
									<div class="blt-errors"></div>
								</div>

								<!-- Lost Password form -->
								<div class="blt-reset-password">
							 
									<h3><?php _e('Reset Password', 'bluthemes'); ?></h3>
									<hr>
							 
									<form id="blt_reset_password_form" action="<?php echo home_url( '/' ); ?>" method="post">
										<div class="form-group">
											<label for="blt_user_or_email"><?php _e('Username or E-mail', 'bluthemes') ?></label>
											<input class="form-control input-lg required" name="blt_user_or_email" id="blt_user_or_email" type="text"/>
										</div>
										<div class="form-group">
											<input type="hidden" name="action" value="blt_reset_password"/>
											<button class="btn btn-theme btn-lg" data-loading-text="<?php _e('Loading...', 'bluthemes') ?>" type="submit"><?php _e('Get new password', 'bluthemes'); ?></button>
										</div>
										<?php wp_nonce_field( 'ajax-login-nonce', 'password-security' ); ?>
									</form>
									<div class="blt-errors"></div>
								</div>

								<div class="blt-loading">
									<p><i class="fa fa-refresh fa-spin"></i><br><?php _e('Loading...', 'bluthemes') ?></p>
								</div><?php

							}else{
								echo '<h3>'.__('Login access is disabled', 'bluthemes').'</h3>';
							} ?>
						</div>
						<div class="modal-footer">
							<span class="blt-register-footer"><?php _e('Don\'t have an account?', 'bluthemes'); ?> <a href="#blt-register"><?php _e('Sign Up', 'bluthemes'); ?></a></span>
							<span class="blt-login-footer"><?php _e('Already have an account?', 'bluthemes'); ?> <a href="#blt-login"><?php _e('Login', 'bluthemes'); ?></a></span>
						</div>				
					</div>
				</div>
			</div><?php

		}
	}
	add_action('wp_footer', 'blt_login_register_modal');




# 	
# 	AJAX FUNCTION
# 	========================================================================================
#   These function handle the submitted data from the login/register modal forms
# 	========================================================================================
# 		

	// LOGIN
	function blt_login_member(){

  		// Get variables
		$user_login		= $_POST['blt_user_login'];	
		$user_pass		= $_POST['blt_user_pass'];


		// Check CSRF token
		if( !check_ajax_referer( 'ajax-login-nonce', 'login-security', false) ){
			echo json_encode(array('error' => true, 'message'=> '<div class="alert alert-danger">'.__('Session token has expired, please reload the page and try again', 'bluthemes').'</div>'));
		}
	 	
	 	// Check if input variables are empty
	 	elseif(empty($user_login) or empty($user_pass)){
			echo json_encode(array('error' => true, 'message'=> '<div class="alert alert-danger">'.__('Please fill all form fields', 'bluthemes').'</div>'));
	 	}

	 	else{

	 		$user = wp_signon( array('user_login' => $user_login, 'user_password' => $user_pass), false );

		    if(is_wp_error($user)){
				echo json_encode(array('error' => true, 'message'=> '<div class="alert alert-danger">'.$user->get_error_message().'</div>'));
			}
		    else{
				echo json_encode(array('error' => false, 'message'=> '<div class="alert alert-success">'.__('Login successful, reloading page...', 'bluthemes').'</div>'));
			}
	 	}

	 	die();
	}
	add_action('wp_ajax_blt_login_member', 'blt_login_member');
	add_action('wp_ajax_nopriv_blt_login_member', 'blt_login_member');



	// REGISTER
	function blt_register_member(){
  		// Get variables
		$user_login	= $_POST['blt_user_login'];	
		$user_email	= $_POST['blt_user_email'];

		$show_terms_conditions = blt_get_option('show_terms_conditions', false);


		if(blt_get_option('enable_captcha', false)){

			require_once get_template_directory().'/inc/vendor/recaptcha/src/ReCaptcha/ReCaptcha.php';
			require_once get_template_directory().'/inc/vendor/recaptcha/src/ReCaptcha/RequestMethod.php';
			require_once get_template_directory().'/inc/vendor/recaptcha/src/ReCaptcha/RequestParameters.php';
			require_once get_template_directory().'/inc/vendor/recaptcha/src/ReCaptcha/Response.php';
			require_once get_template_directory().'/inc/vendor/recaptcha/src/ReCaptcha/RequestMethod/Post.php';
			require_once get_template_directory().'/inc/vendor/recaptcha/src/ReCaptcha/RequestMethod/Socket.php';
			require_once get_template_directory().'/inc/vendor/recaptcha/src/ReCaptcha/RequestMethod/SocketPost.php';

			$secret = blt_get_option('google_api_private_key');

			$recaptcha = new \ReCaptcha\ReCaptcha($secret);

			$resp = $recaptcha->verify( $_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR'] );

			if(!$resp->isSuccess()){
			    // $errors = $resp->getErrorCodes();
				echo json_encode(array('error' => true, 'message'=> '<div class="alert alert-danger">'.__('Captcha failed, please try again.', 'bluthemes').'</div>'));
				die();
			}

		}


		
		// Check CSRF token
		if( !check_ajax_referer( 'ajax-login-nonce', 'register-security', false) ){
			echo json_encode(array('error' => true, 'message'=> '<div class="alert alert-danger">'.__('Session token has expired, please reload the page and try again', 'bluthemes').'</div>'));
			die();
		}
	 	
	 	// Check if input variables are empty
	 	elseif(empty($user_login) or empty($user_email)){
			echo json_encode(array('error' => true, 'message'=> '<div class="alert alert-danger">'.__('Please fill all form fields', 'bluthemes').'</div>'));
			die();
	 	}

	 	elseif($show_terms_conditions and !isset($_POST['blt_terms'])){
			echo json_encode(array('error' => true, 'message'=> '<div class="alert alert-danger">'.__('Please accept the terms and conditions before registering', 'bluthemes').'</div>'));
			die();
	 	}
		
		$errors = register_new_user($user_login, $user_email);	
		
		if(is_wp_error($errors)){

			$registration_error_messages = $errors->errors;

			$display_errors = '<div class="alert alert-danger">';
			
				foreach($registration_error_messages as $error){
					$display_errors .= '<p>'.$error[0].'</p>';
				}

			$display_errors .= '</div>';

			echo json_encode(array('error' => true, 'message' => $display_errors));

		}else{
			echo json_encode(array('error' => false, 'message' => '<div class="alert alert-success">'.__( 'Registration complete. Please check your e-mail.', 'blutheme').'</p>'));
		}
	 

	 	die();
	}
	add_action('wp_ajax_blt_register_member', 'blt_register_member');
	add_action('wp_ajax_nopriv_blt_register_member', 'blt_register_member');


	// RESET PASSWORD
	function blt_reset_password(){

		
  		// Get variables
		$username_or_email = $_POST['blt_user_or_email'];

		// Check CSRF token
		if( !check_ajax_referer( 'ajax-login-nonce', 'password-security', false) ){
			echo json_encode(array('error' => true, 'message'=> '<div class="alert alert-danger">'.__('Session token has expired, please reload the page and try again', 'bluthemes').'</div>'));
		}		

	 	// Check if input variables are empty
	 	elseif(empty($username_or_email)){
			echo json_encode(array('error' => true, 'message'=> '<div class="alert alert-danger">'.__('Please fill all form fields', 'bluthemes').'</div>'));
	 	}

	 	else{

			$username = is_email($username_or_email) ? sanitize_email($username_or_email) : sanitize_user($username_or_email);

			$user_forgotten = blt_lostPassword_retrieve($username);
			
			if(is_wp_error($user_forgotten)){
			
				$lostpass_error_messages = $user_forgotten->errors;

				$display_errors = '<div class="alert alert-error">';
				foreach($lostpass_error_messages as $error){
					$display_errors .= '<p>'.$error[0].'</p>';
				}
				$display_errors .= '</div>';
				
				echo json_encode(array('error' => true, 'message' => $display_errors));
			}else{
				echo json_encode(array('error' => false, 'message' => '<p class="alert alert-success">'.__('Password Reset. Please check your email.', 'bluthemes').'</p>'));
			}
	 	}

	 	die();
	}	
	add_action('wp_ajax_blt_reset_password', 'blt_reset_password');
	add_action('wp_ajax_nopriv_blt_reset_password', 'blt_reset_password');


	function blt_lostPassword_retrieve( $user_data ) {
		
		global $wpdb, $current_site, $wp_hasher;

		$errors = new WP_Error();

		if(empty($user_data)){
			$errors->add( 'empty_username', __( 'Please enter a username or e-mail address.', 'bluthemes' ) );
		}elseif(strpos($user_data, '@')){
			$user_data = get_user_by( 'email', trim( $user_data ) );
			if(empty($user_data)){
				$errors->add( 'invalid_email', __( 'There is no user registered with that email address.', 'bluthemes'  ) );
			}
		}else{
			$login = trim( $user_data );
			$user_data = get_user_by('login', $login);
		}

		if($errors->get_error_code()){
			return $errors;
		}

		if(!$user_data){
			$errors->add('invalidcombo', __('Invalid username or e-mail.', 'bluthemes'));
			return $errors;
		}

		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;

		do_action('retrieve_password', $user_login);

		$allow = apply_filters('allow_password_reset', true, $user_data->ID);

		if(!$allow){
			return new WP_Error( 'no_password_reset', __( 'Password reset is not allowed for this user', 'bluthemes' ) );
		}
		elseif(is_wp_error($allow)){
			return $allow;
		}

		$key = wp_generate_password(20, false);

		do_action('retrieve_password_key', $user_login, $key);

		if(empty($wp_hasher)){
			require_once ABSPATH.'wp-includes/class-phpass.php';
			$wp_hasher = new PasswordHash(8, true);
		}

		$hashed = $wp_hasher->HashPassword($key);

		$wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));
		
		$message = __('Someone requested that the password be reset for the following account:', 'bluthemes' ) . "\r\n\r\n";
		$message .= network_home_url( '/' ) . "\r\n\r\n";
		$message .= sprintf( __( 'Username: %s', 'bluthemes' ), $user_login ) . "\r\n\r\n";
		$message .= __('If this was a mistake, just ignore this email and nothing will happen.', 'bluthemes' ) . "\r\n\r\n";
		$message .= __('To reset your password, visit the following address:', 'bluthemes' ) . "\r\n\r\n";
		$message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n\r\n";
		
		if ( is_multisite() ) {
			$blogname = $GLOBALS['current_site']->site_name;
		} else {
			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}

		$title   = sprintf( __( '[%s] Password Reset', 'bluthemes' ), $blogname );
		$title   = apply_filters( 'retrieve_password_title', $title );
		$message = apply_filters( 'retrieve_password_message', $message, $key );

		if ( $message && ! wp_mail( $user_email, $title, $message ) ) {
			$errors->add( 'noemail', __( 'The e-mail could not be sent.<br />Possible reason: your host may have disabled the mail() function.', 'bluthemes' ) );

			return $errors;

			wp_die();
		}

		return true;
	}	


    function wsl_use_fontawesome_icons( $provider_id, $provider_name, $authenticate_url ){ ?>
       <a rel="nofollow" href="<?php echo $authenticate_url; ?>" data-provider="<?php echo $provider_id ?>" class="btn btn-lg wp-social-login-provider wp-social-login-provider-<?php echo strtolower( $provider_id ); ?>">
          <span>
             <i class="fa fa-<?php echo strtolower( $provider_id ); ?>"></i>
             <?php echo $provider_name; ?>
          </span>
       </a><?php
    }
    add_filter( 'wsl_render_auth_widget_alter_provider_icon_markup', 'wsl_use_fontawesome_icons', 10, 3 );	