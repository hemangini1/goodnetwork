<?php
/**
 * General Panel
 *
 * @package Jobify
 * @category Customizer
 * @since 3.0.0
 */
class Jobify_Customizer_Panel_General extends Jobify_Customizer_Panel {

    public function __construct() {
        parent::__construct( array(
            'id' => 'general',
            'title' => __( 'General', 'jobify' ),
            'priority' => 1
        ) );

        add_action( 'customize_register', array( $this, 'move_background_image' ), 40 );
        add_action( 'customize_register', array( $this, 'move_site_identity' ), 40 );
        add_action( 'customize_register', array( $this, 'move_header_image' ), 40 );
        add_action( 'customize_register', array( $this, 'move_front_page' ), 40 );

		$this->sections = array(
			'title_tagline' => array(
				'title' => __( 'Site Logo & Header', 'jobify' ),
				'controls' => array(
					'fixed-header' => array(
						'label' => __( 'Fixed Header', 'jobify' ),
						'type' => 'checkbox'
					)
				)
			),
			'accounts' => array(
				'title' => __( 'Accounts', 'jobify' ),
				'controls' => array()
			)
		);

		if ( jobify()->get( 'woocommerce' ) ) {
			$this->sections[ 'accounts' ][ 'controls' ][ 'registration-roles' ] = array(
				'label'   => __( 'Available Registration Roles', 'jobify' ),
				'type'    => 'Jobify_Customize_Mulitcheck_Control',
				'choices' => jobify()->get( 'woocommerce' )->registration->get_registration_roles(),
				'description' => __( 'If no roles are selected, the default in "Job Listings > Settings" will be used and no role field will be shown on the registration form.', 'jobify' )
			);
			$this->sections[ 'accounts' ][ 'controls' ][ 'registration-default' ] = array(
				'label'   => __( 'Default Role Selection', 'jobify' ),
				'type'    => 'select',
				'choices' => jobify()->get( 'woocommerce' )->registration->get_registration_roles(),
				'description' => __( 'The role which is selected in the dropdown by default.', 'jobify' )
			);
		}
    }

    public function move_background_image( $wp_customize ) {
        $wp_customize->get_section( 'background_image' )->panel = 'general';
    }

    public function move_site_identity( $wp_customize ) {
        $wp_customize->get_section( 'title_tagline' )->panel = 'general';
        $wp_customize->get_section( 'title_tagline' )->title = __( 'Site Logo & Header', 'jobify' );
    }

    public function move_header_image( $wp_customize ) {
        $wp_customize->get_control( 'header_image' )->section = 'title_tagline';
        $wp_customize->get_control( 'header_image' )->label = __( 'Site Logo', 'jobify' );
    }

    public function move_front_page( $wp_customize ) {
        if ( $wp_customize->get_section( 'static_front_page' ) ) {
            $wp_customize->get_section( 'static_front_page' )->panel = 'general';
            $wp_customize->get_section( 'static_front_page' )->title = __( 'Homepage Display', 'jobify' );
        }
    }
}
