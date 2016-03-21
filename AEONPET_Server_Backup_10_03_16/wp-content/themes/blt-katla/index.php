<?php get_header(); ?>


<div id="site-content" class="clearfix">
	
	<?php

		// Front Page - Top of container
		if(is_front_page() and is_active_sidebar('front-top_of_container')){
			dynamic_sidebar('front-top_of_container');
		}

	?>
	<div id="site-content-column"><?php

		if(is_archive()){
			blt_get_title();		
		}


		if(have_posts()){ 
			
			echo '<div class="row">';
			include(get_template_directory().'/loop.php');
			echo '</div>';

			
			// Previous/next page navigation.
			if(!blt_get_option('enable_infinite_scrolling')){

				the_posts_pagination(array(
					'prev_text'          => '<i class="fa fa-chevron-left"></i>',
					'next_text'          => '<i class="fa fa-chevron-right"></i>',
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'bluthemes' ) . ' </span>',
				));

			}			

		}else{ 
			
			get_template_part( 'inc/template-parts/content', 'none' );
		
		} ?> 
		
	</div><?php

	# 	
	# 	SIDEBAR
	# 	========================================================================================
	#   Load the sidebar if needed
	# 	========================================================================================
	# 		
	if(in_array(blt_get_option('sidebar_layout', 'right'), array('left', 'right'), true)){
		get_sidebar();
	} ?>

</div>

<?php get_footer(); ?>