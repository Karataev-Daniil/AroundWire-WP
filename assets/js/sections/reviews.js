/**
 * Video reviews carousel: endless, autoplaying, paused on hover and while a video plays.
 */
( function () {
	'use strict';

	const { AW } = window;

	AW.ready( () => {
		document.querySelectorAll( '[data-reviews-slider]' ).forEach( ( element ) => {
			const slider = AW.slider( element, {
				type: 'loop',
				autoWidth: true,
				autoplay: true,
				interval: 3000,
				pauseOnHover: true,
				pauseOnFocus: true,
			} );

			AW.pauseWithVideo( slider );
		} );
	} );
}() );
