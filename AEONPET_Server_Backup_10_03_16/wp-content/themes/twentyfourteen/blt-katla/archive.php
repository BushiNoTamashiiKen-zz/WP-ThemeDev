<?php get_header(); ?>

<header class="site-content-header">
	
	<div class="container"><?php

		echo blt_archive_title( '<h1 class="header-title">', '</h1>' );
		the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
	
	</div>

</header><!-- .page-header -->

<div id="site-content" class="clearfix">

	<div id="site-content-column"><?php

		if(is_archive()){
			blt_archive_title( '<h1 class="page-title">', '</h1>' );		
		}


		if(have_posts()){ 

			echo '<div class="row">';
			include(get_template_directory().'/loop.php');
			echo '</div>';


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

	if(in_array(blt_get_option( $sidebar_layout, 'right'), array('left', 'right'), true)){
		get_sidebar();
	} ?>


</div>

<?php get_footer(); ?>