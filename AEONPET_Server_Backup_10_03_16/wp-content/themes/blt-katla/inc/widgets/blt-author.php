<?php
/*
Plugin Name: Bluthemes Author Widget
Description: Show authors of your blog
Author: Bluthemes
Version: 1
Author URI: http://www.bluthemes.com/
*/
class blt_author extends WP_Widget {

	public function __construct() {
		parent::__construct('blu_author', 'Bluthemes - Author', array('classname' => 'blt_author', 'description' => 'Disaply the blogs authors'));
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'author_name' => '', 'text' => '', 'image_bg_uri' => false, 'image_author_uri' => false) );
		
		$title 				= strip_tags($instance['title']);
		$author_name 		= strip_tags( $instance['author_name']);
		$text 				= esc_textarea($instance['text']);
		$image_bg_uri 		= esc_url( $instance['image_bg_uri']);
		$image_author_uri 	= esc_url( $instance['image_author_uri']);
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'bluthemes'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>

		<div class="upload_image_container">
			
      		<label for="<?php echo esc_attr($this->get_field_id('image_bg_uri')); ?>"><?php _e('Background image', 'bluthemes') ?>:</label><br />
			<input class="uploaded_image_uri" type="hidden" name="<?php echo esc_attr($this->get_field_name('image_bg_uri')); ?>" id="<?php echo esc_attr($this->get_field_id('image_bg_uri')); ?>" value="<?php echo esc_url($image_bg_uri); ?>" />
			<input class="button" onClick="blt_open_uploader(this, 'uploaded_bg_image')" id="bluth_image_upload" value="Upload" />
			<input class="button" style="width: 30px; display: inline-block;" onClick="blt_remove_image(this)" value="&times;" /><?php

		    if(!empty($image_bg_uri)){ ?>
		      		<img class="uploaded_image" src="<?php echo esc_url($image_bg_uri); ?>" style="margin-top: 10px; width:100%;"><?php
		      } ?>

		</div>

      	<hr style="background:#ddd;height: 1px;margin: 15px 0px;border:none;">
		
		<div class="upload_image_container">

      		<label for="<?php echo esc_attr($this->get_field_id('image_author_uri')); ?>"><?php _e('Author image: <small>Recommended 1:1</small>', 'bluthemes') ?></label><br />
			<input class="uploaded_image_uri" type="hidden" name="<?php echo esc_url($this->get_field_name('image_author_uri')); ?>" id="<?php echo esc_attr($this->get_field_id('image_author_uri')); ?>" value="<?php echo esc_url($image_author_uri); ?>" />
			<input class="button" onClick="blt_open_uploader(this, 'uploaded_author_image')" id="bluth_image_upload" value="Upload" />
			<input class="button" style="width: 30px; display: inline-block;" onClick="blt_remove_image(this)" value="&times;" /><?php

		    if(!empty($image_author_uri)){ ?>
		      	<img class="uploaded_image" src="<?php echo esc_url($image_author_uri); ?>" style="margin-top: 10px; width:100%;"><?php
	      	} ?>
	    
	    </div>

      	<hr style="background:#ddd;height: 1px;margin: 15px 0px;border:none;">

	    <p>
      		<label for="<?php echo esc_attr($this->get_field_id('author_name')); ?>"><?php _e('Author Name:', 'bluthemes') ?></label><br />
			<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('author_name')); ?>" name="<?php echo esc_attr($this->get_field_name('author_name')); ?>" value="<?php echo esc_attr($author_name); ?>">
	    </p>
	    <p>
      		<label for="<?php echo esc_attr($this->get_field_id('text')); ?>"><?php _e('Bio:', 'bluthemes' ) ?></label><br />
			<textarea class="widefat" rows="5" id="<?php echo esc_attr($this->get_field_id('text')); ?>" name="<?php echo esc_attr($this->get_field_name('text')); ?>"><?php echo esc_textarea($text); ?></textarea>
	    </p>

	<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] 			= strip_tags($new_instance['title']);
		$instance['author_name'] 			= strip_tags($new_instance['author_name']);
		$instance['image_bg_uri'] 		= $new_instance['image_bg_uri'];
		$instance['image_author_uri'] 		= $new_instance['image_author_uri'];

		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) );
		
		$instance['filter'] = isset($new_instance['filter']);
		return $instance;
	}

	function widget( $args, $instance ) {
		extract($args);
		$title 	= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$text 	= apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		$author_name 	= apply_filters( 'widget_text', empty( $instance['author_name'] ) ? '' : $instance['author_name'], $instance );


		echo $before_widget;

			echo '<div class="widget-body" style="background-image:url('.esc_url($instance['image_bg_uri']).')">';
				echo !empty($title) ? '<h3 class="widget-head">'.$title.'</h3>' : '';
				echo '<div class="blt_author_bio">';

				if(!empty($instance['image_author_uri'])){
					echo '<div class="blt_author_img"><img src="'.esc_url($instance['image_author_uri']).'" /></div>';
				}

				echo !empty( $author_name ) ? '<h3>'.$author_name.'</h3>' : '';
				echo '<p class="muted">'.(!empty( $instance['filter'] ) ? wpautop( $text ) : $text).'</p>';
				echo '</div>';
				echo '<div class="dark-overlay"></div>';
			echo '</div>';

		echo $after_widget;
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("blt_author");') );