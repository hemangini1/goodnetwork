<?php
/**
 * Layout Panel
 *
 * @since Listify 1.3.0
 */

class Listify_Customizer_Panel_Layout extends Listify_Customizer_Panel {

    public function __construct() {
        parent::__construct( array(
            'id' => 'layout',
            'title' => __( 'Content', 'listify' ),
            'priority' => 3
        ) );

        $this->sections = array( 
            'content' => array(
                'title' => __( 'Global', 'listify' ),
                'controls' => array(
                    'content-box-style' => array(
                        'label' => __( 'Content Box & Widget Style', 'listify' ),
                        'type' => 'select',
                        'choices' => array(
                            'default' => __( 'Default', 'listify' ),
                            'minimal' => __( 'Minimal', 'listify' ),
                            'shadow' => __( 'Shadow', 'listify' )
                        )
                    ),
                    'content-button-style' => array(
                        'label' => __( 'Button Style', 'listify' ),
                        'type' => 'select',
                        'choices' => array(
                            'default' => __( 'Default', 'listify' ),
                            'solid' => __( 'Solid', 'listify' ),
                            'outline' => __( 'Outline', 'listify' )
                        )
                    ),
                )
            ),
            'header' => array(
                'title' => __( 'Header', 'listify' ),
                'controls' => array(
                    'fixed-header' => array(
                        'label' => __( 'Fixed Header', 'listify' ),
                        'type' => 'checkbox',
                    ),
                    // 'transparent-header' => array(
                    //     'label' => __( 'Transparent Header', 'listify' ),
                    //     'type' => 'checkbox',
                    // )
                )
            ),
            'blog' => array(
                'title' => __( 'Blog', 'listify' ),
                'controls' => array(
                    'content-sidebar-position' => array(
                        'label' => __( 'Blog Sidebar Position', 'listify' ),
                        'type' => 'select',
                        'choices' => array(
                            'none' => __( 'None', 'listify' ),
                            'left' => __( 'Left', 'listify' ),
                            'right' => __( 'Right', 'listify' ),
                        )
                    )
                )
            )
        );

        if ( listify_has_integration( 'wp-job-manager' ) ) {
            $this->sections[ 'listings' ] = array(
            );
        }
    }

}
