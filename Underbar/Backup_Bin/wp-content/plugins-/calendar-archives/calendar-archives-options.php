<style>
<!--
input.backgroundColorPreview {
    text-align: center;
}

input#day_background_color_preview {
    background-color: <?php echo $dayBackgroundColor; ?>
}

input#day_box_background_color_preview {
    background-color: <?php echo $dayBoxBackgroundColor; ?>
}

input#weekday_background_color_preview {
    background-color: <?php echo $weekdayBackgroundColor; ?>
}
//-->
</style>
<script src="<?php echo get_option('siteurl'); ?>/wp-includes/js/jquery/jquery.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript">
<!--
jQuery(document).ready(function()
{
    jQuery(".backgroundColorField").keyup(function()
    {
        jQuery("#" + this.id + "_preview").css("background-color", this.value);
    });
});
//-->
</script>
<?php
// Weekdays
$weekdays = array
(
    __('Sunday', 'calendar-archives')
    , __('Monday', 'calendar-archives')
    , __('Tuesday', 'calendar-archives')
    , __('Wednesday', 'calendar-archives')
    , __('Thursday', 'calendar-archives')
    , __('Friday', 'calendar-archives')
    , __('Saturday', 'calendar-archives')
);
?>
<div class="wrap" style="max-width: 950px !important;">
	<h2>Calendar Archives</h2>
	<div id="poststuff" style="margin-top: 10px;">
        <div id="mainblock" style="width: 710px;">
            <div class="dbx-content">
                <form name="SnazzyArchives" action="<?php echo $actionUrl; ?>" method="post">
                    <input type="hidden" name="submitted" value="1" />
                    <?php wp_nonce_field('calendar-nonce'); ?>
                    <h2>Usage</h2>
                    <p>Create a new page (having 'no sidebars' layout) for your calendar archive and insert the code [calendar-archive] in the editor. Load this page and enjoy the view!</p>
                    <h2>Options</h2>
                    <?php if ($writeable) : ?>
                        <input type="checkbox" name="cache" id="cache" <?php echo $cache; ?> /> <label for="cache">Use calendar cache</label>
                    <?php else : ?>
                        <span style="color: red;">Your plugin directory (or 'cached_pages' directory, if it exists, in your plugin directory) is not writeable by WordPress. Caching is not possible.</span>
                    <?php endif; ?>
                    <br /><br />
                    <p>Calendar Archives currently supports two layouts, and you can buid your own.</p>
                    <input type="text" name="layout" id="layout" size="10" value="<?php echo $layout; ?>" /> <label for="layout">Default layout (1 or 2)</label><br/><br /><br />
                    <input type="checkbox" name="hide_no_posts_months" id="hide_no_posts_months" <?php echo $hideNoPostsMonths; ?> /> <label for="hide_no_posts_months">Hide months in which no posts</label><br />
                    <p>If enabled, months which don't have any posts will not be displayed in calendar.</p><br />
                    <input type="checkbox" name="reverse_months" id="reverse_months" <?php echo $reverseMonths; ?> /> <label for="reverse_months">Reverse months</label><br />
                    <p>If enabled, archive months will be displayed in descending order (December through January).</p><br />
                    <?php if ($writeableForImages) : ?>
                        <input type="checkbox" name="show_images" id="show_images" <?php echo $showImages; ?> /> <label for="show_images">Show images</label>
                        <?php if ($remoteImagesDownloadDisabled) : ?>
                            [<span style="color: red; font-size: 10px;">URL file-access (PHP configuration setting 'allow_url_fopen') is disabled in the server configuration. Hence, the images will not get downloaded and will not be displayed.</span>]
                        <?php endif; ?>
                        <br />
                        <p>Show post images. You can disable this to preserve bandwidth.</p>
                        <p><a href="<?php echo $actionUrl; ?>&amp;remove_cached_images=1" onclick="return confirm('Do you really want to remove cached images?');">Remove cached images</a></p>
                    <?php else : ?>
                        <span style="color: red;">Your plugin directory (or 'cached_images' directory, if it exists, in your plugin directory) is not writeable by WordPress. Display of images for posts is not possible.</span><br />
                    <?php endif; ?>
                    <br />
                    <p>You can provide dimension for calendar's day box. This dimension will also apply to background images displayed</p>
                    <input type="text" name="box_dimension" id="box_dimension" size="10" value="<?php echo $boxDimension; ?>" /> <label for="box_dimension">Box/image dimension</label><br /><br /><br />
                    First day of week is
                    <select name="first_day_of_week">
                        <?php foreach ($weekdays as $value => $label) : ?>
                        <option value="<?php echo $value ?>"<?php echo ($firstDayOfWeek == $value ? ' SELECTED' : ''); ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select><br /><br /><br />
                    <input type="checkbox" name="show_future_posts" id="show_future_posts" <?php echo $showFuturePosts; ?> /> <label for="show_future_posts">Display upcoming posts</label><br />
                    <p>If enabled, posts which have publish date in future will also be displayed in calendar (useful for events).</p><br />
                    <p>
                        <b>Background colors</b><br />
                        Here you can customize various background colors used in calendar layouts
                    </p>
                    <p>
                        <input type="text" class="backgroundColorPreview" id="weekday_background_color_preview" size="10" value="Preview" /> <input type="text" name="weekday_background_color" class="backgroundColorField" id="weekday_background_color" size="10" value="<?php echo $weekdayBackgroundColor; ?>" /> <label for="weekday_background_color">Weekday</label><br />
                        <input type="text" class="backgroundColorPreview" id="day_box_background_color_preview" size="10" value="Preview" /> <input type="text" name="day_box_background_color" class="backgroundColorField" id="day_box_background_color" size="10" value="<?php echo $dayBoxBackgroundColor; ?>" /> <label for="day_box_background_color">Day box</label><br />
                        <input type="text" class="backgroundColorPreview" id="day_background_color_preview" size="10" value="Preview" /> <input type="text" name="day_background_color" class="backgroundColorField" id="day_background_color" size="10" value="<?php echo $dayBackgroundColor; ?>" /> <label for="day_background_color">Day (for first layout only)</label>
                    </p>
                    <div class="submit"><input type="submit" name="Submit" value="Update" /></div>
                </form>
            </div>
        </div>
	</div>
    <h5>WordPress plugin by <a href="http://www.sanisoft.com/blog/author/amitbadkas/">Amit Badkas</a></h5>
</div>