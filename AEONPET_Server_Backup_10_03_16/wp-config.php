<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', '_aeon');

/** MySQL database username */
define('DB_USER', '_aeon');

/** MySQL database password */
define('DB_PASSWORD', 'aeon6631a');

/** MySQL hostname */
define('DB_HOST', 'mysql501.heteml.jp');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'B&n|{)EzVAKN-8tvwj6|jw4xIUN,Spa3&=2QG+(c[-={mWXtMkB2dfeKSe.WP^6&');
define('SECURE_AUTH_KEY',  '7-@pVF7QQj8X,}19)Rg56PU2e0DQ&RexD~(`Tn|XO,~<pC0|-c2EHT|=Vgn?q:S+');
define('LOGGED_IN_KEY',    'pr>^;^=|AD9z[.R=?00iA$1q>N|M*$,-k|P4XzstQ R10wdYd~W_vE<f4W|=#oD9');
define('NONCE_KEY',        ':J1yOi;OlRZTqWGUO|3W)Qv~:XWAR`C-jY/hI|8-~XwBEA{55+|SCmKfN]1ZPqh/');
define('AUTH_SALT',        ',-UjJP?0gThvMbT1,89,Nm|8(m-e9/fDz!]NMeC/^Mj@}U#%0|vpOrS-f(-bm&wU');
define('SECURE_AUTH_SALT', 'f>|i,GpOGc-VK&=CQQ1 ?xZC5xdaA!-j@9&s 8,-?j*#J(t)Rh6S})9;EL.^zHlO');
define('LOGGED_IN_SALT',   'd[Tn+ta3)*=qr>m[f.|gGsUQ~MpJacXhEPKz+7hS_`{qQc,9eFLXc[~WwfE]+<A$');
define('NONCE_SALT',       'Cair4DA|*wNxWG+ o=R8~#Ja9#[G*|(g,T8dVu]8uQx>:!+)H9PMa^z/!LJ|-uhE');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
