<?php $post_thumbnail_size = blt_has_sidebar() == 'none' ? 'hg' : 'lg'; ?>

<article <?php post_class('content-post col-xs-12 col-sm-12 col-md-4 col-lg-4'); ?>><?php

	#  
	#  DISPLAY POST STATUS
	#  ========================================================================================
	#   

		if(get_post_status() != 'publish'){ ?>
			<div class="label post-status"><?php echo get_post_status(); ?></div><?php
		}

	$image_location = blt_get_option('image_location_on_blog_page', 'above');

		if($image_location == 'above' and has_post_thumbnail()){ ?>
			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"> <?php
				the_post_thumbnail( $post_thumbnail_size, array( 'alt' => get_the_title() ) ); ?>
			</a><?php
		} ?>

	<div class="content-body"><?php

		if(is_sticky()){
			echo '<p class="label-wrap"><span class="label label-blt label-sticky"><i class="fa fa-star"></i>'.__('Featured Post', 'bluthemes').'</span></p>';
		} ?>

		<h2 class="content-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		<div class="content-meta">
			<?php blt_get_post_meta() ?>
		</div>

		<div class="content-text"><?php 

			if($image_location == 'below' and has_post_thumbnail()){ ?>
				
				<p>
					<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"> <?php
						the_post_thumbnail( $post_thumbnail_size, array( 'alt' => get_the_title() ) ); ?>
					</a>
				</p><?php

			}
			
			switch (blt_get_option('show_content_or_excerpt', 'excerpt')) {
				
				case 'title':
					// don't render anything
					break;

				case 'content':
					the_content(__('Continue reading...', 'bluthemes')); 
					break;
				
				case 'excerpt':
				default:
					the_excerpt();
					break;
			} ?>

		</div>

	</div>

	<div class="content-footer clearfix">

		<div class="content-footer-share">
			<?php get_share_buttons(); ?>
		</div>

		<?php if(blt_get_option('post_voting', true)){ 
			
			$score = get_post_meta( get_the_ID(), 'blt_score', true );

				if(empty($score)){
					$score = 0;
				}
				?>
			<div class="content-footer-meta">
				<div class="post-vote-header">
					<a class="btn btn-default post-vote post-vote-up" data-post-id="<?php echo get_the_ID() ?>" href="#vote-up" title="<?php echo esc_attr(__('Like', 'bluthemes')) ?>"><i class="fa fa-chevron-up"></i></a>
					<span class="vote-count"><?php echo esc_attr($score); ?></span>
					<a class="btn btn-default post-vote post-vote-down" data-post-id="<?php echo get_the_ID() ?>" href="#vote-down" title="<?php echo esc_attr(__('Dislike', 'bluthemes')) ?>"><i class="fa fa-chevron-down"></i></a>
				</div>
			</div>
		<?php } ?>

	</div>

</article>