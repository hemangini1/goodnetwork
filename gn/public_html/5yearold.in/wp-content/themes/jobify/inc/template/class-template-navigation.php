<?php

class Jobify_Template_Navigation {

	public function __construct() {
		add_filter( 'wp_page_menu_args', array( $this, 'always_show_home' ) );
		add_filter( 'nav_menu_css_class', array( $this, 'popup_trigger_class' ), 10, 3 );
	}

	/**
	 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
	 */
	public function always_show_home( $args ) {
		$args['show_home'] = true;

		return $args;
	}

	public function popup_trigger_class( $classes, $item, $args ) {
		$popup = array_intersect( array( 'login', 'register', 'popup' ), $classes );

		if ( false === $popup || empty( $popup ) ) {
			remove_filter( 'nav_menu_link_attributes', array( $this, 'popup_trigger_attributes' ), 10, 3 );

			return $classes;
		} else {
			foreach ( $popup as $key ) {
				unset( $classes[ $key ] );
			}

			add_filter( 'nav_menu_link_attributes', array( $this, 'popup_trigger_attributes' ), 10, 3 );
		}

		return $classes;
	}

	public function popup_trigger_attributes( $atts, $item, $args ) {
		$atts[ 'class' ] = 'popup-trigger-ajax';

		if ( in_array( 'popup-wide', $item->classes ) ) {
			$atts[ 'class' ] .= ' modal-wide';
		}

		return $atts;
	}

}
