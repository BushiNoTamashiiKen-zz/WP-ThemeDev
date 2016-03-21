<?php
/**
 * Template Name: Archives
 * This page is a custom archive page for displaying a list of categories
 * Created by Thabo Mbuyisa
 * Last modified 23 - 02 - 16
 */
get_header(); ?>


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

	<div id="site-content-column">

	<!-- Adding the categories list -->
	<h3 class="widget-head">Top Pet Categories</h3>
	<?php 


	 	/*function blt_wp_list_categories($links) {

			return str_replace(array('</a> (',')'), array('</a> <span class="badge">','</span>'), $links);

		}*/

		//wp_list_categories('number=all&show_count=1&orderby=count&order=DESC&title_li=');

		//for each category, show posts
		$cat_args=array(
		  'orderby' => 'count',
		  'order' => 'DESC'
		   );
		$categories=get_categories($cat_args);
		  foreach($categories as $category) {
		    $args=array(
		      'orderby' => 'count',
		      'order' => 'DESC',
		      'posts_per_page' => 5,
		      'category__in' => array($category->term_id),
		      'post_status' => 'publish',
		      'show_count' => 1
		    );

		     // Usual loop to display categories
		    //
		    /*$myposts = new WP_Query( $args );
			if ($myposts->have_posts()) {
  				while ($myposts->have_posts()) {
  					echo '<a class="categoryLink" href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a> ';
    				$myposts->the_post(); ?>
    				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><?php
  				}
  			}*/
  			//
  			// Customized loop for displaying categories
		    $posts=get_posts($args);
		      if ($posts) {
		        echo '<a class="categoryLink" href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a> ';
		        //
		          foreach($posts as $post) {
		          setup_postdata($post); ?>
		          <ul class="category-entries"><li><p><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><!--<div class="thumbnail"><?php /*the_post_thumbnail( array(90,90) );*/?></div>--><span><?php the_title(); ?></span></a></p></li></ul>
		          <?php
		        } // foreach($posts
		      } // if ($posts
		    } // foreach($categories
		?>
		
	<!--<ul class="bycategories">
		<?php wp_list_categories('title_li='); ?>
	</ul>-->

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
