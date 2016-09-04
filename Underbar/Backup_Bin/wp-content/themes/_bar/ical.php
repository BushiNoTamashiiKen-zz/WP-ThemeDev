<?php
require_once(TEMPLATEPATH . '/iCalcreator.class.php');

$vcalendar = new vcalendar();
$vcalendar->setConfig( "unique_id", "underbar.in" );
$vcalendar->setProperty( "X-WR-CALNAME", "UNDERBAR Schedule" );
$vcalendar->setProperty( "X-WR-CALDESC", "UNDERBARのマスター予定表" );
$vcalendar->setProperty( "X-WR-TIMEZONE", "Asia/Tokyo" ); 

global $query_string;
query_posts($query_string . '&posts_per_page=-1&meta_key=event_date&orderby=event_date&post_status=publish&post_type=post');
if (have_posts()) {
	while (have_posts()) {
		the_post();
		$event_date = explode('-', get_post_meta(get_the_ID(), 'event_date', true));

		$vevent = new vevent();
		$vevent->setProperty( "DTSTART", array( "year" => $event_date[0] , "month" => $event_date[1] , "day" => $event_date[2] ), array("VALUE" => "DATE"));
		$vevent->setProperty( "LOCATION", "_ UNDERBAR" );
		$vevent->setProperty( "SUMMARY", get_the_title() );
		$vevent->setProperty( "DESCRIPTION", 'master : ' . getMasterByScheduleId(get_the_ID()) . "\n\n" . strip_tags(get_the_content()) , array('LANGUAGE' => 'ja'));
		$vcalendar->setComponent( $vevent );

	}
}

$vcalendar->returnCalendar(); 
?>
