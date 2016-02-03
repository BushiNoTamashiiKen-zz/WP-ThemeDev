<?php
/** MYSQL Server runtime test **/

$link = mysql_connect('mysql501.heteml.jp', '_aeon', 'aeon6631a');
if (!$link) {
	die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully! MYSQL server running just fine :)';
mysql_close($link);

/** **/
?>