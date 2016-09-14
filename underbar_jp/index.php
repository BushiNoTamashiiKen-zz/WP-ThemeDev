<?php get_header(); ?>

<?php get_sidebar(); ?>

	<main role="main" class="flex-lrg">

		<!-- Header -->
		<header class="section-head">
		
			<div class="flex-horizontal left-btn-area">
				<!--<div class="side-icon"><span></span></div>-->
				<div class="flex-vertical left-actions">

					<h3><?php _e('Upcoming events'); ?></h3>
					<a id="active-link" href="<?php echo home_url('/event/'); ?>"><?php /*echo date('j F Y');*/ echo tribe_get_start_date( $post ); ?></a>
				</div>
			</div>

			<!-- logo -->
			<div class="logo">
				<a href="<?php echo home_url(); ?>">

					<!--<img src="<?php echo get_template_directory_uri(); ?>/img/underbar-logo-01.svg" alt="Logo" class="logo-img">-->
					<img src="<?php echo get_template_directory_uri(); ?>/img/underbar-logo-01.png" alt="Logo" class="logo-img">
				</a>
			</div>
			<!-- /logo -->

			<p><?php if(is_user_logged_in()) {

				$user = wp_get_current_user();
				echo 'Welcome'.' '.$user->display_name;
			} else {

			  	_e( 'Underbar Events', 'Underbar' );
			}?>
			</p>
			<div class="right-btn-area">
				<?php if(!is_user_logged_in()) {?>

					<a class="signup_button" id="show_signup" href=""><i class="fa fa-calendar-plus-o" aria-hidden="true"></i>Add Event</a>
				<?php } else { ?>

					<span><a class="green_button" href="<?php echo home_url('wp-admin/edit.php?post_type=tribe_events'); ?>"><i class="fa fa-calendar-o" aria-hidden="true"></i>New Event</a></span>
				<?php } ?>
			</div>
		</header>
		<!-- /Header -->

		<!-- section -->
		<section class="content-area">

			<?php get_template_part('event-loop'); ?>
			<?php get_template_part('pagination'); ?>
		</section>
		<!-- /section -->

		<!-- Toggle scroll up/down -->
		<div class="updown-buttons">
			<a class="scrolltoTop" href="#"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>
			<a class="scrolltoBottom" href="#"><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
		</div>
	</main>
	<?php get_footer(); ?>