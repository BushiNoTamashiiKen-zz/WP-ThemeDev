		<div id="footer">
			<div id="footerInner">
				<p id="copyright">福岡市中央区今泉1-23-4新天神ビル211<br />COPYRIGHT 2010-2011 UNDERBAR , ALL RIGHT RESERVED.</p>
				<script type="text/javascript" >
					jQuery(document).ready(function(){
						jQuery('#to_top').bind('click', function(){
							jQuery.scrollTo('#header', 2000, {easing: 'easeOutQuint'});
						});
					});
				</script>
				<p id="pageTop"><a id="to_top" href="#header"><img src="<?php bloginfo('template_directory') ?>/images/img_pageTop.jpg" width="167" height="49" alt="ページトップへ" /></a></p>
<!-- /#footerInner --></div>
<!-- /#footer --></div>

<div class="bannerUshioCafe"><a target="_blank" href="http://www.caracri-works.com/"><img src="/images/caracri_banner.gif" alt="カラクリワークスがやってます" /></a></div>

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
