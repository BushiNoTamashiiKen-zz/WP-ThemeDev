<?php
/**
 * Social Connect interface for UPME
 *
 * This class provides the common functionality required for connecting to social
 * networks and managing registration of new users for UPME.
 *
 * @package     UPME Social 
 * @subpackage  -
 * @since       1.0
 */
class UPME_Social_Connect{
	


	/**
	 * Settings of UPME core
	 * @access public
	 * @since 1.0
	 */
	public $upme_settings;

	/**
	 * Intializing the settings for social connect
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function __construct(){
		$this->upme_settings = get_option('upme_options');
	}

	/**
	 * Generate the callback URL
	 *
	 * @access public
	 * @since 1.0
	 * @return string $url Callback URL for social network
	 */
	public function callback_url(){
		$url = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"];
		if(strpos($url, '?')===false){
			$url .= '?';
		}else{
			$url .= '&';
		}
		return $url;
	}

	/**
	 * Redirecting user to the specified URL
	 *
	 * @access public
	 * @since 1.0
	 * @param string $redirect URL for redirection
	 * @return void 
	 */
	public function redirect($redirect){
		wp_redirect($redirect);exit;
	}

	/**
	 * Handling the errors generated from upme for new user registrations or login 
	 * and redirecting the user to login screen
	 *
	 * @access public
	 * @since 1.0
	 * @uses  upme_add_query_string
	 * @param string $type 			Name of social network
	 * @param string $error_code 	UPME Social specific error string for identifications
	 * @return void 
	 */
	public function handle_error($type,$error_code){

		/* Get the URL of the Login page of UPME */
		$login_page_id = (int) isset($this->upme_settings['login_page_id']) ? $this->upme_settings['login_page_id'] : 0;
        $url = get_permalink($login_page_id);

        /* Add the error code and redirect the user to login screen */
        $url = upme_add_query_string($url,'upme_social_login='.$type.'&upme_err_code='.$error_code);
        $this->redirect($url);
	}

	/**
	 * Handling the errors generated from social networks for new user registrations or login 
	 * and redirecting the user to login screen
	 *
	 * @access public
	 * @since 1.0
	 * @param string $type 			Name of social network
	 * @param string $error_code 	UPME Social specific error string for identifications
	 * @return void 
	 */
	public function handle_social_error($type,$error_code){

		/* Get the URL of the Login page of UPME */
		$login_page_id = (int) isset($this->upme_settings['login_page_id']) ? $this->upme_settings['login_page_id'] : 0;
        $url = get_permalink($login_page_id);

        /* Add the error code and redirect the user to login screen */
        $url = upme_add_query_string($url,'upme_social_login='.$type.'&upme_err_code='.$error_code);
        $this->redirect($url);
	}

	/**
	 * Create a new user registration for UPME
	 *
	 * @access public
	 * @since 1.0
	 * @uses  handle_social_error,upme_new_user_notification,validate_email_confirmation
	 *		  automatic_user_login, redirect_registered_users
	 * @param object $result Result object created from the response generated from social network
	 *		  This object will contain all the user profile data requested by the application
	 * @return void 
	 */
	public function register_user($result){
		global $upme_register;
	
		/*  Check for succefull registration or login */
		if($result->status){

			if($result->upme_network_type != 'twitter'){
				$user = get_user_by('email',$result->email);
			}else{
				$user = get_user_by('login',$result->username);
			}
			

			if(!$user){
		
				/* Generate a custom username using the combination of first and last names plus a random
				 * number for preventing duplication.
				 */
				if($result->upme_network_type != 'twitter'){
					$username = strtolower($result->first_name.$result->last_name);
                    if(trim($username) == ''){
                        $username = $result->email; 
                    }
                    
					if(username_exists($username)){
						$username = $username.rand(10,99);
					}
				}else{
					$username = $result->username;
				}				


            	$sanitized_user_login = sanitize_user($username);

	            /* Generate password */
                $user_pass = wp_generate_password(12, false);

            	/* Create the new user */
            	$user_id = wp_create_user($sanitized_user_login, $user_pass, $result->email);
            	if (!is_wp_error($user_id)) {
            		update_user_meta($user_id, 'user_email', $result->email);
            		update_user_meta($user_id, 'upme_network_type', $result->upme_network_type);
                	wp_update_user( array ('ID' => $user_id, 'display_name' => $result->first_name.' '.$result->last_name) ) ;
            	} 
                
                $upme_user_role		= isset($_GET['upme_user_role']) ? $_GET['upme_user_role'] : '';
                if($upme_user_role != ''){
                    wp_update_user( array( 'ID' => $user_id, 'role' => $upme_user_role ) );
                }

            	// Set intial details for users
            	$user_info = get_userdata($user_id);
	            update_user_meta($user_id, 'first_name', $result->first_name);
	            update_user_meta($user_id, 'last_name', $result->last_name);
	            update_user_meta($user_id, 'display_name', $result->first_name.' '.$result->last_name);

		    $activation_status = 'ACTIVE';
	            update_user_meta($user_id, 'upme_activation_status', "ACTIVE");
            

            	// Set approval status when user profile approvals are enabled
	            $approval_setting_status = $upme_register->validate_user_approval();
	            if($approval_setting_status){
	                $approval_status = 'INACTIVE';
	                update_user_meta($user_id, 'upme_approval_status', $approval_status);
	            }else{
	                $approval_status = 'ACTIVE';
	                update_user_meta($user_id, 'upme_approval_status', $approval_status);
	            }

	            // Set Profile Status to active by default
	            update_user_meta( $user_id, 'upme_user_profile_status', 'ACTIVE' );


	            // Set the password nag when user selected password setting is disabled
	            // Set activation status and codes when selected password setting is enabled
	            $upme_settings = get_option('upme_options');
	            $set_pass = (boolean) $upme_settings['set_password'];
	            $activation_setting_status = $upme_register->validate_email_confirmation();


	           
	            if (!$set_pass) {                
	                update_user_option($user_id, 'default_password_nag', true, true); //Set up the Password change nag.
	            }

	            // if($activation_setting_status){
	            //     $activation_status = 'INACTIVE';
	            //     update_user_meta($user_id, 'upme_activation_status', $activation_status);
	            // }else{
	            //     $activation_status = 'ACTIVE';
	            //     update_user_meta($user_id, 'upme_activation_status', $activation_status);
	            // }

	            // $activation_code = wp_generate_password(12, false);

	            // update_user_meta($user_id, 'upme_activation_code',$activation_code);

	            // Set automatic login based on the setting value in admin
	            if ($upme_register->validate_automatic_login()) {
	                wp_set_auth_cookie($user_id, false, is_ssl());
	            }

	            /* action after Account Creation */
	            do_action('upme_user_register', $user_id);


	            if (!empty($activation_status) && 'INACTIVE' == $activation_status) {
	                upme_new_user_notification($user_id, $user_pass,$activation_status,$activation_code);
	            }else{
	                wp_new_user_notification($user_id, $user_pass);
	            }

	            upme_update_user_cache($user_id);

	            $this->redirect_registered_users($user_id,$activation_status,$approval_status,'reg');

			}else{
				/* User already registered. Send him for automatic or manual login */
				$this->automatic_user_login($user->ID);		
			}

			

		}else{

			/* Request failed due to an error from social network. Redirect the user to login form
			 * with respective error key.
			 */
			$type = isset($result->upme_network_type) ? $result->upme_network_type : 'Undefined';
			$error_code = isset($result->error_code) ? $result->error_code : 'Undefined';
			$this->handle_social_error($type,$error_code);
		}
	}

	/**
	 * Redirect users to login screen to login redirect URL based on the error codes
	 * and settings defined in UPME
	 *
	 * @access public
	 * @since 1.0
	 * @uses  handle_social_error,upme_new_user_notification,validate_email_confirmation
	 *		  automatic_user_login, redirect_registered_users
	 * @param object $result Result object created from the response generated from social network
	 *		  This object will contain all the user profile data requested by the application
	 * @return void 
	 */
	public function redirect_registered_users($user_id,$activation_status,$approval_status,$type){

		/* Get login page from UPME settings */
		$login_page_id = (int) isset($this->upme_settings['login_page_id']) ? $this->upme_settings['login_page_id'] : 0;


		if('ACTIVE' == $activation_status && 'ACTIVE' == $approval_status){
	    		
            /* Automatically log the user when Activation and Approval status is set to TRUE */
            wp_set_auth_cookie($user_id, false, is_ssl());
            $login_redirect_page_id = (int) isset($this->upme_settings['login_redirect_page_id']) ? $this->upme_settings['login_redirect_page_id'] : 0;

            if ($login_redirect_page_id) {

                $url = get_permalink($login_redirect_page_id);
                wp_redirect($url);exit;
            }else{
                $url = get_permalink($login_page_id);
                wp_redirect($url);exit;
            }

        }
        else if('INACTIVE' == $activation_status && 'INACTIVE' == $approval_status){

            /* Redirect Activation + confirmation pending users to the login screen with respoective error message */
            if ($login_page_id) {
                if($type == 'reg'){
                    $url = upme_add_query_string(get_permalink($login_page_id), 'upme_login_error=reg_activation_approval');
                }else{
                    $url = upme_add_query_string(get_permalink($login_page_id), 'upme_login_error=activation_approval');
                }
                wp_redirect($url);exit;
            }
        }else if('INACTIVE' == $activation_status){

            /* Redirect Activation pending users to the login screen with respoective error message */
            if ($login_page_id) {
                if($type == 'reg'){
                    $url = upme_add_query_string(get_permalink($login_page_id), 'upme_login_error=reg_activation');
                }else{
                    $url = upme_add_query_string(get_permalink($login_page_id), 'upme_login_error=activation');
                }
                wp_redirect($url);exit;
            }
        }else if('INACTIVE' == $approval_status){

            /* Redirect Approval pending users to the login screen with respoective error message */
            if ($login_page_id) {
                if($type == 'reg'){
                $url = upme_add_query_string(get_permalink($login_page_id), 'upme_login_error=reg_approval');
            }else{
                $url = upme_add_query_string(get_permalink($login_page_id), 'upme_login_error=approval');
            }
            wp_redirect($url);exit;
        }

        }else{

		}

	}
		
		
}


/* Add the social login buttons to the registration and login forms based on the settings */
add_filter('upme_register_after_fields', 'upme_social_login_buttons',10,2);
add_filter('upme_login_after_fields', 'upme_social_login_buttons');
function upme_social_login_buttons($html,$params = array()){

	$upme_settings = get_option('upme_options');
	$allowed_networks = isset($upme_settings['social_login_allowed_networks']) ? $upme_settings['social_login_allowed_networks'] :array();
	
	if (get_option('users_can_register') == '1') {

		$html = '<div align="center" style="margin:10px">';
		$html .= '<div align="center" class="upme-social-header" >'. $upme_settings['social_login_display_message'] .'</div>';
        
        $user_role_param = (isset($params['user_role']) && $params['user_role'] != '') ? ' upme_user_role="'. $params['user_role'] . '" ' : '';
        
        if(is_array($allowed_networks)){
            foreach ($allowed_networks as $key => $network) {
                $network = ucfirst($network);
                $html .= do_shortcode('[upme_social_login_button '.$user_role_param.' network="'.$network.'" ]');
            }
        }

		$html .= '</div>';
	}

    return $html;
}

/* Intialize the social networks for login and registration */
add_action('wp_loaded','upme_social_login_initialize');
function upme_social_login_initialize(){
	$upme_social_login_obj = false;

	$upme_social_login = isset($_GET['upme_social_login']) ? $_GET['upme_social_login'] : '';
	$upme_social_action = isset($_GET['upme_social_action']) ? $_GET['upme_social_action'] : '';

	if('' != $upme_social_login ){

		switch ($upme_social_login) {
			case 'Linkedin':
				$upme_social_login_obj = new UPME_LinkedIn_Connect();

				break;

			case 'Facebook':
				$upme_social_login_obj = new UPME_Facebook_Connect();
				break;

			case 'Twitter':
				$upme_social_login_obj = new UPME_Twitter_Connect();
				break;
            
            case 'Google':
				$upme_social_login_obj = new UPME_Google_Connect();
				break;
			
			default:
				break;
		}

		if($upme_social_login_obj){
			$login_response = $upme_social_login_obj->login(); 			
			$upme_social_login_obj->register_user($login_response);
		}
	}
}


add_filter('upme_login_after_head','upme_login_after_head');
function upme_login_after_head($display){


	if(isset($_GET['upme_err_code'])){
		$err_code = $_GET['upme_err_code'];
		$message = '';
		switch ($err_code) {
			case 'user_profile_failed':
				$message = upme_language_entry('Error retrieving profile information. Please try again later.');
				break;

			case 'req_token_fail':
				$message = upme_language_entry('Request token retrieval failed. Please try again later.');
				break;

			case 'req_profile_fail':
				$message = upme_language_entry('Error retrieving profile information. Please try again later.');
				break;

			case 'access_token_fail':
				$message = upme_language_entry('Access token retrieval failed. Please try again later.');
				break;

			case 'user_refused':
				$message = upme_language_entry('User refused by application. Please try again later.');
				break;

			case 'req_cancel':
				$message = upme_language_entry('Request cancelled by user.');
				break;

			case 'auth_invalid':
				$message = upme_language_entry('Invalid authorization.  Please try again later.');
				break;
		
			default:

				$message = upme_language_entry('Social login request failed.');			
				break;
		}

		
		if(!$_POST){
			$display = '<div class="upme-main">
							<div class="upme-errors">
								<span class="upme-error upme-error-block">
									<i class="upme-icon-remove"></i>' . $message. '
								</span>
							</div>
					</div>';
		}


		return $display;
	}
    
    
}

add_action('wp_loaded','upme_handle_social_errors');
function upme_handle_social_errors(){
    global $upme_login;
    if(isset($_GET['upme_login_error']) && $_GET['upme_login_error'] != '' ){
        $error_code = $_GET['upme_login_error'];
        switch($error_code){
            case 'approval':
                $upme_login->delete_profile_message = __('Your account is pending admin approval.','upme');
                $upme_login->delete_profile_message_status = 'upme-errors';
                break;
            case 'reg_approval':
                $upme_login->delete_profile_message = __('Registration sucessfully completed.Your account is pending admin approval.','upme');
            $upme_login->delete_profile_message_status = 'upme-success';
                break;
            case 'activation':
                $upme_login->delete_profile_message = __('Please click the link on registration email to activate the account.','upme');
                $upme_login->delete_profile_message_status = 'upme-errors';
                break;
            case 'reg_activation':
                $upme_login->delete_profile_message = __('Registration sucessfully completed.Please click the link on registration email to activate the account.','upme');
                $upme_login->delete_profile_message_status = 'upme-success';
                break;
            case 'activation_approval':
                $upme_login->delete_profile_message = __('Please click the link on registration email to activate the account. Your account is pending admin approval.','upme');
                $upme_login->delete_profile_message_status = 'upme-errors';
                break;
            case 'reg_activation_approval':
                $upme_login->delete_profile_message = __('Registration sucessfully completed.Please click the link on registration email to activate the account. Your account is pending admin approval.','upme');
                $upme_login->delete_profile_message_status = 'upme-success';
                break;
        }
    }
}

