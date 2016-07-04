<?php
/**
 * WP Job Manager - WC Paid Listings
 */
class Jobify_WP_Job_Manager_WCPL extends Jobify_Integration {

    public function __construct() {
        $this->includes = array();

        parent::__construct( dirname( __FILE__) );
    }

    public function setup_actions() {
        add_action( 'widgets_init', array( $this, 'widgets_init' ), 20 );

        add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'job_package_add_to_cart_url' ), 10, 2 );
        add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'resume_package_add_to_cart_url' ), 10, 2 );
    }

    /**
     * Registers widgets, and widget areas for WooCommerce
     *
     * @since Jobify 1.7.0
     *
     * @return void
     */
    public function widgets_init() {
		if ( ! jobify()->get( 'woocommerce' ) ) {
			return;
		}

        require_once( $this->get_dir() . 'widgets/class-widget-price-table.php' );

        register_widget( 'Jobify_Widget_Price_Table_WC' );
    }

    public function job_package_add_to_cart_url( $url, $product ) {
        if ( ! in_array( $product->product_type, array( 'job_package', 'job_package_subscription' ) ) ) {
            return $url;
        }

        if ( '' == ( $submit = job_manager_get_permalink( 'submit_job_form' ) ) ) {
            return $url;
        }

        return $submit;
    }

    public function resume_package_add_to_cart_url( $url, $product ) {
        if ( ! in_array( $product->product_type, array( 'resume_package', 'resume_package_subscription' ) ) ) {
            return $url;
        }

        if ( '' == ( $submit = resume_manager_get_permalink( 'submit_resume_form' ) ) ) {
            return $url;
        }

        return $submit;
    }

}
