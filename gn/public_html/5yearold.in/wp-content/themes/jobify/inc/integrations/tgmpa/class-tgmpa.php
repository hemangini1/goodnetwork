<?php

class Jobify_TGMPA extends Jobify_Integration {

	public function __construct() {
		$this->includes = array(
			'class-tgm-plugin-activation.php'
		);

		parent::__construct( dirname( __FILE__ ) );
	}

	public function setup_actions() {
		add_action( 'tgmpa_register', array( $this, 'register_required_plugins' ) );
	}

	public function register_required_plugins() {
		$plugins = array(
			array( 
				'name'      => 'Envato WordPress Toolkit',
				'slug'      => 'envato-wordpress-toolkit',
				'source'    => 'https://github.com/envato/envato-wordpress-toolkit/archive/master.zip',
				'external_url' => 'https://github.com/envato/envato-wordpress-toolkit',
				'required'  => false
			),
			array(
				'name'      => 'WP Job Manager',
				'slug'      => 'wp-job-manager',
				'required'  => true
			),
			array(
				'name'      => 'WP Job Manager - Company Profiles',
				'slug'      => 'wp-job-manager-companies',
				'required'  => false,
			),
			array(
				'name'      => 'WP Job Manager - Job Colors',
				'slug'      => 'wp-job-manager-colors',
				'required'  => false,
			),
			array(
				'name'      => 'WP Job Manager - Job Regions',
				'slug'      => 'wp-job-manager-locations',
				'required'  => false,
			),
			array(
				'name'      => 'WP Job Manager - Contact Listing',
				'slug'      => 'wp-job-manager-contact-listing',
				'required'  => false,
			),
			array(
				'name'      => 'WooCommerce',
				'slug'      => 'woocommerce',
				'required'  => true
			),
			array(
				'name'      => 'WordPress Importer',
				'slug'      => 'wordpress-importer',
				'required'  => false 
			),
			array(
				'name'      => 'Widget Importer & Exporter',
				'slug'      => 'widget-importer-exporter',
				'required'  => false,
			),
			array(
				'name'      => 'Jetpack',
				'slug'      => 'jetpack',
				'required'  => false,
			),
			array(
				'name'      => 'Ninja Forms',
				'slug'      => 'ninja-forms',
				'required'  => false,
			),
			array(
				'name'      => 'Testimonials',
				'slug'      => 'testimonials-by-woothemes',
				'required'  => false,
			),
			array(
				'name'      => 'Nav Menu Roles',
				'slug'      => 'nav-menu-roles',
				'required'  => false,
			)
		);

		$config = array(
			'id'          => 'tgmpa-jobify-' . get_option( 'jobify_version', '3.0.0' ),
			'has_notices' => false
		);

		tgmpa( $plugins, $config );
	}

}
