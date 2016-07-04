<?php

class Jobify_WooCommerce_Template {

    public function __construct() {
        add_action( 'jobify_output_customizer_css', array( $this, 'output_colors' ), 10 );

        add_action( 'after_setup_theme', array( $this, 'add_theme_support' ) );
        add_action( 'widgets_init', array( $this, 'widgets_init' ), 20 );

        add_filter( 'woocommerce_enqueue_styles', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_social_login_scripts' ) );
    }

	public function load_social_login_scripts() {
		if ( class_exists( 'WC_Social_Login' ) ) {
			wc_social_login()->frontend->load_styles_scripts();
		}
	}

    public function output_colors() {
        $css = new Jobify_Customizer_CSS;

        $primary = jobify_theme_mod( 'color-primary' );
        $accent = jobify_theme_mod( 'color-accent' );

        $css->add( array(
            'selectors' => array(
                '.woocommerce ul.products li.product .onsale, .woocommerce-page ul.products li.product .onsale'
            ),
            'declarations' => array(
                'background-color' => $accent
            )
        ) );


        $css->add( array(
            'selectors' => array(
                '.woocommerce .price ins',
                '.woocommerce ul.product_list_widget ins'
            ),
            'declarations' => array(
                'background-color' => $primary
            )
        ) );

        $css->add( array(
            'selectors' => array(
                '.single-product #content .woocommerce-tabs .tabs li.active a'
            ),
            'declarations' => array(
                'color' => $primary,
                'border-bottom' => '2px solid ' . $primary
            )
        ) );
    }

    public function enqueue_styles($enqueue_styles) {
		if ( isset( $enqueue_styles[ 'woocommerce-general' ] ) ) {
			unset( $enqueue_styles[ 'woocommerce-general' ] );
		}

        return $enqueue_styles;
    }

    /**
     * Sets up theme support.
     *
     * @since Jobify 1.7.0
     *
     * @return void
     */
    public function add_theme_support() {
        add_theme_support( 'woocommerce' );
    }

    /**
     * Registers widgets, and widget areas for WooCommerce
     *
     * @since Jobify 1.7.0
     *
     * @return void
     */
    public function widgets_init() {
        register_sidebar( array(
            'name'          => __( 'Shop Sidebar', 'listify' ),
            'id'            => 'widget-area-sidebar-shop',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
    }

}
