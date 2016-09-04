			<!-- footer -->
			<footer class="footer" role="contentinfo">

				<!-- Fixed bottom nav -->
				<div class="mobile-nav">
					<nav class="navbar navbar-bottom-fixed">

						<?php html5blank_nav(); ?>

						<ul class="flex-horizontal foot-login">
							<?php if(!is_user_logged_in()) {?>

							<li><a class="signup_button" id="show_signup" href=""><i class="fa fa-calendar-plus-o" aria-hidden="true"></i>イベントを作る</a></li>
							<?php } else { ?>

								<li><span><a class="green_button" href="<?php echo home_url('/wp-admin/'); ?>"><i class="fa fa-calendar-o" aria-hidden="true"></i>イベントカアレンダー</a></span></li>
							<?php } ?>
						</ul>
					</nav>
				</div>
				<!-- /Fixed bottom nav -->

				<!-- copyright -->
				<p class="copyright">
					&copy; <?php echo date('Y'); ?> Copyright<?php //bloginfo('name'); ?>.<?php _e('', 'html5blank'); ?>
					<a id="active-link" href="//caracri-works.com" title="Caracri">カラクリワークス株式会社</a>
				</p>
				<!-- /copyright -->

			</footer>
			<!-- /footer -->

		</div>
		<!-- /wrapper -->

		<?php wp_footer(); ?>

		<!-- analytics -->
		<script>
		(function(f,i,r,e,s,h,l){i['GoogleAnalyticsObject']=s;f[s]=f[s]||function(){
		(f[s].q=f[s].q||[]).push(arguments)},f[s].l=1*new Date();h=i.createElement(r),
		l=i.getElementsByTagName(r)[0];h.async=1;h.src=e;l.parentNode.insertBefore(h,l)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-XXXXXXXX-XX', 'yourdomain.com');
		ga('send', 'pageview');
		</script>

	</body>
</html>
