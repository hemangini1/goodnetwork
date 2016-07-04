<?php
/**
 * Navigation Panel
 *
 * @since Listify 1.3.0
 */

class Listify_Customizer_Panel_Nav_Menus extends Listify_Customizer_Panel {

    public function __construct() {
        parent::__construct( array(
            'id' => 'nav_menus',
            'title' => __( 'Menus & Navigation', 'listify' ),
            'priority' => 1
        ) );

        add_action( 'listify_output_customizer_css', array( $this, 'add_css' ) );

        $this->sections = array( 
            'nav-settings' => array(
                'title' => __( 'Settings', 'listify' ),
                'controls' => array(
                    'nav-cart' => array(
                        'label' => __( 'Display cart icon', 'listify' ),
                        'type' => 'checkbox'
                    ),
                    'nav-search' => array(
                        'label' => __( 'Display search icon', 'listify' ),
                        'type' => 'checkbox'
                    ),
                    'nav-secondary' => array(
                        'label' => __( 'Display secondary menu', 'listify' ),
                        'type' => 'checkbox'
                    ),
                    'nav-megamenu' => array(
                        'label' => __( 'Secondary Mega Menu Taxonomy', 'listify' ),
                        'type' => 'select',
                        'choices' => array_merge( array( 'none' => __( 'None', 'listify' ) ), $this->get_taxonomies() )
                    )
                ),
                'priority' => 0
            )
        );
    }

    public function add_css() {
        $css = new Listify_Customizer_CSS();

        $css->add( array(
            'selectors' => array(
                '.primary.nav-menu .current-cart .current-cart-count'
            ),
            'declarations' => array(
                'border-color' => listify_theme_mod( 'color-header-background' ) 
            )
        ) );
    }

    private function get_taxonomies() {
        $taxonomies = get_taxonomies( array( 'public' => true ), array( 'output' => 'objects' ) );
        $_taxonomies = array();

        foreach ( $taxonomies as $taxonomy ) {
            $_taxonomies[ $taxonomy->name ] = $taxonomy->labels->name;
        }

        return $_taxonomies;
    }

}
