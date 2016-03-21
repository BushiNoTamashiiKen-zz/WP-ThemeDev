<article <?php post_class(); ?>>
	
	<h1 class="single-title"><?php the_title(); ?></h1>
	
	<?php blt_post_thumbnail(); ?>

	<div class="single-text clearfix"><?php 
		
		the_content(sprintf(
			__( 'Continue reading %s', 'bluthemes' ),
			the_title( '<span class="screen-reader-text">', '</span>', false )
		));

		wp_link_pages(array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'bluthemes' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'bluthemes' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		));
		?>
	</div>


</article>