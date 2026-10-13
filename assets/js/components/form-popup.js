/**
 * Contact form popup: opened by any .form-popup-button, reset when closed.
 */
( function () {
	'use strict';

	const { AW } = window;

	AW.ready( () => {
		const popup = document.getElementById( 'form-popup' );

		if ( ! popup ) {
			return;
		}

		document.addEventListener( 'click', ( event ) => {
			if ( event.target.closest( '.form-popup-button' ) ) {
				event.preventDefault();
				AW.openDialog( popup );
			}
		} );

		document.getElementById( 'close-popup' )?.addEventListener( 'click', () => AW.closeDialog( popup ) );

		popup.addEventListener( 'aw:close', () => {
			popup.querySelectorAll( 'form' ).forEach( ( form ) => form.reset() );
		} );
	} );
}() );
