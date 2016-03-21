<?php

require_once 'add-ons/acf-google-font-selector-field/acf-google_font_selector.php';
require_once 'add-ons/acf-role-selector-field/acf-role_selector.php';

// 1. customize ACF path
add_filter('acf/settings/path', 'my_acf_settings_path');
function my_acf_settings_path( $path ) {
 
    $path = get_template_directory() . '/inc/acf/';
    return $path;
}
 
// 2. customize ACF dir
add_filter('acf/settings/dir', 'my_acf_settings_dir');
function my_acf_settings_dir( $dir ) {
    $dir = get_template_directory_uri() . '/inc/acf/';
    return $dir;
}

// 3. Hide ACF field group menu item
add_filter('acf/settings/show_admin', '__return_'.(defined('BLT_DEBUG') ? 'true' : 'false'));

// 4. Include ACF
include_once( get_template_directory() . '/inc/acf/acf.php' );



// Create options pages
if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page(array(
        'page_title'    => 'Theme Settings',
        'menu_title'    => BLT_THEME_NAME. ' Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_theme_options',
        'redirect'      => false
    ));
    
    acf_add_options_sub_page(array(
        'page_title'    => 'Purchase Key',
        'capability'    => 'edit_theme_options',
        'menu_title'    => 'Purchase Key',
        'parent_slug'   => 'theme-general-settings',
    ));
}


add_filter('acf/settings/load_json', 'blt_load_json');

function blt_load_json($paths){
    
    $paths = array(get_template_directory() . '/acf-json');

    if(is_child_theme())
    {
        $paths[] = get_stylesheet_directory() . '/acf-json';
    }

    return $paths;
}


#   
#   SAVE PURCHASE KEY
#   ========================================================================================
#   When the options page with the purchase key is saved we need to do a remote check to see
#   if everything is all right with the given email and purchase code.
#   ========================================================================================
#       
function update_purchase_code(){

    if(isset($_POST['acf']['field_5513f0517426d'])){

        $email_address = $_POST['acf']['field_5513f0227426c'];
        $purchase_code = $_POST['acf']['field_5513f0517426d'];

        if(empty($email_address)){
            die(json_encode(array('result'=>0,'message'=>'Please fix the below errors','errors'=>array(array('input'=>'acf[field_5513f0227426c]','message'=>'Email address can not be empty')))));
        }

        if(empty($purchase_code)){
            die(json_encode(array('result'=>0,'message'=>'Please fix the below errors','errors'=>array(array('input'=>'acf[field_5513f0517426d]','message'=>'Purchase code can not be empty')))));
        }

        $response = @wp_remote_retrieve_body(@wp_remote_get("http://www.bluthemes.com/api/verify?email_address=".$email_address."&purchase_code=".$purchase_code."&theme_namespace=".BLT_THEME_NAMESPACE));

            if(empty($response)){
                die(json_encode(array('result'=>0,'message'=>'Could not connect to bluthemes.com for verification','errors'=>array())));
            }


        $response = json_decode($response, true);
        

            if($response['error']){

                if(isset($response['field']) and $response['field'] == 'email'){
                    die(json_encode(array('result'=>0,'message'=>'Please fix the below errors','errors'=>array(array('input'=>'acf[field_5513f0227426c]','message'=>$response['message'])))));
                }

                elseif(isset($response['field']) and $response['field'] == 'purchase_code'){
                    die(json_encode(array('result'=>0,'message'=>'Please fix the below errors','errors'=>array(array('input'=>'acf[field_5513f0517426d]','message'=>$response['message'])))));
                }

                else{
                    die(json_encode(array('result'=>0,'message'=>$response['message'],'errors'=>array())));
                }
            }else{
                update_option( 'blt_'.BLT_THEME_NAMESPACE.'_purchase_verified', true );
            }

    }
}
add_action( 'after_setup_theme', 'update_purchase_code' );