

<!-- sidebar -->
<aside class="sidebar flex-sml" role="complementary">

	<div class="fixed-side-container">
		<!-- Absolute container -->
		<div class="side-content-container">

			<!-- logo -->
			<div class="logo">
				<a href="<?php echo home_url(); ?>">
					<!-- svg logo - toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script -->
					<img src="<?php echo get_template_directory_uri(); ?>/img/underbar-logo-01.svg" alt="Logo" class="logo-img">
				</a>
			</div>
			<!-- /logo -->

			<!-- nav -->
			<div class="side-nav-wrap flex-vertical">
				<nav class="nav" role="navigation">

					<?php if(!is_user_logged_in()) {?>

						<ul>
							<li><a class="login_button" id="show_login" href="">Login</a></li>
						</ul>
					<?php } else { ?>

						<ul>
							<li><a class="login_button" href="<?php echo wp_logout_url( home_url() ); ?>">Logout</a></li>
						</ul>
					<?php } ?>

					<?php html5blank_nav(); ?>
				</nav>
			</div>
			<!-- /nav -->

		
			<?php get_template_part('searchform'); ?>

			<div class="sidebar-widget">
				<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-2')) ?>
			</div>
		</div>

		<div class="sidebar-widget">
			<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-1')) ?>
		</div>
	</div>
</aside>
<!-- /sidebar -->

