<?php

class UPME_Options {

    public $upme_settings;
    public $upme_profile_fields;
    public $upme_profile_fields_by_key;

    function __construct() {
        add_action('init',array($this,'upme_init'));
        
        $this->upme_settings                = array();
        $this->upme_profile_fields          = array();
        $this->upme_profile_fields_by_key   = array();
        
        $this->upme_init_settings();
        $this->upme_init_profile_fields();
    }
    
    public function upme_init(){
        $this->upme_init_settings();
        $this->upme_init_profile_fields();
    }

    public function upme_init_settings(){
        $this->upme_settings = get_option('upme_options');
        
    }
    
    public function upme_init_profile_fields(){
        $this->upme_profile_fields = get_option('upme_profile_fields');
        
        foreach($this->upme_profile_fields as $key => $field){
            $meta = isset($field['meta']) ? $field['meta'] : '';
            if($meta != ''){
                if (!isset($field['deleted']))
                    $field['deleted'] = 0;

                if (!isset($field['private']))
                    $field['private'] = 0;
                
                $field['key_index'] = $key;
                
                $this->upme_profile_fields_by_key[$meta] = $field;
            }
        }

    }
}

$upme_options = new UPME_Options();