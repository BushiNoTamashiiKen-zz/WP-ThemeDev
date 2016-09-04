<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * This is a local template override of the same name from the plugin views directory
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural = tribe_get_event_label_plural();

$event_id = get_the_ID();

?>
<!-- Header -->
<header class="section-head">	

	<!-- logo -->
	<div class="logo">
		<a href="<?php echo home_url(); ?>">
			<!-- svg logo - toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script -->
			<img src="<?php echo get_template_directory_uri(); ?>/img/underbar-logo-01.svg" alt="Logo" class="logo-img">
		</a>
	</div>
	<!-- /logo -->
	
	<!-- Navigation -->
	<div class="left-btn-area">
		<h3 class="tribe-events-visuallyhidden"><?php printf( esc_html__( '%s Navigation', 'the-events-calendar' ), $events_label_singular ); ?></h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous">
				<?php tribe_the_prev_event_link( '<i class="fa fa-chevron-left" aria-hidden="true"></i>Previous' ) ?>
			</li>
			<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( 'Next<i class="fa fa-chevron-right" aria-hidden="true"></i>' ) ?>
			</li>
		</ul>
	</div>
	<!-- .tribe-events-sub-nav -->
	
	<!--<h3>Doors open</h3>
		<div id="active-link">
			<?php echo tribe_events_event_schedule_details( $event_id, '<span>', '</span>' ); ?>
		</div>-->
	<p><?php the_title(); ?></p>
	<!-- Navigation -->
	<div class="right-btn-area">
		<a class="green_button" href="<?php echo esc_url( tribe_get_events_link() ); ?>"> 
			<i class="fa fa-calendar" aria-hidden="true"></i><?php printf( esc_html__( 'All %s', 'the-events-calendar' ), $events_label_plural ); ?>
		</a>
	</div>
</header>

<section class="content-area-single">
	<div id="tribe-events-content" class="tribe-events-single">

		<!-- Notices -->
		<?php tribe_the_notices() ?>

		<!--<div class="tribe-events-schedule tribe-clearfix">
			<?php if ( tribe_get_cost() ) : ?>
				<span class="tribe-events-divider">|</span>
				<span class="tribe-events-cost"><?php echo tribe_get_cost( null, true ) ?></span>
			<?php endif; ?>
		</div>-->

		<!-- Event header -->
		<div id="tribe-events-header" <?php tribe_events_the_header_attributes() ?>>
			<!-- Navigation -->
			<h3 class="tribe-events-visuallyhidden"><?php printf( esc_html__( '%s Navigation', 'the-events-calendar' ), $events_label_singular ); ?></h3>
			<ul class="tribe-events-sub-nav">
				<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ) ?></li>
				<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ) ?></li>
			</ul>
			<!-- .tribe-events-sub-nav -->
		</div>
		<!-- #tribe-events-header -->

		<?php while ( have_posts() ) :  the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<!-- .tribe-events-single-event-description -->
				<?php do_action( 'tribe_events_single_event_after_the_content' ) ?>

				<!-- Event featured image, but exclude link -->
				<?php echo tribe_event_featured_image( $event_id, 'full', false ); ?>

				<!-- Event content -->
				<?php do_action( 'tribe_events_single_event_before_the_content' ) ?>
				<div class="tribe-events-single-event-description tribe-events-content">
					<?php the_content(); ?>
				</div>

				<!-- Event meta -->
				<?php do_action( 'tribe_events_single_event_before_the_meta' ) ?>

				<?php tribe_get_template_part( 'modules/meta' ); ?>
				<?php do_action( 'tribe_events_single_event_after_the_meta' ) ?>
			</div> <!-- #post-x -->
			<?php if ( get_post_type() == Tribe__Events__Main::POSTTYPE && tribe_get_option( 'showComments', true ) ) comments_template() ?>
		<?php endwhile; ?>

		<!-- Event footer -->
		<div id="tribe-events-footer">
			<!-- Navigation -->
			<h3 class="tribe-events-visuallyhidden"><?php printf( esc_html__( '%s Navigation', 'the-events-calendar' ), $events_label_singular ); ?></h3>

			<!-- Edit Event Link for logged in users-->
			<?php
				if (is_user_logged_in()) {

					edit_post_link('Edit event');
				} 
			 ?>

			<!--<ul class="tribe-events-sub-nav">
				<li class="tribe-events-nav-previous">
					<?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ) ?>
				</li>
				<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ) ?></li>
			</ul>-->
			<!-- .tribe-events-sub-nav -->
		</div>
		<!-- #tribe-events-footer -->

	</div><!-- #tribe-events-content -->
</section>	
