<?php
/**
 * Create a section in the customizer
 *
 * @package Jobify
 * @category Customizer
 * @since 3.0.0
 */
class Jobify_Customizer_Section {

    public $args = array();
    public $wp_customize;

    public function __construct( $wp_customize, $args = array() ) {
        $this->wp_customize = $wp_customize;

        if ( ! empty( $args ) ) {
            $this->args = $args;
            $this->id = isset( $this->args[ 'id' ] ) ? esc_attr( $this->args[ 'id' ] ) : null;

            $this->add_section();
        }
    }

    public function add_section() {
        $this->wp_customize->add_section( $this->id, $this->args );
    }

}
