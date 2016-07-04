<?php
/**
 * Activating Jobify
 *
 * @since 3.0.0
 * @package Jobify
 * @category Admin
 */
class Jobify_Activation {
	
	/**
	 * @var object
	 */
	public static $theme;

	/**
	 * @var string
	 */
	public static $version;

	/**
	 * Start things up.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public static function init() {
		if ( ! is_admin() ) {
			return;
		}

		// see if a child theme is active. If it is we need the parent theme's dir.
		$maybe_child_theme = wp_get_theme();

		if ( '' != $maybe_child_theme->Template ) {
			self::$theme = wp_get_theme( $maybe_child_theme->Template );
		} else {
			self::$theme = wp_get_theme();
		}

		// get the previous version
		self::$version = get_option( 'jobify_version' );


		self::maybe_upgrade();
		self::setup_actions();
	}

	/**
	 * Set hooks and callbacks.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public static function setup_actions() {
		add_action( 'after_switch_theme', array( __CLASS__, 'after_switch_theme' ), 10, 2 );
		add_action( 'add_option_job_manager_installed_terms', array( __CLASS__, 'enable_categories' ) );
	}

	/**
	 * See if the new version has an upgrade routine. If so, run it.
	 *
	 * @since 3.0.0
	 * 
	 * @return void
	 */
	public static function maybe_upgrade() {
		if ( ! version_compare( self::$version, self::$theme->Version, '<' ) ) {
			return;
		}
		
		$version = str_replace( '.', '', self::$theme->Version );
		$upgrade = '_upgrade_' . $version;

		if ( method_exists( __CLASS__, $upgrade ) ) {
			self::$upgrade();
		}
	}

	/**
	 * When this theme is activated set the current version and redirect if a new
	 * install. This is only called when a theme is actually switched.
	 *
	 * @since 3.0.0
	 *
	 * @param object $theme
	 * @param object $old
	 * @return void
	 */
	public static function after_switch_theme( $theme, $old ) {
		// If it's set just update version can cut out
		if ( get_option( 'jobify_version' ) ) {
			self::set_version();

			return;
		}

		self::set_version();
		self::redirect();
	}

	/**
	 * Save the current theme's version to the database.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public static function set_version() {
		update_option( 'jobify_version', self::$theme->version );
	}

	/**
	 * Redirect to the theme setup admin page.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public static function redirect() {
		unset( $_GET[ 'action' ] );

		wp_safe_redirect( admin_url( 'themes.php?page=jobify-setup' ) );

		exit();
	}

	/**
	 * Automatically turn on WP Job Manager categories.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public static function enable_categories() {
		update_option( 'job_manager_enable_categories', 1 );
	}

	/**
	 * Upgrade script for 2.0.5
	 */
	private static function _upgrade_205() {
		$theme_mods = get_theme_mods();
		
		foreach ( $theme_mods as $mod => $value ) {
			switch ($mod) {
				case 'jobify_listings_display_area' :
					set_theme_mod( 'job-display-sidebar', $value );
					set_theme_mod( 'resume-display-sidebar', $value );
					remove_theme_mod( 'jobify_listings_display_area' );
				case 'jobify_listings_topbar_columns' :
					set_theme_mod( 'job-display-sidebar-columns', $value );
					set_theme_mod( 'resume-display-sidebar-columns', $value );
					remove_theme_mod( 'jobify_listings_topbar_columns' );
				case 'header_background' :
					set_theme_mod( 'color-header-background', $value );
					remove_theme_mod( 'header_background' );
				case 'navigation' :
					set_theme_mod( 'color-navigation-background', $value );
					remove_theme_mod( 'navigation' );
				case 'primary' :
					set_theme_mod( 'color-primary', $value );
					remove_theme_mod( 'primary' );
				case 'jobify_cta_display' :
					set_theme_mod( 'cta-display', $value );
					remove_theme_mod( 'jobify_cta_display' );
				case 'jobify_cta_text' :
					set_theme_mod( 'cta-text', $value );
					remove_theme_mod( 'jobify_cta_text' );
				case 'jobify_cta_text_color' :
					set_theme_mod( 'cta-text-color', $value );
					remove_theme_mod( 'jobify_cta_text_color' );
				case 'jobify_cta_background_color' :
					set_theme_mod( 'cta-background-color', $value );
					remove_theme_mod( 'jobify_cta_background_color' );
				case 'clusters' :
					set_theme_mod( 'map-behavior-clusters', $value );
					remove_theme_mod( 'clusters' );
				case 'grid-size' :
					set_theme_mod( 'map-behavior-grid-size', $value );
					remove_theme_mod( 'grid-size' );
				case 'autofit' :
					set_theme_mod( 'map-behavior-autofit', $value );
					remove_theme_mod( 'autofit' );
				case 'center' :
					set_theme_mod( 'map-behavior-center', $value );
					remove_theme_mod( 'center' );
				case 'zoom' :
					set_theme_mod( 'map-behavior-zoom', $value );
					remove_theme_mod( 'zoom' );
				case 'max-zoom' :
					set_theme_mod( 'map-behavior-max-zoom', $value );
					remove_theme_mod( 'max-zoomg' );
			}
		}
	}

	/**
	 * Jobify 3.0.0
	 *
	 * Changes how the theme mods are stored (again).
	 */
	private static function _upgrade_300() {
		$theme_mods = get_theme_mods();

		if ( ! $theme_mods ) {
			return;
		}

		foreach ( $theme_mods as $mod => $value ) {
			switch ($mod) {
				case 'jobify_listing_display_area' :
					set_theme_mod( 'job-display-sidebar', $mod );
					set_theme_mod( 'resume-display-sidebar', $mod );
					break;
				case 'jobify_listing_topbar_columns' :
					set_theme_mod( 'job-display-sidebar-columns', $mod );
					set_theme_mod( 'resume-display-sidebar-columns', $mod );
					remove_theme_mod( 'jobify_listings_topbar_colspan' );
					break;
				case 'header_background' :
					set_theme_mod( 'color-header-background', $value );
					break;
				case 'primary' :
					set_theme_mod( 'color-primary', $value );
					break;
				case 'navigation' :
					set_theme_mod( 'color-navigation-text', $value );
					break;
				case 'jobify_cta_display' :
					set_theme_mod( 'cta-display', $value );
					break;
				case 'jobify_cta_text' :
					set_theme_mod( 'cta-text', $value );
					break;
				case 'jobify_cta_text_color' :
					set_theme_mod( 'color-cta-text', $value );
					break;
				case 'jobify_cta_background_color' :
					set_theme_mod( 'color-cta-background', $value );
					break;
				case 'clusters' :
					set_theme_mod( 'map-behavior-clusters', $value );
					break;
				case 'grid-size' :
					set_theme_mod( 'map-behavior-grid-size', $value );
					break;
				case 'autofit' :
					set_theme_mod( 'map-behavior-autofit', $value );
					break;
				case 'center' :
					set_theme_mod( 'map-behavior-center', $value );
					break;
				case 'zoom' :
					set_theme_mod( 'map-behavior-zoom', $value );
					break;
				case 'max-zoom' :
					set_theme_mod( 'map-behavior-max-zoom', $value );
					break;
				default:
					//
					break;
			}
		}
	}

}
