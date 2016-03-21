<?php

/* Include open source files for acessing social network API's */
require_once upme_path.'modules/social/lib/OAuth.php';
require_once upme_path.'modules/social/lib/linkedin/linkedin_3.2.0.class.php';
//require_once upme_path.'modules/social/lib/facebook/facebook.php';
require_once upme_path.'modules/social/lib/twitter/twitteroauth.php';
require_once upme_path.'modules/social/lib/google/autoload.php';
//require_once UAIO_PLUGIN_DIR.'lib/google/Service/Oauth2.php';
require_once upme_path.'modules/social/lib/facebook_SDK/autoload.php';


/* Include classes for handling social network login and registration */
require_once upme_path.'modules/social/class-upme-social-settings.php';
require_once upme_path.'modules/social/class-upme-social-connect.php';
require_once upme_path.'modules/social/class-upme-linkedin-connect.php';
require_once upme_path.'modules/social/class-upme-facebook-connect.php';
require_once upme_path.'modules/social/class-upme-twitter-connect.php';
require_once upme_path.'modules/social/class-upme-google-connect.php';

/* Load the shortcode files */
require_once upme_path.'modules/social/shortcodes/social-login-shortcodes.php';

function upme_language_entry($string,$domain = 'upme'){
	return __($string,$domain);
}
