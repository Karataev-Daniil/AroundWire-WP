/**
 * Benefits cards. data-slider says when they become a swipeable row:
 * "always" (v1), or below 1025px / 650px; above that they are a CSS grid.
 */
( function () {
	'use strict';

	const { AW } = window;
	// Card spacing comes from the version stylesheets, so no "gap" here.
	const options = {
		autoWidth: true,
		drag: 'free',
		omitEnd: true,
	};

	AW.ready( () => {
		document.querySelectorAll( '.benefits__slider[data-slider]' ).forEach( ( slider ) => {
			const mode = slider.dataset.slider;

			if ( mode === 'always' ) {
				AW.slider( slider, options );
			} else {
				AW.responsiveSlider( slider, `(max-width: ${ Number( mode ) - 1 }px)`, options );
			}
		} );
	} );
}() );
