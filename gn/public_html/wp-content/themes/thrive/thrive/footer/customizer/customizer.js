( function( $ ) {

	"use strict";

	//Footer Color.
	wp.customize( 'thrive_footer_color', function( value ) {
		value.bind( function( color ) {
			$('#thrive_footer').css('color', color );
		} );
	});

	//Footer Links Color.
	wp.customize( 'thrive_footer_links', function( value ) {
		value.bind( function( color ) {
			$('#thrive_footer a').css('color', color );
		} );
	});

	//Footer Background.
	wp.customize( 'thrive_footer_background', function( value ) {
		value.bind( function( color ) {
			$('#thrive_footer').css('background', color );
		});
	});

	//Footer Copyright.
	wp.customize( 'thrive_footer_copyright', function( value ) {
		value.bind( function( html ){
			$('#thrive_footer_copytext').html( html );
		});	
	});

	/*
		// Height
	wp.customize( 'thrive_branding_height', function( value ) {
		value.bind( function( height ) {
			$('#site-branding').css('height', height+'px');
			$('#thrive_nav').css('height', height+'px');
		} );
	} );

	// Logo Background
	wp.customize( 'thrive_logo_background', function( value ) {
		value.bind( function( color ) {
			$('#site-branding').css('background-color', color);
		} );
	} );

	// Menu Background
	wp.customize( 'thrive_header_menu_background', function( value ) {
		value.bind( function( color ) {
			$('#thrive_nav').css('background-color', color );
		} );
	} );
	 */

})(jQuery);