
<?php if (have_posts()): while (have_posts()) : the_post(); ?>

	<section class="post-block">
		<!-- article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<!-- post thumbnail -->
			<div class="post-thumb-container">

				<?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
					<div class="hovereffect">
					
						<?php the_post_thumbnail(array(300,250)); // Declare pixel size you need inside the array ?>
						<a class="overlay" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					    	<h2><?php the_title(); ?></h2>
					    </a>
					</div>
				<?php endif; ?>
			</div>
			<!-- /post thumbnail -->

			<!-- Post Info Container -->
			<div class="post-info-container">

				<div class="flex-horizontal post-title-container">

					<!-- post title -->
					<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
					<!-- /post title -->

					<div class="post-social-icons">
						<ul>
							<li><a href="#" class="fa fa-twitter"></a></li>
							<li><a href="#" class="fa fa-facebook"></a></li>
							<li><a href="#" class="fa fa-instagram"></a></li>
						</ul>
					</div>
				</div>

				<?php the_content(); ?>

				<!-- post details -->
				<span class="date"><?php the_time('F j, Y'); ?> <?php the_time('g:i a'); ?></span>
				<span class="author"><?php _e( 'Published by', 'html5blank' ); ?> <?php the_author_posts_link(); ?></span>
				<span class="comments"><?php if (comments_open( get_the_ID() ) ) comments_popup_link( __( 'Leave your thoughts', 'html5blank' ), __( '1 Comment', 'html5blank' ), __( '% Comments', 'html5blank' )); ?></span>
				<!-- /post details -->

				<?php html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>

				<?php //edit_post_link(); ?>

				<!-- Footer Post Meta -->
				<footer class="flex-horizontal post-footer-meta">
					<span class="event-title"><?php //echo "$post->post_title"; ?></span>
					<span class="event-date">When: <a id="active-link" href=""><?php echo tribe_get_start_date( $post ); ?></a></span>
					<span class="author"><?php _e( 'Hosted by:', 'html5blank' ); ?><?php the_author_posts_link(); ?><i class="fa fa-user" aria-hidden="true"></i></span>
				</footer>
			</div>
			<!-- /Post Info Container -->
			
		</article>
		<!-- /article -->
	<section>

<?php endwhile; ?>

<?php else: ?>

	<!-- article -->
	<article class="no-content">
		<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
	</article>
	<!-- /article -->

<?php endif; ?>
