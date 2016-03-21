<?php $article_class = blt_has_sidebar() == 'none' ? 'col-sm-6 col-md-4 col-lg-4' : 'col-sm-6 col-md-4 col-lg-4'; ?>

<article <?php post_class('content-post '.$article_class); ?>>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"> <?php
		the_post_thumbnail( 'md-crop', array( 'alt' => get_the_title() ) ); ?>
	</a>

	<div class="content-body"><?php

		if(is_sticky()){
			echo '<span class="label label-blt label-sticky"><i class="fa fa-star"></i>'.__('Featured Post', 'bluthemes').'</span>';
		} ?>

		<h2 class="content-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

	</div>

</article>