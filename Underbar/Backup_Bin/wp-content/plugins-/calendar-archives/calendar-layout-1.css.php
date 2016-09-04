<style>
<!--
/* Style for calendar container */
table.calendar {
    border: 1px black solid;
    width: <?php echo ($boxDimension * 7); ?>px; /* Container's width will be box width multiplied by number of days in week */
}

/* Style for calendar container caption */
table.calendar caption {
    font-size: 20px;
    font-weight: bold;
}

/* Style for calendar weekdays data row */
table.calendar thead tr {
    background-color: <?php echo $weekdayBackgroundColor; ?>;
}

/* Style for calendar weekday data container */
table.calendar thead tr th {
    text-align: center;
    width: <?php echo $boxDimension; ?>px; /* Container's width will be same as box dimension */
}

/* Style for calendar days data row */
table.calendar tbody tr {
    background-color: <?php echo $dayBoxBackgroundColor; ?>;
    height: <?php echo $boxDimension; ?>px; /* Container's height will be same as box dimension */
    text-align: left;
    vertical-align: top;
}

/* Style for calendar day data container */
table.calendar tbody tr td div {
    background-color: <?php echo $dayBackgroundColor; ?>;
    font-style: italic;
    font-weight: bold;
    text-align: right;
}

/* Style for calendar day data container which is semi-transparent */
table.calendar tbody tr td div.semi-transparent {
    -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
    filter: alpha(opacity=50);
    opacity: 0.5;
}

/* Style for calendar day posts container */
table.calendar tbody tr td ul {
    height: <?php echo ($boxDimension - 25); ?>px; /* Posts container's height will be 25 pixels less than box dimension by considering day number container's height */
    margin: 0px;
    overflow: hidden;
    padding: 0px;
}

/* Style for calendar day posts container on hovering */
table.calendar tbody tr td ul:hover {
    overflow-y: auto;
}

/* Style for calendar day posts data container which has background image */
table.calendar tbody tr td ul.invisible {
    visibility: hidden;
}

/* Style for calendar day posts data container which has background image on hovering */
table.calendar tbody tr td:hover ul.invisible {
    visibility: visible;
}

/* Style for calendar day post data */
table.calendar tbody tr td ul li {
    list-style: none;
    margin: 3px;
    padding-bottom: 3px;
}

/* Style for calendar day post data which has background image */
table.calendar tbody tr td ul.invisible li {
    -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=35)";
    filter: alpha(opacity=35);
    opacity: 0.35;
}

/* Style for calendar day post data on hovering */
table.calendar tbody tr td ul li:hover, table.calendar tbody tr td ul.invisible li {
    background-color: #EEEEEE;
}

/* Style for calendar day post data which is semi-transparent on hovering */
table.calendar tbody tr td:hover ul.invisible li:hover {
    -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=75)";
    filter: alpha(opacity=75);
    opacity: 0.75;
}

/* Style for calendar day post data link */
table.calendar tbody tr td ul li a {
    font-size: 9px;
}

/* Style for calendar day post data link on hovering */
table.calendar tbody tr td ul li:hover a, table.calendar tbody tr td ul.invisible li a {
    color: #000000;
}

/* Style for calendar day post data link on hovering link */
table.calendar tbody tr td ul li:hover a:hover {
    color: red;
}
//-->
</style>