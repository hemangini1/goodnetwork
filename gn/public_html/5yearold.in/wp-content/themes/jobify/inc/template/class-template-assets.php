<?php

class Jobify_Template_Assets {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

        add_filter( 'mce_css', array( $this, 'mce_css' ) );

        add_filter( 'body_class', array( $this, 'body_class' ) );
    }

    public function enqueue_scripts() {
        global $post;

        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

        $deps = array( 'jquery' );

        if ( jobify()->get( 'woocommerce' ) ) {
            $deps[] = 'woocommerce';
        }

        wp_enqueue_script( 'jobify', get_template_directory_uri() . '/js/jobify.min.js', $deps, 20140416, true );
        wp_enqueue_script( 'salvattore', get_template_directory_uri() . '/js/vendor/salvattore/salvattore.min.js', array(), '', true );

        $jobify_settings = array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'archiveurl' => get_post_type_archive_link( 'job_listing' ),
            'i18n'    => array(

            ),
            'pages'   => array(
                'is_job'          => is_singular( 'job_listing' ),
                'is_resume'       => is_singular( 'resume' ),
                'is_testimonials' => is_page_template( 'page-templates/testimonials.php' ) || is_post_type_archive( 'testimonial' )
            ),
        );

        $jobify_settings = apply_filters( 'jobify_js_settings', $jobify_settings );

        wp_localize_script( 'jobify', 'jobifySettings', $jobify_settings );
    }

    public function enqueue_styles() {
        do_action( 'jobify_output_customizer_css' );

        $fonts_url = $this->google_fonts_url();

        if ( ! empty( $fonts_url ) ) {
            wp_enqueue_style( 'jobify-fonts', esc_url_raw( $fonts_url ), array(), null );
        }

        wp_enqueue_style( 'jobify-parent', get_template_directory_uri() . '/style.css' );
        wp_style_add_data( 'jobify-parent', 'rtl', 'replace' );

        $jobify_customizer_css = jobify_customizer()->css;
        $jobify_customizer_css->output();
    }

    public function mce_css( $mce_css ) {
        $fonts_url = $this->google_fonts_url();

        if ( empty( $fonts_url ) )
            return $mce_css;

        if ( ! empty( $mce_css ) )
            $mce_css .= ',';

        $mce_css .= esc_url_raw( str_replace( ',', '%2C', $fonts_url ) );

        return $mce_css;
    }

    public function body_class( $classes ) {
        if ( wp_style_is( 'jobify-fonts', 'queue' ) )
            $classes[] = 'custom-font';

        return $classes;
    }

    private function google_fonts_url() {
        $fonts_url = '';

        $montserrat = _x( 'on', 'Montserrat font: on or off', 'jobify' );
        $varela = _x( 'on', 'Varela Round font: on or off', 'jobify' );

        if ( 'off' !== $montserrat || 'off' !== $varela ) {
            $font_families = array();

            if ( 'off' !== $montserrat )
                $font_families[] = 'Montserrat:400,700';

            if ( 'off' !== $varela )
                $font_families[] = 'Varela+Round';

            $protocol = is_ssl() ? 'https' : 'http';
            $query_args = array(
                'family' => implode( '|', $font_families ),
                'subset' => 'latin',
            );
            $fonts_url = add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" );
        }

        return $fonts_url;
    }

}
