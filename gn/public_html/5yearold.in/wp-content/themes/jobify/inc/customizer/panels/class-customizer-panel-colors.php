<?php
/**
 * Color Panel
 *
 * @package Jobify
 * @category Customizer
 * @since 3.0.0
 */
class Jobify_Customizer_Panel_Colors extends Jobify_Customizer_Panel {

    public function __construct() {
        parent::__construct( array(
            'id' => 'colors',
            'title' => __( 'Colors', 'jobify' ),
            'priority' => 2
        ) );

        add_action( 'customize_register', array( $this, 'remove_section' ), 40 );
        add_action( 'customize_register', array( $this, 'move_controls' ), 40 );

        $this->sections = array( 
            'color-global' => array(
                'title' => __( 'Global', 'jobify' ),
                'controls' => array(
                    'color-primary' => array(
                        'label' => __( 'Primary Color', 'jobify' ),
                        'type'    => 'WP_Customize_Color_Control'
                    ),
                    'color-accent' => array(
                        'label' => __( 'Accent Color', 'jobify' ),
                        'type'    => 'WP_Customize_Color_Control'
                    ),
                    'color-body-text' => array(
                        'label' => __( 'Body Text Color', 'jobify' ),
                        'type'    => 'WP_Customize_Color_Control'
                    ),
                    'color-link' => array(
                        'label' => __( 'Link Text Color', 'jobify' ),
                        'type' => 'WP_Customize_Color_Control',
                    )
                )
            ),
            'color-header' => array(
                'title' => __( 'Header/Navigation', 'jobify' ),
                'controls' => array(
                    'color-navigation-text' => array(
                        'label' => __( 'Navigation Text Color', 'jobify' ),
                        'type'    => 'WP_Customize_Color_Control'
                    ),
                    'color-header-background' => array(
                        'label' => __( 'Header Background Color', 'jobify' ),
                        'type'    => 'WP_Customize_Color_Control'
                    ),
                )
            ),
            'color-cta' => array(
                'title' => __( 'Call to Action', 'jobify' ),
                'controls' => array(
                    'color-cta-text' => array(
                        'label' => __( 'Call to Action Text Color', 'jobify' ),
                        'type' => 'WP_Customize_Color_Control'
                    ),
                    'color-cta-background' => array(
                        'label' => __( 'Call to Action Background Color', 'jobify' ),
                        'type' => 'WP_Customize_Color_Control'
                    )
                )
            ),
            'color-footer-widgets' => array(
                'title' => __( 'Footer Widgets', 'jobify' ),
                'controls' => array(
                    'color-footer-widgets-title' => array(
                        'label' => __( 'Footer Widgets Title Color', 'jobify' ),
                        'type' => 'WP_Customize_Color_Control'
                    ),
                    'color-footer-widgets-link' => array(
                        'label' => __( 'Footer Widgets Link Color', 'jobify' ),
                        'type' => 'WP_Customize_Color_Control'
                    ),
                    'color-footer-widgets-text' => array(
                        'label' => __( 'Footer Widgets Text Color', 'jobify' ),
                        'type' => 'WP_Customize_Color_Control'
                    ),
                    'color-footer-widgets-background' => array(
                        'label' => __( 'Footer Widgets Background Color', 'jobify' ),
                        'type' => 'WP_Customize_Color_Control'
                    )
                )
            ),
            'color-copyright' => array(
                'title' => __( 'Copyright', 'jobify' ),
                'controls' => array(
                    'color-copyright-link' => array(
                        'label' => __( 'Copyright Link Color', 'jobify' ),
                        'type' => 'WP_Customize_Color_Control'
                    ),
                    'color-copyright-text' => array(
                        'label' => __( 'Copyright Text Color', 'jobify' ),
                        'type' => 'WP_Customize_Color_Control'
                    ),
                    'color-copyright-background' => array(
                        'label' => __( 'Copyright Background Color', 'jobify' ),
                        'type' => 'WP_Customize_Color_Control'
                    )
                )
            ),
        );
    }

    public function remove_section( $wp_customize ) {
        $wp_customize->remove_section( 'colors' );
    }

    public function move_controls( $wp_customize ) {
        $wp_customize->get_control( 'header_textcolor' )->section = 'color-header';

        $wp_customize->get_control( 'background_color' )->section = 'color-global';
        $wp_customize->get_control( 'background_color' )->label = __( 'Page Background Color', 'jobify' );
    }

}
