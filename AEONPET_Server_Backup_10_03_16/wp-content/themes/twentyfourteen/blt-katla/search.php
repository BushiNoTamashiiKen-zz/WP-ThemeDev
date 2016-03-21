<?php get_header(); ?>

<header class="site-content-header">
	
	<div class="container">
		<h1 class="header-title"><?php printf( __( 'Search Results for: <span>%s</span>', 'bluthemes' ), get_search_query() ); ?></h1>
	</div>

</header><!-- .page-header -->

<div id="site-content" class="clearfix">

	<div id="site-content-column"><?php

		if(is_archive()){
			blt_archive_title( '<h1 class="page-title">', '</h1>' );		
		}

		if(have_posts()){ 

			while(have_posts()){ 

				the_post(); 
				get_template_part( 'inc/template-parts/content', get_post_format() );
			}

			// Previous/next page navigation.
			the_posts_pagination(array(
				'prev_text'          => __( 'Previous page', 'bluthemes' ),
				'next_text'          => __( 'Next page', 'bluthemes' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'bluthemes' ) . ' </span>',
			));

		}else{ 

			get_template_part( 'inc/template-parts/content', 'none' );
		}


		?> 
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