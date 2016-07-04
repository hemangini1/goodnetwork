<?php
/**
 * Output the Footer CSS.
 *
 * @package Jobify
 * @category Customizer
 * @since 3.1.0
 */
class Jobify_Customizer_CSS_Footer {

    public function __construct() {
        $this->css = jobify_customizer()->css;

        add_action( 'jobify_output_customizer_css', array( $this, 'colors' ), 0 );
    }

    public function colors() {
		/**
		 * Call to Action
		 */
		$text = jobify_theme_mod( 'color-cta-text' );
        $background = jobify_theme_mod( 'color-cta-background' );

        $this->css->add( array(
            'selectors' => array(
                '.footer-cta',
                '.footer-cta a',
                '.footer-cta tel'
            ),
            'declarations' => array(
                'color' => esc_attr( $text )
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
                '.footer-cta a.button:hover'
            ),
            'declarations' => array(
                'color' => esc_attr( $background ) . ' !important' // ew
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
                '.footer-cta',
            ),
            'declarations' => array(
                'background-color' => esc_attr( $background ),
            )
        ) );

		/**
		 * Footer Widgets
		 */
		$title = jobify_theme_mod( 'color-footer-widgets-title' );
		$link = jobify_theme_mod( 'color-footer-widgets-link' );
		$text = jobify_theme_mod( 'color-footer-widgets-text' );
        $background = jobify_theme_mod( 'color-footer-widgets-background' );

        $this->css->add( array(
            'selectors' => array(
                '.widget-title--footer'
            ),
            'declarations' => array(
                'color' => esc_attr( $title )
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
                '.widget--footer a'
            ),
            'declarations' => array(
                'color' => esc_attr( $link )
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
                '.widget--footer'
            ),
            'declarations' => array(
                'color' => esc_attr( $text )
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
                '.footer-widgets'
            ),
            'declarations' => array(
                'background-color' => esc_attr( $background )
            )
        ) );

		/**
		 * Copyright
		 */
		$link = jobify_theme_mod( 'color-copyright-link' );
		$text = jobify_theme_mod( 'color-copyright-text' );
        $background = jobify_theme_mod( 'color-copyright-background' );

        $this->css->add( array(
            'selectors' => array(
                '.copyright a'
            ),
            'declarations' => array(
                'color' => esc_attr( $link )
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
                '.copyright'
            ),
            'declarations' => array(
                'color' => esc_attr( $text )
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
                '.site-footer'
            ),
            'declarations' => array(
                'background-color' => esc_attr( $background )
            )
        ) );
    }

}
