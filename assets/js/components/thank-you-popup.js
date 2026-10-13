/**
 * "Thank you" popup shown after a Contact Form 7 form is sent.
 */
( function () {
	'use strict';

	const { AW } = window;

	AW.ready( () => {
		const popup = document.getElementById( 'thank-you-popup' );

		if ( ! popup ) {
			return;
		}

		document.addEventListener( 'wpcf7mailsent', () => {
			AW.closeDialog( document.getElementById( 'form-popup' ) );
			AW.openDialog( popup );
		} );

		popup.querySelector( '.close-popup' )?.addEventListener( 'click', () => AW.closeDialog( popup ) );
	} );
}() );
