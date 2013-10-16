/**
 * UberMenu Hover Shadow Scripts
 *
 * @since 1.0.0
 * @author Laurens Offereins
 *
 * @package UberMenu Hover Shadow
 * @subpackage Scripts
 */

( function( $, document, window, undefined ) {

	$(document).ready( function() {

		// Set vars and setup hover layer
		var $body   = $('body'),
			$umhs   = $('<div></div>').addClass( 'umhs ubermenu-hover-bg' ).css( 'height', $(document).height() + 'px' ),
			pause   = 300,
			timeout = 0;
		
		// When hovering the menu
		$body.find( '#megaUber' ).on( 'mouseenter', function() {

			// Clear remove timeout if set
			clearTimeout( timeout );

			// Create hover layer
			$umhs.prependTo( $body );

			// Darken hover layer after pause. Somehow delay() doesn't do the trick
			setTimeout( function() { $umhs.addClass( 'darken' ); }, 1 );

		// When unhovering the menu
		}).on( 'mouseleave', function() {

			// Undarken hover layer
			$umhs.removeClass( 'darken' );

			// Remove hover layer after pause
			timeout = setTimeout( function() { $umhs.remove(); }, pause );

		});

	});

})( jQuery, document, window );