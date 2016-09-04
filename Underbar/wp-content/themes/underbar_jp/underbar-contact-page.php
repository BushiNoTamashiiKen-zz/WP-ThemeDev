<?php 
/**
 * Template Name: Contact Page Template 
 * Description: Displays a regular page for site content such as contact us
 */ 

get_header(); ?>
	
<?php get_sidebar(); ?>
	<main role="main" class="main flex-lrg">

		<!-- section -->
		<section class="section-contact flex-vertical">

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
				<div class="banner-block flex-vertical">
					<h1>Link up</h1>
					<p>with Underbar</p>
				</div>
				<div class="contact-card-wrap flex-vertical">

					<div class="contact-card-block flex-vertical">

						<?php if (have_posts()): while (have_posts()) : the_post(); ?>

							<!-- article -->
							<p id="post-id=<?php the_ID(); ?>" <?php post_class(); ?>>

								<?php the_content(); ?>
								<?php //comments_template( '', true ); ?>
								<?php edit_post_link(); ?>
							</p>
							<!-- /article -->

						<?php endwhile; ?>

						<?php else: ?>

							<!-- article -->
							<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
							<!-- /article -->

						<?php endif; ?>
					</div>
					<footer>
						<div class="social-contact-bar">
							<ul>
								<li><a href="#" class="fa fa-twitter"></a></li>
								<li><a href="#" class="fa fa-facebook"></a></li>
								<li><a href="#" class="fa fa-instagram"></a></li>
							</ul>
						</div>
					</footer>
				</div>
			</div>
		</section>
		<!-- /section -->

	</main>

<?php get_footer(); ?>
