<?php
/*
Plugin Name: bluthemes Socialbox
Description: Box with links to social sites
Author: Bluthemes
Version: 1.0
Author URI: http://www.bluthemes.com
*/
class blt_socialbox extends WP_Widget{


	public $sites = array(
		'facebook' => array(
			'title' => 'Facebook',
			'icon' => 'facebook',
		),
		'twitter' => array(
			'title' => 'Twitter',
			'icon' => 'twitter',
		),
		'googleplus' => array(
			'title' => 'Google+',
			'icon' => 'google-plus',
		),
		'linkedin' => array(
			'title' => 'Linkedin',
			'icon' => 'linkedin',
		),
		'youtube' => array(
			'title' => 'Youtube',
			'icon' => 'youtube',
		),
		'rss' => array(
			'title' => 'Rss',
			'icon' => 'rss',
		),
		'flickr' => array(
			'title' => 'Flickr',
			'icon' => 'flickr',
		),
		'vimeo' => array(
			'title' => 'Vimeo',
			'icon' => 'vimeo-square',
		),
		'pinterest' => array(
			'title' => 'Pinterest',
			'icon' => 'pinterest-p',
		),
		'dribbble' => array(
			'title' => 'Dribbble',
			'icon' => 'dribbble',
		),
		'tumblr' => array(
			'title' => 'Tumblr',
			'icon' => 'tumblr',
		),
		'instagram' => array(
			'title' => 'Instagram',
			'icon' => 'instagram',
		),
		'vine' => array(
			'title' => 'Vine',
			'icon' => 'vine',
		)
    );

	public function __construct() {
		parent::__construct('blt_socialbox', 'Bluthemes - Socialbox', array('classname' => 'blt_socialbox clearfix', 'description' => 'Displays links to social networks in a stylish manner'));
	}
 
	function form($instance){

		$instance = wp_parse_args((array)$instance, array('title' => ''));
		
		$title = empty($instance['title']) ? '' : $instance['title'];

		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Title</label><br>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>">
		</p>
		<hr>
		<p>Insert the URL's to your social networks</p><?php

		foreach ($this->sites as $key => $value) {
			echo '<p>';
				echo '<label for="'.esc_attr($this->get_field_id($key)).'">'.esc_attr($value['title']).'</label><br>';
				echo '<input class="widefat" type="text" id="'.esc_attr($this->get_field_id($key)).'" name="'.esc_attr($this->get_field_name($key)).'" value="'.(empty($instance[$key]) ? '' : esc_attr($instance[$key])).'">';
			echo '</p>';
		}
	}
 
	function update($new_instance, $old_instance){

		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		foreach ($this->sites as $key => $value) {
			$instance[$key] = esc_url($new_instance[$key]);
		}

		return $instance;
	}
 
	function widget($args, $instance){

		extract($args, EXTR_SKIP);

		echo $before_widget; 

		echo !empty($instance['title']) ? $before_title.esc_attr($instance['title']).$after_title : '' ?>
		<div class="widget-body">
			<ul class="social-links-list list-unstyled clearfix"><?php

				foreach ($this->sites as $key => $value) {
					if(!empty($instance[$key])){ ?>
						<li>
							<a class="blt-<?php echo esc_attr($key); ?>-background blt-<?php echo esc_attr($key); ?>-border" target="_blank" href="<?php echo esc_url($instance[$key]); ?>">
							<i class="fa fa-<?php echo esc_attr($value['icon']); ?>"></i></a>
						</li><?php
					}
				} ?>
			</ul>
		</div><?php

		echo $after_widget;
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("blt_socialbox");') );