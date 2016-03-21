	</div>

	<footer id="site-footer">

		<div class="container">
            <div class="row">
            	<?php dynamic_sidebar('footer-sidebar'); ?>
            </div>
		</div><?php

		$footer_text = blt_get_option('footer_text', 'Copyright &copy; {year} &middot; Theme design by Bluthemes &middot; <a href="http://www.bluthemes.com" rel="nofollow">www.bluthemes.com</a>');

		if(!empty($footer_text)){ ?>
			<div class="footer-text">
				<div class="container">
	            	<p><?php echo html_entity_decode(str_replace("{year}", date('Y'), $footer_text)); ?></p>
				</div>
			</div><?php
		} ?>

	</footer>

</main>

<?php wp_footer(); ?>
</body>
</html>