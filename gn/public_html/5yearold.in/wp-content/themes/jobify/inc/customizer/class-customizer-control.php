<?php
/**
 * Create a control in the customizer
 *
 * @package Jobify
 * @category Customizer
 * @since 3.0.0
 */
class Jobify_Customizer_Control {

    public $args = array();
    public $wp_customize;

    public function __construct( $wp_customize, $args = array() ) {
        $this->wp_customize = $wp_customize;

        if ( ! empty( $args ) ) {
            $this->args = $args;
            $this->id = isset( $this->args[ 'id' ] ) ? esc_attr( $this->args[ 'id' ] ) : null;

            $this->add_setting();
            $this->add_control();
        }
    }

    public function add_setting() {
        $this->wp_customize->add_setting( $this->id, array(
            'default' => jobify_theme_mod( $this->id )
        ) );
    }

    public function add_control() {
        if ( ! isset( $this->args[ 'type' ] ) ) {
            $this->args[ 'type' ] = 'text';
        }

        if ( class_exists( $this->args[ 'type' ] ) ) { 
            $type = $this->args[ 'type' ];

            unset( $this->args[ 'type' ] );

            $this->wp_customize->add_control( new $type( $this->wp_customize, $this->id, $this->args ) );
        } else {
            $this->wp_customize->add_control( $this->id, $this->args );
        }
    }

}
