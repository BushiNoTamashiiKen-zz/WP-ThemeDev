<article class="post">

	<div class="content-body">

		<h1 class="title"><?php _e('Post not found', 'bluthemes'); ?></h1>
		<p class="lead"><?php _e('We could not find that post you were looking for.', 'bluthemes'); ?></p>
		<hr>
		<h3><?php _e('Try searching', 'bluthemes') ?></h3>
		<?php echo get_search_form(); ?>
		<hr>
		<?php get_template_part( 'inc/recent-posts' ); ?>

	</div>

</article>