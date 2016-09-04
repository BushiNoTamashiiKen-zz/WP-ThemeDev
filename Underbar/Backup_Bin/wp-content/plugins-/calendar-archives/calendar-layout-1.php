<?php
// Get current page's URL
$pageUrl = get_page_link(get_the_ID());

// Links for calendar years
$yearsLinks = array();

// Loop through list of years to display link for each year
foreach ($years as $yearDetails)
{
    $queryArguments = array('calendar_year' => $yearDetails->year);
    if ($category) : $queryArguments['category'] = $category; endif;
    $yearsLinks[] = '<a href="' . add_query_arg($queryArguments, $pageUrl) . '">' . $yearDetails->year . '</a>';
}

// Output links for calendar years
printf(__('View calendar for year %s', 'calendar-archives'), implode(' ', $yearsLinks));

// First day of week to use
$firstDayOfWeek = (int)$options['first_day_of_week'];

// Setting flag 'hide no posts months'
$hideNoPostsMonths = (bool)$options['hide_no_posts_months'];

// Setting flag 'reverse months'
$reverseMonths = (bool)$options['reverse_months'];

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

// Loop through months to display calendar with posts
for ($month = ($reverseMonths ? 12 : 1); ($reverseMonths ? 0 < $month : 12 >= $month); ($reverseMonths ? $month-- : $month++))
{
    // If 'hide no posts months' setting flag is ON and there are no posts for current month in current year then move to next month
    if ($hideNoPostsMonths && !isset($postsPerDay[$month]))
    {
        continue;
    }

    // Time for first day of current month/year
    $timeForFirstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
?>
<table cellspacing="1" class="calendar">
    <caption><?php echo date('F', $timeForFirstDayOfMonth); ?> <?php echo $year; ?></caption>
    <thead>
        <tr>
<?php
    // Loop for seven times to output weekday names
    for ($counter = 0, $i = $firstDayOfWeek; 7 > $counter; $counter++, $i++)
    {
?>
            <th><?php echo $weekdays[$i]; ?></th>
<?php
        // If counter reached to 6, set it to -1
        if (6 == $i)
        {
            $i = -1;
        }
    }
?>
        </tr>
    </thead>
    <tbody>
        <tr>
<?php
    // Total number of days in current month/year
    $totalDaysInMonth = date('t', $timeForFirstDayOfMonth);

    // Weekday for first day of current month/year
    $weekdayForFirstDayOfMonth = date('w', $timeForFirstDayOfMonth);

    // If 'first day of week' is not equal to weekday for first day of month then proceed further to output empty TDs
    if ($firstDayOfWeek != $weekdayForFirstDayOfMonth)
    {
        // Calculate total empty days
        $totalEmptyDays = ($weekdayForFirstDayOfMonth - $firstDayOfWeek);

        // If first day of week is greater than weekday for first day of month then add 7 days to total empty days
        if ($firstDayOfWeek > $weekdayForFirstDayOfMonth)
        {
            $totalEmptyDays += 7;
        }

        // Loop for 'total empty days' to output empty TDs if first day of current month/year doesn't start on 'first day of week'
        for ($i = 0; $i < $totalEmptyDays; $i++)
        {
?>
            <td>&nbsp;</td>
<?php
        }
    }

    // Loop for total number of days in current month/year to output calendar with posts
    for ($day = 1; $day <= $totalDaysInMonth; $day++)
    {
        // If new week started then close current table row and start new one
        if (1 < $day && $firstDayOfWeek == date('w', mktime(0, 0, 0, $month, $day, $year)))
        {
?>
        </tr>
        <tr>
<?php
        }

        // Initialize variable used to store background image
        $backgroundImage = false;

        // If background image set for current day in current month/year then use it
        if (isset($backgroundImages[$month][$day]) && false !== $backgroundImages[$month][$day])
        {
            $backgroundImage = $backgroundImages[$month][$day];
        }
?>
            <td<?php echo ($backgroundImage ? ' style="background-image: url(' . $this->getImageUrl($backgroundImage, $boxDimension) . ');"' : ''); ?>>
                <div<?php echo ($backgroundImage ? ' class="semi-transparent"' : ''); ?>><?php echo $day; ?></div>
<?php
        // If any post(s) for current day in current month/year then display it/them
        if (isset($postsPerDay[$month][$day]))
        {
?>
                <ul<?php echo ($backgroundImage ? ' class="invisible"' : ''); ?>>
<?php
            // Loop through post(s) for current day in current month/year to display it/them
            foreach ($postsPerDay[$month][$day] as $key => $index)
            {
?>
                    <li><a href="<?php echo get_permalink($posts[$index]->ID); ?>"><?php echo $posts[$index]->post_title; ?></a></li>
<?php
            }
?>
                </ul>
<?php
        }
?>
            </td>
<?php
    }

    // Weekday for last day of current month/year
    $weekdayForLastDayOfMonth = date('w', mktime(0, 0, 0, $month, $totalDaysInMonth, $year));

    // Calculate total empty days
    $totalEmptyDays = ($firstDayOfWeek - $weekdayForLastDayOfMonth - 1);

    // If first day of week is less than or equals to weekday for last day of month then add 7 days to total empty days
    if ($firstDayOfWeek <= $weekdayForLastDayOfMonth)
    {
        $totalEmptyDays += 7;
    }

    // Loop for 'total empty days' to output empty TDs if last day of current month/year doesn't end on 'first day of week'
    for ($i = 0; $i < $totalEmptyDays; $i++)
    {
?>
            <td>&nbsp;</td>
<?php
    }
?>
        </tr>
    </tbody>
</table><br />
<?php
}
?>