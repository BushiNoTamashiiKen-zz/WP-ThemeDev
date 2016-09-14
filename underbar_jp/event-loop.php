<?php 
// Ensure the global $post variable is in scope
global $post;
 
// Retrieve upcoming events
$events = tribe_get_events( array(

    'posts_per_page' => 0,
    'order' => 'DESC'
) ); ?>

<?php if (have_posts()) {

	foreach ( $events as $post ) {

    	setup_postdata( $post );?>

		<section class="post-block">
			<!-- article -->
			<article class="" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<!-- post thumbnail -->
				<div class="post-thumb-container">

					<?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
						<div class="hovereffect">
							<?php the_post_thumbnail(array(500,350)); // Declare pixel size you need inside the array ?>
							<a class="overlay" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					            <h2><?php the_title(); ?></h2>
					            <!--<a class="info" href="#">link here</a>-->
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
								<li><a href="#" class=""><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
								<li><a href="#" class=""><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
								<li><a href="#" class=""><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
							</ul>
						</div>
					</div>

					<?php the_content(); ?>

					<!-- post details -->
					<span class="date"><?php //the_time('F j, Y'); ?> <?php //the_time('g:i a'); ?></span>
					
					<?php html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>

					<!-- Footer Post Meta -->
					<footer class="flex-horizontal post-footer-meta">
						<span class="event-title"><?php //echo "$post->post_title"; ?></span>
						<span class="event-date">When: <a id="active-link" href="<?php echo home_url('/event/') ?>"><?php echo tribe_get_start_date( $post ); ?></a><i class="fa fa-calendar-check-o" aria-hidden="true"></i></span>
						<span class="author"><?php _e( 'Hosted by:', 'html5blank' ); ?><?php the_author_posts_link(); ?><i class="fa fa-user" aria-hidden="true"></i></span>
						<span class="comments">Comments: <?php if (comments_open( get_the_ID() ) ) comments_popup_link( __( 'Leave a comment', 'html5blank' ), sprintf( '1 Comment', 'html5blank' ), __( '% Comments', 'html5blank' )); ?>
							<i class="fa fa-comments" aria-hidden="true"></i>
						</span>
					</footer>
				</div>
				<!-- /Post Info Container -->

			</article>
			<!-- /article -->
		<section>
	<?php } ?>

<?php } else { ?>

	<!-- article -->
	<article>

		<h2><?php _e( 'すみません, ここ何も否です.', 'html5blank' ); ?></h2>
	</article>
	<!-- /article -->

<?php } ?>
