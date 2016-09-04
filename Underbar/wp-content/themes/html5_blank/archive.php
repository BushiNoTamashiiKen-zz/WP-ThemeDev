<?php get_header(); ?>

<?php get_sidebar(); ?>

	<main role="main">
		<!-- section -->
		<section>

			<h1><?php _e( 'Archives', 'html5blank' ); ?></h1>

			<div id="content">
				<?php get_template_part('loop'); ?>
			</div>

			<?php //get_template_part('pagination'); ?>

		</section>
		<!-- /section -->
	</main>

<?php get_footer(); ?>
