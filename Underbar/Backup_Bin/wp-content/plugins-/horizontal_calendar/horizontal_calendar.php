<?php
/*
Plugin Name: Horizontal Calendar
Plugin URI: http://www.h-fj.com/blog/wpplgdoc/horizontalcalendar.php
Description: This plugin enables to show horizontal calendar.
Author: Hajime Fujimoto
Version: 1.01
Author URI: http://www.h-fj.com/blog/
*/ 

function get_calendar_horizontal($initial = true) {
	global $wpdb, $m, $monthnum, $year, $timedifference, $wp_locale, $posts;

	$key = md5( $m . $monthnum . $year );
	if ( $cache = wp_cache_get( 'get_calendar_horizontal', 'calendar' ) ) {
		if ( isset( $cache[ $key ] ) ) {
			echo $cache[ $key ];
			return;
		}
	}

	ob_start();
	// Quick check. If we have no posts at all, abort!
	if ( !$posts ) {
		$gotsome = $wpdb->get_var("SELECT ID
			FROM $wpdb->posts 
			LEFT OUTER JOIN $wpdb->postmeta ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id AND {$wpdb->postmeta}.meta_key = 'event_date'
			WHERE post_type = 'post' 
			AND post_status = 'publish' 
			ORDER BY {$wpdb->postmeta}.meta_value DESC LIMIT 1");
		if ( !$gotsome )
			return;
	}

	if ( isset($_GET['w']) )
		$w = ''.intval($_GET['w']);

	// week_begins = 0 stands for Sunday
	$week_begins = intval(get_option('start_of_week'));
	$add_hours = intval(get_option('gmt_offset'));
	$add_minutes = intval(60 * (get_option('gmt_offset') - $add_hours));

	// Let's figure out when we are
	if ( !empty($monthnum) && !empty($year) ) {
		$thismonth = ''.zeroise(intval($monthnum), 2);
		$thisyear = ''.intval($year);
	} elseif ( !empty($w) ) {
		// We need to get the month from MySQL
		$thisyear = ''.intval(substr($m, 0, 4));
		$d = (($w - 1) * 7) + 6; //it seems MySQL's weeks disagree with PHP's
		$thismonth = $wpdb->get_var("SELECT DATE_FORMAT((DATE_ADD('${thisyear}0101', INTERVAL $d DAY) ), '%m')");
	} elseif ( !empty($m) ) {
		$calendar = substr($m, 0, 6);
		$thisyear = ''.intval(substr($m, 0, 4));
		if ( strlen($m) < 6 )
				$thismonth = '01';
		else
				$thismonth = ''.zeroise(intval(substr($m, 4, 2)), 2);
	} else {
		$thisyear = gmdate('Y', current_time('timestamp'));
		$thismonth = gmdate('m', current_time('timestamp'));
	}

	$unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);

	// Get the next and previous month and year with at least one post
	$previous_month = $wpdb->get_row("SELECT DISTINCT {$wpdb->posts}.ID AS id, DATE_FORMAT({$wpdb->postmeta}.meta_value, '%m') AS month, YEAR({$wpdb->postmeta}.meta_value) AS year
		FROM $wpdb->posts
		LEFT OUTER JOIN $wpdb->postmeta ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id AND {$wpdb->postmeta}.meta_key = 'event_date'
		WHERE {$wpdb->postmeta}.meta_value < '$thisyear-$thismonth-01'
		AND post_type = 'post' AND post_status = 'publish'
			ORDER BY {$wpdb->postmeta}.meta_value DESC
			LIMIT 1");
	$previous = $wpdb->get_row("SELECT DISTINCT {$wpdb->posts}.ID AS id, MONTH({$wpdb->postmeta}.meta_value) AS month, YEAR({$wpdb->postmeta}.meta_value) AS year
		FROM $wpdb->posts
		INNER JOIN $wpdb->postmeta ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id AND {$wpdb->postmeta}.meta_key = 'event_date'
		WHERE '{$previous_month->year}-{$previous_month->month}-01' <= {$wpdb->postmeta}.meta_value
		AND post_type = 'post' AND post_status = 'publish'
			ORDER BY {$wpdb->postmeta}.meta_value ASC
			LIMIT 1");
	$next = $wpdb->get_row("SELECT DISTINCT {$wpdb->posts}.ID AS id, MONTH({$wpdb->postmeta}.meta_value) AS month, YEAR({$wpdb->postmeta}.meta_value) AS year
		FROM $wpdb->posts
		LEFT OUTER JOIN $wpdb->postmeta ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id AND {$wpdb->postmeta}.meta_key = 'event_date'
		WHERE {$wpdb->postmeta}.meta_value >	'$thisyear-$thismonth-01'
		AND MONTH( {$wpdb->postmeta}.meta_value ) != MONTH( '$thisyear-$thismonth-01' )
		AND post_type = 'post' AND post_status = 'publish'
			ORDER	BY {$wpdb->postmeta}.meta_value ASC
			LIMIT 1");

	echo "<p id=\"wp-calendar-horizontal\">\n";
	if($thisyear == 2015 && $thismonth == 12) {
		$next -> year = 2016;
		$next -> month = 1;
		$next -> id = 7598;
	}
	if($thisyear == 2016 && $thismonth == 1) {
		$previous -> year = 2015;
		$previous -> month = 12;
		$previous -> id = 7374;
	}
	if ($previous) {
		echo "\t<span id=\"cal-prev-month\"><a href=\"" .
		get_permalink($previous->id) . '" title="' . sprintf(__('View posts for %1$s %2$s'), $wp_locale->get_month($previous->month),
			date('Y', mktime(0, 0 , 0, $previous->month, 1, $previous->year))) . '">&laquo; ' . $wp_locale->get_month_abbrev($wp_locale->get_month($previous->month)) . "</a></span>\n";
	}

	echo "\t<span id=\"cal-month\">" . date('Y', $unixmonth) . ' ' . $wp_locale->get_month($thismonth) . "</span>\n";

	// Get days with posts
	$dayswithposts = $wpdb->get_results("SELECT DISTINCT DAYOFMONTH({$wpdb->postmeta}.meta_value), {$wpdb->posts}.ID
		FROM $wpdb->posts 

		LEFT OUTER JOIN $wpdb->postmeta ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id AND {$wpdb->postmeta}.meta_key = 'event_date'

		WHERE MONTH({$wpdb->postmeta}.meta_value) = '$thismonth'
		AND YEAR({$wpdb->postmeta}.meta_value) = '$thisyear'
		AND post_type = 'post' AND post_status = 'publish'", ARRAY_N);

	if ( $dayswithposts ) {
		foreach ( $dayswithposts as $daywith ) {
			$daywithpost[$daywith[0]] = $daywith[1];
		}
	} else {
		$daywithpost = array();
	}



	if ( strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'camino') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'safari') )
		$ak_title_separator = "\n";
	else
		$ak_title_separator = ', ';

	$ak_titles_for_day = array();
	$ak_post_titles = $wpdb->get_results("SELECT post_title, DAYOFMONTH({$wpdb->postmeta}.meta_value) as dom "
		."FROM $wpdb->posts "
		."LEFT OUTER JOIN $wpdb->postmeta ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id AND {$wpdb->postmeta}.meta_key = 'event_date' "
		."WHERE YEAR({$wpdb->postmeta}.meta_value) = '$thisyear' "
		."AND MONTH({$wpdb->postmeta}.meta_value) = '$thismonth' "
		."AND post_date < '".current_time('mysql')."' "
		."AND post_type = 'post' AND post_status = 'publish'"
	);
	if ( $ak_post_titles ) {
		foreach ( $ak_post_titles as $ak_post_title ) {
				if ( empty($ak_titles_for_day['day_'.$ak_post_title->dom]) )
					$ak_titles_for_day['day_'.$ak_post_title->dom] = '';
				if ( empty($ak_titles_for_day["$ak_post_title->dom"]) ) // first one
					$ak_titles_for_day["$ak_post_title->dom"] = str_replace('"', '&quot;', wptexturize($ak_post_title->post_title));
				else
					$ak_titles_for_day["$ak_post_title->dom"] .= $ak_title_separator . str_replace('"', '&quot;', wptexturize($ak_post_title->post_title));
		}
	}

	$daysinmonth = intval(date('t', $unixmonth));
	for ( $day = 1; $day <= $daysinmonth; ++$day ) {
		if ( 5 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins) ) {
			$span_class = ' class="saturday"';
		}
		else if ( 6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins) ) {
			$span_class = ' class="sunday"';
		}
		else {
			$span_class = '';
		}
		if ( $day == gmdate('j', (time() + (get_option('gmt_offset') * 3600))) && $thismonth == gmdate('m', time()+(get_option('gmt_offset') * 3600)) && $thisyear == gmdate('Y', time()+(get_option('gmt_offset') * 3600)) )
			echo "\t" . '<span id="today">';
		else
			echo "\t<span${span_class}>";
		if ( $daywithpost[$day] ) // any posts today?
				echo '<a href="' . get_permalink($daywithpost[$day]) . "\" title=\"$ak_titles_for_day[$day]\">$day</a>";
		else
			echo $day;
		echo "</span>\n";

	}

	if ($next) {
		echo "\t<span id=\"cal-next-month\"><a href=\"" .
		get_permalink($next->id) . '" title="' . sprintf(__('View posts for %1$s %2$s'), $wp_locale->get_month($next->month),
			date('Y', mktime(0, 0 , 0, $next->month, 1, $next->year))) . '">' . $wp_locale->get_month_abbrev($wp_locale->get_month($next->month)) . ' &raquo;</a></span>' ."\n";
	}
	echo "</p>\n";

	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
	$cache[ $key ] = $output;
	wp_cache_set( 'get_calendar_horizontal', $cache, 'calendar' );
}
?>
