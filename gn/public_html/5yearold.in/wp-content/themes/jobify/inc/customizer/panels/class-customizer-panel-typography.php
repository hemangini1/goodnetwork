<?php
/**
 * Typography Panel
 *
 * @since Listify 1.3.0
 */

class Listify_Customizer_Panel_Typography extends Listify_Customizer_Panel {

    public function __construct() {
        parent::__construct( array(
            'title' => __( 'Typography', 'listify' ),
            'id' => 'typography',
            'priority' => 3
        ) );

        $this->sections = array( 
            'typography-font-pack' => array(
                'title' => __( 'Font Pack', 'listify' ),
                'controls' => array(
                    'font-pack' => array(
                        'label'   => __( 'Font Pack', 'listify' ),
                        'type'    => 'Listify_Customize_Font_Pack_Control'
                    )
                )
            ),
            'typography-font-subset' => array(
                'title' => __( 'Font Subset', 'listify' ),
                'controls' => array(
                    'typography-font-subset' => array(
                        'label' => __( 'Font Subset', 'listify' ),
                        'type' => 'select',
                        'choices' => listify_customizer()->fonts->get_google_font_subsets(),
                        'description' => __( 'Please note not all subsets are available for each font family. <a href="https://www.google.com/fonts">Read more</a>.', 'marketify' )
                    )
                )
            ),
            'typography-global' => array(
                'title' => __( 'Global', 'listify' ),
                'controls' => $this->get_font_style_options( 'body' )
            ),
            'typography-page-headings' => array(
                'title' => __( 'Page Headings', 'listify' ),
                'controls' => $this->get_font_style_options( 'page-headings' )
            ),
            'typography-content-headings' => array(
                'title' => __( 'Content Box Headings', 'listify' ),
                'controls' => $this->get_font_style_options( 'content-headings' )
            ),
            'typography-home-headings' => array(
                'title' => __( 'Homepage Widget Headings', 'listify' ),
                'controls' => $this->get_font_style_options( 'home-headings' )
            ),
            'typography-home-descriptions' => array(
                'title' => __( 'Homepage Widget Descriptions', 'listify' ),
                'controls' => $this->get_font_style_options( 'home-description' )
            ),
            'typography-button' => array(
                'title' => __( 'Buttons', 'listify' ),
                'controls' => $this->get_font_style_options( 'button' )
            )
        );

        add_filter( 'listify_customizer_scripts_data', array( $this, 'customizer_scripts' ) );
    }

    public function get_font_style_options( $element ) {
        return array(
            'typography-' . $element . '-font-family' => array(
                'label'   => __( 'Font Family', 'listify' ),
                'type'    => 'select',
                'choices' => array( 'placeholder' => __( 'Loading...', 'listify' ) )
            ),
            'typography-' . $element . '-font-size' => array(
                'label'   => __( 'Font Size', 'listify' ),
                'type'    => 'number',
                'input_attrs' => array(
                    'min'   => 1,
                    'max'   => 78,
                    'step'  => 1,
                ),
            ),
            'typography-' . $element . '-font-weight' => array(
                'label'   => __( 'Font Weight', 'listify' ),
                'type'    => 'select',
                'choices' => array(
                    'normal' => __( 'Normal', 'listify' ),
                    'bold' => __( 'Bold', 'listify' )
                )
            ),
            'typography-' . $element . '-line-height' => array(
                'label'   => __( 'Line Height (em)', 'listify' ),
                'type'    => 'number',
                'input_attrs' => array(
                    'min'   => 1,
                    'max'   => 5,
                    'step'  => 0.25,
                ),
            )
        );
    }

    public function customizer_scripts( $data ) {
        $data[ 'fonts' ] = array(
            'options' => listify_customizer()->output->get_regex_theme_mods( 'font-family' ),
            'choices' => listify_customizer()->fonts->all_font_choices_js()
        );

        return $data;
    }
}
