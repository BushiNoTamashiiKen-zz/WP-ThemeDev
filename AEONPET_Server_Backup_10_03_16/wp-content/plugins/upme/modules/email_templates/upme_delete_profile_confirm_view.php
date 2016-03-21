<?php

$display  = __('Someone (hopefully you) has requested to delete your profile at {%blog_name%}','upme'). "\r\n\r\n";
$display .= __('Username:','upme'). " {%username%} \r\n\r\n";
$display .= __('E-mail:','upme'). " {%email%} \r\n\r\n";

$display .= __('Please click the link below to confirm the removal of profile:','upme') . "\r\n\r\n";
$display .= "{%profile_delete_confirm_link%} \r\n\r\n";
$display .= __('Thanks','upme') . "\r\n";
$display .= "{%blog_name%} \r\n\r\n";

echo $display;

?>