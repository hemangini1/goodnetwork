<?php
/**
 * Plugin Name: Task Breaker
 * Description: A WordPress plug-in that will help you break some task!
 * Version: 1.0.6
 * Author: Dunhakdis
 * Author URI: http://dunhakdis.me
 * Text Domain: task_breaker
 * License: GPL2
 *
 * PHP version 5
 *
 * @since     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

define( 'TASK_BREAKER_VERSION', '0.1.1' );

define( 'TASK_BREAKER_PROJECT_LIMIT', 10 );

define( 'TASK_BREAKER_PROJECT_SLUG', 'project' );

define( 'TASK_BREAKER_ASSET_URL', plugin_dir_url(__FILE__) . 'assets/' );

// Setup the tables on activation.
register_activation_hook( __FILE__, 'task_breaker_install' );

// Migration over the old version
register_activation_hook( __FILE__, 'task_breaker_import_thrive_intranet_data' );

// Plugin l10n.
add_action( 'plugins_loaded', 'task_breaker_localize_plugin' );

// Include task_breaker projects transactions.
add_action( 'init', 'task_breaker_register_transactions' );

// Include task_breaker projects component.
add_action( 'bp_loaded', 'task_breaker_register_projects_component' );

// Require the assets needed.
require_once plugin_dir_path( __FILE__ ) . 'core/enqueue.php';

// Require the script that registers our 'Project' post type.
require_once plugin_dir_path( __FILE__ ) . 'includes/project-post-type.php';

// Require install script.
require_once plugin_dir_path( __FILE__ ) . 'install/table.php';

/**
 * TaskBreaker l10n callback.
 * @return void
 */
function task_breaker_localize_plugin() {

		$rel_path = basename( dirname( __FILE__ ) ) . '/languages';

    load_plugin_textdomain( 'task_breaker', FALSE, $rel_path );

    return;
}

/**
 * Register our middle man API transactions.
 *
 * @return void
 */
function task_breaker_register_transactions() {

	include_once plugin_dir_path( __FILE__ ) . 'transactions/controller.php';

	return;
}

/**
 * Register our project components.
 *
 * @return void
 */
function task_breaker_register_projects_component() {

	include_once plugin_dir_path( __FILE__ ) . '/includes/project-component.php';

	return;
}

register_activation_hook( __FILE__, 'task_breaker_deactivate_thrive_intranet' );

function task_breaker_deactivate_thrive_intranet() {

	// De-activate Thrive Intranet in case it is used to prevent conflict.
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	deactivate_plugins( '/thrive-intranet/thrive-intranet.php' );

	return;
}

// Enable GitHub Updater.
add_action( 'init', 'task_breaker_plugin_updater_init' );

/**
 * The callback function that wraps 'WP_GitHub_Updater'
 * to init action of WordPress to check for our plugin updates.
 *
 * @return void
 */
function task_breaker_plugin_updater_init() {

	if ( is_admin() ) {

		include_once plugin_dir_path( __FILE__ ) . '/update-check.php';

		$repo_name = 'task-breaker';

	    $config = array(
	        'slug' => plugin_basename( __FILE__ ),
	        'proper_folder_name' => 'task-breaker',
	        'api_url' => sprintf('https://api.github.com/repos/codehaiku/%s', $repo_name),
	        'raw_url' => sprintf('https://raw.github.com/codehaiku/%s/master', $repo_name),
	        'github_url' => sprintf('https://github.com/codehaiku/%s', $repo_name),
	        'zip_url' => sprintf('https://github.com/codehaiku/%s/zipball/master', $repo_name),
	        'sslverify' => true,
	        'requires' => '4.0',
	        'tested' => '4.4.2',
	        'readme' => 'README.md',
	        'access_token' => '', // Access private repositories by authorizing under Appearance > GitHub Updates when this example plugin is installed
	    );

    	new WP_GitHub_Updater( $config );

	}

	return;
}
