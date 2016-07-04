<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package thrive
 */

global $post;

$sidebar 		= 'sidebar-1';
$sidebar_right 	= 'sidebar-1';
$sidebar_left 	= 'sidebar-1';
$page_layout    = 'content-sidebar';
$is_shop 		= false;

if ( function_exists('is_shop') ) {
	if ( is_shop() ) {
		$post->ID = get_option( 'woocommerce_shop_page_id' );
		$is_shop = true;
	}
}

if ( !empty( $post ) ) {

	$sidebar_right = get_post_meta( $post->ID, 'thrive_sidebar', true );
	$sidebar_left  = get_post_meta( $post->ID, 'thrive_sidebar_left', true );
	$page_layout   = get_post_meta( $post->ID, 'thrive_page_layout', true );

}

if ( 'sidebar-content' === $page_layout ) {
	$sidebar = $sidebar_left;
}

if ( 'content-sidebar' === $page_layout ) {
	$sidebar = $sidebar_right;
}

if ( is_archive() || is_home() ) {
	if ( !$is_shop ) {
		$sidebar = 'archives-sidebar';
	}
}

if ( function_exists( 'is_buddypress' ) ) {

	if ( is_buddypress() ) {

		$sidebar = 'bp-sidebar';

	}
}

?>

<div id="secondary" class="widget-area" role="complementary">

	<?php if ( is_active_sidebar( $sidebar ) ) { ?>

		<?php dynamic_sidebar( esc_attr( $sidebar ) ); ?>

	<?php } ?>


</div><!-- #secondary -->
