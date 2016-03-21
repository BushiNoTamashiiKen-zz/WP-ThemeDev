<?php

class UPME_Custom_Fields{
    
    public $upme_settings;
    
    public function __construct(){
        add_filter('upme_module_settings_array_fields', array($this, 'settings_list'));
        add_filter('upme_init_options', array($this,'general_settings') );
        add_filter('upme_default_module_settings', array($this,'default_module_settings') );
        add_filter('upme_option_with_checkbox', array($this,'option_with_checkbox') );
        
        
        add_action( 'upme_addon_module_tabs',array($this, 'module_tabs') );
        add_action( 'upme_addon_module_settings',array($this, 'module_settings') );   

        add_action('wp_ajax_upme_save_separator_field_groups', array($this, 'upme_save_separator_field_groups'));
    }
        
    public function settings_list($settings){
        return $settings;
    }

    public function general_settings($settings){
        return $settings;
    }
        
    public function default_module_settings($settings){
        return $settings;
    }

    public function option_with_checkbox($settings){
        return $settings;
    }
    
    public function module_tabs(){

        echo '<li class="upme-tab " id="upme-custom-fields-settings-tab">'. __('Custom Fields','upme').'</li>';
        echo '<li class="upme-tab " id="upme-separator-field-groups-settings-tab">'. __('Separator Field Groups','upme').'</li>';
    }

    public function module_settings(){
        global $upme_template_loader;

        ob_start();
        $upme_template_loader->get_template_part('custom-fields');
        $upme_template_loader->get_template_part('separator-field-groups');
        $display = ob_get_clean();        
        echo $display;
    }

    public function upme_save_separator_field_groups(){
        $data = array();

        parse_str($_POST['data'], $data);

        $separator_group_fields = isset($data['separator_group_fields']) ? $data['separator_group_fields'] : array();

        update_option('upme_separator_group_fields',$separator_group_fields);

        echo json_encode(array('status' => 'success'));exit;
    }

}

add_action( 'plugins_loaded', 'upme_custom_fields_plugin_init' );

function upme_custom_fields_plugin_init(){
    global $upme_custom_fields;
    $upme_custom_fields = new UPME_Custom_Fields();
}