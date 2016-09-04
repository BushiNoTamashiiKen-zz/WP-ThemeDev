<?php
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APP_DATA_PATH . '/app_data/library'),
    get_include_path(),
)));
require_once 'Zend/Cache.php';
require_once "Zend/Service/Twitter.php";
require_once "Zend/Service/Flickr.php";

$frontendOption = array(
	'lifetime' => CACHE_TIME, 	
	'automatic_serialization' => true, 	
);

$backendOption = array(
	'cache_dir' => APP_DATA_PATH . '/app_data/data/cache/',	
);
$cache = Zend_Cache::factory('Core', 'File', $frontendOption, $backendOption);

if (!($entries = $cache->load(BODYBOX_CACHE_ID))) {
	$twitter = new Zend_Service_Twitter(array('username' => 'underbar_master', 'accessToken' => unserialize(file_get_contents(APP_DATA_PATH . '/app_data/twitter_token.txt'))));
	try {
		$twitter_response = $twitter->status->userTimeline(array('count' => 1));
		if ($twitter_response->isSuccess()) {
			foreach ($twitter_response as $tweet) {
				$entries[] = array(
					'text'		 => (string) $tweet->text,
					'created_at' => (string) $tweet->created_at,
					'source' => (string) $tweet->source,
					'profile_image_url' => (string) $tweet->user->profile_image_url,
					'tweet_url' => 'http://twitter.com/' . (string) $tweet->user->screen_name . '/status/' . (string) $tweet->id,
				);
			}
		} else {
			$entries = array();
		}
		$cache->save($entries);
	} catch (Zend_Rest_Client_Result_Exception $e) {
		$entries = array();
		$cache->save($entries);
	}
}

$tweet_count = count($entries);
?>
<?php get_header(); ?>
<body <?php body_class(); ?>>
	<div id="wrapper">
		<div id="header" class="clearfix">

			<div id="headerL">
				<h1><a href="http://underbar.in/"><img src="<?php bloginfo('template_directory') ?>/images/logo.gif" width="113" height="23" alt="福岡天神の日替わりマスターズバー「_アンダーバー」"  class="imgover"/></a></h1>
<!-- /#headerL --></div>

			<div id="headerR">
				<ul id="nav" class="clearfix">
					<li class="nav01"><a href="index.htm" onclick="return hs.htmlExpand(this)" title="WHAT _"><img src="<?php bloginfo('template_directory') ?>/images/btn_nav01.gif" width="77" height="24" alt="what" class="imgover" /></a><div class="highslide-maincontent">
	<div align="center">
<img src="http://underbar.in/app/wp-content/themes/_bar/graphics/what_background.gif" width="320" height="154" style="margin:10px;" /></div> <br />_(underbar)は、毎日マスターが変わる、「日替わりマスターのバー」です。マスター毎にコンセプトやメニューが違います。<br />Twitterやカレンダーでお気に入りのマスターを探してください。<br />
開店情報をお知らせする<a href="http://twitter.com/underbar_bot">Twitter_bot「underbar_bot」</a>も公開中です。<br /><br />
【マスター募集中】<br />
マスターをしてくれる方を募集中です。簡単な審査はありますが、常識のある方なら基本どなたでも。カウンターに立ったことが無い方は、二人体制くらいでやるのがちょうどいいかもしれません。お酒を作って、お客さんとも話すのはちょっと大変です。もちろん一人でもＯＫです。<br />
ご希望の方は<a href="mai&#108;&#116;&#111;&#58;c&#111;n&#116;&#97;&#99;&#116;&#64;&#117;n&#100;e&#114;b&#97;r&#46;i&#110;">ご連絡</a>ください。<br />
■条件<br />
・公序良俗に反しない方<br />
・薬物などを持ち込まない方<br />
・そこそこの常識を持ち合わせた方<br />
・店を破壊しない方<br />
・楽しいことがしたい方<br />
 </div>
</li>
					
					<li class="nav05"><a href="index.htm" onclick="return hs.htmlExpand(this)" title="_ SCHEDULE"><img src="<?php bloginfo('template_directory') ?>/images/btn_nav02.gif" width="112" height="24" alt="schedule" class="imgover" /></a>
<div class="highslide-maincontent">
iCalでスケジュールを公開中です。<br />
----------------------------<br />
http://underbar.in/?ical=1<br />
----------------------------<br />
※googleカレンダーに追加するには、「他のカレンダー」の「追加」から「URLで追加」です。
</div>
</li>
					<li class="nav05"><a href="index.htm" onclick="return hs.htmlExpand(this)" title="_ MENU"><img src="<?php bloginfo('template_directory') ?>/images/btn_nav05.gif" width="74" height="24" alt="schedule" class="imgover" /></a><div class="highslide-maincontent">
<img src="http://underbar.in/app/wp-content/themes/_bar/graphics/menu_background.gif" width="192" height="47" style="margin:10px;" /><br /><br />
Beer/Cocktail：500yen～<br />
Wine：500yen～<br />
Snack：300yen～<br />
※マスターによって、内容、金額が異なる場合があります。
</div>
</li>
					<li class="nav03"><a id="toAccess" href="#shopMap" title="ACCESS _ "><img src="<?php bloginfo('template_directory') ?>/images/btn_nav03.gif" width="96" height="24" alt="access" class="imgover" /></a></li>
					<li class="nav04"><a href="index.htm" onclick="return hs.htmlExpand(this)" title="CONTACT _"><img src="<?php bloginfo('template_directory') ?>/images/btn_nav04.gif" width="97" height="24" alt="contact" class="imgover" /></a>
<div class="highslide-maincontent">
<img src="http://underbar.in/app/wp-content/themes/_bar/graphics/contact_background.gif" width="285" height="47" style="margin:10px;" /><br /><br />
_(underbar) へのお問い合わせは、<a href="mai&#108;&#116;&#111;&#58;c&#111;n&#116;&#97;&#99;&#116;&#64;&#117;n&#100;e&#114;b&#97;r&#46;i&#110;">conta&#99;&#116;&#64;&#117;nd&#101;r&#98;a&#114;.&#105;&#110;</a>　まで。
</li>
				</ul>
<!-- /#headerR --></div>

<!-- /#header --></div>


		<div id="slideshow">

<?php
	if (is_home()) {
		global $query_string;
		query_posts($query_string . "&showposts=1&orderby=event_date&order=ASC&meta_key=event_date&meta_compare=>=&meta_value=" . getDisplayLimitDate());
	}
?>

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<script type="text/javascript" >
					jQuery(document).ready(function(){
						setTimeout(function(){
							jQuery('#schedule').scrollTo('#schedule_<?php echo get_the_ID() ?>', 1500, {easing: 'easeOutQuint'});
						},3000);
					});
				</script>
				<?php $cufon_font = (get_post_meta(get_the_ID(), 'cufon_font', true)) ? get_post_meta(get_the_ID(), 'cufon_font', true) : get_option('cufon_font'); ?>
				<script type="text/javascript" src="<?php bloginfo('url') ?>/js/<?php echo $cufon_font ?>_400.font.js"></script>
<?php
/*
$main_event = clone $post;
$next_event = getNextPost(get_the_ID());

$flickr = new Zend_Service_Flickr('672ef4cdbf6d08685851d0552cedb0b0');
$results = $flickr->userSearch('underbar_master@yahoo.com', array(
	'per_page' => 1,
));
$photo_obj = $results->current();

$main_img = array();
if ($photo_obj->dateupload > getDisplayLimitTimestamp()) {
	$main_img['uri'] = (!is_null($photo_obj->Original)) ? $photo_obj->Original->uri 
		: (!is_null($photo_obj->Large)) ? $photo_obj->Large->uri 
		: (!is_null($photo_obj->Medium)) ? $photo_obj->Medium->uri 
		: $photo_obj->Small->uri;
	$main_img['date'] = $photo_obj->dateupload;
} elseif (get_post_meta(get_the_ID(), 'default_bg_img', true)) {
 */
if (get_post_meta(get_the_ID(), 'default_bg_img', true)) {

	$default_bg_img = wp_get_attachment_image_src(get_post_meta(get_the_ID(), 'default_bg_img', true), 'large');
	$main_img['uri'] = $default_bg_img[0];
} else {
	$main_img['uri'] = get_bloginfo('template_directory') . '/images/img_mainImage.jpg';
}
?>
				<div class="slideshow-images">
					<img src="<?php echo $main_img['uri'] ?>" width="950" height="400" alt="メインイメージ" />
					<div class="slideshow-loader"></div>
				</div>
				<div id="scheduleDetail">
					<p class="cufon"><?php echo formateDate(get_post_meta(get_the_ID(), 'event_date', true)) ?><br /><?php if (date('Y-m-d') === get_post_meta(get_the_ID(), 'event_date', true)) : ?>TODAY'S <?php endif ?>MASTER : <span><?php echo getMasterByScheduleId(get_the_ID()) ?></span></p>
					<dl class="cufon">
						<dt style="color:#<?php echo get_post_meta(get_the_ID(), 'title_color', true) ?>;"><?php the_title() ?></dt>
						<?php if (get_post_meta(get_the_ID(), 'event_time', true)) : ?>
						<dd>time : <?php echo get_post_meta(get_the_ID(), 'event_time', true) ?></dd>
						<?php endif ?>
						<?php if (get_post_meta(get_the_ID(), 'charge', true)) : ?>
						<dd>charge : <?php echo get_post_meta(get_the_ID(), 'charge', true) ?></dd>
						<?php endif ?>
						<?php if (get_post_meta(get_the_ID(), 'comment', true)) : ?>
						<dd><?php echo get_post_meta(get_the_ID(), 'comment', true) ?></dd>
						<?php endif ?>
						<?php if (get_post_meta(get_the_ID(), 'comment2', true)) : ?>
						<dd><?php echo get_post_meta(get_the_ID(), 'comment2', true) ?></dd>
						<?php endif ?>
						<?php if (get_post_meta(get_the_ID(), 'comment3', true)) : ?>
						<dd><?php echo get_post_meta(get_the_ID(), 'comment3', true) ?></dd>
						<?php endif ?>
					</dl>
					<div class="slidshow_comment"><?php the_content() ?></div>

				<!-- /#scheduleDetail --></div>
				<?php if (get_post_meta(get_the_ID(), 'flyer', true)) : ?>
				<?php $flyer = wp_get_attachment_image_src(get_post_meta(get_the_ID(), 'flyer', true), 'large') ?>
				<img src="<?php echo $flyer[0] ?>" width="223" alt="フライヤー"  id="flyer" />
				<?php endif ?>
				<div class="slideshow-captions">
					<div id="twitterVoice">
						<p><?php echo $entries[0]['text'] ?></p>
					<!-- /#twitterVoice --></div>
					<?php if ($main_img['date']) : ?>
					<div id="updated">
						<p>Uploaded on <?php echo date('Y.m.d H:i:s', $photo_obj->dateupload) ?></p>
					<!-- /#updated --></div>
					<?php endif ?>
				</div>
				<?php endwhile; else: ?>
					<div class="slideshow-images">
						<img src="<?php echo get_bloginfo('template_directory') . '/images/img_mainImage.jpg' ?>" width="950" height="400" alt="メインイメージ" />
						<div class="slideshow-loader"></div>
					</div>
					<div id="scheduleDetail">
						<dl class="cufon">
							<dt>_</dt>
						</dl>
						<p><?php echo get_option('default_message') ?></p>

					<!-- /#scheduleDetail --></div>
					<div class="slideshow-captions">
						<div id="twitterVoice">
							<p><?php echo $entries[0]['text'] ?></p>
						<!-- /#twitterVoice --></div>
						<?php if ($main_img['date']) : ?>
						<div id="updated">
							<p>Uploaded on <?php echo date('Y.m.d H:i:s', $photo_obj->dateupload) ?></p>
						<!-- /#updated --></div>
						<?php endif ?>
					</div>
			<?php endif ?>
			
<!-- /#main_image --></div>

<?php
/*
		<!--div id="borderBox" class="clearfix">
			<div id="borderBoxL">
				<div id="borderBoxLInner">
					<img src="<?php bloginfo('template_directory') ?>/images/head_what.jpg" width="64" height="18" alt="" />
					<p><?php the_content() ?></p>
<!-- /#borderBoxLInner --></div>

<!-- /#borderBoxL --></div>

			<div id="borderBoxR">
				<div id="borderBoxRInner">
					<?php if ($next_event) : $post = $next_event; setup_postdata($post) ?>
						<p id="date">TOMORROW > <?php echo formateDate(get_post_meta(get_the_ID(), 'event_date', true)) ?><br />
						MASTER ： <span><?php echo getMasterByScheduleId(get_the_ID()) ?></span></p>
						<dl>
							<dt><?php the_title() ?></dt>
							<dd>18:00-24:00 / CHARGE:500yen</dd>
						</dl>
					<?php else : ?>
						<p>次の予定はありません</p>
					<?php endif ?>
				<!-- /#borderBoxRInner --></div>
			<!-- /#borderBoxR --></div>

<!-- /#borderBox --></div-->
*/
?>
		<div id="top_cube"></div>

		<?php $main_event_date = (get_post_meta($main_event->ID, 'event_date', true)) ? get_post_meta($main_event->ID, 'event_date', true) : getDisplayLimitDate() ?>
		<?php $main_event_date = explode('-', $main_event_date) ?>
		<?php $year = $main_event_date[YEAR_INDEX] ?>
		<?php $monthnum = $main_event_date[MONTH_INDEX] ?>
		<div id="scheduleOut">

		<div id="contentCalendar">
			<?php get_calendar_horizontal(); ?>
			
<!-- /#contentCalendar--></div>
		<div id="schedule">
			<ul>
				<?php $month_events = getMonthEvents($main_event_date[YEAR_INDEX], $main_event_date[MONTH_INDEX]); ?>
				<?php if ($month_events) : ?>
					<?php foreach ( $month_events as $event ) : ?>
						<div id="schedule_<?php echo $event->ID ?>" class="schedule_row clearfix">
						<a href="<?php echo get_permalink($event->ID) ?>">
						<?php if (get_post_meta($event->ID, 'event_thumbnail', true)) : ?>
							<?php $flyer = wp_get_attachment_image_src(get_post_meta($event->ID, 'event_thumbnail', true), 'large') ?>
							<img class="flyer_thumb" src="<?php echo $flyer[0] ?>" width="50" />
						<?php else : ?>
							<img class="flyer_thumb" src="<?php bloginfo('template_directory') ?>/images/dammy.gif" width="50" />
						<?php endif ?>
						</a>
						<li>
							<dl>
								<dt class="cufon"><span class="date"><?php echo formateDate(get_post_meta($event->ID, 'event_date', true)) ?></span> MASTER ： <span class="master"><?php echo getMasterByScheduleId($event->ID) ?></span> [ <a href="<?php echo get_permalink($event->ID) ?>"><?php echo $event->post_title ?></a> ]</dt>
								<dd><?php echo $event->post_content ?></dd>
							</dl>
						</li>
						</div>
					<?php endforeach ?>
				<?php endif ?>
			</ul>
<!-- /#schedule--></div>

<!-- /#scheduleOut --></div>
		<div id="bottom_cube"></div>

		<div id="shopMap" class="clearfix">
			<div id="left">
			<iframe width="550" height="375" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.co.jp/maps?f=q&amp;source=s_q&amp;hl=ja&amp;geocode=&amp;q=%E7%A6%8F%E5%B2%A1%E7%9C%8C%E7%A6%8F%E5%B2%A1%E5%B8%82%E4%B8%AD%E5%A4%AE%E5%8C%BA%E4%BB%8A%E6%B3%89%EF%BC%91%E4%B8%81%E7%9B%AE%EF%BC%92%EF%BC%93%E2%88%92%EF%BC%94&amp;sll=36.5626,136.362305&amp;sspn=53.684,79.013672&amp;brcurrent=3,0x3541918552fe0707:0x7ee26e55049bc154,0,0x3541918553b91c39:0xeb658a80e32503bd&amp;ie=UTF8&amp;hq=&amp;hnear=%E7%A6%8F%E5%B2%A1%E7%9C%8C%E7%A6%8F%E5%B2%A1%E5%B8%82%E4%B8%AD%E5%A4%AE%E5%8C%BA%E4%BB%8A%E6%B3%89%EF%BC%91%E4%B8%81%E7%9B%AE%EF%BC%92%EF%BC%93%E2%88%92%EF%BC%94&amp;ll=33.588034,130.400709&amp;spn=0.003575,0.006051&amp;z=17&amp;iwloc=A&amp;output=embed"></iframe><br /><small><a href="http://maps.google.co.jp/maps?f=q&amp;source=embed&amp;hl=ja&amp;geocode=&amp;q=%E7%A6%8F%E5%B2%A1%E7%9C%8C%E7%A6%8F%E5%B2%A1%E5%B8%82%E4%B8%AD%E5%A4%AE%E5%8C%BA%E4%BB%8A%E6%B3%89%EF%BC%91%E4%B8%81%E7%9B%AE%EF%BC%92%EF%BC%93%E2%88%92%EF%BC%94&amp;sll=36.5626,136.362305&amp;sspn=53.684,79.013672&amp;brcurrent=3,0x3541918552fe0707:0x7ee26e55049bc154,0,0x3541918553b91c39:0xeb658a80e32503bd&amp;ie=UTF8&amp;hq=&amp;hnear=%E7%A6%8F%E5%B2%A1%E7%9C%8C%E7%A6%8F%E5%B2%A1%E5%B8%82%E4%B8%AD%E5%A4%AE%E5%8C%BA%E4%BB%8A%E6%B3%89%EF%BC%91%E4%B8%81%E7%9B%AE%EF%BC%92%EF%BC%93%E2%88%92%EF%BC%94&amp;ll=33.588034,130.400709&amp;spn=0.003575,0.006051&amp;z=17&amp;iwloc=A" style="color:#0000FF;text-align:left">大きな地図で見る</a></small>
			</div>
			<div style="padding:10px; background-color:#111; height:365px;">
			<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like-box href="http://www.facebook.com/album.php?profile=1&amp;id=142757315787831#!/pages/%E3%82%B7%E3%82%A7%E3%82%A2%E3%83%AA%E3%83%B3%E3%82%B0%E3%83%90%E3%83%BC-Underbar/142757315787831" width="375" show_faces="true" stream="false" header="false"></fb:like-box>
			</div>
			<!-- <img src="<?php bloginfo('template_directory') ?>/images/img_map.jpg" width="375" height="400" id="right" alt="" /> -->

<!-- /#shopMap --></div>


<!-- twitter follow badge by go2web20 -->
<script src='http://www.go2web20.net/twitterfollowbadge/1.0/badge.js' type='text/javascript'></script><script type='text/javascript' charset='utf-8'><!--
tfb.account = 'underbar_bot';
tfb.label = 'follow-us';
tfb.color = '#1B1B1B';
tfb.side = 'r';
tfb.top = 39;
tfb.showbadge();
--></script>
<!-- end of twitter follow badge -->

<!-- /#wrapper --></div>
<?php get_footer(); ?>
