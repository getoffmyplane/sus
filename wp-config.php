<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
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
define('DB_NAME', 'sus');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'N67dpl6S8piHr8pkyKbA');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         ';TOK+nt28YA`.UWqt81/I-zPp(X2b%l$Q({Wpt7aD3zzxS+|j^7x|cCr+]ap@3v=');
define('SECURE_AUTH_KEY',  '5K$1b>^ILdc+{XXt*^r_IQ(dO/z.Bb1R<m_m>Kl&[T}Ha0}E bxYl&U-|@zr$t-f');
define('LOGGED_IN_KEY',    '+kc32dp?p!ZM65zDn2tFPeu`Iz-iyPcTYmmX$+R)RYX<v#&y!n8uT?=nZFkO )VU');
define('NONCE_KEY',        '!6|SuY+pIs$*{X~Qy7g^nXt<@.tTp+fm%ya`W0(AgY`hz&RQfd2,zr[iMu8JuLR^');
define('AUTH_SALT',        '..2qGm~?A@MC1&xJ%o}M*i?YOjyDsu2nKyX_0bxvdU`<JY74f-Li+_3H5[d-~*D.');
define('SECURE_AUTH_SALT', '+|VY%UZHJIzR%WYqg$m{st|A]Rg`/]H:y!zIg9-BgIjTtX-g;2]M#AeD~VVD]kl9');
define('LOGGED_IN_SALT',   ';Wa6b]J-F`cxBm%%O,![|uNe26D%by#R/MwkHFJ~DH`@A#|GTK]2fWe[4O+WAm93');
define('NONCE_SALT',       'k`XgVCq(q`1}72|dBwZ8S68Gv0#T&~rA=bl50%91u7rr[o22&]J0C@-br7hI^O?$');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
