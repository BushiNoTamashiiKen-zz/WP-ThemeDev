<?php get_header(); ?>

	<main role="main">
	<!-- section -->
	<section>

	<?php if (have_posts()): while (have_posts()) : the_post(); ?>

		<!-- article -->
		<article>
			<?php $price = get_post_meta($post->ID, 'store_item_price', true); ?>

			<h1 class="single-title">
				<?php the_title(); ?>
				<span><?php echo '¥'. $price ?></span>
			</h1>
			<form class="blt_posts_form" action="" method="post">
				<fieldset class="form-group">
					<h3>Done Shopping?</h3>
					<p>Checkout to proceed otherwise cancel</p>

					<input type="hidden" name="action" value="1">
					<button class="btn btn-theme btn-lg" name="checkout_submitted" id="checkout_submitted" type="submit"><?php _e('Checkout', 'bluthemes'); ?>
						</button>
					<span></span>
					<button class="btn btn-theme btn-lg" name="checkout_cancelled" id="checkout_cancelled" type="submit"><?php _e('Go back', 'bluthemes'); ?>
					</button>
				</fieldset>
			</form>

			<div class="custom-post-img"> <?php the_post_thumbnail(); ?></div>

			<div class="single-text"><?php 

				the_content(sprintf(
					__( 'Continue reading %s', 'bluthemes' ),
						the_title( '<span class="screen-reader-text">', '</span>', false )
				));

				// Paginated posts
				wp_link_pages( array( 'before' => '<nav class="blu-post-pagination" role="navigation"><strong>'.__( 'Pages:', 'bluthemes' ).'</strong>', 'link_before' => '<span>', 'after' => '</nav>', 'link_after' => '</span>', 'pagelink' => '%'));
						
				?>
			</div>

		<?php edit_post_link(); // Always handy to have Edit Post Links available ?>

		<?php comments_template(); ?>

		</article>
		<!-- /article -->

	<?php endwhile; ?>

	<?php else: ?>

		<!-- article -->
		<article>

			<h1><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h1>

		</article>
		<!-- /article -->

	<?php endif; ?>

	</section>
	<!-- /section -->
	</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>