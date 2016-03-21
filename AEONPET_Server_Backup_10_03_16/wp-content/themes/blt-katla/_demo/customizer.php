<?php


function load_customizer(){ 

	global $blu_config;
    
    // Add the color picker css file    
    wp_enqueue_style( 'spectrum-css', get_template_directory_uri().'/_demo/assets/css/plugins.css', array(), false );  
    wp_enqueue_script( 'spectrum-js', get_template_directory_uri().'/_demo/assets/js/plugins.js', array(), false );  ?>

	<style type="text/css">

		.absolute{
			position: absolute;
		}

		.blu-customizer{

			font-family: 'open sans';
			position: fixed;
			top: 0;
			left: -300px;
			bottom: 0;
			width: 300px;
			background-color: #222;
			color: #FFFFFF;
			z-index: 9999;
			padding: 50px 0;

			-webkit-transition: left 0.4s ease;
			-moz-transition: left 0.4s ease;
			-o-transition: left 0.4s ease;
			transition: left 0.4s ease;

		}
		.blu-customizer.active{
			left: 0;
		}
		.blu-customizer .blu-customizer-open{
			top: 150px;
			padding: 5px;
			background-color: #222;
			color: #FFFFFF;
			position: absolute;
			right: -50px;
			width: 50px;
			height: 50px;
			font-size: 30px;
			text-align: center;
			text-decoration: none;
			outline: none;

		}
		.blu-customizer .blu-customizer-open i{
			vertical-align: top;
			margin-top: 5px;
		}

		.blu-customizer p{
			font-size: 12px;
			color: #CCC;
		}
		.blu-customizer label{
			margin: 2px 0;
		}
		.blu-customizer select{
			border: none;
			padding: 1px 3px;
			background: #111111;
			max-width: 100%;
		}
		.blu-customizer select option{
			border: none;
		}
		.blu-customizer #background-color{
			opacity: 0;
		}

		.blu-customizer .customizer-control{
			padding: 10px 25px;
			border-top: 1px solid #000000;
		}
		.blu-customizer .customizer-control:hover{
			background-color: #000000;
		}
		.blu-customizer .customizer-control:last-child{
			border-bottom: 1px solid #000000;
		}
		.blu-customizer .intro-message{
			padding: 10px 25px;
		}
		.blu-customizer .customizer-control.background-image label{
			margin-bottom: 5px;
		}
		.blu-customizer .customizer-control.background-image a{
			width: 50px;
			height: 50px;
			overflow: hidden;
			display: inline-block;
			float: left;
			margin-right: 10px;
			margin-bottom: 10px;
		}
		.blu-customizer .customizer-control.background-image a img{
			height: 50px;
			max-width: none;
		}

	</style>

	<section class="blu-customizer">

		<a class="blu-customizer-open" href="#"><i class="fa fa-cog"></i></a>
		
		<div class="intro-message">
		
			<h4>Hi, I'm the customizer</h4>
			<p>You can check out what's available in the theme here, there are many many many other options in the Theme Options within the admin panel, but here's a taste of what you can achieve.</p>
		
		</div>

		<div class="customizer-controls">
			
			<!-- <div class="customizer-control">

				<div class="row">
				
					<label class="col-md-6" for="container-style">Container Style</label>
					
					<div class="col-md-6">
						
						<select name="container-style" id="container-style" autocomplete="off">
							
							<option value="" selected="selected">- Select -</option>
							<option value="boxed">Boxed</option>
							<option value="full_width">Full Width</option>

						</select>

					</div>

				</div>

			</div> -->
			
			<div class="customizer-control">

				<div class="row">
				
					<label class="col-md-6" for="sidebar-style">Sidebar Style</label>
					
					<div class="col-md-6">
						
						<select name="sidebar-style" id="sidebar-style" autocomplete="off">
							
							<option value="" selected="selected">- Select -</option>
							<option value="sidebar-none">No Sidebar</option>
							<option value="sidebar-left">Sidebar Left</option>
							<option value="sidebar-right">Sidebar Right</option>

						</select>

					</div>

				</div>

			</div>
			
			<div class="customizer-control">

				<div class="row">
					
					<label class="col-md-8" for="show-adspots">Show All AdSpots</label>
					<div class="col-md-4"><input autocomplete="off" type="checkbox" name="show-adspots" id="show-adspots"></div>
				
				</div>

			</div>
			
			<div class="customizer-control">

				<div class="row">
					
					<label class="col-md-8" for="background-color">Background Color</label>
					<div class="col-md-4"><input type="text" class="background-color" id="background-color" name="background-color"></div>
				
				</div>

			</div>
			
			<div class="customizer-control background-image">

				<div class="row">
					
					<label class="col-md-12">Background Image</label>
					<p class="col-md-12">Works best with Boxed Container Style</p>
					<div class="col-md-12">
						
						<a href="#"><img data-lg="<?php echo get_template_directory_uri().'/_demo/assets/img/01.jpg'; ?>" src="<?php echo get_template_directory_uri().'/_demo/assets/img/01-sm.jpg'; ?>"></a>
						<a href="#"><img data-lg="<?php echo get_template_directory_uri().'/_demo/assets/img/02.jpg'; ?>" src="<?php echo get_template_directory_uri().'/_demo/assets/img/02-sm.jpg'; ?>"></a>
						<a href="#"><img data-lg="<?php echo get_template_directory_uri().'/_demo/assets/img/03.jpg'; ?>" src="<?php echo get_template_directory_uri().'/_demo/assets/img/03-sm.jpg'; ?>"></a>
						<a href="#"><img data-lg="<?php echo get_template_directory_uri().'/_demo/assets/img/04.jpg'; ?>" src="<?php echo get_template_directory_uri().'/_demo/assets/img/04-sm.jpg'; ?>"></a>

					</div>
				
				</div>

			</div>

		</div>

	</section><?php
}
add_action('wp_footer', 'load_customizer');