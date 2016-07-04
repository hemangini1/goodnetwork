<?php
/**
 * Footer Panel
 *
 * @package Jobify
 * @category Customizer
 * @since 3.0.0
 */
class Jobify_Customizer_Panel_Footer extends Jobify_Customizer_Panel {

    public function __construct() {
        parent::__construct( array(
            'title' => __( 'Footer', 'jobify' ),
            'id' => 'footer',
            'priority' => 60
        ) );

        $this->sections = array( 
            'call-to-action' => array(
                'title' => __( 'Call to Action', 'jobify' ),
                'controls' => array(
                    'cta-display' => array(
                        'label' => __( 'Display this section', 'jobify' ),
                        'type' => 'checkbox'
                    ),
                    'cta-text' => array(
                        'label' => __( 'Description', 'jobify' ),
                        'type' => 'textarea'
                    )
                )
            ),
            'copyright' => array(
                'title' => __( 'Copyright', 'jobify' ),
                'controls' => array(
                    'copyright' => array(
						'label' => __( 'Copyright Text', 'jobify' ),
						'type' => 'textarea'
                    )
                )
            )
        );
    }

}
