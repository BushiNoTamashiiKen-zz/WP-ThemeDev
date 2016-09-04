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
	return date('Y-m-d', time() + 60 * 60 * DISPLAY_LIMIT_HOUR);
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
function dashboard_widget_function() {
    echo "■重要<br />１．店内には何も残さない！残してあるものは使われたり捨てられたり辱められたりしますよ！<br />※お昼営業中のお店の看板、コーヒーメーカー等は例外！※ロッカー導入検討中！<br />※妙案募集中！<br />２．忘れ物はすぐ連絡してください！取りにいきます！<br />３．左のシンクは使わない！右のシンクに固形物を流さない！流したのみつけたら●●さんが家にいって固形物を詰めますよ！ホントですよ！<br />
 <br />■予約<br />先着・同月4回まで登録可・当日空いていれば4回以上でも可！<br />※固定枠という考えはなくなりました。予定が決まっている方は先のほうまでズイーっと予約していただけると安心です！<br /> <br />■料金<br />初回お試し：5000円　/　2回目以降：6000円<br /> <br />■備品<br />グラス・皿・カセットコンロ・サラウンドスピーカーシステム・ラジカセ・<br />家庭用冷蔵庫・プレステ２・液晶テレビ<br /> <br />■当日用意する必要があるもの<br />・ダスター類（グラスや皿を拭くやっつなど）<br />・ドリンク類、フード類<br />・コンロ使用の場合はカセットボンベ<br />・紙ナプキン<br /> <br />■利用の流れ<br />１、管理画面から予約<br />２、当日17：00以降入店<br />３、開店準備<br />・お酒の注文（持ち込み可）<br />・「大事なもの袋」の中身を確認、不足があった場合は後原までご連絡ください。<br />４．開店<br />５、閉店後店内の清掃（来たときよりも美しく！じゃなくてよいですけど、来たときくらいに）<br />忘れ物チェック！（必ず！）<br />６、1週間以内に利用料を振り込み<br />※以前採用していた、ビールと利用料の相殺システムはなくなりました！（えー）";
}
function add_dashboard_widgets() {
    wp_add_dashboard_widget('dashboard_widget', '★★バーの予約について★★', 'dashboard_widget_function');
}
add_action('wp_dashboard_setup', 'add_dashboard_widgets' );


function dashboard_widget_function02() {
    echo "左メニュー「投稿」から「新規追加」をクリックして、バーの名称、内容、チャージ等を入力してください。<br />
メイン画像、カレンダー用のサムネイル画像、フライヤー画像（メイン画像右端に表示）も登録できます。<br />
マスター名は、事前に左メニュー「マスター」から登録したマスター名を呼び出して登録します。";
}
function add_dashboard_widgets02() {
    wp_add_dashboard_widget('dashboard_widget02', '★★バーの予約方法★★', 'dashboard_widget_function02');
}
add_action('wp_dashboard_setup', 'add_dashboard_widgets02' );
