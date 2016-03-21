<?php
/*
Plugin Name: Bluthemes Mailchimp Widget
Description: Grow your mailing list with this Mailchimp widget 
Author: Bluthemes
Version: 1.0
Author URI: http://www.bluthemes.com/
*/
class blt_mailchimp extends WP_Widget{

	public function __construct() {
		parent::__construct('blt_mailchimp', 'Bluthemes - MailChimp', array('classname' => 'blt_mailchimp', 'description' => 'Mailchimp newsletter signup form'));
	}
	function form($instance){
		
	    wp_enqueue_style( 'wp-color-picker' );

		$instance = wp_parse_args( (array) $instance, array( 
			'title' => 'Sign up to our Newsletter!', 
			'text_above' => '', 
			'text_below' => 'Enter your email address below to subscribe to our newsletter.', 
			'layout' => 'compact',
			'list_id' => '',
			'size' => 'normal',
		));
	    
		$api_key = blt_get_option('mailchimp_api_key', false);

		if(empty($api_key)){ ?>

			<p><strong>Instructions</strong><br>Please insert your MailChimp API key in the theme options under the API Keys section</p>
			<p>(<strong style="color:#ED4040">API key is missing</strong>)</p>
			<hr><?php

		}else{

			require_once(get_template_directory().'/inc/vendor/mailchimp/MCAPI.class.php');
			$api 	= new MCAPI($api_key);
			$lists 	= $api->lists();

			if($api->errorCode){
				echo "<strong>Unable to load lists from MailChimp!</strong>";
				echo "<p>".esc_html($api->errorMessage)."</p>";	
			}else{

				if($lists['total'] == 0){
					echo '<p>You need to create a list first on mailchimp.com</p>';
				
				}else{ ?>
				<p>
					<label for="<?php echo esc_attr($this->get_field_id('list')); ?>">Select mailing list</label><br>
					<select class="widefat" id="<?php echo esc_attr($this->get_field_id('list_id')); ?>" name="<?php echo esc_attr($this->get_field_name('list_id')); ?>"><?php

						if(empty($instance['list_id'])){
							echo '<option disabled="disabled" value="-1" selected="">Select a mailing list</option>'; 	
						}
						
						foreach($lists['data'] as $mail_list){
						  	
						  	if($instance['list_id'] == $mail_list['id']){
						 		echo '<option value="'.esc_attr($mail_list['id']).'" selected="">'.esc_attr($mail_list['name']).'</option>'; 	
						  	}else{
						 		echo '<option value="'.esc_attr($mail_list['id']).'">'.esc_attr($mail_list['name']).'</option>'; 	
						  	}
						} ?>  
					</select>
				</p><?php
				}
			}
		} ?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Title</label><br>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('text_above')); ?>">Text/HTML above input fields</label><br>
			<textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('text_above')); ?>" name="<?php echo esc_attr($this->get_field_name('text_above')); ?>"><?php echo esc_attr($instance['text_above']); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('text_below')); ?>">Text/HTML below input fields</label><br>
			<textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('text_below')); ?>" name="<?php echo esc_attr($this->get_field_name('text_below')); ?>"><?php echo esc_attr($instance['text_below']); ?></textarea>
		</p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('layout')); ?>">Layout</label><br>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('layout')); ?>" name="<?php echo esc_attr($this->get_field_name('layout')); ?>">
                <option value="compact" <?php selected($instance['layout'], 'compact') ?>>Compact - Button and input in one line</option>
                <option value="standard" <?php selected($instance['layout'], 'standard') ?>>Standard - Button below input field</option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('size')); ?>">Size</label><br>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('size')); ?>" name="<?php echo esc_attr($this->get_field_name('size')); ?>">
                <option value="normal" <?php selected($instance['size'], 'normal') ?>>Normal</option>
                <option value="large" <?php selected($instance['size'], 'large') ?>>Large</option>
            </select>
        </p><?php  

	}
 
	function update($new_instance, $old_instance){

		$instance = $old_instance;
		$instance['title']				= strip_tags($new_instance['title']);
		$instance['text_above'] 		= $new_instance['text_above'];
		$instance['layout'] 			= strip_tags($new_instance['layout']);
		$instance['size'] 				= strip_tags($new_instance['size']);
		$instance['text_below'] 		= $new_instance['text_below'];
		$instance['list_id']    		= trim(strip_tags($new_instance['list_id']));

		return $instance;
	}
 
	function widget($args, $instance){

		extract($args, EXTR_SKIP);
		
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);

		echo $before_widget;
		
			echo !empty($title) ? $before_title.esc_attr($title).$after_title : '';

			?>
			<div class="widget-body"><?php

				echo !empty($instance['text_above']) ? '<p>'.$instance['text_above'].'</p>' : '';

					if($instance['layout'] == 'compact'){ ?>
						<div class="form-group">
							<div class="input-group">
								<input class="blt_mailchimp_email form-control<?php echo $instance['size'] == 'large' ? ' input-lg' : ''; ?>" type="text" placeholder="<?php _e('Email address', 'bluthemes'); ?>">
								<span class="input-group-btn">
									<button data-list="<?php echo esc_attr($instance['list_id']); ?>" class="btn btn-theme<?php echo $instance['size'] == 'large' ? ' input-lg' : ''; ?>" type="button"><?php _e('Subscribe!', 'bluthemes'); ?></button>
								</span>
							</div>
						</div><?php
					
					echo !empty($instance['text_below']) ? '<p>'.$instance['text_below'].'</p>' : '';

					}else{ ?>
						<div class="form-group">
							<input class="blt_mailchimp_email form-control<?php echo $instance['size'] == 'large' ? ' input-lg' : ''; ?>" type="text" placeholder="<?php _e('Email address', 'bluthemes'); ?>">
						</div>
						<div class="form-group clearfix">
							<button data-list="<?php echo esc_attr($instance['list_id']); ?>" class="pull-right btn btn-theme<?php echo $instance['size'] == 'large' ? ' btn-lg' : ''; ?>" type="button"><?php _e('Subscribe!', 'bluthemes'); ?></button>
						</div><?php
					
					echo !empty($instance['text_below']) ? '<p>'.$instance['text_below'].'</p>' : '';

					} ?>

			</div><?php

		echo $after_widget;
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("blt_mailchimp");') );