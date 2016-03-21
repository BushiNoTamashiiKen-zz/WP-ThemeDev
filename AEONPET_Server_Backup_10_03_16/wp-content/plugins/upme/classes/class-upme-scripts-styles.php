<?php

class UPME_Scripts_Styles{

    public $upme_settings;
    public function __construct() {    
        $this->upme_settings = get_option('upme_options');
    }
    
    public function registration($params){
        
        // Loading scripts and styles only when required
        /* Password Stregth Checker Script */
        if (!wp_script_is('form-validate')) {
            wp_register_script('form-validate', upme_url . 'js/form-validate.js', array('jquery'));
            wp_enqueue_script('form-validate');

            $validate_strings = upme_form_validate_setting();
            wp_localize_script('form-validate', 'Validate', $validate_strings);
        }

        // Include password strength meter from WordPress core
        wp_enqueue_script('password-strength-meter');

        if (!wp_style_is('upme_password_meter')) {
            wp_register_style('upme_password_meter', upme_url . 'css/password-meter.css');
            wp_enqueue_style('upme_password_meter');
        }

        if (!wp_style_is('upme_date_picker')) {
            wp_register_style('upme_date_picker', upme_url . 'css/upme-datepicker.css');
            wp_enqueue_style('upme_date_picker');
        }


        if (!wp_script_is('upme_date_picker_js')) {
            wp_register_script('upme_date_picker_js', upme_url . 'js/upme-datepicker.js', array('jquery'));
            wp_enqueue_script('upme_date_picker_js');

            // Set date picker default settings
            $date_picker_array = upme_date_picker_setting();
            wp_localize_script('upme_date_picker_js', 'UPMEDatePicker', $date_picker_array);
        }

        do_action('upme_add_registration_scripts');
        
    } 
    
}

$upme_scripts_styles = new UPME_Scripts_Styles();