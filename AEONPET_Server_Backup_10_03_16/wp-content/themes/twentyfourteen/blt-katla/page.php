<?php get_header(); ?>


<div id="site-content" class="clearfix">

	<?php

		// Front Page - Top of container
		if(is_front_page() and is_active_sidebar('home-top_of_container')){ 
			dynamic_sidebar('home-top_of_container');
		}

		// Page - Top of container
		if(is_active_sidebar('page-top_of_container')){ 
			dynamic_sidebar('page-top_of_container');
		}

	?>

	<div id="site-content-column"><?php

		if(have_posts()){ 

			while(have_posts()){

				the_post();

				get_template_part( 'inc/template-parts/page', get_post_format() );

			}				
		
		 } 


		// If comments are open load up the default comment template provided by Wordpress
		if(comments_open() || get_comments_number()){
			comments_template();
		}	

		?> 
	</div><?php

	# 	
	# 	SIDEBAR
	# 	========================================================================================
	#   Load the sidebar if needed
	# 	========================================================================================
	# 		
	if(in_array(blt_get_option('sidebar_layout', 'right'), array('left', 'right'), true) or in_array(get_field('blt_sidebar'), array('left', 'right'), true)){	
		get_sidebar();
	} ?>

</div>

<?php get_footer(); ?>