<?php

$display = __('New user registration on ','upme'). "{%blog_name%}: \r\n\r\n";


$display .= __('Username:','upme'). "{%username%} \r\n\r\n";
$display .= __('E-mail:','upme'). "{%email%} \r\n\r\n";
$display .= __('This user is pending approval.','upme'). " \r\n\r\n";
$display .= __('You can approve the user by visiting the following link.','upme'). " \r\n\r\n";
$display .= "{%approval_link_backend%} \r\n\r\n";
$display .= __('Thanks','upme') . "\r\n";
$display .= "{%blog_name%} \r\n\r\n";

echo $display;

?>