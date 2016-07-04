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
define('DB_NAME', 'goodnk9o_wpgn16');

/** MySQL database username */
define('DB_USER', 'goodnk9o_wpgn16');

/** MySQL database password */
define('DB_PASSWORD', 'Pv9J.f9S5)');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('FORCE_SSL_ADMIN', true);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'mhc7jntxydl94u8taeyzfbi3myn5tdhhcxve5z4erljtvklpcx5dw5o2n062rnjs');
define('SECURE_AUTH_KEY',  'gzylnhjw1w66zbxjppj0l1ho89uxge7zuacbqozrjirvenly5tbxxe9yut3t6xuv');
define('LOGGED_IN_KEY',    'ztmpzs8v8ssizvy0uny7mbddnvr6qi3hacbcfstfufo7gjkfncmbrlrx7tkitv3a');
define('NONCE_KEY',        'lf9k8nhwk4kh20prxm9e6o4lx0sgmeigxherqrcih0ltmzjpvqdhiegvjdjiha5a');
define('AUTH_SALT',        'zfolpelb5zbmy2p5dwballoaz95ke9cmgdsywgjgjxvfqjl0hal9vax16fvp1ojy');
define('SECURE_AUTH_SALT', 'xtwqv3byp0e2zxjlpqq8piveqiialqubhesiedxskgqx0attwrmah3hobgqearem');
define('LOGGED_IN_SALT',   'kvt7pzwpxeudhgy0un1my8uceelta2zsnrdosohzcgr30b2v6vsqaz0omz1bdfy9');
define('NONCE_SALT',       'jt1vwmxlylro7nj6r0h2tasd1d7erlycktmf1qsws9hzwvvilxugmpbes33jxdp6');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp6i_';

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
