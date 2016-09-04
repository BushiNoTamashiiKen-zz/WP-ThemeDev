<?php 
/**
 * Template Name: About Page Template 
 * Description: Displays a regular page for site content such as about
 */ 

get_header(); ?>
	
<?php get_sidebar(); ?>
	<main role="main" class="main flex-lrg">
		<!-- section -->
		<section class="section-about flex-vertical">

			<!-- Header -->
			<header class="section-head flex-vertical">

				<!-- logo -->
				<div class="logo">
					<a href="<?php echo home_url(); ?>">
						<!-- svg logo - toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script -->
						<img src="<?php echo get_template_directory_uri(); ?>/img/underbar-logo-01.svg" alt="Logo" class="logo-img">
					</a>
				</div>
				<!-- /logo -->

				<h1><?php the_title(); ?></h1>
			</header>
			<!-- /Header -->

			<div class="section-banner flex-horizontal">
				<div class="banner-block">
					<h1>Hi</h1>
					<p>we are<span id="active-link">Underbar</span><br /> 
					 	a crowdsourced 
					 	bartending and 
					 	events service
					 	<br />with a twist</p>
				</div>
				<div class="section-about-content flex-vertical">
					<figure class="image-block"><?php the_post_thumbnail(); ?></figure>

					<!--<h3><?php _e('How to become a bartender', 'html5blank');?></h3>-->
					<?php if (have_posts()): while (have_posts()) : the_post(); ?>

						<!-- article -->
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<div class="about-text-area flex-horizontal">

							<?php the_content(); ?>
						</div>
							<?php //comments_template( '', true ); // Remove if you don't want comments ?>

							<br class="clear">

							<?php edit_post_link(); ?>

						</article>
						<!-- /article -->

						<?php if(is_user_logged_in()) {?>

							<div class="start-btn flex-box">
								<a class="green_button" href="<?php echo home_url('/wp-admin/index.php/'); ?>"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i>Get started</a>
							</div>
						<?php } else {?>

							<div class="start-btn flex-box">
								<a class="green_button" id="show_signup" href=""><i class="fa fa-calendar-plus-o" aria-hidden="true"></i>Get started</a>
							</div>
						<?php } ?>

					<?php endwhile; ?>

					<?php else: ?>

						<!-- article -->
						<article>

							<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>

						</article>
						<!-- /article -->

					<?php endif; ?>
				</div>
			</div>
		</section>
		<!-- /section -->

		<!-- Toggle scroll up/down --
		<div class="updown-buttons">
			<a class="scrolltoTop" href="#"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>
			<a class="scrolltoBottom" href="#"><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
		</div>-->
	</main>

<?php get_footer(); ?>
