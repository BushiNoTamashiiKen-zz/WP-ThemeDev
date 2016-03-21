<article <?php post_class('post popular-posts-item'. (!has_post_thumbnail() ? ' popular-posts-item-no-image' : '')); ?>><?php

	if(has_post_thumbnail(get_the_ID())){ ?>
		<div class="top-posts-image">
			<a href="<?php echo esc_url(get_the_permalink()) ?>">
				<?php the_post_thumbnail('thumbnail', array('class' => 'img-circle', 'alt' => get_the_title())); ?>
			</a>
		</div><?php
	} ?>

	<div class="top-posts-meta blt-middle-align">
		<h2 class="top-posts-title">
			<a href="<?php echo esc_url(get_the_permalink()) ?>">
				<?php the_title() ?>
			</a>	
		</h2>
	</div>

</article>