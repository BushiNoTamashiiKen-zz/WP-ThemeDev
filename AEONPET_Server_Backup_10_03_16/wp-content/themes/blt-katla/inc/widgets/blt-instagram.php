<?php
class blt_instagram extends WP_Widget{

	public function __construct() {
		parent::__construct('blt_instagram', 'Bluthemes - Instagram', array('classname' => 'blt_instagram bw_slider', 'description' => __( 'Displays recent instagram images in a widget. Recommended for the sidebar.', 'bluthemes')));
	}

	function form($instance){

	    $instance = wp_parse_args((array)$instance, array(
	    	'title' => '',
	    	'access_token' => '',
	    	'user_id' => '',
	    	'caching_time' => '30',
	    )); 

	    ?>
	  	<p>
	  		<strong>Instructions</strong><br>
	  		You need to authenticate yourself to our instagram app to get an access token to retrieve your images and display them on your page.<br>
		  	<ol>
		    	<li>Attain your access token and user id <a href="https://api.instagram.com/oauth/authorize/?client_id=5961fdebd2e940f9956337ca20ba3a30&redirect_uri=http://www.bluthemes.com/api/instagram&response_type=code" target="_blank">by clicking here</a></li>
		    	<li>Copy the access token and user id</li>
		    	<li>Paste them in the input box below</li>
		  	</ol>
	  	</p>	
		<hr>
	  	<p>
	    	<label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Title</label><br>
	    	<input type="text" style="width:100%" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>">
	  	</p>
		<p>
	    	<label for="<?php echo esc_attr($this->get_field_id('access_token')); ?>">Access token</label><br>
	    	<input type="text" style="width:100%" id="<?php echo esc_attr($this->get_field_id('access_token')); ?>" name="<?php echo esc_attr($this->get_field_name('access_token')); ?>" value="<?php echo esc_attr($instance['access_token']); ?>">
	  	</p>
		<p>
	    	<label for="<?php echo esc_attr($this->get_field_id('user_id')); ?>">User id</label><br>
	    	<input type="text" style="width:100%" id="<?php echo esc_attr($this->get_field_id('user_id')); ?>" name="<?php echo esc_attr($this->get_field_name('user_id')); ?>" value="<?php echo esc_attr($instance['user_id']); ?>">
	  	</p>
	  	<p>
	    	<label for="<?php echo esc_attr($this->get_field_id('caching_time')); ?>">Caching Time</label><br>
			<select style="width:100%" id="<?php echo esc_attr($this->get_field_id('caching_time')); ?>" name="<?php echo esc_attr($this->get_field_name('caching_time')); ?>">
		  		<option value="15" <?php selected($instance['caching_time'], '15') ?>>15 min</option> 
		  		<option value="30" <?php selected($instance['caching_time'], '30') ?>>30 min</option> 
		  		<option value="60" <?php selected($instance['caching_time'], '60') ?>>1 hour</option> 
		  		<option value="120" <?php selected($instance['caching_time'], '120') ?>>2 hours</option> 
		  		<option value="360" <?php selected($instance['caching_time'], '360') ?>>6 hours</option> 
		  		<option value="720" <?php selected($instance['caching_time'], '720') ?>>12 hours</option> 
		  		<option value="1440" <?php selected($instance['caching_time'], '1440') ?>>24 hours</option> 
			</select>
	  	</p>
	  	<hr>
	  	<p>
	  	
	  		<strong>Caching</strong><br>
	  		The widget fetches new images from Instagram and saves the image URLs on your server in the cache, this reduces loading time on your site because we don't need to fetch the image URLs remotely each time a user loads your page.<br><br> If you want to force the widget to fetch new images just save the widget settings again.
	  	
	  	</p><?php
	}

  	function update($new_instance, $old_instance){

    	$instance = $old_instance;
    	$instance['title']          = strip_tags($new_instance['title']);
    	$instance['access_token']   = strip_tags($new_instance['access_token']);
    	$instance['user_id']        = strip_tags($new_instance['user_id']);
    	$instance['caching_time']   = in_array($new_instance['caching_time'], array('15','30','60','120','360','720','1440')) ? $new_instance['caching_time'] : '30'; 
  		
    	# Empty transient
  		delete_transient('blt_instagram');

    	return $instance;
  	}	

  	function widget($args, $instance){

    	extract($args, EXTR_SKIP);

		echo $before_widget;
		
		ob_start();
				
		# Title
		$title = apply_filters('widget_title', $instance['title']);
		
			if(!empty($title)){
				echo $args['before_title'] . $title . $args['after_title'];
			} ?>

    	<div class="widget-body"><?php

			if(empty($instance['user_id'])){
				echo '<div class="alert alert-error" style="margin:0"><h4>Instagram user id not set</h4>';
				echo '<p>Please add your user id in the input field for the widget</p></div>';
			}
			elseif(empty($instance['access_token'])){
				echo '<div class="alert alert-error" style="margin:0"><h4>Instagram access token not set</h4>';
				echo '<p>Please add your access token in the input field for the widget</p></div>';
			}
			else{

		   		#
		   		# CACHING
		   		#
		   		
				// If there isn't a cached version available then make one, otherwise fetch the information from it
		    	if(($cache = get_transient('blt_instagram')) === false){
			    	
			    	// Get Photos
			    	$posts_data = @wp_remote_retrieve_body(@wp_remote_get("https://api.instagram.com/v1/users/".$instance['user_id']."/media/recent/?access_token=".$instance['access_token']));
				    try{ $posts = json_decode($posts_data); }catch(Exception $ex){ $posts = false; }

				    // Get Author Data (followers, photos, following)
			    	$user_data = @wp_remote_retrieve_body(@wp_remote_get("https://api.instagram.com/v1/users/".$instance['user_id']."?access_token=".$instance['access_token']));
					try{ $user = json_decode($user_data); }catch(Exception $ex){ $user = false; }

					// If there's no error message, then set the cache up, if there is an error, then delete it.
					if($user and $posts and !isset($posts->meta->error_message) and !isset($user->meta->error_message)){
				        set_transient( 'blt_instagram', array('posts' => $posts_data, 'user' => $user_data), (int)$instance['caching_time'] * 60);
					}else{ delete_transient( 'blt_instagram' ); }

		    	}else{
					$posts 	= json_decode($cache['posts']);
					$user 	= json_decode($cache['user']);
		   		} 


		   		#
		   		# ERROR HANDLING
		   		#
		   		
				$interactions = array();
				if(!$user and !$posts){
					echo '<div class="alert alert-error" style="margin:0"><h4>Could not load Instagram photos at this time</h4></div>';
				}
				elseif(isset($posts->meta->error_message)){
					echo '<div class="alert alert-error" style="margin:0"><h4>Error</h4>';
					echo '<p>'.$posts->meta->error_message.'</p></div>';     
				}
				else if(isset($user->meta->error_message)){
					echo '<div class="alert alert-error" style="margin:0"><h4>Error</h4>';
					echo '<p>'.$user->meta->error_message.'</p></div>';
				}
				else{ ?>

					<div class="instagram-images clearfix"><?php

						foreach($posts->data as $post){ 

							?>
							<a class="instagram-link" href="<?php echo esc_url($post->link) ?>#" target="_blank"><img src="<?php echo esc_url($post->images->thumbnail->url) ?>" alt="<?php echo isset($post->caption->text) ? esc_attr($post->caption->text) : '' ?>"></a><?php 
						} ?>

					</div>

					<ul class="instagram-info clearfix">	
					
						<li>
							<h4><?php echo esc_html($user->data->counts->followed_by) ?></h4>
							<small class="text-muted"><?php _e('followers', 'bluthemes'); ?></small>
						</li>	

						<li>
							<h4><?php echo esc_html($user->data->counts->follows) ?></h4>
							<small class="text-muted"><?php _e('following', 'bluthemes'); ?></small>
						</li>	

						<li>
							<h4><?php echo esc_html($user->data->counts->media) ?></h4>
							<small class="text-muted"><?php _e('photos', 'bluthemes'); ?></small>
						</li>

					</ul><?php 

				}

			} ?>

      	</div><?php

		echo $after_widget;

		ob_get_contents();

  	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("blt_instagram");') ); 