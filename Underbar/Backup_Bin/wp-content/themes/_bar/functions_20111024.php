<?php
define('YEAR_INDEX', 0);
define('MONTH_INDEX', 1);
define('DISPLAY_LIMIT_HOUR', 3);
define('NEXT_POST_ID_SQL', "SELECT
		*
	FROM
		wp_posts AS w_p
	LEFT OUTER JOIN
		wp_postmeta AS w_pm ON w_pm.post_id = w_p.ID AND w_pm.meta_key = 'event_date'
	WHERE
		w_pm.meta_value > (
			SELECT
				w_pm_s.meta_value
			FROM
				wp_postmeta AS w_pm_s
			LEFT OUTER JOIN
				wp_posts AS w_p_s ON w_p_s.ID = w_pm_s.post_id
			WHERE
				w_p_s.ID = %d
			AND
				w_pm_s.meta_key = 'event_date'
		)
	LIMIT 1"
);

define('CURRENT_MONTH_EVENTS_SQL', "SELECT
		*
	FROM
		wp_posts AS w_p
	LEFT OUTER JOIN
		wp_postmeta AS w_pm ON w_pm.post_id = w_p.ID AND w_pm.meta_key = 'event_date'
	WHERE
		%s = DATE_FORMAT((w_pm.meta_value), '%%Y-%%m')
	AND
		w_p.post_status = 'publish'
	AND
		w_p.post_type = 'post'
	ORDER BY w_pm.meta_value ASC"
);


wp_enqueue_script('jquery');

require_once(dirname(__FILE__) . '/options.php');
require_once(dirname(__FILE__) . '/custom_post_type.php');


function _bar_connection_types() {
    if ( !function_exists('p2p_register_connection_type') )
        return;

    p2p_register_connection_type('post', 'master');
}
add_action('init', '_bar_connection_types', 100);

function getMasterByScheduleId($schedule_id) {
	$masters = p2p_get_connected($schedule_id, 'from', 'master');
	$names = array();
	foreach ($masters as $master_id) {
		$names[] = get_the_title($master_id);
	}

	return implode(' & ', $names);
}

function getNextPost($post_id) {
	global $wpdb;
	$next_id = $wpdb->get_var($wpdb->prepare(NEXT_POST_ID_SQL, $post_id));
	return (is_null($next_id)) ? null: get_post($next_id);
}

function getMonthEvents($year, $month) {
	global $wpdb;
	$current_month_events = $wpdb->get_results($wpdb->prepare(CURRENT_MONTH_EVENTS_SQL, $year . '-' . $month));
	return $current_month_events;
}

function getClosestEventQuery($where = '') {
    $where .= " AND post_date > '" . date('Y-m-d') . "'";
    return $where;
}

function getDisplayLimitTimestamp() {
	return strtotime(date('Y-m-d', time() - 60 * 60 * DISPLAY_LIMIT_HOUR)) + 60 * 60 * DISPLAY_LIMIT_HOUR;
}

function getDisplayLimitDate() {
	return date('Y-m-d', time() - 60 * 60 * DISPLAY_LIMIT_HOUR);
}

function formateDate($date) {
	return date('Y.m.d D', strtotime($date));
}

add_action('admin_print_scripts', 'includeJquery');
function includeJquery() {
	wp_enqueue_script('jquery');
}
add_action('admin_footer', 'fontStyling');
function fontStyling() {
	echo '<script type="text/javascript" src="' . get_bloginfo('url') . '/js/cufon-yui.js"></script>';
	global $custom_field_template;
	$cft = $custom_field_template->get_custom_fields(0);
	$fonts = explode(' # ', $cft[10]['cufon_font']['value']);
	foreach ($fonts as $font) {
		echo '<script type="text/javascript" src="' . get_bloginfo('url') . '/js/' . $font . '_400.font.js"></script>';
	}

	$init_cufon .= '
<script type="text/javascript">
	jQuery(document).ready(function() {';

	foreach ($fonts as $font) {
		$init_cufon .= 'Cufon.replace(jQuery("label[for=cufon_font10_0_' . strtolower($font) . ']"), {fontFamily: "' . str_replace('_', ' ', $font) . '"});';
	}

	$init_cufon .= '
		Cufon.now();
	});
</script>';
	echo $init_cufon;
}

add_action('admin_print_scripts', 'include_colorpicker_css');
function include_colorpicker_css() {
}

add_action('admin_footer', 'colorpicker_include');
function colorpicker_include() {
	echo '<link href="' . get_bloginfo('template_url') . '/colorpicker.css" type="text/css" rel="stylesheet">';
	echo '<script type="text/javascript" src="' . get_bloginfo('url') . '/js/colorpicker.js"></script>';
	$script_text = <<< SCRIPT
<script>
	jQuery(document).ready(function(){
		jQuery('.use_colorpicker').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				jQuery(el).val(hex);
				jQuery(el).ColorPickerHide();
			},
			onBeforeShow: function () {
				jQuery(this).ColorPickerSetColor(this.value);
			}
		}).bind('keyup', function(){
			jQuery(this).ColorPickerSetColor(this.value);
		});
	});
</script>
SCRIPT;
	echo $script_text;
}



add_filter('query_vars','insertMyRewriteQueryVars');
function insertMyRewriteQueryVars($vars) {
	array_push($vars, 'ical');
    return $vars;
}

add_filter('template_include', 'get_post_type_template');
function get_post_type_template($template = '') {
    if (get_query_var('ical')) {
        $template = locate_template(array("ical.php"));
    }
    return $template;
}
