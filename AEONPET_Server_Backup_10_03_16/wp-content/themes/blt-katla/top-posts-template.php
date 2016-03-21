<?php
#   
#   Template Name: Bluthemes - Top Posts Page
#   ========================================================================================
#   This page template can be used to show the most popular posts on your site 
#   ========================================================================================
#    
get_header(); 


$layout = get_field('top_posts_layout');

	if(empty($layout)){
		$layout = 'list';
	}

?>
<div id="site-content" class="clearfix">

	<div id="site-content-column" class="popular-posts-<?php echo esc_attr($layout) ?>"><?php
		
		if(have_posts()){ 

			if(in_array($layout, array('list', 'blog2'))){

				while(have_posts()){ the_post(); 

					if($layout == 'blog2'){ ?>
						<article class="post content-post">

							<div class="content-body page-body">
								<h1 class="page-title"><?php the_title(); ?></h1>

								<?php blt_post_thumbnail(); ?>
								
								<div class="page-text">
									<?php the_content(); ?>
								</div>
							</div>
						</article><?php

					}elseif($layout == 'list'){ ?>
						<div class="page-body">
							<h1 class="page-title"><?php the_title(); ?></h1>

							<?php blt_post_thumbnail(); ?>
							
							<div class="page-text">
								<?php the_content(); ?>
							</div>
						</div><?php
					}
				}		
			}
			echo '<div class="row">';
			include( get_template_directory().'/loop-hottest.php' );
			echo '</div>';

			wp_reset_postdata();
		
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
	if(in_array(blt_get_option('sidebar_layout', 'right'), array('left', 'right'), true) or in_array(get_field('blt_sidebar'), array('left', 'right'), true)){	
		get_sidebar();
	} ?>


</div>

<?php get_footer(); ?>
