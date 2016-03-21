<?php
/**
 * Social registration and login functionality for Google
 *
 * This class provides the common functionality required for connecting to Facebook
 * network and managing registration of new users for UPME.
 *
 * @package     UPME Social 
 * @subpackage  -
 * @since       1.0
 */
class UPME_Google_Connect extends UPME_Social_Connect{
	
	/**
	 * Connceting to Google network for retreiving profile informaion
	 *
	 * @access public
	 * @since 1.0
	 * @return void 
	 */
	public function login(){
        $upme_user_role		= isset($_GET['upme_user_role']) ? $_GET['upme_user_role'] : '';
		$callback_url = upme_add_query_string($this->callback_url(), 'upme_social_login=Google&upme_social_action=verify&upme_user_role='.$upme_user_role);
        $redirect_url = upme_add_query_string($this->callback_url(), 'upme_social_login=Google&upme_social_action=verify&upme_user_role='.$upme_user_role);
		$upme_social_action		= isset($_GET['upme_social_action']) ? $_GET['upme_social_action'] : '';
		
		$response 	= new stdClass();

		/* Configuring settings for Google application */
        $client_id     = $this->upme_settings['social_login_google_client_id'];
        $client_secret = $this->upme_settings['social_login_google_client_secret'];
        
        @session_start();
        $client = new Google_Client;
		$client->setClientId($client_id);
		$client->setClientSecret($client_secret);
        
        $client->setRedirectUri($callback_url);
		$client->addScope("https://www.googleapis.com/auth/plus.profile.emails.read");
		$service = new Google_Service_Plus($client);
        

        if ($upme_social_action == 'login'){
            
			if(!(isset($_SESSION['googleplus_access_token']) && $_SESSION['googleplus_access_token'])){
				$authUrl = $client->createAuthUrl();
				$this->redirect($authUrl);
				die();
			}else{
				$this->redirect($callback_url);
				die();
			}
            
		}elseif(isset($_GET['code'])){ 	// Perform HTTP Request to OpenID server to validate key
			$client->authenticate($_GET['code']);
			$_SESSION['googleplus_access_token'] 	= $client->getAccessToken();
			$this->redirect($callback_url);
			die();

		}elseif(isset($_SESSION['googleplus_access_token']) && $_SESSION['googleplus_access_token']){
			$client->setAccessToken($_SESSION['googleplus_access_token']);
            
			try{
				$user	= $service->people->get("me", array());
			}catch(Exception $fault){
				unset($_SESSION['googleplus_access_token']);
				$this->redirect($callback_url);
				die();
			}
			if(!empty($user)){// OK HERE KEY IS VALID
				if(!empty($user->emails)){
					$response->email    	= $user->emails[0]->value;
					$response->username 	= $user->emails[0]->value;
					$response->first_name	= $user->name->givenName;
					$response->upme_network_type = 'google';
					$response->status   	= TRUE;
					$response->error_message = '';

				}else{
					$response->status = FALSE;
					$response->error_code 	= 'auth_invalid';
                    $response->error_message = upme_language_entry('Invalid Authorization.');
                    $this->handle_social_error('Google',$response->error_code);
				}
			}else{
				$response->status = FALSE;
				$response->error_code = "signature_verify_failed";
                $response->error_message = upme_language_entry('Invalid Authorization.');
                $this->handle_social_error('Google',$response->error_code);
			}
		}elseif ($get['openid_mode'] == 'cancel'){ 
            
			$response->status = FALSE;
			$response->error_code 	= 'cancel_request';
            $response->error_message = upme_language_entry('User Canceled Request.');
            $this->handle_social_error('Google',$response->error_code);
            
		}else{ 
			$response->status = FALSE;
			$response->error_code 	= 'auth_invalid';
			$response->error_message = upme_language_entry('User Login Failed.');
            $this->handle_social_error('Google',$response->error_code);

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
