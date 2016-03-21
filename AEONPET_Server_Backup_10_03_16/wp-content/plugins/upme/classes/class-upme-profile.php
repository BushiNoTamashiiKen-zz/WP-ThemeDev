<?php

class UPME_Profile{

    public $upme_settings;
    function __construct() {
        add_filter( 'upme_profile_tabbed_sections', array($this, 'profile_tabbed_sections') ,10,2);
        add_filter( 'upme_profile_view_forms', array($this, 'profile_tabs_content'),10,2);
        
        $this->upme_settings = get_option('upme_options');
    }
    
    public function  profile_tabbed_sections($display,$params){
        global $upme_template_loader,$upme_profile_tabs_params,$upme;
        
        extract($params);
        
        $profile_tab_status = $this->upme_settings['profile_tabs_display_status'];
        if('disabled' == $profile_tab_status){
            return '';
        }else if('enabled_members' == $profile_tab_status && !is_user_logged_in()){
            return '';
        }else if('enabled_owner' == $profile_tab_status && !$upme->can_edit_profile($upme->logged_in_user, $id) ){
            return '';
        }
        
        $upme_profile_tabs_params = $params;
        $upme_profile_tabs_params['initial_display'] = $this->upme_settings['profile_tabs_initial_display_status'];
        
        $tab_display_status = false;
	    if($group == '' && $view == ''){
            $tab_display_status = true;
        }
        
        $tab_display_status = apply_filters('upme_profile_tabs_display_status',$tab_display_status,$params);

        if($tab_display_status){

            ob_start();
            $upme_template_loader->get_template_part('profile-tabs');
            $display = ob_get_clean();
            return $display;
        }

        return $display;
    }
    
    public function profile_tabs_content($display,$params){
    
        extract($params);
        if($view != 'compact'){
            $display .= '<div id="upme-messages-panel" class="upme-profile-tab-panel" style="display:none;" >
                            <h2>UPME Messages</h2>
                            <p> UPME Messages content </p>        
                        </div>';

            $display .= '<div id="upme-notifications-panel" class="upme-profile-tab-panel" style="display:none;" >
                            <h2>UPME Notifications</h2>
                            <p> UPME Notifications content </p>        
                        </div>';

        }

        return $display;
    }

}

$upme_profile = new UPME_Profile();