<?php
#   
#   Template Name: Bluthemes - User Settings
#   ========================================================================================
#   This page template shows settings which can be changed by users
#   ========================================================================================
#    
	
	$user_id = get_current_user_id();

	if(isset($_POST['blt_user_settings_nounce']) and wp_verify_nonce($_POST['blt_user_settings_nounce'], 'blt_user_settings') and !defined('BLT_DEMO')) {


		$user_email			= sanitize_text_field(wp_unslash($_POST['user_email']));
		$user_display_name	= sanitize_text_field($_POST['user_display_name']);
		$user_description	= sanitize_text_field(trim($_POST['user_description']));
		$user_website		= esc_url_raw($_POST['user_website'] == 'http://' ? '' : $_POST['user_website']);
		$user_website 		= preg_match('/^('.implode('|', array_map('preg_quote', wp_allowed_protocols())).'):/is', $user_website) ? $user_website : 'http://'.$user_website;
		
		# 
		# Validate
		# 
			// Make sure the user is logged in
			if(!is_user_logged_in()){
				blt_errors()->add('not_logged_in', __('You need to be logged in to do that', 'bluthemes'));
			}
			elseif(!is_email($user_email)){
	            blt_errors()->add('invalid_email', __('The email address isn&#8217;t correct.', 'bluthemes'));
	        }
	        elseif(($owner_id = email_exists($user_email)) and ($owner_id != $user_id)){
	            blt_errors()->add('email_exists', __('This email is already registered, please choose another one.', 'bluthemes'));
	        }


		$errors = blt_errors()->get_error_codes();

		// If there are no errors, continue and create the post
		if( empty($errors) ){


			// Update the user.
			$user_id = wp_update_user(array(
				'ID' => $user_id,
				'user_url' => $user_website,
				'user_email' => $user_email,
				'display_name' => $user_display_name,
				'description' => $user_description,
			));

			if ( is_wp_error( $user_id ) ) {
				// There was an error, probably that user doesn't exist.
			} else {
				$success = true;
			}	
		}	

	}


get_header(); 

$current_user = get_userdata( $user_id );

?>
<div id="site-content" class="clearfix">

	<?php 

		// Notifications
		blt_show_error_messages(); 

		if(isset($success)){
			echo '<div class="alert alert-success">'.__('User settings saved successfully', 'bluthemes').'</div>';
		}

	?>

	<div id="site-content-column"><?php

		if(have_posts()){ 

			while(have_posts()){ the_post(); ?>
		
				<article <?php post_class(); ?>>
					
					<h1 class="single-title"><?php the_title(); ?></h1>
					
					<?php blt_post_thumbnail(); ?>

					<div class="single-text clearfix"><?php 


						if(!$current_user){

							echo '<div class="alert alert-danger">' . __('You need to logged in to view this page', 'bluthemes') . '</div>';

						}else{

							the_content(sprintf(
								__( 'Continue reading %s', 'bluthemes' ),
								the_title( '<span class="screen-reader-text">', '</span>', false )
							)); ?>

							<form action="<?php the_permalink() ?>" class="form" method="POST">

								<div class="form-group clearfix">
									<img class="settings-avatar" src="<?php echo get_avatar_url( get_current_user_id(), array('size' => 50) ); ?>">
									<a class="btn btn-default btn-sm" style="margin-top:10px;" target="_blank" href="http://gravatar.com/"><i class="fa fa-wordpress"></i> Change on Gravatar.com</a>
								</div>
								
								<div class="form-group">
									<label for="user_username"><?php _e('Username', 'bluthemes'); ?></label>
									<input type="email" id="user_username" class="form-control disabled" disabled="disabled" value="<?php echo $current_user->user_login; ?>">
									<p class="help-block text-muted"><small><?php _e('Username can not be changed', 'bluthemes'); ?></small></p>
								</div>

								<div class="form-group">
									<label for="user_email"><?php _e('Email', 'bluthemes'); ?></label>
									<input type="email" id="user_email" name="user_email" class="form-control" value="<?php echo $current_user->user_email; ?>">
								</div>

								<div class="form-group">
									<label for="user_display_name"><?php _e('Display Name', 'bluthemes'); ?></label>
									<input type="text" id="user_display_name" name="user_display_name" class="form-control" value="<?php echo $current_user->display_name; ?>">
								</div>

								<div class="form-group">
									<label for="user_website"><?php _e('Website URL', 'bluthemes'); ?></label>
									<input type="text" id="user_website" name="user_website" class="form-control" value="<?php echo $current_user->user_url; ?>">
								</div>

								<div class="form-group">
									<label for="user_description"><?php _e('Bio', 'bluthemes'); ?></label>
									<textarea class="form-control" name="user_description" id="user_description" cols="30" rows="10"><?php the_author_meta( 'description' ); ?></textarea>
								</div>

								<div class="form-group">
									<button class="btn btn-lg btn-theme"><?php _e('Save Settings', 'bluthemes') ?></button>
								</div>

								<?php wp_nonce_field( 'blt_user_settings', 'blt_user_settings_nounce' ); ?>

							</form><?php

						} ?>
					</div>

				</article><?php
			}				
		
		 } ?>

	</div><?php

	# 	
	# 	SIDEBAR
	# 	========================================================================================
	#   Load the sidebar if needed
	# 	========================================================================================
	# 		
	if(in_array(blt_get_option('sidebar_layout', 'right'), array('left', 'right'), true) or in_array(get_field('blt_sidebar'), array('left', 'right'), true)){	
		get_sidebar();
	} ?>


</div>

<?php get_footer(); ?>
