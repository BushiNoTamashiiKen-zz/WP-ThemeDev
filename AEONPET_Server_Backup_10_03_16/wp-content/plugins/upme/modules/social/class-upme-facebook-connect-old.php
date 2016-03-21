<?php
/**
 * Social registration and login functionality for Facebook
 *
 * This class provides the common functionality required for connecting to Facebook
 * network and managing registration of new users for UPME.
 *
 * @package     UPME Social 
 * @subpackage  -
 * @since       1.0
 */
class UPME_Facebook_Connect extends UPME_Social_Connect{
	
	/**
	 * Connceting to Facebook network for retreiving profile informaion
	 *
	 * @access public
	 * @since 1.0
	 * @return void 
	 */
	public function login(){
        $upme_user_role		= isset($_GET['upme_user_role']) ? $_GET['upme_user_role'] : '';
		$callback_url = upme_add_query_string($this->callback_url(), 'upme_social_login=Facebook&upme_social_action=verify&upme_user_role='.$upme_user_role);
		$upme_social_action		= isset($_GET['upme_social_action']) ? $_GET['upme_social_action'] : '';
		
		$response 	= new stdClass();

		/* Configuring settings for LinkedIn application */
		$app_config = array(
			  'appId' 	=> $this->upme_settings['social_login_facebook_app_id'],
			  'secret' 	=> $this->upme_settings['social_login_facebook_app_secret']
		  );

		$facebook = new upme_Facebook($app_config);

		if ($upme_social_action == 'login'){
			/* Get the login URL and redirect the user to Facebook for authentication */
			$loginUrl = $facebook->getLoginUrl(array('redirect_uri'=>$callback_url, 'scope'=>'email'));
			$this->redirect($loginUrl);
			exit(); 
		
		}else{
			/* Retreive the user information from Facebook */
			$user = $facebook->getUser();

			if ($user){
			  	try {

					$user_profile = $facebook->api('/me');

			  	} catch (FacebookApiException $e) {
			  		/* Handling Facebook specific errors */
			  		$user = null;

			  		$response->error_code 	= $e->getCode();
					$response->error_message= $e->getMessage();

					$this->handle_social_error('Facebook',$response->error_code);
				
			  	}
			}

			if($user){

				/* Create the user profile object from response */
				$response->status 		= TRUE;
				$response->upme_network_type = 'facebook';
				$response->first_name	= $user_profile['first_name'];
				$response->last_name	= $user_profile['last_name'];
				$response->email		= $user_profile['email'];
				$response->username		= $user_profile['email'];
			
				$response->error_message = '';
			}else{

				/* Handling Facebook specific errors */
				$response->error_code 	= 'auth_invalid';
				$response->error_message = upme_language_entry('Invalid Authorization');

				$this->handle_social_error('Facebook',$response->error_code);
			}
		}
		
		return $response;
	}

	
	/**
	 * Redirect the user to login or automatically log the user based on the settings
	 *
	 * @access public
	 * @param $user_id User ID
	 * @since 1.0
	 * @return void 
	 */
	public function automatic_user_login($user_id){
		global $upme_register;
		
		// Set automatic login based on the setting value in admin
    	$activation_status = get_user_meta($user_id, 'upme_activation_status',true);
    	$approval_status = get_user_meta($user_id, 'upme_approval_status',true);    	
            
        $this->redirect_registered_users($user_id,$activation_status,$approval_status,'login');
	}

	


}
