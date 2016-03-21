<?php

add_filter( 'wpseo_canonical','upme_wpseo_canonical');

function upme_wpseo_canonical($canonical){
	return $_SERVER['REQUEST_URI'];
}

add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');

function new_mail_from($old) {
    $current_option = get_option('upme_options');
    return $current_option['email_from_address'];
}
function new_mail_from_name($old) {
    $current_option = get_option('upme_options');
    return $current_option['email_from_name'];
}