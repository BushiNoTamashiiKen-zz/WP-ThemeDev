<div class="flex-vertical comments">

	<header class="comments-head">

		<h1>Comments</h1>
	</header>
	<?php if (post_password_required()) : ?>
	<p><?php _e( 'Post is password protected. Enter the password to view any comments.', 'html5blank' ); ?></p>

	<?php return; endif; ?>

	<?php if (have_comments()) : ?>

		<article class="comments-body">

			<h3><?php comments_number(); ?></h3>
			<ul>
				<?php wp_list_comments('type=comment&callback=html5blankcomments'); // Custom callback in functions.php ?>
			</ul>
		</article>
	<?php elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<article class="comments-body">

			<p><?php _e( 'Comments are closed here.', 'html5blank' ); ?></p>
		</article>

	<?php endif; ?>

	<?php comment_form(); ?>
</div>
