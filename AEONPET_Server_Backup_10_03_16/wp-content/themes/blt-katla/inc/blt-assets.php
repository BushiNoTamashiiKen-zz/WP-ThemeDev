<?php

	   
	#  
	#  WP ASSETS
	#  ========================================================================================
	#  Load the assets for the general WordPress areas
	#   
		
		if(!function_exists('blt_assets')){
			function blt_assets()  { 

				// add theme css
				wp_enqueue_style( 'blt-bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), BLT_THEME_VERSION, 'all' );
				wp_enqueue_style( 'blt-style', get_stylesheet_uri(), array(), BLT_THEME_VERSION, 'all' );
				wp_enqueue_style( 'blt-fontawesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), BLT_THEME_VERSION, 'all' );
					
				// add theme scripts
				wp_enqueue_script( 'blt-bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), BLT_THEME_VERSION, true );
				wp_enqueue_script( 'blt-theme', get_template_directory_uri() . '/assets/js/theme.min.js', array('jquery'), BLT_THEME_VERSION, true );


				if(blt_get_option('enable_captcha')){
					wp_enqueue_script( 'blt-recaptcha', 'https://www.google.com/recaptcha/api.js');
				}


				if(blt_get_option('rtl')){
					wp_enqueue_style( 'blt-bootstrap-rtl', get_template_directory_uri() . '/assets/css/bootstrap-rtl.min.css', array(), BLT_THEME_VERSION, 'all' );
				}



				// wp_enqueue_style( 'blt-googlefonts',  blt_google_fonts() );	

			    if(is_singular() and get_option('thread_comments')){
			        wp_enqueue_script( 'comment-reply' );			
			    }

				// Localize Script
			    wp_localize_script('blt-theme', 'blu', array( 
			    	'site_url' => get_site_url(),
			    	'user_logged_in' => is_user_logged_in() ? 'true' : 'false',
			    	'is_single' => is_single(),
			    	'paged' => isset($_GET['page']) ? esc_attr( $_GET['page'] ) : 1,
			    	'post_id' => is_singular() ? get_the_ID() : false,
			    	'ajaxurl' => admin_url( 'admin-ajax.php' ),
			    	'vote_login_required' => blt_get_option('post_voting_logged_in', true) ? 'true' : 'false',
			    	'blt_nonce' => wp_create_nonce('blt_nonce'),
			    	'locale' => array(
			    		'ajax_loading' => __('Loading', 'bluthemes'),
			    		'ajax_no_more' => __('No More Posts', 'bluthemes'),
			    		'no_email_provided' => __('No Email Provided', 'bluthemes'),
			    		'thank_you_for_subscribing' => __('Thank you for subscribing', 'bluthemes')
			    	)
			    ));

			}
		}


	#  
	#  ADMIN ASSETS
	#  ========================================================================================
	#  Load assets for the admin area
	#   
	   
		if(!function_exists('blt_admin_assets')){

			function blt_admin_assets(){

				wp_enqueue_style( 'blt-fontawesome', get_template_directory_uri().'/assets/css/font-awesome.min.css', array(), BLT_THEME_VERSION, 'all' );
				wp_enqueue_style( 'blt-admin-style', get_template_directory_uri().'/assets/css/style-admin.css', array(), BLT_THEME_VERSION, 'all' );
				wp_enqueue_script( 'blt-admin-script', get_template_directory_uri().'/assets/js/script-admin.js', array('jquery'), BLT_THEME_VERSION, true );
				wp_enqueue_media();

			}

		}

	#  
	#  TGM REQUIRED PLUGINS
	#  ========================================================================================
	#  Load required plugins & notify user to install
	#  ========================================================================================
	#   
		if(!function_exists('blt_register_required_plugins')){
			
			function blt_register_required_plugins(){
			    /**
			     * Array of plugin arrays. Required keys are name and slug.
			     * If the source is NOT from the .org repo, then source is also required.
			     */
			    $plugins = array(

			        // This is an example of how to include a plugin pre-packaged with a theme.
			        array(
			            'name'               => 'WordPress Social Login', // The plugin name.
			            'slug'               => 'wordpress-social-login', // The plugin slug (typically the folder name).
			            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
			        ),

			    );

			    /**
			     * Array of configuration settings. Amend each line as needed.
			     * If you want the default strings to be available under your own theme domain,
			     * leave the strings uncommented.
			     * Some of the strings are added into a sprintf, so see the comments at the
			     * end of each line for what each argument will be.
			     */
			    $config = array(
			        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
			        'menu'         => 'tgmpa-install-plugins', // Menu slug.
			        'has_notices'  => true,                    // Show admin notices or not.
			        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			        'message'      => '',                      // Message to output right before the plugins table.
			        'strings'      => array(
			            'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
			            'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
			            'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
			            'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
			            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
			            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
			            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
			            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
			            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
			            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
			            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
			            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
			            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
			            'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
			            'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
			            'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
			            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
			        )
			    );

			    tgmpa( $plugins, $config );
			}

		}
		
add_action( 'tgmpa_register', 'blt_register_required_plugins' );
add_action( 'admin_enqueue_scripts', 'blt_admin_assets' );
add_action( 'wp_enqueue_scripts', 'blt_assets' );
