<?php

class Jobify_Customizer_Output {

    public function __construct() {
        add_action( 'wp', array( $this, 'load_output' ) );
        add_action( 'wp', array( $this, 'output' ) );
    }

    public function load_output() {
        $output = array(
            'class-customizer-css-colors.php',
            'class-customizer-css-footer.php'
        );

        foreach ( $output as $file ) {
            include_once( trailingslashit( dirname( __FILE__) ) . 'css/' . $file );
        }
    }

    public function output() {
        $this->colors = new Jobify_Customizer_CSS_Colors();
        $this->footer = new Jobify_Customizer_CSS_Footer();
    }

    public function get_regex_theme_mods( $property ) {
        $mods = jobify_get_theme_mod_defaults();
        $font_keys = array();

        foreach ( $mods as $key => $default ) {
            if ( preg_match( "/{$property}/i", $key ) ) {
                $font_keys[] = $key;
            }
        }

        return $font_keys;
    }

}
