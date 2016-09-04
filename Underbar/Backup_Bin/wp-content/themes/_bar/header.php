<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="content-style-type" content="text/css" />
<meta http-equiv="content-script-type" content="text/javascript" />
<meta http-equiv="content-language" content="ja" />
<meta http-equiv="imagetoolbar" content="no" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 * We filter the output of wp_title() a bit -- see
	 * twentyten_filter_wp_title() in functions.php.
	 */
	wp_title( '|', true, 'right' );
	// Add the blog name.
	bloginfo( 'name' );

	?></title>
<meta name="description" content="福岡天神今泉にある日替わりマスターのバー「_ underbar（アンダーバー）」" />
<meta name="keywords" content="_,bar,underbar,バー,カフェ,福岡,天神,今泉," />
<link rev="made" href="mailto:info&#64;sample.mail" title="" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory') ?>/import.css" media="screen, projection, tv" />
<link rel="shortcut icon" href="<?php bloginfo('url') ?>/images/favicon.ico" />
<script type="text/javascript" src="<?php bloginfo('url') ?>/js/rollover.js"></script>
<script type="text/javascript" src="<?php bloginfo('url') ?>/js/cufon-yui.js"></script>
<script type="text/javascript" src="<?php bloginfo('url') ?>/js/highslide-with-html.js"></script>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
<script type="text/javascript" src="<?php bloginfo('url') ?>/js/jquery.easing.js"></script>
<script type="text/javascript" src="<?php bloginfo('url') ?>/js/jquery.scrollTo.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		Cufon.replace(jQuery('.cufon'));
		Cufon.now();

		jQuery('#toSchedule').bind('click', function(){
			jQuery.scrollTo('#scheduleOut', 2000, {easing: 'easeOutQuint'});
		});
		jQuery('#toAccess').bind('click', function(){
			jQuery.scrollTo('100%', 2000, {easing: 'easeOutQuint'});
		});
	});
</script>

<script type="text/javascript">
hs.graphicsDir = '<?php bloginfo('template_directory') ?>/graphics/';
hs.outlineType = 'rounded-white';
hs.wrapperClassName = 'draggable-header';

</script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1210184-15']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
