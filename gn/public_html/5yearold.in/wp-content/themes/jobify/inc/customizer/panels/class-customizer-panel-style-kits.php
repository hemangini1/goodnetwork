<?php
/**
 * Style Kits
 *
 * @since Listify 1.3.0
 */

class Listify_Customizer_Panel_Style_Kits extends Listify_Customizer_Panel {

    public function __construct() {
        parent::__construct( array(
            'id' => 'style-kit',
            'title' => __( 'Style Kit', 'listify' ),
            'priority' => 1
        ) );

        $this->sections = array();

        // this shouldn't happen often -- but this "panel" has no sections and controls only
        $this->controls = array(
            'style-kit' => array(
                'label' => __( 'Style Kit', 'listify' ),
                'type' => 'Listify_Customize_Style_Kit_Control',
            )
        );
    }

}
