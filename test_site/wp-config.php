<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'test_db');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '$-~DME(R7w@u:NI W7bHGW-?iz#`2>S6u9@A+zQ}nnGG+K:d-yUXu992hE5Cxv])');
define('SECURE_AUTH_KEY',  'b1Cs@,D,1Q|UUwLDo-v=K&_.^n-#uV~XCU%;3X^!Hb<b+Ftsuy4@)25O moNm*+(');
define('LOGGED_IN_KEY',    '[tentwW[xRw|xHO6*[f9%#d$U,/EoUkdW]qEq[gOh<|pV+a.+Wz{D`hH8$2q?,Ez');
define('NONCE_KEY',        'O_gtK.v%Fc_RK=tyMo3pe6*   _#=coJ*|S^i;:%3] wZ+(eH)_gx&<{v%_Mu`2u');
define('AUTH_SALT',        '67JsgIbi1x3`(Zi9G}v-dtRTj` ti]OuB*:J:-hSx?cD<V`}Qmms8vIEp7*3v8rG');
define('SECURE_AUTH_SALT', 'rLDFgZit|4=<Nh#$_d4r1|7nM~]X4k1EW&wOo>p*vnj&FHLR-9F[or0uPYXmclQR');
define('LOGGED_IN_SALT',   'eLn.MQ |2|/6J/^`~qO@j)urJ_|&)+mwDB)d N<u:+Ptvl_H~f.LrX3O@5]b$R89');
define('NONCE_SALT',       'u.ne|l_W>i2@}d&]ukhFA}qPO0qL:c>eS-^MGFtpco;*&4EYJMPh7XX/#+oa+py7');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
