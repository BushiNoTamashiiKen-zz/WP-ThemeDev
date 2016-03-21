<?php

	#  
	#  BLT THEME CONFIG
	#  ========================================================================================
	#   Configures the theme, what post formats are in it, what sidebars etc.
	#  ========================================================================================
	#   
		
		if(!function_exists('blt_theme_config')){
			function blt_theme_config($return){

				$theme_config = array(

					'post-formats' => array(
						'video',
						'audio',
						'quote',
					),
					
					'widgets' => array(
						'blt-author',
						'blt-posts',
						'blt-instagram',
						'blt-mailchimp',
						'blt-socialbox',
					),
					'sidebars' => array(
						
						array(
							'id' 	=> 'secondary',
							'name' 	=> 'Secondary Sidebar',
							'description' => 'A custom thabo sidebar'
						),
						array(
							'id' 	=> 'primary-sidebar',
							'name' 	=> 'Primary Sidebar',
							'description' => 'The Primary Sidebar'
						),
						array(
							'id' 	=> 'post-sidebar',
							'name' 	=> 'Post Sidebar',
							'description' => 'The Post Sidebar'
						),
						array(
							'id' 	=> 'post-below',
							'name' 	=> 'Below Single Posts',
							'description' => 'Displays only on single posts'
						),
						array(
							'id' 	=> 'page-sidebar',
							'name' 	=> 'Page Sidebar',
							'description' => 'The Page Sidebar'
						),
						array(
							'id' 	=> 'front-top_of_container',
							'name' 	=> 'Front page - Top of Container',
							'description' => 'Above the container on the front page'
						),
						array(
							'id' 	=> 'page-top_of_container',
							'name' 	=> 'Page - Top of Container',
							'description' => 'Above the container in regular pages'
						),
						array(
							'id' 	=> 'single-top_of_container',
							'name' 	=> 'Single - Top of Container',
							'description' => 'Above the container in single posts'
						),
						array(
							'id' 	=> 'primary-sidebar-sticky',
							'name' 	=> 'Sticky Primary Sidebar',
							'description' => 'Sticky Primary Sidebar'
						),
						array(
							'id' 	=> 'post-sidebar-sticky',
							'name' 	=> 'Sticky Post Sidebar',
							'description' => 'Sticky Post Sidebar'
						),
						array(
							'id' 	=> 'page-sidebar-sticky',
							'name' 	=> 'Sticky Page Sidebar',
							'description' => 'Sticky Page Sidebar'
						),
						array(
							'id' 	=> 'footer-sidebar',
							'name' 	=> 'Footer Sidebar',
							'description' => 'The Footer Sidebar'
						),
						array(
							'id' 	=> 'user-posts-sidebar',
							'name' 	=> 'Post Submission Page Sidebar',
							'description' => 'Loads on all pages using the user-post-template (Post submission form)'
						)

					)

				);

				return $theme_config[$return];

			}
		}

	#  
	#  SET BODY CLASS
	#  ========================================================================================
	#   Add class names to the global body class variable
	#  ========================================================================================
	#   
		
		if(!function_exists('blt_set_body_class')){
			function blt_set_body_class($output){
				
				#  
				#  RTL
				#  ========================================================================================
				#   
				
					if(blt_get_option('rtl')){
						$output[] = 'rtl';
					}

				#  
				#  INFINITE SCROLL
				#  ========================================================================================
				#   

					if(is_home() and blt_get_option('enable_infinite_scrolling')){

						$output[] = 'infinite-scroll';
					
					}
					if(is_singular() and get_field('enable_infinite_scrolling')){
						
						$output[] = 'infinite-scroll';

					}

				#  
				#  SIDEBAR
				#  ========================================================================================
				#   
				

					// Check if we need to load the sidebar
					if(is_singular()){

						$sidebar = get_field('blt_sidebar');

							if(is_array($sidebar)){
								$sidebar = array_filter($sidebar);
							}

							if(empty($sidebar)){
								$sidebar = blt_get_option('default_post_sidebar_layout', 'none');
							}

						$output[] = 'sidebar-'.$sidebar;

					}else{

						if(is_category()){

							$sidebar_layout = 'category_sidebar_layout';
						}
						elseif(is_author()){
							$sidebar_layout = 'author_sidebar_layout';
						}
						elseif(is_archive()){
							$sidebar_layout = 'archive_sidebar_layout';
						}
						else{
							$sidebar_layout = 'sidebar_layout';
						}

						$output[] = 'sidebar-'.blt_get_option($sidebar_layout, 'right');

					}


				#  
				#  FIXED HEADER
				#  ========================================================================================
				#   

					if(blt_get_option('enable_fixed_header')){
						$output[] = 'fixed-header';
					}


				#  
				#  POST LAYOUT
				#  ========================================================================================
				#  
					if(is_front_page() and is_home()){
						$output[] = 'post-layout-'.blt_get_option('home_post_layout', 'normal');
					}

					if(is_page()){
						$output[] = 'post-layout-'.get_field('top_posts_layout');
					}

					if(is_category()){
						$output[] = 'post-layout-'.blt_get_option('category_post_layout', 'normal');
					}
					elseif(is_author()){
						$output[] = 'post-layout-'.blt_get_option('author_post_layout', 'normal');
					}
					elseif(is_archive()){
						$output[] = 'post-layout-'.blt_get_option('archive_post_layout', 'normal');
					}

				return $output;

			}
		}

	#  
	#  ACF SETUP
	#  ========================================================================================
	#   

		if(!function_exists('blt_register_acf')){
			function blt_register_acf( $acf_array ){
				
				foreach($acf_array as $acf){
					
				    register_field_group($acf);

				}
			}
		}

	#  
	#  THEME SETUP
	#  ========================================================================================
	#   

		if(!function_exists('blt_theme_setup')){
			function blt_theme_setup() {
			   	
			   	#  
			   	#  THEME SUPPORT
			   	#  ========================================================================================
			   	#   
					
					add_theme_support( 'automatic-feed-links' );
				   	add_theme_support( 'title-tag' );
				   	add_theme_support( 'html5', array( 'search-form' ) );
					add_theme_support( 'post-formats', blt_theme_config('post-formats')); 

				#  
				#  REGISTER SIDEBARS
				#  ========================================================================================
				#   
					
					$sidebars = blt_theme_config('sidebars');
			
					// Get footer widget columns
					$footer_columns = blt_get_option('footer_columns', '3');
					
					foreach($sidebars as $sidebar){

						// If this is a footer widget, we want to wrap it in a column
						if(strpos($sidebar['id'], 'footer') !== false){

							$class = array(
								'1' => 'col-sm-6 col-md-12 col-lg-12',
								'2' => 'col-sm-6 col-md-6 col-lg-6',
								'3' => 'col-sm-12 col-md-4 col-lg-4',
								'4' => 'col-sm-6 col-md-3 col-lg-3',
								'5' => 'col-sm-4 col-md-2 col-lg-2 col-md-offset-1'
							);

							$before_widget = '<div class="'.$class[$footer_columns].'"><div id="%1$s" class="single-widget %2$s">';
							$after_widget = '</div></div>';
						}else{
							$before_widget = '<div id="%1$s" class="single-widget %2$s">';
							$after_widget = '</div>';
						}


						$sidebar = wp_parse_args($sidebar, array(
							'before_widget' 	=> $before_widget,	
							'after_widget' 		=> $after_widget,	
							'before_title'		=> '<h3 class="widget-head">',	
							'after_title' 		=> '</h3>',
						));


						register_sidebar($sidebar);
					}

				#  
				#  REGISTER MENUS
				#  ========================================================================================
				#  
					
					register_nav_menus(array(
						'primary' => 'Primary Menu',
						'secondary' => 'Seconday Menu',
						'user_logged_in_menu' => 'User Logged In Menu',
					));


			}
		}


	#  
	#  ADD POST THUMBNAIL SIZES
	#  ========================================================================================
	# 
	
	if(!function_exists('blt_add_thumbnail_sizes')){
		function blt_add_thumbnail_sizes() {
				
			if ( function_exists( 'add_image_size' ) ) {
				add_theme_support( 'post-thumbnails' );

				// add_image_size( '4by3-hg-crop', 1020, 765, true ); 		// 4:3 - cropped
				// add_image_size( '4by3-lg-crop', 700, 525, true ); 		// 4:3 - cropped
				// add_image_size( '4by3-md-crop', 320, 240, true ); 		// 4:3 - cropped
				// add_image_size( '4by3-sm-crop', 160, 120, true ); 		// 4:3 - cropped

				// add_image_size( 'hg-crop', 1020, 574, true ); 	// 16:9 - cropped
				// add_image_size( 'lg-crop', 700, 394, true ); 	// 16:9 - cropped
				add_image_size( 'md-crop', 320, 180, true ); 	// 16:9 - cropped
				// add_image_size( 'sm-crop', 160, 90, true ); 	// 16:9 - cropped

				add_image_size( 'hg', 1020 ); 	// 16:9
				add_image_size( 'lg', 700); 	// 16:9
				add_image_size( 'md', 320); 	// 16:9
				add_image_size( 'sm', 160); 	// 16:9


				add_image_size( 'thumbnail-lg', 300, 300, true); 	// 1:1 cropped	
			}
		}
	}

	#  
	#  BLT WP HEAD
	#  ========================================================================================
	#   Write inside the head
	#  ========================================================================================
	#   
		
		if(!function_exists('blt_wp_head')){
			function blt_wp_head() {

				// Custom CSS & JS
				echo '<style type="text/css">'.blt_get_option('custom_css').'</style>';
				echo '<script type="text/javascript">'.blt_get_option('custom_js').'</script>';

				// Favicon
				if(blt_get_option('favicon')){ 
					echo '<link rel="Shortcut Icon" type="image/x-icon" href="'.blt_get_option('favicon').'" />';
				}

				// Facebook APP ID
				if(blt_get_option('facebook_app_id')){ ?>
					<div id="fb-root"></div>
					<script>
						function get_facebook_sdk(){
							(function(d, s, id) {
							  var js, fjs = d.getElementsByTagName(s)[0];
							  if (d.getElementById(id)) return;
							  js = d.createElement(s); js.id = id;
							  js.src = "//connect.facebook.net/<?= blt_get_option('facebook_language', 'en_GB') ?>/all.js#xfbml=1&appId=<?php echo blt_get_option('facebook_app_id'); ?>";
							  fjs.parentNode.insertBefore(js, fjs);
							}(document, 'script', 'facebook-jssdk'));
						}
						jQuery(function(){
							get_facebook_sdk();
						});
					</script> <?php 
				}	 

				// Backwards compatibility for title-tag
				if(!function_exists('_wp_render_title_tag')){
			    	echo '<title>';
			    	wp_title( '|', true, 'right' );
			    	echo '</title>';
			    }

			}
		}

	#  
	#  BLT WP FOOTER
	#  ========================================================================================
	#   Write inside the footer
	#  ========================================================================================
	#   
		
		if(!function_exists('blt_wp_footer')){
			function blt_wp_footer() {

			    // Add background pattern
			    if(blt_get_option('background_pattern')){
			    	echo '<div class="blt-pattern"></div>';
			    }

			}
		}

	#  
	#  BLT HAS SIDEBAR
	#  ========================================================================================
	#   Checks if the page we're on has a sidebar present
	#  ========================================================================================
	#   
		if(!function_exists('blt_has_sidebar')){
			function blt_has_sidebar(){

				// Check if we need to load the sidebar
				if(is_singular()){

					$sidebar = get_field('blt_sidebar');

						if(empty($sidebar)){
							$sidebar = blt_get_option('default_post_sidebar_layout', 'none');
						}
				}else{

					$sidebar = blt_get_option('sidebar_layout', 'right');

				}
				return $sidebar;

			}
		}


	#  
	#  BLT WP LIST CATEGORIES
	#  ========================================================================================
	#   Add a span tag around categories post count
	#  ========================================================================================
	#   
		
		// if(!function_exists('blt_wp_list_categories')){ 
		// 	function blt_wp_list_categories($links) {

		// 		return str_replace(array('</a> (',')'), array('</a> <span class="badge">','</span>'), $links);

		// 	}
		// }
	#  
	#  BLT GET ARCHIVES LINK
	#  ========================================================================================
	#   Add a span tag around archives link count
	#  ========================================================================================
	#  
		
		// if(!function_exists('blt_get_archives_link')){ 
		// 	function blt_get_archives_link($links) {

		// 	  	return str_replace(array('</a>&nbsp;(',')'), array('</a> <span class="badge">','</span>'), $links);

		// 	}
		// }

	#  
	#  BLT INFINITE SCROLL
	#  ========================================================================================
	#   A code to add infinite scroll to the native loop as well as the custom loop
	#  ========================================================================================
	#   
		/* Adding custom post types to the usual feed *
		add_filter( 'pre_get_posts', 'my_get_posts' );

		function my_get_posts( $query ) {

			if ( ( is_home() && $query->is_main_query() ) || is_feed() )
			$query->set( 'post_type', array( 'post', 'page', 'art', 'movie', 'quote' ) );

			return $query;
		}*/

		function blt_infinitepaginate(){ 

			$has_posts 		= true;
		    $paged          = is_numeric($_POST['page_no']) ? $_POST['page_no'] : 1;
		    $posts_per_page = get_option('posts_per_page');
		    $post_id 		= isset($_POST['post_id']) ? $_POST['post_id'] : false;
		
		    # Load the posts
		    if($post_id){
		    
		    	include( get_template_directory().'/loop-hottest.php' );
		    		
		    }else{

		    	query_posts(array(
		    		'paged' => $paged + 1,
		    		'post_status' => 'publish',
		    		'post__not_in' => get_option('sticky_posts')
		    	));

		    	include( get_template_directory().'/loop.php' );

		    }

		    if(!$has_posts){
		    	return false;
		    }
		 
		    exit;

		}

		function blt_change_main_loop( $query ) {
		    
		    if ( is_admin() || ! $query->is_main_query() )
		        return;

		    if ( is_home() and isset($_GET['page'])) {
		        $query->set( 'posts_per_page', $_GET['page'] * get_option('posts_per_page') );
		        return;
		    }

		}
		add_action( 'pre_get_posts', 'blt_change_main_loop', 1 );



	#  
	#  BLT EXCERPT MORE
	#  ========================================================================================
	#   Excerpt Read More Link
	#  ========================================================================================
	#   
		if(!function_exists('blt_excerpt_more')){ 
			function blt_excerpt_more($output) {

				$continue_reading = blt_get_option('continue_reading', '');

				switch($continue_reading) {
					
					case 'link':
						return '...<p><a class="more-link" href="'. get_permalink() . '"> '.__('Continue reading...', 'bluthemes').'</a></p>';
						break;
					
					case 'button':
						return '...<p><a class="more-link btn btn-theme" href="'. get_permalink() . '"> '.__('Continue reading...', 'bluthemes').'</a></p>';
						break;
					
					default:
						return '...';
						break;
				}

			}
		}

	#  
	#  BLT EXCERPT LENGTH
	#  ========================================================================================
	#   The length of the excerpt
	#  ========================================================================================
	#  

		if(!function_exists('blt_excerpt_length')){ 
			function blt_excerpt_length($length) {
				return blt_get_option('excerpt_length', 55);
			}
		}

	#  
	#  BLT CONTENT MORE
	#  ========================================================================================
	#   Content Read More Link
	#  ========================================================================================
	#  
		
		if(!function_exists('blt_the_content_more_link')){ 
			function blt_the_content_more_link($more_link, $more_link_text) {

				$continue_reading = blt_get_option('continue_reading', '');

				switch($continue_reading) {
					
					case 'link':
						return '<p><a class="more-link" href="'. get_permalink() . '">'.$more_link_text.'</a></p>';
						break;
					
					case 'button':
						return '<p><a class="more-link btn btn-theme" href="'. get_permalink() . '">'.$more_link_text.'</a></p>';
						break;
					
					default:
						return '...';
						break;
				}
			}

			add_filter( 'the_content_more_link', 'blt_the_content_more_link', 10, 2 );	
		}





	#  
	#  ========================================================================================
	#  COMMENTS
	#  ========================================================================================
	#   

		#  
		#  COMMENT PAGINATION
		#  ========================================================================================
		#   Paginates the comments if necessary
		#  ========================================================================================
		#   
			if(!function_exists('blt_comment_nav')){
				function blt_comment_nav(){

					// Are there comments to navigate through?
					if(get_comment_pages_count() > 1 && get_option('page_comments')){ ?>
						<nav class="navigation comment-navigation" role="navigation">
							<h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'bluthemes' ); ?></h2>
							<div class="nav-links"><?php

								if($prev_link = get_previous_comments_link(__('Older Comments', 'bluthemes'))){
									printf('<div class="nav-previous">%s</div>', $prev_link);
								}

								if($next_link = get_next_comments_link(__('Newer Comments', 'bluthemes'))){
									printf('<div class="nav-next">%s</div>', $next_link);
								} ?>
							</div>
						</nav><?php

					}

				}
			}

		#  
		#  BLT GET COMMENTS
		#  ========================================================================================
		#   Display the comments
		#  ========================================================================================
		#   
			
			if(!function_exists('blt_get_comment')){
				function blt_get_comment($comment, $args, $depth){

					$GLOBALS['comment'] = $comment;
					extract($args, EXTR_SKIP);

					$args['style'] = isset($args['style']) ? $args['style'] : 'li';

					# Opening tag
					echo '<li '.comment_class((empty($args['has_children']) ? '' : 'parent'), null, null, false).' id="comment-'.get_comment_ID(). '">';


					echo '<div id="div-comment-'. get_comment_ID() .'" class="comment-body">';


						# Comment Head
						echo '<div class="comment-head vcard">';
							
							# Avatar
							if($args['avatar_size'] != 0){
								echo get_avatar( $comment, $args['avatar_size'] );
							}
						

							# Comment Meta
							echo '<div class="comment-meta">';
								
								// Name
								echo '<span class="comment-name">'.get_comment_author_link().'</span>';
								
								// Date
								echo '<div class="comment-date">';
									echo '<a href="'.esc_url( get_comment_link($comment->comment_ID) ).'">';
										printf( __('%1$s at %2$s', 'bluthemes'), get_comment_date(),  get_comment_time() );
									echo '</a>';
									edit_comment_link( __( '(Edit)', 'bluthemes' ), '  ', '' );
								echo '</div>';
							echo '</div>';




						echo '</div>';



						# Comment Text
						echo '<div class="comment-text">';

							comment_text();

						echo '</div>';

						# Reply link
						echo '<div class="comment-reply">';
							comment_reply_link(array_merge($args, array(
								'add_below' => $args['style'] == 'div' ? 'comment' : 'div-comment',
								'depth' => $depth,
								'reply_text' => '<i class="fa fa-reply"></i> '.__('Reply', 'bluthemes'),
								'max_depth' => $args['max_depth']
							)));
						echo '</div>';

						# Not approved
						if($comment->comment_approved == '0'){
							echo '<p class="comment-awaiting-moderation"><i class="fa fa-info-circle"></i> '.__( 'Your comment is awaiting moderation.' ).'</p>';
						} 


					echo '</div>';
				}
			}

		#  
		#  HOOK FOR EMBEDDED OBJECTS IN CONTENT
		#  ========================================================================================
		#   Wraps embedded content in a div for more control
		#  ========================================================================================
		#
		if(!function_exists('blt_embed_html')){
		function blt_embed_html( $html ) {
		    return '<div class="video-container">' . $html . '</div>';
		}
		add_filter( 'embed_oembed_html', 'blt_embed_html', 10, 3 );
		add_filter( 'video_embed_html', 'blt_embed_html' );
		}   


		#  
		#  BLT GET COMMENTS
		#  ========================================================================================
		#   Display the comments
		#  ========================================================================================
		#   
			
			if(!function_exists('blt_ajax_mailchimp')){
				function blt_ajax_mailchimp(){

					$api_key = blt_get_option('mailchimp_api_key', false);

					$error = false;

					if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $_POST['email'])) {
						$error = __('Email address is invalid', 'bluthemes');
					}
					else if(!isset($_POST['list'])){
						$error = __('No mailing list selected', 'bluthemes');
					}
					else if(empty($api_key)){
						$error = __('API key not defined', 'bluthemes');
					}
					else if(!isset($_POST['email'])){ 
						$error = __('No email address provided','bluthemes');
					}

					if($error){
						echo json_encode(array('error' => $error));
					}else{

						require_once get_template_directory().'/inc/vendor/mailchimp/Mailchimp.php';

						$mailchimp = new MailChimp($api_key);

						$api = $mailchimp->call('lists/subscribe', array(
				            'id'                => $_POST['list'],
				            'email'             => array('email' => sanitize_email( $_POST['email'] ) ),
				            'double_optin'      => 'off',
				            // 'merge_vars'        => array('FNAME'=>'Davy', 'LNAME'=>'Jones'),
				            'update_existing'   => true,
				            'replace_interests' => false,
				            'send_welcome'      => true,
				        ));

						if(isset($api['error'])) {
							echo json_encode(array("error" => 'Error: ' . $api['name'] . ': ' . $api['error']));
						}else{
							echo json_encode(array("status" => 'ok'));
						}
					}

				    die();
				}
			}



	# 	
	# 	INCREASE VIEW COUNT
	# 	========================================================================================
	#   Increase the view count for a specific post, this is used to order posts by popularity
	# 	========================================================================================
	# 

    function wpb_set_post_views($postID) {
    $count_key = 'wpb_post_views_count';
    $count = get_post_meta($postID, $count_key, true);

    if($count==''){

        $count = 0;

        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');

    }else{

        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
//To keep the count accurate, lets get rid of prefetching

remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
//
	if(!function_exists('blt_add_view')){		
	function blt_add_view(){
		$post_id = $_POST['post_id'];

		if(is_numeric($post_id) and !current_user_can('administrator')){

		    # Get IP address of the visitor
		    $user_ip = getRemoteIPAddress(); 

		    # Hash it with the post id to form unique key
		    $key = md5($post_id.'x'.$user_ip); 

		    # Get transient and store in variable
		    $visited = get_transient($key); 

		    # Check to see if the Key address is currently stored as a transient
		    if($visited === false){

		        //store the unique key for 12 hours if it does not exist
		        set_transient($key, 1, 3600 * 12);

		        # Get view count
		        $count = get_post_meta($post_id, 'blt_post_views', true);

			        if($count == ''){

			            delete_post_meta($post_id, 'blt_post_views');
			            add_post_meta($post_id, 'blt_post_views', '1');

			            echo 'Added view';

			        }else{

			            $count++;
			            update_post_meta($post_id, 'blt_post_views', $count);

			        }
			}
	    }
	    wp_die();
	}
	}



	# 	
	# 	GET IP ADDRESS
	# 	========================================================================================
	#   This function retrieves the IP address of the user
	# 	========================================================================================
	# 
	if(!function_exists('getRemoteIPAddress')){		
	function getRemoteIPAddress(){

		if(!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			return $_SERVER['HTTP_CLIENT_IP'];
		}
		else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		return $_SERVER['REMOTE_ADDR'];
	}
	}



	# 	
	# 	GET OPTION
	# 	========================================================================================
	#   Get an option value from the theme options
	# 	========================================================================================
	#
	if(!function_exists('blt_get_option')){	
	function blt_get_option( $field_key, $default = false ){


    	$options = get_transient( 'blt_options' );

			if(!is_array($options)){
				$options = array();
			}

		    if(!isset($options[$field_key])){

				$field_object = get_field($field_key, 'option');	

				$options[$field_key] = $field_object;
				// $options[$field_key] = $field_object['value'];

		        set_transient( 'blt_options', $options, 3600 * 24 ); // 24 hours
		    }

		if(isset($options[$field_key])){
		
			return $options[$field_key];
		
		}else{

			if(empty($options[$field_key])){
				return $default ? $default : '';
			}else{
				return '';
			}
		}

	}
	}

	if(!function_exists('blt_get_calendar')){
	function blt_get_calendar($calendar){

		return str_replace(array('&laquo; ', ' &raquo;'), '', $calendar);
	}
	}


	# 	
	# 	Redirect non-admin users to home page
	# 	========================================================================================
	#   This function redirects user to the home url if the don't have the sufficient rights
	#   to access the wp back-end panel
	# 	========================================================================================
	# 		
	/*if(!function_exists('redirect_non_admin_users')){
	function redirect_non_admin_users(){

		if(!current_user_can('manage_options') and '/wp-admin/admin-ajax.php' != $_SERVER['PHP_SELF']){

			// If is ajax
			if( $_SERVER['HTTP_X_REQUESTED_WITH'] !== null and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'){
				// die();
			}else{
				wp_redirect( home_url() );
				exit;
			}
		}
	}
	add_action('admin_init', 'redirect_non_admin_users');	
	}*/



	# 	
	# 	HIDE ADMIN BAR
	# 	========================================================================================
	#   Hide the admin bar for all users except administrators
	# 	========================================================================================
	# 		
	function remove_admin_bar() {
		if(!current_user_can('administrator') and !is_admin()){
			show_admin_bar(false);
		}
	}
	add_action('after_setup_theme', 'remove_admin_bar');

	# 	
	# 	RADNOM VOTE COUNT
	# 	========================================================================================
	#   Adds a random upvote count for all posts
	# 	========================================================================================
	# 
	function blt_random_vote(){

		if(!current_user_can('administrator')){
			wp_die('You don\'t have access to this');
		}

		$max = isset($_POST['max']) ? (int) $_POST['max'] : 50;

			if(!is_numeric($max)){
				$max = 50;
			}


		global $wpdb;

		$query = 'SELECT id FROM '.$wpdb->posts.' WHERE post_status = "publish" AND post_type = "post"'; 

		$posts = $wpdb->get_results($query);

		foreach($posts as $post){

			$upvotes = rand(1, $max);
			update_post_meta( $post->id, 'blt_upvotes', $upvotes);
			blt_update_score( $post->id, $upvotes, 0);

		}

		wp_die();
	}



	function blt_has_access($min_role){

		$has_premission = false;

		$min_role = array_filter($min_role);

		$user = new WP_User( get_current_user_id() );

			if(!empty($user->roles) and is_array($user->roles)){
				foreach($user->roles as $u_role){

					if( in_array($u_role, $min_role, true) ){
						$has_premission = true;
					}
				}
			}

		return $has_premission;
	}



	function doctype_opengraph($output) {
	    return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
	}
	add_filter('language_attributes', 'doctype_opengraph');

	function fb_opengraph(){

		$seo_features = blt_get_option('seo_features', true);

		if($seo_features){
		    global $post;
		 
		    if(is_single()){

		        if(has_post_thumbnail($post->ID)){
		            $img_src = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'hg');
		            $img_src = $img_src[0];
		        } 

		        if($excerpt = $post->post_excerpt){

		            $excerpt = str_replace("", "'", strip_tags($post->post_excerpt));

		        }else{
		            $excerpt = get_bloginfo('description');
		        }

				if(blt_get_option('facebook_app_id')){
					echo '<meta property="fb:app_id" content="' . blt_get_option('facebook_app_id') . '" />' . "\n";
				}

			    echo '<meta property="og:title" content="'. get_the_title() .'"/>' . "\n";
			    echo '<meta property="og:description" content="'. $excerpt .'"/>' . "\n";
			    echo '<meta property="og:type" content="article"/>' . "\n";
			    echo '<meta property="og:url" content="'.get_the_permalink(). '"/>' . "\n";
			    echo '<meta property="og:site_name" content="'. get_bloginfo() .'"/>' . "\n";

			    if(!empty($img_src)){
			    	echo '<meta property="og:image" content="'. $img_src .'"/>' ."\n";
			    }
		 
		    }else{
		        return;
		    }
		}
	}
	add_action('wp_head', 'fb_opengraph', 5);


	// used for tracking error messages
	function blt_errors(){
	    static $wp_error; // Will hold global variable safely
	    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
	}


	// displays error messages from form submissions
	function blt_show_error_messages() {
		if($codes = blt_errors()->get_error_codes()) {
			echo '<div class="alert alert-danger">';
			   foreach($codes as $code){
			        $message = blt_errors()->get_error_message($code);
			        echo '<p class="error"><strong>' . __('Error', 'bluthemes') . '</strong>: ' . $message . '</p>';
			    }
			echo '</div>';
		}	
	}

	// Register the new secondary-sidebar
	function my_register_sidebars() {
 
	    register_sidebar(array(

	       	'id'            => 'secondary',
	        'name'          => __( 'Secondary Sidebar' ),
	        'description'   => __( 'A custom thabo sidebar' ),
	        'before_widget' => '<div id="%1$s" class="widget %2$s">',
	        'after_widget'  => '</div>',
	        'before_title'  => '<h3 class="widget-title">',
	        'after_title'   => '</h3>',
	        
	        ));

	    //
	    /* Repeat register_sidebar() code for additional sidebars. */
	}

	// Enqueue my custom js file for scroll-to-top
	function my_scripts_method() {
		wp_enqueue_script(
			'custom-script',
			get_stylesheet_directory_uri(). '/assets/js/custom-back-to-top.js',
			array('jquery')
			);
	}

	// Attempting to fetch most popular posts
	function wpb_set_post_views($postID) {

    	$count_key = 'wpb_post_views_count';
    	$count = get_post_meta($postID, $count_key, true);

    		if($count==''){

        		$count = 0;
        		delete_post_meta($postID, $count_key);
        		add_post_meta($postID, $count_key, '0');

    		}else{

        $count++;

        update_post_meta($postID, $count_key, $count);
    }
}

//To keep the count accurate, lets get rid of prefetching

remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

add_action('wp_enqueue_scripts', 'my_scripts_method');
add_action( 'widgets_init', 'my_register_sidebars' );
add_action( 'wp_head', 'blt_wp_head' );
add_action( 'wp_footer', 'blt_wp_footer' );
// add_filter( 'wp_list_categories', 'blt_wp_list_categories' );
// add_filter( 'get_archives_link', 'blt_get_archives_link' );
add_filter( 'get_calendar', 'blt_get_calendar' );
add_action( 'after_setup_theme', 'blt_theme_setup' );
add_action( 'after_setup_theme', 'blt_add_thumbnail_sizes' );
add_filter( 'excerpt_more', 'blt_excerpt_more' );

add_filter( 'excerpt_length', 'blt_excerpt_length' );
add_filter( 'body_class', 'blt_set_body_class' );

// AJAX
add_action( 'wp_ajax_blt_random_vote', 'blt_random_vote' );           // for logged in user
add_action( 'wp_ajax_blt_infinite_scroll', 'blt_infinitepaginate' );           // for logged in user
add_action( 'wp_ajax_nopriv_blt_infinite_scroll', 'blt_infinitepaginate' );

add_action( 'wp_ajax_blt_ajax_mailchimp', 'blt_ajax_mailchimp' );
add_action( 'wp_ajax_nopriv_blt_ajax_mailchimp', 'blt_ajax_mailchimp' ); 

add_action( 'wp_ajax_blt_add_view', 'blt_add_view' );
add_action( 'wp_ajax_nopriv_blt_add_view', 'blt_add_view' );  

