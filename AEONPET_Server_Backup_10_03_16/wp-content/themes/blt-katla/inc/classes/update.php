<?php
#
# 	Update Class - Part of the Deep Blu Framework
#
# 	-----------------------------------------------------------------------------------------------------
# 
#  	The Update class which is needed for the automatic update functionality
#
# 	-----------------------------------------------------------------------------------------------------
#
# 	Author:  Bluthemes
# 	Link:    http://bluthemes.com
#  
class BluUpdate{

	# The theme slug
	public $theme = '';

	# The purchase code from ThemeForest
	public $purchase_code = '';

	# Name for the option which is saved the database
	protected $optionName = '';

	# Automatic check done or not
	protected $automaticCheckDone = false;


	# Constructor
	public function __construct($theme, $purchase_code){

		$this->theme 			= trim($theme);
		$this->purchase_code 	= trim($purchase_code);
		$this->optionName 		= 'bluthemes_updates_'.$this->theme;
		
		if(!empty($purchase_code)){
			$this->installHooks();		
		}
	}
	
	#  
	#  INSTALL HOOKS
	#  ========================================================================================
	#   Install the hooks required to run periodic update checks and inject update info 
	# 	into WP data structures.
	#  ========================================================================================
	#   
	public function installHooks(){

		# Check for updates when WordPress does. We can detect when that happens by tracking
		# updates to the "update_themes" transient, which only happen in wp_update_themes().
		add_filter('pre_set_site_transient_update_themes', array($this, 'onTransientUpdate'));
		
		# Insert our update info into the update list maintained by WP.
		add_filter('site_transient_update_themes', array($this,'injectUpdate')); 
		
		# Delete our update info when WP deletes its own.
		# This usually happens when a theme is installed, removed or upgraded.
		add_action('delete_site_transient_update_themes', array($this, 'deleteStoredData'));
	}

	
	#  
	#  REQUEST UPDATE
	#  ========================================================================================
	#   Retrieve update info from the configured metadata URL.
	#	Returns either an instance of BluUpdater, or NULL if there is 
	#	no newer version available or if there's an error.
	#  ========================================================================================
	#   
	public function requestUpdate(){

		
		# Send the request.
		$result = wp_remote_get('http://www.bluthemes.com/api/'.$this->theme.'/version?purchase_code='.$this->purchase_code, array('timeout' => 10));

		# Try to parse the response
		$themeUpdate = null;

		$code = wp_remote_retrieve_response_code($result);
		$body = wp_remote_retrieve_body($result);

			if(($code == 200) && !empty($body)){

				$themeUpdate = BluUpdater::fromJson($body);

				# The update should be newer than the currently installed version.
				if(($themeUpdate != null) && version_compare($themeUpdate->version, $this->getInstalledVersion(), '<=')){
					$themeUpdate = null;
				}
			}

		
		$themeUpdate = apply_filters('blu_request_update_result-'.'blt-'.$this->theme, $themeUpdate, $result);
		return $themeUpdate;
	}
	

	#  
	#  GET INSTALLED VERSION
	#  ========================================================================================
	#   Get the currently installed version of our theme.
	#  ========================================================================================
	#   
	public function getInstalledVersion(){

		if(function_exists('wp_get_theme')){
			$theme = wp_get_theme('blt-'.$this->theme);
			return $theme->get('Version');
		}

		foreach(get_themes() as $theme){
			if ( $theme['Stylesheet'] === 'blt-'.$this->theme ){
				return $theme['Version'];
			}
		}
		return '';
	}
	

	#  
	#  CHECK FOR UPDATES
	#  ========================================================================================
	#   Check for theme updates
	#  ========================================================================================
	#   
	public function checkForUpdates(){

		$state = get_option($this->optionName);

		if(empty($state)){
			$state = new StdClass;
			$state->lastCheck = 0;
			$state->checkedVersion = '';
			$state->update = null;
		}
		
		$state->lastCheck = time();
		$state->checkedVersion = $this->getInstalledVersion();
		update_option($this->optionName, $state);
		
		$update = $this->requestUpdate();
		$state->update = ($update instanceof BluUpdater) ? $update->toJson() : $update;
		update_option($this->optionName, $state);
	}
	

	#  
	#  ON TRANSIENT UPDATE
	#  ========================================================================================
	#   Run the automatic update check, but no more than once per page load.
	#	This is a callback for WP hooks. Do not call it directly.  
	#  ========================================================================================
	#   
	public function onTransientUpdate($value){
		
		if(!$this->automaticCheckDone){
			$this->checkForUpdates();
			$this->automaticCheckDone = true;
		}
		return $value;
	}
	

	#  
	#  INJECT UPDATE
	#  ========================================================================================
	#   Insert the latest update (if any) into the update list maintained by WP.
	#  ========================================================================================
	#   
	public function injectUpdate($updates){

		$state = get_option($this->optionName);
		
		if(!empty($state) && isset($state->update) && !empty($state->update)){

			$update = $state->update;

				if(is_string($state->update) ) {
					$update = BluUpdater::fromJson($state->update);
				}

			$updates->response['blt-'.$this->theme] = $update->toWpFormat();
		}
		
		return $updates;
	}
	

	#  
	#  DELETED STORED DATA
	#  ========================================================================================
	#   Delete any stored book-keeping data.
	#  ========================================================================================
	#   
	public function deleteStoredData(){
		delete_option($this->optionName);
	} 

	
	#  
	#  ADD RESULT FILTER
	#  ========================================================================================
	#   Register a callback for filtering the theme info retrieved from the external API.
	#	The callback function should take two arguments. If a theme update was retrieved 
	#	successfully, the first argument passed will be an instance of  BluUpdater. Otherwise, 
	#	it will be NULL. The second argument will be the corresponding return value of 
	#	wp_remote_get (see WP docs for details).
	#	 
	#	The callback function should return a new or modified instance of BluUpdater or NULL.
	#  ========================================================================================
	#  
	public function addResultFilter($callback){
		add_filter('blu_request_update_result-'.'blt-'.$this->theme, $callback, 10, 2);
	}
}


class BluUpdater {

	# Version number
	public $version;

	# URL that directs to the changelog for the theme
	public $details_url;

	# URL to download the latest version of the theme
	public $download_url;

	#  
	#  FROM JSON
	#  ========================================================================================
	#   Create a new instance of BluUpdater from its JSON-encoded representation.
	#  ========================================================================================
	#   
	public static function fromJson($json){

		$apiResponse = json_decode($json);

		if(empty($apiResponse) || !is_object($apiResponse)){
			return null;
		}
		
		//Very, very basic validation.
		$valid = isset($apiResponse->version) && !empty($apiResponse->version) && isset($apiResponse->details_url) && !empty($apiResponse->details_url);
		if(!$valid){
			return null;
		}
		
		$update = new self();
		foreach(get_object_vars($apiResponse) as $key => $value){
			$update->$key = $value;
		}
		
		return $update;
	}


	#  
	#  TO JSON
	#  ========================================================================================
	#   Serialize update information as JSON.
	#  ========================================================================================
	#   
	public function toJson() {
		return json_encode($this);
	}
	

	#  
	#  TO WP FORMAT
	#  ========================================================================================
	#   Transform the update into the format expected by the WordPress core.
	#  ========================================================================================
	#   
	public function toWpFormat(){
		$update = array(
			'new_version' => $this->version,
			'url' => $this->details_url,
		);
		
		if(!empty($this->download_url)){
			$update['package'] = $this->download_url;
		}
		
		return $update;
	}
}
	
