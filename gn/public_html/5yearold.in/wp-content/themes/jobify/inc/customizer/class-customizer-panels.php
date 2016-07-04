<?php
/**
 * Manage customizer panels.
 *
 * @package Jobify
 * @caegory Customizer
 * @since 3.0.0
 */
class Jobify_Customizer_Panels {

    /**
     * array of registered panels
     */
    public $panels = array();

    public function __construct() {
        $this->panels = array( 'general', 'colors', 'footer' );

        add_action( 'customize_register', array( $this, 'load_panels' ) );
        add_action( 'customize_register', array( $this, 'set_panels' ), 12 );
        add_action( 'customize_register', array( $this, 'register_panels' ), 15 );
    }

    public function load_panels() {
        foreach ( $this->panels as $panel ) {
            require_once( dirname( __FILE__ ) . '/panels/class-customizer-panel-' . $panel . '.php' );
        }
    }

    public function set_panels() {
        $this->colors = new Jobify_Customizer_Panel_Colors();
        $this->general = new Jobify_Customizer_Panel_General();
        $this->footer = new Jobify_Customizer_Panel_Footer();
    }

    public function get_panels() {
        return apply_filters( 'jobify_customizer_panels', $this->panels );
    }

    public function register_panels( $wp_customize ) {
        foreach ( $this->get_panels() as $panel ) {
            $panel = str_replace( '-', '_', $panel );

            if ( $wp_customize->get_panel( $panel ) ) {
                continue;
            }

            /*
             * This shouldn't happen often, but for when a panel only has controls, and no sections
             *
             * Should find a better way to do this
             */
            if ( empty( $this->$panel->sections ) && isset( $this->$panel->controls ) ) {
                $wp_customize->add_section( $this->$panel->id, $this->$panel->args );
            } else {
                $wp_customize->add_panel( $this->$panel->id, $this->$panel->args );
            }
        }
    }

}
