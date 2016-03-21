<?php acf_form_head(); get_header();  ?>


<div id="site-content" class="clearfix">

	<?php 

		// Front Page - Top of container
		if(is_front_page() and is_active_sidebar('home-top_of_container')){ 
			dynamic_sidebar('home-top_of_container');
		}

		// Single - Top of container
		if(is_active_sidebar('single-top_of_container')){ 
			dynamic_sidebar('single-top_of_container');
		}

	?>

	<div id="site-content-column"><?php
		
		if($ad_spot_between_posts = blt_get_option('spot_above_content', 'none') != 'none'){
			blt_get_ad_spot('spot_above_content');
		}

		if(have_posts()){ 

			while(have_posts()){ the_post();

				get_template_part( 'inc/template-parts/single', get_post_format() );

				get_template_part( 'inc/template-parts/post-share' );

				get_template_part( 'inc/template-parts/voting-area'); //Fetch voting area part for display

				edit_post_link(__('<button class="edit-btn" type="button"><i class="fa fa-pencil-square"></i><span class="edit-txt">Edit Post</span></button>'), '');	

				// Previous/next post navigation.
				the_post_navigation(array(
					'next_text' => '<span class="meta-nav meta-nav-next" aria-hidden="true">' . __( 'Next', 'bluthemes' ) . '</span><span class="post-title">%title</span>',
					'prev_text' => '<span class="meta-nav meta-nav-prev" aria-hidden="true">' . __( 'Previous', 'bluthemes' ) . '</span><span class="post-title">%title</span>',
				));

				// Related Posts
				if(blt_get_option('show_related_posts', true)){
				 	get_template_part( 'inc/template-parts/post-related' );
				} 

				
			}

			// If comments are open load up the default comment template provided by Wordpress
			if(comments_open() || get_comments_number()){
				comments_template();
			}				
		
		 } ?> 


		<?php

		// Below Single Posts
		if(is_active_sidebar('post-below')){ 
			dynamic_sidebar('post-below');
		}

		?>


	</div><?php


	# 	
	# 	SIDEBAR
	# 	========================================================================================
	#   Load the sidebar if needed
	# 	========================================================================================
	# 		
	if(in_array(blt_get_option('default_post_sidebar_layout', 'right'), array('left', 'right'), true) or in_array(get_field('blt_sidebar'), array('left', 'right'), true)){
		get_sidebar();
	} ?>

	
</div><?php


	echo '<div class="footer-share clearfix">';


		if(blt_get_option('post_voting', true)){
			
			$score 	= get_post_meta( get_the_ID(), 'blt_score', true );

			if(empty($score)){
				$score = 0;
			} ?>
			<div class="post-vote-header">
				<a class="btn btn-default post-vote post-vote-up" data-post-id="<?php echo get_the_ID() ?>" href="#vote-up" title="<?php echo esc_attr(__('Like', 'bluthemes')) ?>"><i class="fa fa-chevron-up"></i></a>
				<span class="vote-count"><?php echo esc_attr($score); ?></span>
				<a class="btn btn-default post-vote post-vote-down" data-post-id="<?php echo get_the_ID() ?>" href="#vote-down" title="<?php echo esc_attr(__('Dislike', 'bluthemes')) ?>"><i class="fa fa-chevron-down"></i></a>
			</div><?php
		}

	    $share_url = rawurlencode(esc_url(get_permalink()));
	    $share_title = rawurlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8'));

		?>
		<a class="btn social-sharing share-facebook" onclick="blt_social_share(event, 'http://www.facebook.com/sharer.php?u=<?php echo $share_url; ?>&amp;t=<?php echo esc_attr($share_title); ?>')">
			<i class="fa fa-facebook"></i> <span class="visible-xs-inline visible-sm-inline"><?php _e('Share', 'bluthemes') ?></span>
		</a>
		<a class="btn social-sharing share-twitter" onclick="blt_social_share(event, 'http://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&amp;text=<?php echo esc_attr($share_title); ?>')">
			<i class="fa fa-twitter"></i> <span class="visible-xs-inline visible-sm-inline"><?php _e('Tweet', 'bluthemes') ?></span>
		</a><?php
	echo '</div>';

get_footer(); ?>