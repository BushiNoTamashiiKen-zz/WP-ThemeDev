<?php

/**
 *
 * Please see single-event.php in this directory for detailed instructions on how to use and modify these templates.
 *
 */

?>

<script type="text/html" id="tribe_tmpl_tooltip">
	<div style="border-radius: 4px; background-color: #494d4c; box-shadow: 2px 2px 2px 1px #3c3f3e;" id="tribe-events-tooltip-[[=eventId]]" class="tribe-events-tooltip">
		<h4 style="font-family: avenir-medium, Arial, Helvetica, sans-serif; font-weight: normal; color: #fafafa;" class="entry-title summary">[[=raw title]]</h4>

		<div class="tribe-events-event-body">
			<div class="tribe-event-duration">
				<abbr style="font-family: avenir-medium, Arial, Helvetica, sans-serif; font-weight: normal; color: #74ffca;" class="tribe-events-abbr tribe-event-date-start">[[=dateDisplay]] </abbr>
			</div>
			[[ if(imageTooltipSrc.length) { ]]
			<div class="tribe-events-event-thumb">
				<img src="[[=imageTooltipSrc]]" alt="[[=title]]" />
			</div>
			[[ } ]]
			[[ if(excerpt.length) { ]]
			<div style="font-family: avenir-light, Arial, Helvetica, sans-serif; font-weight: normal; color: #fafafa;" class="tribe-event-description">[[=raw excerpt]]</div>
			[[ } ]]
			<!--<span style="background: #494d4c;" class="tribe-events-arrow"></span>-->
		</div>
	</div>
</script>
