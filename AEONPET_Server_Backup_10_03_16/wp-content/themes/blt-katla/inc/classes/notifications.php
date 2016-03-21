<?php
#
# 	Notifications Class - Part of the Deep Blu Framework
#
# 	-----------------------------------------------------------------------------------------------------
# 
#  	Handles the notifications for the Framework
#
# 	-----------------------------------------------------------------------------------------------------
#
# 	Author:  Bluthemes
# 	Link:    http://bluthemes.com
#  
class BluNotifications{

	protected $notifications = array();

	protected $theme = '';

	protected $user_id = '';

	public function __construct($theme, $notifications = array()){


		# Grab the global config array that was created in the core_config.php file
		global $blu_config;

		# Get the current user
		$current_user = wp_get_current_user();
		$this->user_id = $current_user->ID;

		# Set the theme name
		$this->theme = trim($theme);

		# Set default admin notices
		$this->notifications = array(
			'missing_purchase_code' => array(
				'condition' => array(
					'value' => blt_get_option('field_5513f0517426d', ''),
					'operator' => '==',
					'value2' => '',
				),
				'class' => 'update-nag',
				'text' => sprintf( __('%s Theme Updates: %s To be able to update the theme via the WordPress admin panel you need to insert your %s ThemeForest purchase code %s into the Purchase code field in the theme options.', 'bluthemes'), '<strong>','</strong><br>', '<a target="_blank" href="http://www.bluthemes.com/assets/img/purchase_code_1200.png" title="How to find the purchase code">', '</a>'),
			),
		);

			# Add any theme specific notifications to the array
			if(isset($blu_config['notifications'])){
				foreach($blu_config['notifications'] as $notification){
					$this->notifications[] = $notification;
				}
			}


		$this->notifications = array_merge($this->notifications, $notifications);

		$this->hooks();		
	}

	public function hooks(){

		add_action('admin_notices', array($this, 'show_notification'));
		add_action( 'wp_ajax_blu_notifications', array($this, 'hide_notification') );
	}

	#  
	#  HIDE NOTIFICATION
	#  ========================================================================================
	#   Hide an admin notification
	#  ========================================================================================
	#   
	public function hide_notification(){

		if(!empty($_POST['key'])){
	        add_user_meta($this->user_id, $_POST['key'], 'true', true);
		}
	}

	#  
	#  SHOW NOTIFICATION
	#  ========================================================================================
	#   Show an admin notification
	#  ========================================================================================
	#   
	public function show_notification(){

		foreach($this->notifications as $key => $value){

			if(current_user_can('edit_theme_options') and !get_user_meta($this->user_id, $key) and self::condition($value['condition'])){
				
			    echo '<div class="blu-notification '.$value['class'].'" data-key="'.$key.'">';
			    echo '<p>'.$value['text'].'</p>';
			    echo '<a class="blu-hide-notification" href="'.admin_url('admin-ajax.php').'" data-key="'.$key.'">'.__('Hide Notice', 'bluthemes').'</a></p></div>';
			}
		}
	}

	#  
	#  CONDITIONS
	#  ========================================================================================
	#   Set a condition for a notification to appear
	#  ========================================================================================
	#   
	function condition($condition){

	    switch ($condition['operator']){
	        case '==':  return $condition['value'] == $condition['value2'];
	        case '===':  return $condition['value'] === $condition['value2'];
	        case '!=': return $condition['value'] != $condition['value2'];
	        case '!==': return $condition['value'] !== $condition['value2'];
	        case '>=': return $condition['value'] >= $condition['value2'];
	        case '<=': return $condition['value'] <= $condition['value2'];
	        case '>':  return $condition['value'] >  $condition['value2'];
	        case '<':  return $condition['value'] <  $condition['value2'];
	    	default:       return true;
	    }  
	}
}