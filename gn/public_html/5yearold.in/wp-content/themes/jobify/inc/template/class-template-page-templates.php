<?php

class Jobify_Template_Page_Templates {

    public function __construct() {
        add_filter( 'theme_page_templates', array( $this, 'wp_job_manager_resumes' ) );
        add_filter( 'theme_page_templates', array( $this, 'testimonials' ) );
    }

    public function wp_job_manager_resumes( $page_templates ) {
        if ( jobify()->get( 'wp-job-manager-resumes' ) ) {
            return $page_templates;
        }

        unset( $page_templates[ 'page-templates/map-resumes.php' ] );
        unset( $page_templates[ 'page-templates/pricing-resumes.php' ] );

        return $page_templates;
    }

    public function testimonials( $page_templates ) {
        if ( jobify()->get( 'testimonials' ) ) {
            return $page_templates;
        }

        unset( $page_templates[ 'page-templates/testimonials.php' ] );

        return $page_templates;
    }

}
