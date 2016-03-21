<?php get_header(); ?>

<div id="site-content" class="clearfix">
	<?php

	# 	
	# 	SIDEBAR
	# 	========================================================================================
	#   Load the left sidebar if needed
	# 	========================================================================================
	# 
	if(in_array(blt_get_option('sidebar_layout', 'left'), array('left', 'right'), false)){
		get_sidebar();
	}

	?>
	<div id="site-content-column"><?php

		/*if(is_archive()){
			blt_get_title();		
		}*/
		/*if(is_front_page()){
		echo '<div class="cat-post-wrap">
				<div class="cat-post-thumbnail">
					<img src="http://aeonpet.designsample.biz/Dev/AEONPET/wp-content/uploads/2016/03/amgmtimes_page_pc_under_header_img.jpg">
				</div>
			  </div>';
		}*/

		if(have_posts()){

			//query_posts('meta_key=post_views_count&orderby=meta_value_num&order=DESC&posts_per_page=6');//Function call for views

			echo '<div class="row">';
			include(get_template_directory().'/category-stuff.php');
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
	#   Load the right sidebar
	# 	========================================================================================
	#

	if(is_active_sidebar('secondary')) {

		echo '<aside id="site-content-sidebar-right">';
			echo '<div class="content-sidebar-wrap">';

				// Regular
				if(is_active_sidebar('secondary')){ 

					dynamic_sidebar('secondary');

				}else{

					dynamic_sidebar('primary-sidebar');
				}

			echo '</div>';
		echo '</aside>';

	} 		
	?>

</div>

<?php get_footer(); ?>