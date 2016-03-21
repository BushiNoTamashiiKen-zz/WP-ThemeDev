<article <?php post_class(); ?>>
	
	<?php
		if(is_sticky()){
			echo '<p class="label-wrap"><span class="label label-blt label-sticky"><i class="fa fa-star"></i>'.__('Featured Post', 'bluthemes').'</span></p>';
		}
	?>


	<h1 class="single-title"><?php the_title(); ?></h1>

	<div class="single-meta"><?php blt_get_post_meta() ?></div>

	<div class="single-text"><?php 
		
		the_content(sprintf(
			__( 'Continue reading %s', 'bluthemes' ),
			the_title( '<span class="screen-reader-text">', '</span>', false )
		));


		// Paginated posts
		wp_link_pages( array( 'before' => '<nav class="blu-post-pagination" role="navigation"><strong>'.__( 'Pages:', 'bluthemes' ).'</strong>', 'link_before' => '<span>', 'after' => '</nav>', 'link_after' => '</span>', 'pagelink' => '%'));
		
		?>
	</div><?php

	blt_post_thumbnail();


	// AD SPOT #5
	blt_get_ad_spot('spot_below_content');

	// Tags
	the_tags('<div class="blu-post-tags"><strong>'.__( 'Tags:', 'bluthemes' ).'</strong><ul><li>', '</li><li>', '</li></ul></div>');

	// More Info URL 
	if(get_field('blt_info_url')){ ?>
		<hr>
		<strong><a href="<?php echo get_field('blt_info_url'); ?>" target="_blank"><?php echo get_field('blt_info_name') ? get_field('blt_info_name') : __('More Info'); ?></a></strong><?php
	}

	?>
</article>