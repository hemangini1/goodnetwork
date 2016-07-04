<?php
/**
 * Create a panel in the customizer
 *
 * @package Jobify
 * @category Customizer
 * @since 3.0.0
 */
class Jobify_Customizer_Panel {

	/**
	 * @var string $id The unique slug of the panel.
	 */
    public $id;

	/**
	 * @var array $args
	 */
    public $args = array();

	/**
	 * @var array $sections
	 */
    public $sections = array();

    public function __construct( $args = array() ) {
        if ( ! empty( $args ) ) {
            $this->args = $args;
            $this->id = isset( $this->args[ 'id' ] ) ? esc_attr( $this->args[ 'id' ] ) : null;
        }

        add_action( 'customize_register', array( $this, 'register_sections' ), 20 );
        add_action( 'customize_register', array( $this, 'register_controls' ), 30 );
    }

    public function get_sections() {
        return apply_filters( 'jobify_customizer_sections', $this->sections );
    }

    public function register_sections( $wp_customize ) {
        foreach ( $this->get_sections() as $id => $section ) {
            // merge our ID and Panel to existing settings
            $args =  wp_parse_args( $section, array( 
                'id' => $id,
                'panel' => $this->id
            ) );

            new Jobify_Customizer_Section( $wp_customize, $args );
        }
    }

    public function register_controls( $wp_customize ) {
        $sections = $this->get_sections();

        /*
         * This shouldn't happen often, but for when a panel only has controls, and no sections
         *
         * Should find a better way to do this
         */
        if ( empty( $sections ) && ! empty( $this->controls ) ) {
            foreach ( $this->controls as $id => $control ) {
                // merge our ID and Section to existing settings
                $args = wp_parse_args( $control, array(
                    'id' => $id,
                    'section' => $this->id
                ) );

                new Jobify_Customizer_Control( $wp_customize, $args );
            }
        } else {
            foreach ( $sections as $key => $section ) {
                $controls = isset( $section[ 'controls' ] ) ? $section[ 'controls' ] : array();

                if ( empty( $controls ) ) {
                    continue;
                }

                foreach ( $controls as $id => $control ) {
                    // merge our ID and Section to existing settings
                    $args = wp_parse_args( $control, array(
                        'id' => $id,
                        'section' => $key
                    ) );

                    new Jobify_Customizer_Control( $wp_customize, $args );
                }
            }
        }
    }

}
