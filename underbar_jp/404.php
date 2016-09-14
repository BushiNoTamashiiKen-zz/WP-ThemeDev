<?php get_header(); ?>
	
<?php get_sidebar(); ?>

	<main role="main">
		<!-- section -->
		<section class="flex-vertical error-text">

			<!-- article -->
			<article id="post-404">

				<h1><?php _e( 'Page not found', 'html5blank' ); ?></h1>
				<h2>
					<a href="<?php echo home_url(); ?>"><?php _e( 'Return home?', 'html5blank' ); ?></a>
				</h2>

			</article>
			<!-- /article -->

		</section>
		<!-- /section -->
	</main>

<?php get_footer(); ?>
