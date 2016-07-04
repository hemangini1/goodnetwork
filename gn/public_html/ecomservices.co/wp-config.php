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
define('DB_NAME', 'goodnk9o_wp642');

/** MySQL database username */
define('DB_USER', 'goodnk9o_wp642');

/** MySQL database password */
define('DB_PASSWORD', '5].IS861P9');

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
define('AUTH_KEY',         'zjgro6cpyx42di7gmtmpqqa2vbm7c35vkzrsgwp1dboavflycj9r48jbj6wppex2');
define('SECURE_AUTH_KEY',  'ykesaj9x4fhwovhkoposow7figiyzjvxzqschhcmuaomwcmfa0vpqkkjmatppygh');
define('LOGGED_IN_KEY',    '5ebirqoih7ophcgnnnodjtmj7d72jii6fme5ua56vtcfeqqxjfbebr0ujfwvpdw2');
define('NONCE_KEY',        '5cseyeftros6rvon0rmjatp3ypvtz1w5s0s1cvyu0j7i547nfd9uxfh6b0ygvcxa');
define('AUTH_SALT',        'ozktf2bdoldzohrekgvqpg5w2rmggxobuifugqrh4mlgu8msq0j8zpdwhhpnfiwl');
define('SECURE_AUTH_SALT', 'sxanzedglfjqnfqgbf8ful0hluelsdvu0vp2xdztllm7wxaw6gqyla1glqnw789u');
define('LOGGED_IN_SALT',   'rwql1lvvzr8edmjdbp0z0nawdtct3tk4zfrf7l3n4uaqdsifkvvuokqztvj3sebm');
define('NONCE_SALT',       'hwkvrrpdc9n5dvtyolj0sa507ldxh2kvpnarrut4smue1azao9glddm4ywftzclw');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpqe_';

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
