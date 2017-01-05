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
define('DB_NAME', 'mesapor8_n8AmEq9d');

/** MySQL database username */
define('DB_USER', 'mesapor8_E9PUy6M');

/** MySQL database password */
define('DB_PASSWORD', 'cUsiCu9Qs6fy');

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
define('AUTH_KEY',         ')Y. ?Ol2L)2M+$R,3ol}v?7wVpwz1Ol^[k):9ODt52-9&|m&89yYdW>Bb=X4#+}W');
define('SECURE_AUTH_KEY',  '-^vyMWb)|!v_ns-|=dY%s3AOrf:?]_l<Sd- b+`dZ{|a10Vd|&8%K9F /Ty56:#a');
define('LOGGED_IN_KEY',    '[/K;E9#skqzYG 31L~q3<V?-f+lN&Lc*<inwytUX6,m#<p~1~ ^U?68AW5Hy}<Hv');
define('NONCE_KEY',        '5Vb,m@)zJV&^-~C0bcgFFBN*o<II0B0d6jX%VVRyUwy1-qr[sm5|mw_MZKowx>5*');
define('AUTH_SALT',        'VXC-jO %+kb/:(kgMx2T@AA6+!W=6g|P+~b~3HG9PLB8PKW,#Q2^KS+7>`QQrA1B');
define('SECURE_AUTH_SALT', 'bx+.:dCm]-h|BEdcN!w@F=@A$-<IPJK`BfQ,5]@7M!f$ ~XA/s`ZCd]b}.1!$]U1');
define('LOGGED_IN_SALT',   'Sq<~`uODCzz`3(q#-C,oP,Wchis7a`mEw99>(W_,s.(4?-D#eO^snh2 ]-o)#8}8');
define('NONCE_SALT',       'b9a#qIzCFe&u&F|Wi8*p.MUSE7k/+C9f|2o7Dk^ug_~ZPC$+b[aQOd#{tb3s}L#W');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpX3qckmv6_';

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

/** limit revisions to 3 */
define('WP_POST_REVISIONS', 3);

/* increase WordPress Available Memory */
define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '256M' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
