<?php

class UPME_SEO {

	public $upme_settings;

	function __construct() {
        
        add_filter('upme_module_settings_array_fields', array($this, 'settings_list'));
        add_filter('upme_init_options', array($this,'general_settings') );
        add_filter('upme_default_module_settings', array($this,'default_module_settings') );

        add_action('upme_addon_module_tabs',array($this, 'module_tabs') );
        add_action('upme_addon_module_settings',array($this, 'module_settings') );   
        add_action('wp',array($this, 'init_meta') );   
//        $this->init_meta();
	}
    
    public function init_meta(){
        global $upme_options,$post;
        
        $settings = $upme_options->upme_settings;        
        if ( is_page() && $post->post_parent ) {   
            return $post->post_parent;             

        }
        $this->title_prefix = isset($settings['seo_profile_title_prefix']) ? $settings['seo_profile_title_prefix'] : '';
        $this->title_suffix = isset($settings['seo_profile_title_suffix']) ? $settings['seo_profile_title_suffix'] : '';
        $this->title_field = isset($settings['seo_profile_title_field']) ? $settings['seo_profile_title_field'] : '';
        $this->desc_field = isset($settings['seo_profile_description_field']) ? $settings['seo_profile_description_field'] : '';
        $this->image_field = isset($settings['seo_profile_image_field']) ? $settings['seo_profile_image_field'] : '';
   
        if( isset($settings['profile_page_id']) && isset($post->ID) && $post->ID == $settings['profile_page_id'] ){
            $this->user_id = upme_get_user_id_by_profile_url();
            
            add_filter('wpseo_title',array($this,'upme_wpseo_title'));
            add_filter('wpseo_googleplus_title',array($this,'upme_wpseo_title'));

            add_filter('wpseo_metadesc',array($this,'upme_wpseo_metadesc'));
            add_filter('wpseo_googleplus_desc',array($this,'upme_wpseo_metadesc'));

            add_filter('wpseo_opengraph_image',array($this,'upme_wpseo_opengraph_image'));
            add_filter('wpseo_googleplus_image',array($this,'upme_wpseo_opengraph_image'));
            add_filter('wpseo_twitter_image', array($this,'upme_wpseo_twitter_image'));
        }
        
    }
    
    public function settings_list($settings){
        $settings['upme-seo-settings'] = array('seo_profile_title_prefix','seo_profile_title_suffix','seo_profile_title_field','seo_profile_description_field','seo_profile_image_field');
        return $settings;
    }
        
    public function general_settings($settings){
        $settings['seo_profile_title_prefix'] = '';
        $settings['seo_profile_title_suffix'] = '';
        $settings['seo_profile_description_field'] = '0';
        $settings['seo_profile_title_field'] = '0';
        $settings['seo_profile_image_field'] = '0';
        return $settings;
    }

    public function default_module_settings($settings){
        $settings['upme-seo-settings'] = array(
                                            'woocommerce_profile_tab_status' => '0',
                                            'seo_profile_title_prefix' => '',
                                            'seo_profile_title_suffix' => '',
                                            'seo_profile_title_field' => '0',
                                            'seo_profile_description_field' => '0',
                                            'seo_profile_image_field' => '0',
                                        );
        return $settings;
    }

    
    public function module_tabs(){
        echo '<li class="upme-tab " id="upme-seo-settings-tab">'. __('SEO','upme').'</li>';
    }

    public function module_settings(){
        global $upme_template_loader;

        ob_start();
        $upme_template_loader->get_template_part('seo');
        $display = ob_get_clean();        
        echo $display;
    }
    
    public function upme_wpseo_title($title){    
                
        if($this->title_field != '0'){
            $title = get_user_meta( $this->user_id , $this->title_field ,true);
        }
        
        if($this->title_prefix  != '' && $title != ''){
            $title = $this->title_prefix . ' - ' . $title;
        }
        
        if($this->title_suffix  != '' && $title != ''){
            $title = $title . ' - ' . $this->title_suffix;
        }
        
        return $title;
    }

    public function upme_wpseo_metadesc($desc){
        if($this->desc_field != '0'){
            $desc = get_user_meta( $this->user_id , $this->desc_field ,true);
        }
        return $desc;
    }

    public function upme_wpseo_opengraph_image($image){
        if($this->image_field != '0'){
            $image = get_user_meta( $this->user_id , $this->image_field ,true);
        }
        return $image;
    }

    public function upme_wpseo_twitter_image( $image ) {
        if($this->image_field != '0'){
            $image = get_user_meta( $this->user_id , $this->image_field ,true);
        }
        return $image;
    }
}



add_action( 'plugins_loaded', 'upme_seo_plugin_init' );

function upme_seo_plugin_init(){
    global $upme_seo;
    if ( defined( 'WPSEO_PATH' ) ) {
        $upme_seo = new UPME_SEO();
    }
}