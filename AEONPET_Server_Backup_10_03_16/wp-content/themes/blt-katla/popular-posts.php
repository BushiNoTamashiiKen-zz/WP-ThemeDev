<?php
/**
 * Template Name: Popular Posts by view/comment
 *
 * Description: This page is a custom archive page for displaying the most popular posts
 *
 * @param $post_view or $comments
 *
 * Author: Thabo Mbuyisa
 *
 * Last modified: 01 - 03 - 16
 */
get_header(); 

?>
<div id="site-content" class="clearfix">
	<div id="site-content-column">
		<div class="row">
			<?php
			//Comments
			/*echo aeonpet_popular_by_comment(10);*/

			//Views
			query_posts('meta_key=post_views_count&orderby=meta_value_num&order=DESC&posts_per_page=6');//Function call for views

			while(have_posts()) : the_post();

				the_post();
				get_template_part( 'inc/template-parts/content', $post_layout );

			endwhile; ?>

			<?php /*wp_reset_query();*/ //reset the function call after loop has run ?>
		</div>
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
