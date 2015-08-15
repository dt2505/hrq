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
define('DB_NAME', 'wp_play_around');

/** MySQL database username */
define('DB_USER', 'wp_dev_user');

/** MySQL database password */
define('DB_PASSWORD', 'wp_dev_user');

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
define('AUTH_KEY',         'c#H2?v%Pks}@Ev|Nd4KvvimqC3.Z=[}nmv{vT1$T)(X0Sbm*FV6W,.11Pk`[`rVR');
define('SECURE_AUTH_KEY',  'k^70ZC3cz%d.m]<6Rn|6Z*z5}61CZW&igsFk1o4ReY(#j(BryYT5;d_/|V~&w#5 ');
define('LOGGED_IN_KEY',    'E3woTb[dLphB[],jxm{mL7SneX6zux33Wf}?1o2&~*T#.MBB/ppPftZn7)RAM/3r');
define('NONCE_KEY',        '*>Xac$M/X9EbYw{ifVPlW?|_+7y*=XFVUXZ3CI7Vu%JU]c&.$u~}81:~*};|Cd|!');
define('AUTH_SALT',        'j(A$O3.%,:5c4Ns>r)OG=x*Y[|rzeXj*EJ$K,Cc`Ld&]|o5-GN8{1tq53m:E*@Tz');
define('SECURE_AUTH_SALT', 'ttp%5[PM/SCt,Ne]rx6R1smY(Ru8wDqdKiN&LRJKNN:m)@kIaL~e}F?rNN_l)[Rk');
define('LOGGED_IN_SALT',   '>Fqu<Y/PA{V|yY}}=Uqo4o)CEzin{f{HrclvV?$akz&l)7-|asQui?e[Lb4s)[Gx');
define('NONCE_SALT',       'HZ3Nrmw[.JUL(GqbWBo_8xm!BSAHx K>8BjeJu8d3;V`V?6[Ytxb]g+Vr!54^9X9');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'ccc_';

define( 'WP_ALLOW_MULTISITE', true );
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'wp-play-around');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

define('FS_METHOD', 'direct');

/* block external requests for local install -- use it for dev mode only */
define('WP_HTTP_BLOCK_EXTERNAL', false);

define('WP_LANG', "zh_CN");

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

