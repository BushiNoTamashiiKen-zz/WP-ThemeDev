<?php

function blt_custom_css(){ 

	$theme_color = blt_get_option('theme_color', '#4CC2BF');
	$theme_color_hover = adjustBrightness( $theme_color, -20);
	$theme_color_light = adjustBrightness( $theme_color, 170);
	$container_width = blt_get_option('container_width', 1050);
		
	?>
	<style type="text/css">

		body{

			/* image */
			<?php if(blt_get_option('background_image')){ ?>
				background-image: url('<?php echo esc_attr(blt_get_option('background_image')); ?>');
				background-size: cover;
				background-attachment: fixed;
				background-position: center center;
			<?php } ?>

			background-color: <?php echo esc_attr(blt_get_option('background_color', '#F4F4F4')); ?>;

			font-family: <?php echo esc_attr(current(blt_get_option('main_font', array('Open Sans')))); ?>;
		}

		<?php if(blt_get_option('background_pattern')){ ?>
			.blt-pattern{
				background-image: url('<?php echo esc_attr(blt_get_option('background_pattern')); ?>');	
			}
		<?php } ?>


		h1,h2,h3,h4,h5{ 
			font-family: <?php echo esc_attr(current(blt_get_option('headings_font', array('Open Sans')))); ?>; 
		}

		.wpcf7-submit,
		.btn-theme{
			background: <?php echo esc_attr($theme_color); ?>;
		}
		.wpcf7-submit,
		.btn-theme:hover{
			background: <?php echo esc_attr($theme_color_hover) ?>;
		}
		.blt_mailchimp .input-group .btn{
			border-left-color: <?php echo esc_attr($theme_color_hover) ?>;
		}

		a{
			color: <?php echo esc_attr($theme_color); ?>;
		}
		.blu-post-tags a:hover,
		a.label-blt:hover{
			border-color: <?php echo esc_attr($theme_color_hover) ?>;
			color: <?php echo esc_attr($theme_color_hover) ?>;
		}
		a:focus, a:hover, a.label:focus, a.label:hover,
		.primary-menu .nav a:hover,
		.widget_calendar table tbody td a:hover{
			color: <?php echo esc_attr($theme_color_hover) ?>;
		}

		::-moz-selection{
			background-color: <?php echo esc_attr($theme_color) ?>;
			color: #FFF;
		}
		::selection{
			background-color: <?php echo esc_attr($theme_color) ?>;
			color: #FFF;
		}
		.pagination .page-numbers.current{
			background-color: <?php echo esc_attr($theme_color) ?>;
			border-color: <?php echo esc_attr($theme_color_hover) ?>;
		}

		.blu-post-pagination > a:hover,
		.blu-post-pagination > span{
			background-color: <?php echo esc_attr($theme_color) ?>;
			border-color: <?php echo esc_attr($theme_color) ?>;
		}

		.select2-container-active .select2-choice,
		.select2-container-active .select2-choices,
		.select2-drop-active,
		.select2-search{
			border-color: <?php echo esc_attr($theme_color_hover) ?>;
		}
		.select2-results .select2-highlighted{
			background-color: <?php echo esc_attr($theme_color_hover) ?>;
		}

		@media (min-width: <?php echo $container_width + 50 ?>px){
			#site-content{
				width: <?php echo $container_width ?>px;
			}
			#site-content-column{
				width: <?php echo $container_width - 360 ?>px;
			}
			.spot_below_menu,
			.container {
				width: <?php echo $container_width ?>px;
			}					
		}

		.post-status{
			background-color: <?php echo esc_attr($theme_color) ?>;
		}
		.new-post-list > .list-group > .blt-add-new-image:hover{
			background-color: <?php echo esc_attr($theme_color) ?>;
		}		
	</style><?php



}
add_action( 'wp_head', 'blt_custom_css' );



# 	
# 	LIGHTER/DARKER COLOR
# 	=================================================================================================
#   This function can be used to change a HEX value so it is darker or lighter than the given value
# 	=================================================================================================
# 		
function adjustBrightness($hex, $steps) {
    
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Normalize into a six character long hex string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
        $color   = hexdec($color); // Convert to decimal
        $color   = max(0,min(255,$color + $steps)); // Adjust color
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    }

    return $return;
}	