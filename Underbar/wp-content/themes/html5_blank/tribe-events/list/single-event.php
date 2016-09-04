<?php
/**
 * List View Single Event
 * This file contains one event in the list view
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/single-event.php
 *
 * @package TribeEventsCalendar
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Setup an array of venue details for use later in the template
$venue_details = tribe_get_venue_details();

// Venue
$has_venue_address = ( ! empty( $venue_details['address'] ) ) ? ' location' : '';

// Organizer
$organizer = tribe_get_organizer();

?>

<!-- Event Image -->
<div class="post-thumb-container">
	<div class="hovereffect">
		<?php echo tribe_event_featured_image( null, 'medium' ) ?>
		<a class="overlay" href="<?php echo esc_url( tribe_get_event_link() ); ?>" title="<?php the_title_attribute() ?>">
			<h2><?php the_title(); ?></h2>
			<div class="event-info">
				<!-- Event Cost -->
				<?php if ( tribe_get_cost() ) : ?>
					<span><?php echo tribe_get_cost( null, true ); ?></span>
				<?php endif; ?>
			</div>
		</a>
	</div>
</div>
<!-- /Event Image -->

<!-- Post Info Container -->
<div class="post-info-container">

	<!-- Event Meta -->
	<?php do_action( 'tribe_events_before_the_meta' ) ?>
	<div class="tribe-events-event-meta">

		<!-- Schedule & Recurrence Details -->
		<footer class="flex-horizontal post-footer-meta">
			<span class="event-date">When: <a id="active-link" href=""><?php echo tribe_events_event_schedule_details(); ?></a></span>
			<span class="author"><?php _e( 'Hosted by:', 'html5blank' ); ?><?php the_author_posts_link(); ?><i class="fa fa-user" aria-hidden="true"></i></span>
		</footer>

		<?php if ( $venue_details ) : ?>
			<!-- Venue Display Info --
			<div class="tribe-events-venue-details">
				<?php echo implode( ', ', $venue_details ); ?>
			</div> <!-- .tribe-events-venue-details -->
		<?php endif; ?>
	</div><!-- .tribe-events-event-meta -->
	<?php do_action( 'tribe_events_after_the_meta' ) ?>
	<!-- /Event Meta -->

	<!-- Event Content -->
	<?php do_action( 'tribe_events_before_the_content' ) ?>
	<div class="tribe-events-list-event-description tribe-events-content">
		<?php echo tribe_events_get_the_excerpt( null, wp_kses_allowed_html( 'post' ) ); ?>
		<a href="<?php echo esc_url( tribe_get_event_link() ); ?>" class="tribe-events-read-more" rel="bookmark"><?php esc_html_e( 'Find out more', 'the-events-calendar' ) ?> &raquo;</a>
	</div><!-- .tribe-events-list-event-description -->

	<!-- Footer Post Meta --
	<footer class="post-footer-meta flex-horizontal">
		<span class="event-title"><?php //echo "$post->post_title"; ?></span>
		<span class="event-date">When: <a id="active-link" href=""><?php echo tribe_get_start_date( $post ); ?></a></span>
		<span class="author"><?php _e( 'Hosted by:', 'html5blank' ); ?><?php the_author_posts_link(); ?><i class="fa fa-user" aria-hidden="true"></i></span>
	</footer>-->
</div>

<?php
do_action( 'tribe_events_after_the_content' );

