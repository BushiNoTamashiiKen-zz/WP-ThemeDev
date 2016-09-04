=== Calendar Archives ===
Contributors: amitbadkas
Tags: calendar, archives
Requires at least: 3.0.1
Tested up to: 3.0.1
Stable tag: 2.1

Calendar Archives is a visualization plugin for your WordPress site which creates yearly calendar for your posts.

== Description ==

Calendar Archives is a visualization plugin for your WordPress site which creates yearly calendar for your posts. Create a new page (having 'no sidebars' template) for your calendar archive and insert the shortcode [calendar-archive] in the editor. Load this page and enjoy the view!

Each day of calendar will display first available photo from posts of that day in the background. The plugin's layout is customizable and having two working layouts for demonstration

== Installation ==

1. Upload directory 'calendar-archives' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure plugin options by clicking on 'Settings' link near plugin name or 'Settings' -> 'Calendar Archives' navigation link. Make sure that your plugin directory must be writable by webserver to use caching of pages and images
4. Create page having [calendar-archive] shortcode. It will be better to use empty (having no sidebars) template for this page

== Frequently Asked Questions ==

= Where to find texts to translate? =

The plugin contains 'calendar-archives.pot' which can be used for translation

= How to display posts under particular category? =

If URL has 'category' parameter then it will be used as category slug, for example, http://www.my-domain.net/my-blog/my-calendar/?category=uncategorized

== Screenshots ==

1. Calendar archives using first layout
2. Calendar archives using second layout
3. Calendar archives options

== Changelog ==

= 2.1 =
* Fixed bug in 'Display upcoming posts' feature, thanks to Anandajoti Bhikkhu for bug reporting

= 2.0 =

* Added ability to provide first day of week
* Added ability to display posts (in calendar) under particular category
* Added ability to display upcoming posts (to use as event calendar)

= 1.5 =
* Added wrappers for PHP5 only functions 'file_put_contents' and 'scandir' so that plugin will work on PHP4 too

= 1.4 =
* Use of actual path instead of URL for local images uploaded in wordpress if 'allow_url_fopen' is disabled

= 1.3 =
* Background colors customization

= 1.2 =
* Dimension customization for day box and background image

= 1.1 =
* Internationalization support

= 1.0 =
* First release