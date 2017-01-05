<?php
define('WP_CACHE', true); // Added by WP Rocket
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
define('DB_NAME', 'mesaportal');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         'ssWYMCJa~*Jj>l/h{rb0kj;}i&h*42f1N)t}1Zxs.7O S]1K1m!M/},F`NoL~9>&');
define('SECURE_AUTH_KEY',  'aW]vTIJ2QxbT[GE.<~*+nU{cDV~Die!7t!3j6N#!6I8si`(2t=NpZm1x~G(TfplT');
define('LOGGED_IN_KEY',    'r>M%j}eIpwlToh3N-!@MuhZ$NFW<;,z`niDx.+S8FG8E5Y<O%I)J7$g->VFqK<xg');
define('NONCE_KEY',        '@jNV]|G#)JLIP#?~c`[}%TMMz{WTB%:#Q>Bex*qq#TV}pELG{c4=UX;J`X{f(r@C');
define('AUTH_SALT',        '$w|Dx[ixRl=o(MJTEY6=-fQ_sqy,:eO=Y6#K9+2 1 HdMH{DD<qj-6jFR PP(]28');
define('SECURE_AUTH_SALT', 'hjEN_2:QdHF_7Wj$rn^z_x%hUOc+*]g}aFyvwNf44c a}Pff?G@TyHrEcYRzo2YZ');
define('LOGGED_IN_SALT',   'F=s/E9s#|[e}eHOm<:2@+;kIfk,/G&hyZLTbT6|p=~O42MoD#Nk}EUP]BkIYmx-p');
define('NONCE_SALT',       ':0b2HX$E6d]3|fvy.*a4_N5usvW,hW+l6MoASp|U@;)1?Z1t6-Efu|;+itp?sGcy');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpq8Ruf6bs_';

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
