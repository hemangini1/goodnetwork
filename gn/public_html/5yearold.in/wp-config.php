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
define('DB_NAME', 'goodnk9o_wp226');

/** MySQL database username */
define('DB_USER', 'goodnk9o_wp226');

/** MySQL database password */
define('DB_PASSWORD', '.4!F09S4ZP');

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
define('AUTH_KEY',         'rtas4myfigw1hy9npz4dlz9szopukj7sue16fjkgfbvrvz9fy3iicaacucjdar3h');
define('SECURE_AUTH_KEY',  'lodxtk5scrowctjteo54n2f07oyp68kv02ldl8jzzedkqwkafimzq5jp1ksjmb01');
define('LOGGED_IN_KEY',    'svdzpaoh4ehw88jvirka3rz4aljez8rsw69kytdcbynkxvhvgdk9fgfmw3skknpx');
define('NONCE_KEY',        'pjmjitdoyv2dmjvui1ncczd7ivmy4tetg75ht6fenw89eizem8ix0g2wc8fwnmvs');
define('AUTH_SALT',        'zko81hbckj50p3rmqvy3itdujinxhpustoo2ofaklgtwpoekhdu9r17ak7axdlpt');
define('SECURE_AUTH_SALT', 'px4e3rjcyy2l0djbf6ru0ygxh8bi82fuqdhopu64fgshmtx2eh8mujowh6wqsosc');
define('LOGGED_IN_SALT',   'b9zj4ygsjla8wogvumx5rfztekxnnggi399bxywctwkbhfqodyhe0mqvcxezmr5u');
define('NONCE_SALT',       'x3ei2enikizt7zfb28pd23fawthrxfb9pkohecyglfevkp217m2cssc2bvnuderf');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp46_';

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
