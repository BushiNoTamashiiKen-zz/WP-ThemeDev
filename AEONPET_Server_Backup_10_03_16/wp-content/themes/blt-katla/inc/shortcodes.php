<?php
/* 
Plugin Name: BluthCodes
Plugin URI: http://www.bluthemes.com/
Description: A Shortcode plugin from Bluthemes
Version: 1.00
Author: Bluthemes
Author URI: http://www.bluthemes.com
*/

class BluCodes{
		

	# 	
	# 	Alert
	# 		
	public static function alert($atts, $content = null){

		extract(shortcode_atts(array(
	      'style' 	=> 'primary',
	    ),$atts));

		return '<div class="alert alert-'.$style.'">'.do_shortcode($content).'</div>';
	}


	#
	#	Button
	#
	public static function button($atts, $content = null){

		extract(shortcode_atts(array(
	        'url'      => '#',
			'style'     => '',
	        'size'    	=> 'md',
	        'block'    	=> '',
			'target'    => '_self',
			'icon'		=> ''
    	), $atts));

		return '<a href="'.esc_url($url).'" class="btn btn-' . esc_attr($style) . ' ' . 'btn-'.esc_attr($size) . ' ' . ( $block == 'true' ? 'btn-block' : '' ) . '" target="'.esc_attr($target).'">'.(!empty($icon) ? '<i class="fa fa-'.esc_attr($icon).'"></i> ' : '').do_shortcode($content).'</a>';
	}


	#
	#	Divider
	#
	public static function divider( $atts, $content = null ) {

		extract(shortcode_atts(array(
	      'type' 	=> 'white',
	      'color' 	=> 'rgba(0,0,0,0.1)',
	      'text' 	=> '',
	    ),$atts));


		$icon = !empty($atts['icon']) ? $atts['icon'] == 'off' ? '' : '<i class="'.esc_attr($atts['icon']).'"></i>' : '<i class="fa fa-caret-square-o-up"></i>';

		$spacing = !empty($atts['spacing']) ? ' margin-top:'.esc_attr($atts['spacing']).'px; margin-bottom:'.esc_attr($atts['spacing']).'px; ' : ' margin-top:10px; margin-bottom:10px; ';

		$html = '<div style="min-height:0; padding-top:0; padding-bottom:0;">';
		switch($type){
			case 'white';
				$html .= '<div class="col-lg-12 col-md-12 col-sm-12" style="min-height:0; '.esc_attr($spacing).'"></div>';
			break;
			case 'thin':
				$html .= '<div class="col-lg-12 col-md-12 col-sm-12" style="min-height:0; border-bottom:1px solid '.esc_attr($color).';'.esc_attr($spacing).'"></div>';
			break;
			case 'thick':
				$html .= '<div class="col-lg-12 col-md-12 col-sm-12" style="min-height:0; border-bottom:2px solid '.esc_attr($color).';'.esc_attr($spacing).'"></div>';
			break;
			case 'short':
				$html .= '<div class="col-lg-2 col-md-2 col-sm-2 col-lg-offset-5 col-md-offset-5 col-sm-offset-5" style="min-height:0; border-bottom:2px solid '.esc_attr($color).';'.esc_attr($spacing).'"></div>';
			break;
			case 'dotted':
				$html .= '<div class="col-lg-12 col-md-12 col-sm-12" style="min-height:0; border-bottom:1px dotted '.esc_attr($color).';'.esc_attr($spacing).'"></div>';
			break;
			case 'dashed':
				$html .= '<div class="col-lg-12 col-md-12 col-sm-12" style="min-height:0; border-bottom:1px dashed '.esc_attr($color).';'.esc_attr($spacing).'"></div>';
			break;
			case 'go_to_top':
				$bigspan = empty($text) ? 'col-md-10' : 'col-md-8';
				$smallspan = empty($text) ? 'col-md-2' : 'col-md-4';
				$html .= '<div class="'.esc_attr($bigspan).'" style="border-bottom:2px solid '.esc_attr($color).';'.esc_attr($spacing).'"></div><a class="'.esc_attr($smallspan).'" href="#top">'.$text. '  ' .$icon.'</a>';
			break;
		}
		$html .= '</div>';

		return $html;
	}


	#
	#	Adsense
	#
	public static function adsense($atts, $content = null){

		extract(shortcode_atts(array(
			'align' => '',
			'label' => '',
			'format' => '',
			'google_publisher_id' => '',
			'google_ad_unit_id' => '',
			'width' => 'auto',
			'height' => 'auto'
		),$atts));


		if(defined('BLT_THEME_VERSION')){

			# Publisher ID
			$google_publisher_id = blt_get_option('google_publisher_id', false);

			# AD unit ID
			$google_ad_unit_id = blt_get_option('google_ad_unit_id', false);

			$label = empty($label) ? __('Advertisement', 'bluthemes') : $label;
		}

		if(!empty($align) and $align == 'left'){
			$align = ' pull-left';
		}
		if(!empty($align) and $align == 'right'){
			$align = ' pull-right';
		}


		if(empty($format)){
			$format = !empty($align) ? 'rectangle' : 'auto';
		}


		$ad_code = '<ins class="adsbygoogle" style="display:block" data-ad-client="'.esc_attr($google_publisher_id).'" data-ad-slot="'.esc_attr($google_ad_unit_id).'" data-ad-format="'.esc_attr($format).'"></ins>';

		// return '<div style="width:'.esc_attr($width).';height:'.esc_attr($height).';" class="text-center blu-google-ads'.esc_attr($align).' blu-google-ads-format-'.esc_attr($format).'"'.(!empty($label) ? ' data-label="'.esc_attr($label).'"' : '').'>'.((!empty($google_publisher_id) and !empty($google_ad_unit_id)) ? $ad_code : '<div class="alert alert-warning alert-warning-o">Please insert your Google Publisher ID and Ad unit ID into the theme options</div>').'</div>';
		return '<div style="width:'.esc_attr($width).';" class="text-center blu-google-ads'.esc_attr($align).' blu-google-ads-format-'.esc_attr($format).'"'.(!empty($label) ? ' data-label="'.esc_attr($label).'"' : '').'>'.((!empty($google_publisher_id) and !empty($google_ad_unit_id)) ? $ad_code : '<div class="alert alert-warning alert-warning-o">Please insert your Google Publisher ID and Ad unit ID into the theme options</div>').'</div>';
	}


	#
	#	Columns
	#
	public static function _50_50_first( $atts, $content = null){
		return '<div class="row"><div class="col-md-6 col-sm-6">'. do_shortcode( wpautop($content) ) . '</div>';
	}
	public static function _50_50_second( $atts, $content = null){
		return '<div class="col-md-6 col-sm-6">'. do_shortcode( wpautop($content) ) . '</div></div>';
	}
	public static function _66_33_first( $atts, $content = null){
		return '<div class="row"><div class="col-md-8 col-sm-8">'. do_shortcode( wpautop($content) ) . '</div>';
	}
	public static function _66_33_second( $atts, $content = null){
		return '<div class="col-md-4 col-sm-4">'. do_shortcode( wpautop($content) ) . '</div></div>';
	}
	public static function _33_66_first( $atts, $content = null){
		return '<div class="row"><div class="col-md-4 col-sm-4">'. do_shortcode( wpautop($content) ) . '</div>';
	}
	public static function _33_66_second( $atts, $content = null){
		return '<div class="col-md-8 col-sm-8">'. do_shortcode( wpautop($content) ) . '</div></div>';
	}
	public static function _33_33_33_first( $atts, $content = null){
		return '<div class="row"><div class="col-md-4 col-sm-4">'. do_shortcode( wpautop($content) ) . '</div>';
	}
	public static function _33_33_33_second( $atts, $content = null){
		return '<div class="col-md-4 col-sm-4">'. do_shortcode( wpautop($content) ) . '</div>';
	}
	public static function _33_33_33_third( $atts, $content = null){
		return '<div class="col-md-4 col-sm-4">'. do_shortcode( wpautop($content) ) . '</div></div>';
	}
	public static function _25_25_25_25_first( $atts, $content = null){
		return '<div class="row"><div class="col-md-3 col-sm-3">'. do_shortcode( wpautop($content) ) . '</div>';
	}
	public static function _25_25_25_25_second( $atts, $content = null){
		return '<div class="col-md-3 col-sm-3">'. do_shortcode( wpautop($content) ) . '</div>';
	}
	public static function _25_25_25_25_third( $atts, $content = null){
		return '<div class="col-md-3 col-sm-3">'. do_shortcode( wpautop($content) ) . '</div>';
	}
	public static function _25_25_25_25_fourth( $atts, $content = null){
		return '<div class="col-md-3 col-sm-3">'. do_shortcode( wpautop($content) ) . '</div></div>';
	}
	public static function _25_25_50_first( $atts, $content = null){
		return '<div class="row"><div class="col-md-3 col-sm-3">'. do_shortcode( wpautop($content) ) . '</div>';
	}
	public static function _25_25_50_second( $atts, $content = null){
		return '<div class="col-md-3 col-sm-3">'. do_shortcode( wpautop($content) ) . '</div>';
	}
	public static function _25_25_50_third( $atts, $content = null){
		return '<div class="col-md-6 col-sm-6">'. do_shortcode( wpautop($content) ) . '</div></div>';
	}
	public static function _50_25_25_first( $atts, $content = null){
		return '<div class="row"><div class="col-md-6 col-sm-6">'. do_shortcode( wpautop($content) ) . '</div>';
	}
	public static function _50_25_25_second( $atts, $content = null){
		return '<div class="col-md-3 col-sm-3">'. do_shortcode( wpautop($content) ) . '</div>';
	}
	public static function _50_25_25_third( $atts, $content = null){
		return '<div class="col-md-3 col-sm-3">'. do_shortcode( wpautop($content) ) . '</div></div>';
	}
	public static function _25_50_25_first( $atts, $content = null){
		return '<div class="row"><div class="col-md-3 col-sm-3">'. do_shortcode( wpautop($content) ) . '</div>';
	}
	public static function _25_50_25_second( $atts, $content = null){
		return '<div class="col-md-6 col-sm-6">'. do_shortcode( wpautop($content) ) . '</div>';
	}
	public static function _25_50_25_third( $atts, $content = null){
		return '<div class="col-md-3 col-sm-3">'. do_shortcode( wpautop($content) ) . '</div></div>';
	}


	#
	#	FUNCTIONS
	#
	public static function process_shortcode($content) {

	    global $blu_shortcode_tags;
		
		$original_shortcode_tags = $blu_shortcode_tags;

			add_shortcode('alert', array('BluCodes', 'alert'));
			add_shortcode('badge', array('BluCodes', 'badge'));
			add_shortcode('button', array('BluCodes', 'button'));
			add_shortcode('divider', array('BluCodes', 'divider'));
			add_shortcode('adsense', array('BluCodes', 'adsense'));

			# COLUMNS
			add_shortcode( '50_50_first',		 	array('BluCodes', '_50_50_first'));
			add_shortcode( '50_50_second',			array('BluCodes', '_50_50_second'));
			add_shortcode( '66_33_first',		 	array('BluCodes', '_66_33_first'));
			add_shortcode( '66_33_second',		 	array('BluCodes', '_66_33_second'));
			add_shortcode( '33_66_first',		 	array('BluCodes', '_33_66_first'));
			add_shortcode( '33_66_second',		 	array('BluCodes', '_33_66_second'));
			add_shortcode( '33_33_33_first',		array('BluCodes', '_33_33_33_first'));
			add_shortcode( '33_33_33_second',		array('BluCodes', '_33_33_33_second'));
			add_shortcode( '33_33_33_third',		array('BluCodes', '_33_33_33_third'));
			add_shortcode( '25_25_25_25_first',		array('BluCodes', '_25_25_25_25_first'));
			add_shortcode( '25_25_25_25_second',	array('BluCodes', '_25_25_25_25_second'));
			add_shortcode( '25_25_25_25_third',		array('BluCodes', '_25_25_25_25_third'));
			add_shortcode( '25_25_25_25_fourth',	array('BluCodes', '_25_25_25_25_fourth'));
			add_shortcode( '25_25_50_first',		array('BluCodes', '_25_25_50_first'));
			add_shortcode( '25_25_50_second',		array('BluCodes', '_25_25_50_second'));
			add_shortcode( '25_25_50_third',		array('BluCodes', '_25_25_50_third'));
			add_shortcode( '50_25_25_first',		array('BluCodes', '_50_25_25_first'));
			add_shortcode( '50_25_25_second',		array('BluCodes', '_50_25_25_second'));
			add_shortcode( '50_25_25_third',		array('BluCodes', '_50_25_25_third'));
			add_shortcode( '25_50_25_first',		array('BluCodes', '_25_50_25_first'));
			add_shortcode( '25_50_25_second',		array('BluCodes', '_25_50_25_second'));
			add_shortcode( '25_50_25_third',		array('BluCodes', '_25_50_25_third'));
	    
	    $blu_shortcode_tags = $original_shortcode_tags;
	    return $content;

	}


	public static function add_tinymce() {  

	   if( current_user_can('edit_posts') or current_user_can('edit_pages') )  
	   {  
	     add_filter('mce_external_plugins', array('BluCodes','add_tinymce_plugin'));  
	     add_filter('mce_buttons', array('BluCodes', 'add_tinymce_button'));
	   }  

	}  

	public static function add_tinymce_plugin($plugin_array) {  

		$plugin_array['bluthcodes_location'] = get_template_directory_uri() . '/assets/js/shortcodes.js';
	   
	   return $plugin_array;  

	}  

	# Define Position of TinyMCE Icons
	public static function add_tinymce_button($buttons) {  

		array_push($buttons, 'bluthcodes');  
		return $buttons;  

	} 

}

# Add the necessary filter
add_filter( 'the_content', array('BluCodes', 'process_shortcode'), 7 );

# Shortcodes in widget
add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'shortcode_filter', array('BluCodes', 'process_shortcode'), 7 );
add_action( 'admin_head', array('BluCodes', 'add_tinymce') );