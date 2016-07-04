<?php
/**
 * WooCommerce
 *
 * @package Jobify
 * @category Integration
 * @since 3.0.0
 */
class Jobify_WooCommerce extends Jobify_Integration {

    public function __construct() {
        $this->includes = array(
            'class-woocommerce-registration.php',
            'class-woocommerce-template.php',
            'class-woocommerce-layout.php'
        );

        parent::__construct( dirname( __FILE__ ) );
    }

    public function init() {
        $this->template = new Jobify_WooCommerce_Template();
        $this->layout = new Jobify_WooCommerce_Layout();
        $this->registration = new Jobify_WooCommerce_Registration();

        add_filter( 'submit_job_form_login_url', array( $this, 'login_url' ), 10 );
        add_filter( 'submit_resume_form_login_url', array( $this, 'login_url' ), 10 );
    }

	public function login_url( $url ) {
		// not sure why this is -1 sometimes
		if ( -1 == wc_get_page_id( 'myaccount' ) ) {
			return $url;
		}

		$parts = parse_url( $url );
		parse_str( $parts['query'], $query );

		$url = get_permalink( wc_get_page_id( 'myaccount' ) );

		if ( isset( $query[ 'redirect_to' ] ) ) {
			$url = add_query_arg( 'redirect_to', $query[ 'redirect_to' ], $url );
		}

		return esc_url( $url );
	}

}
