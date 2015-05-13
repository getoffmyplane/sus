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
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'tQ_`g/~-yIS`pX_R Cc,(0jj5jrin,H#&-:72>2%@|0y8M>?y`[(1T{>Y1#:3Z:k');
define('SECURE_AUTH_KEY',  '+nmgCtE+Y5&?1naMHa|( LiG>X~hG)3@J&8P%4ph;%(qNH@E)nWc=HPF~:KR|26I');
define('LOGGED_IN_KEY',    'y!{.R9%!f,RpN7uw%o9/(wF@T||!_xMx9EL8|}YAF5-%yh[Yi:WnKr`rHi.A+P~Z');
define('NONCE_KEY',        '0EmSON{|$Ae$X@i)W=H6x=pcf-Sfd&xefdRbo:D&=,aLAa(A1!->5A9k0h-{`.YK');
define('AUTH_SALT',        ']/(U@ER;)?U-C`}q7$7%O f!+-aAW6R^RV6wOk.v4wU(nMin|y`-Z.NYz)_9p=KS');
define('SECURE_AUTH_SALT', '~x6#XVBO=Vg9tHxH]9[)=xA#t^payv{/-4RzfQ0l:_9=mTxf-`}Xmc?Cq&dfG6U/');
define('LOGGED_IN_SALT',   '~mRIzR<7Ur*g=a@~oIx{Er3Q&oX8/!F0g<+XO:_Pp-w~/%O.^w8V13=0xj_3yvc|');
define('NONCE_SALT',       '.m/mxVAh-l]xq-.G;-eh:TvXLL|D:!8VinT$RK?$j<x3 ^928Jbl-i~AO~W+Jq_=');

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
