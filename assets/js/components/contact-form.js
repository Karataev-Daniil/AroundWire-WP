/**
 * Contact Form 7 fields: US phone mask "+1 (702) 979-3555".
 */
( function () {
	'use strict';

	function formatPhone( value ) {
		let digits = value.replace( /\D/g, '' );

		if ( digits.startsWith( '1' ) ) {
			digits = digits.slice( 1 );
		}

		digits = digits.slice( 0, 10 );

		if ( ! digits ) {
			return '';
		}

		let formatted = `+1 (${ digits.slice( 0, 3 ) }`;

		if ( digits.length > 3 ) {
			formatted += `) ${ digits.slice( 3, 6 ) }`;
		}

		if ( digits.length > 6 ) {
			formatted += `-${ digits.slice( 6 ) }`;
		}

		return formatted;
	}

	document.addEventListener( 'input', ( event ) => {
		const input = event.target;

		if ( input.matches?.( '.wpcf7 input[type="tel"]' ) ) {
			input.value = formatPhone( input.value );
		}
	} );
}() );
