<?php
/**
 * Customizer helper functions and template tags.
 *
 * @package Jobify
 * @category Customizer
 * @since 3.0.0
 */

/**
 * Return a single theme mod, or its default.
 *
 * @since 3.0.0
 *
 * @param string $key The mod key.
 * @return string $mod The mod.
 */
function jobify_theme_mod( $key ) {
	return Jobify_Customizer_Mods::get( $key );
}
