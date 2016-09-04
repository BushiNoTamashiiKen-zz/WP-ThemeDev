<?php

/*
Plugin Name: Calendar Archives
Plugin URI: http://www.sanisoft.com/blog/2009/08/21/wordpress-plugin-calendar-archives/
Description: Calendar Archives is a visualization plugin for your WordPress site which creates yearly calendar for your posts. Create a new page (having 'no sidebars' layout) for your calendar archive and insert the code [calendar-archive] in the editor. Load this page and enjoy the view!
Version: 2.1
Author: Amit Badkas
Author URI: http://www.sanisoft.com/blog/author/amitbadkas/
*/

/*  Copyright 2010  Amit Badkas  (email : amit@sanisoft.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class CalendarArchives
{
    function __construct()
    {
        // Call method to build everything needed by constructor
        $this->CalendarArchives();
    }

    function CalendarArchives()
    {
        // Plugin's directory
        $pluginDirectory = dirname(__FILE__) . DIRECTORY_SEPARATOR;

        // Path to cache generated HTML calendar
        $this->cachePath = $pluginDirectory . 'cached_pages' . DIRECTORY_SEPARATOR;

        // Path to cache background images
        $this->backgroundImagesCachePath = $pluginDirectory . 'cached_images' . DIRECTORY_SEPARATOR;

        // Add shortcode handler
        add_shortcode('calendar-archive', array(&$this, 'display'));

        // Add 'options' page
        add_action('admin_menu', array(&$this, 'adminMenu'));

        // Need to delete cache whenever post added/edited/deleted
        add_action('delete_post', array(&$this, 'deleteCache'));
        add_action('edit_post', array(&$this, 'deleteCache'));
        add_action('save_post', array(&$this, 'deleteCache'));
    }

    function activate()
    {
        // Need to build options while activation
        $this->__getOptions();
    }

    function __getOptions()
    {
        // Create cache directory if not exists already
        if (!is_dir($this->cachePath) && @mkdir($this->cachePath))
        {
            chmod($this->cachePath, 0777);
        }

        // Create cache directory for images if not exists already
        if (!is_dir($this->backgroundImagesCachePath) && @mkdir($this->backgroundImagesCachePath))
        {
            chmod($this->backgroundImagesCachePath, 0777);
        }

        // Default options
        $default = array
        (
            'layout' => 1
            , 'box_dimension' => 140
            , 'first_day_of_week' => 0
            , 'reverse_months' => 'on'
            , 'show_future_posts' => ''
            , 'hide_no_posts_months' => ''
            , 'day_background_color' => '#BFBC94'
            , 'day_box_background_color' => '#DBD7A8'
            , 'weekday_background_color' => '#A7A37E'
            , 'cache' => (is_writeable($this->cachePath) ? 'on' : '')
            , 'show_images' => (is_writeable($this->backgroundImagesCachePath) ? 'on' : '')
        );

        // Get saved options
        $saved = get_option('CalendarArchives_options');

        // If possible, merge default and saved options to default one
        if (is_array($saved) && 0 < count($saved))
        {
            $default = array_merge($default, $saved);
        }

        // If saved options are different from merged default options then save merged default options as new one
        if ($saved != $default)
        {
            update_option('CalendarArchives_options', $default);
        }

        // Return merged default options
        return $default;
    }

    function deactivate()
    {
        // Delete plugin's options
        delete_option('CalendarArchives_options');
    }

    function plugin_action_links($links, $file)
    {
        // Plugin's file
        $pluginFile = basename(__FILE__);

        // If given file and plugin's file are same then prepend link of plugin's settings page
        if (basename($file) == $pluginFile)
        {
            array_unshift($links, '<a href="options-general.php?page=' . $pluginFile . '">Settings</a>');
        }

        // Return built links
        return $links;
    }

    function adminMenu()
    {
        // Add 'options' page to admin menu
        add_options_page('Calendar Archives Options', 'Calendar Archives', 8, basename(__FILE__), array(&$this, 'handleOptions'));
    }

    function handleOptions()
    {
        // If admin wants to remove cached images
        if (isset($_GET['remove_cached_images']) && (bool)$_GET['remove_cached_images'])
        {
            // Scan directory for cached images and remove them
            foreach ($this->__scanDirectory($this->backgroundImagesCachePath) as $directoryContent)
            {
                if (is_file($this->backgroundImagesCachePath . $directoryContent))
                {
                    unlink($this->backgroundImagesCachePath . $directoryContent);
                }
            }

            // Remove references of 'remove cached images' flag from needed places and output success message
            $_SERVER['REQUEST_URI'] = str_replace('&remove_cached_images=1', '', $_SERVER['REQUEST_URI']);
            echo '<div class="updated fade"><p>Cached images have been removed successfully</p></div>';
            unset($_GET['remove_cached_images']);
        }

        // Get plugin's options
        $options = $this->__getOptions();

        // If form is submitted then save options
        if (isset($_POST['submitted']))
        {
            // Security check
            check_admin_referer('calendar-nonce');

            // Build options to save
            $options = array
            (
                'layout' => (int)$_POST['layout']
                , 'cache' => (string)$_POST['cache']
                , 'show_images' => (string)$_POST['show_images']
                , 'box_dimension' => (int)$_POST['box_dimension']
                , 'reverse_months' => (string)$_POST['reverse_months']
                , 'first_day_of_week' => (int)$_POST['first_day_of_week']
                , 'show_future_posts' => (string)$_POST['show_future_posts']
                , 'day_background_color' => (string)$_POST['day_background_color']
                , 'hide_no_posts_months' => (string)$_POST['hide_no_posts_months']
                , 'day_box_background_color' => (string)$_POST['day_box_background_color']
                , 'weekday_background_color' => (string)$_POST['weekday_background_color']
            );

            // Update options
            update_option('CalendarArchives_options', $options);

            // Delete cache
            $this->deleteCache();

            // Output success message
            echo '<div class="updated fade"><p>Plugin settings saved.</p></div>';
        }

        // Options form's action
        $actionUrl = $_SERVER['REQUEST_URI'];

        // Variables to use in form
        $layout = (int)$options['layout'];
        $boxDimension = (int)$options['box_dimension'];
        $firstDayOfWeek = (int)$options['first_day_of_week'];
        $cache = ($options['cache'] == 'on' ? 'checked' : '');
        $dayBackgroundColor = (string)$options['day_background_color'];
        $showImages = ($options['show_images'] == 'on' ? 'checked' : '');
        $dayBoxBackgroundColor = (string)$options['day_box_background_color'];
        $reverseMonths = ($options['reverse_months'] == 'on' ? 'checked' : '');
        $weekdayBackgroundColor = (string)$options['weekday_background_color'];
        $showFuturePosts = ($options['show_future_posts'] == 'on' ? 'checked' : '');
        $hideNoPostsMonths = ($options['hide_no_posts_months'] == 'on' ? 'checked' : '');

        // Is cache directory writeable?
        $writeable = is_writeable($this->cachePath);

        // Is cache directory for images writeable?
        $writeableForImages = is_writeable($this->backgroundImagesCachePath);

        // Is download for remote images disabled?
        $remoteImagesDownloadDisabled = !(bool)ini_get('allow_url_fopen');

        // Include options form
        include('calendar-archives-options.php');
    }

    function __scanDirectory($directory)
    {
        // If 'scandir' function exists then use it
        if (function_exists('scandir'))
        {
            return scandir($directory);
        }

        // Initialize variable to store directory contents
        $directoryContents = array();

        // Open directory to read it
        $dh = opendir($directory);

        // If directory failed to open then no need to proceed further
        if (!$dh)
        {
            return $directoryContents;
        }

        // Read directory contents
        while (false !== ($filename = readdir($dh)))
        {
            $directoryContents[] = $filename;
        }

        // Close directory
        closedir($dh);

        // Sort directory contents
        sort($directoryContents);

        // Return directory contents
        return $directoryContents;
    }

    function deleteCache()
    {
        // Get all cache files
        $cacheFiles = glob($this->cachePath . '*.html');

        // If no cache files found then no need to proceed further
        if (!is_array($cacheFiles) || 0 >= count($cacheFiles))
        {
            return;
        }

        // Remove all cache files
        foreach ($cacheFiles as $cacheFile)
        {
            unlink($cacheFile);
        }
    }

    function display()
    {
        // Global DB object
        global $wpdb;

        // If year is set in URL then get it from there
        if (isset($_GET['calendar_year']))
        {
            $year = (int)$_GET['calendar_year'];
        }

        // If year is not set/valid then use current one
        if (!isset($year))
        {
            $year = date('Y');
        }

        // Initialize variable to store category
        $category = null;

        // If category is set in URL then get it from there
        if (isset($_GET['category']))
        {
            $category = (string)$_GET['category'];
        }

        // Get plugin's options
        $options = $this->__getOptions();

        // Initialize variable to store cache flag
        $cache = (bool)$options['cache'];

        // If cache is enabled and cache file exists then use it to display output
        if ($cache && is_file($cacheFile = $this->cachePath . $year . ($category ? '-' . $category : '') . '.html'))
        {
            return file_get_contents($cacheFile);
        }

        // By default, consider published posts only
        $postStatuses = array('publish');

        // If 'show future posts' flag is enabled then display future posts too
        if ((bool)$options['show_future_posts'])
        {
            $postStatuses[] = 'future';
        }

        // Build condition to display posts for given statuses
        $postStatuses = '("' . implode('", "', $postStatuses) . '")';

        // Conditions to fetch list of posts, by default fetch given year's posts
        $conditions = array('year' => 'YEAR(post_date) = ' . $year);

        // If category is set then filter posts using it
        if ($category)
        {
            // Get list of post IDs for given category
            $postIds = $wpdb->get_col('SELECT ' . $wpdb->term_relationships . '.object_id FROM ' . $wpdb->terms . ', ' . $wpdb->term_taxonomy . ', ' . $wpdb->term_relationships . ' WHERE ' . $wpdb->terms . '.term_id = ' . $wpdb->term_taxonomy . '.term_id AND ' . $wpdb->term_taxonomy . '.term_taxonomy_id = ' . $wpdb->term_relationships . '.term_taxonomy_id AND ' . $wpdb->term_taxonomy . '.taxonomy = "category" AND ' . $wpdb->terms . '.slug = "' . $category . '"');

            // Build posts fetching condition according to category
            $conditions['id'] = 'ID ' . (0 < count($postIds) ? 'IN (' . implode(', ', $postIds) . ')' : 'IS NULL');
        }

        // Get posts for given year
        $posts = $wpdb->get_results('SELECT * FROM ' . $wpdb->posts . ' WHERE post_status IN ' . $postStatuses . ' AND post_password = "" AND post_type = "post" AND ' . implode(' AND ', $conditions) . ' ORDER BY post_date ASC');

        // Initialize variable to store 'show images' flag
        $showImages = (bool)$options['show_images'];

        // Box dimension to use
        $boxDimension = (int)$options['box_dimension'];

        // Initialize variable used to store indexes for posts per day
        $postsPerDay = array();

        // Initialize variable used to store background images
        $backgroundImages = array();

        // Is download for remote images disabled?
        $remoteImagesDownloadDisabled = !(bool)ini_get('allow_url_fopen');

        // Loop through list of posts to build 'posts per day' data
        for ($index = 0; $index < count($posts); $index++)
        {
            // Post's time
            $postTime = strtotime($posts[$index]->post_date);

            // Post's month
            $month = (int)date('m', $postTime);

            // If no posts for given month then initialize array for it
            if (!isset($postsPerDay[$month]))
            {
                $postsPerDay[$month] = array();
            }

            // If no background images for given month then initialize array for it
            if (!isset($backgroundImages[$month]))
            {
                $backgroundImages[$month] = array();
            }

            // Post's month
            $day = (int)date('d', $postTime);

            // If no posts for given month's given day then initialize array for it
            if (!isset($postsPerDay[$month][$day]))
            {
                $postsPerDay[$month][$day] = array();
            }

            // If no background image for given month's given day then initialize boolean flag false for it
            if (!isset($backgroundImages[$month][$day]))
            {
                $backgroundImages[$month][$day] = false;
            }

            // Build needed data
            $postsPerDay[$month][$day][] = $index;

            // If 'show images' flag is enabled and no background image for given month's given day then proceed further
            if ($showImages && false === $backgroundImages[$month][$day])
            {
                // Initialize variable used to store list of matched images as per provided regular expression
                $matches = array();

                // Get all images from post's body
                preg_match_all('/<\s*img [^\>]*src\s*=\s*[\""\']?([^\""\'>]*)/i', $posts[$index]->post_content, $matches);

                // If there are any image in post's body then proceed further
                foreach ($matches[1] as $backgroundImage)
                {
                    // Initialize variable used to store image's name
                    $backgroundImageName = $posts[$index]->ID . '-' . md5($backgroundImage);

                    // Background image's path
                    $backgroundImagePath = $this->backgroundImagesCachePath . $backgroundImageName;

                    // By default, no need to rename downloaded file
                    $rename = false;

                    // If image already downloaded then use it otherwise download it
                    if (is_file($backgroundImagePath))
                    {
                        $rename = true;
                    }
                    else if (is_file($backgroundImagePath . '.gif'))
                    {
                        $backgroundImagePath = $backgroundImagePath . '.gif';
                    }
                    else if (is_file($backgroundImagePath . '.jpg'))
                    {
                        $backgroundImagePath = $backgroundImagePath . '.jpg';
                    }
                    else if (is_file($backgroundImagePath . '.png'))
                    {
                        $backgroundImagePath = $backgroundImagePath . '.png';
                    }
                    else if ($remoteImagesDownloadDisabled)
                    {
                        // Replace 'site URL' in background image's URL with 'absolute path'
                        $backgroundImage = str_replace(get_option('siteurl') . '/', ABSPATH, $backgroundImage);

                        // If image is not local then continue the loop
                        if (!is_file($backgroundImage))
                        {
                            continue;
                        }

                        // If image is local then copy it and prepare it for renaming
                        copy($backgroundImage, $backgroundImagePath);
                        $rename = true;
                    }
                    else if (false !== ($backgroundImageContents = @file_get_contents($backgroundImage)) && false !== $this->__filePutContents($backgroundImagePath, $backgroundImageContents))
                    {
                        chmod($backgroundImagePath, 0777);
                        $rename = true;
                    }
                    else
                    {
                        continue;
                    }

                    // Get image's information
                    $backgroundImageInformation = @getimagesize($backgroundImagePath);

                    // If image is valid and its height and width not less than box dimension then use it
                    if (false !== $backgroundImageInformation && $boxDimension <= $backgroundImageInformation[0] && $boxDimension <= $backgroundImageInformation[1])
                    {
                        // Background image's extension
                        $backgroundImageExtension = str_replace('image/', '', $backgroundImageInformation['mime']);
                        $backgroundImageExtension = (in_array($backgroundImageExtension, array('gif', 'png')) ? $backgroundImageExtension : 'jpg');

                        // Rename downloaded image
                        if ($rename)
                        {
                            rename($backgroundImagePath, $backgroundImagePath . '.' . $backgroundImageExtension);
                        }

                        // Build background image's name
                        $backgroundImages[$month][$day] = $backgroundImageName . '.' . $backgroundImageExtension;
                        break;
                    }
                }
            }
        }

        // Get unique years for which posts are available
        $years = $wpdb->get_results('SELECT DISTINCT YEAR(post_date) AS year FROM ' . $wpdb->posts . ' WHERE post_status IN ' . $postStatuses . ' AND post_password = "" AND post_type = "post"' . (isset($conditions['id']) ? ' AND ' . $conditions['id'] : '') . ' ORDER BY post_date DESC');

        // Layout number to use
        $layout = (int)$options['layout'];

        // Various background colors to use
        $dayBackgroundColor = (string)$options['day_background_color'];
        $dayBoxBackgroundColor = (string)$options['day_box_background_color'];
        $weekdayBackgroundColor = (string)$options['weekday_background_color'];

        // Grab output
        ob_start();
        include('calendar-layout-' . $layout . '.css.php');
        include('calendar-layout-' . $layout . '.php');
        $result = ob_get_contents();
        ob_end_clean();

        // If cache is enabled then cache generated output
        if ($cache)
        {
            // Put generated output in cache file
            $this->__filePutContents($cacheFile, $result . '<!-- cached -->');

            // Make cache file world writeable
            chmod($cacheFile, 0777);
        }

        // Return generated output
        return $result;
    }

    function __filePutContents($filename, $data)
    {
        // If 'file_put_contents' function exists then use it
        if (function_exists('file_put_contents'))
        {
            return file_put_contents($filename, $data);
        }

        // Open file to write it
        $fp = fopen($filename, 'w');

        // If file failed to open then no need to proceed further
        if (!$fp)
        {
            return false;
        }

        // Write data into file
        $bytes = fwrite($fp, $data);

        // Close file
        fclose($fp);

        // Return file write status
        return $bytes;
    }

    function getImageUrl($backgroundImage, $backgroundImageDimension)
    {
        // Get background image's name and file extension
        list($backgroundImageName, $backgroundImageExtension) = explode('.', $backgroundImage);

        // Background image path
        $backgroundImage = $this->backgroundImagesCachePath . $backgroundImage;

        // Re-sized background image's path
        $resizedBackgroundImage = $this->backgroundImagesCachePath . $backgroundImageName . '-' . $backgroundImageDimension . '.' . $backgroundImageExtension;

        // If re-sized background image doesn't exist then create it
        if (!is_file($resizedBackgroundImage))
        {
            // Get background image's width and height
            list($backgroundImageWidth, $backgroundImageHeight) = getimagesize($backgroundImage);

            // If background image's width and height is same as re-sized dimension then copy it instead of re-sizing
            if ($backgroundImageHeight == $backgroundImageDimension && $backgroundImageWidth == $backgroundImageDimension)
            {
                copy($backgroundImage, $resizedBackgroundImage);
            }
            else
            {
                // Include needed library
                require_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'image.php';

                // Re-size background image
                image_resize($backgroundImage, $backgroundImageDimension, $backgroundImageDimension, true, $backgroundImageDimension);
            }

            // Make re-sized background image world writeable
            chmod($resizedBackgroundImage, 0777);
        }

        // Return re-sized background image's URL
        return str_replace(ABSPATH, get_option('siteurl') . '/', $resizedBackgroundImage);
    }
}

// Create plugin object
$CalendarArchives = new CalendarArchives();

// Register activation hook as plugin's activate() method
register_activation_hook(__FILE__, array(&$CalendarArchives, 'activate'));

// Register de-activation hook as plugin's deactivate() method
register_deactivation_hook(__FILE__, array(&$CalendarArchives, 'deactivate'));

// Add filter for plugin's action links
add_filter('plugin_action_links', array(&$CalendarArchives, 'plugin_action_links'), 10, 2);
