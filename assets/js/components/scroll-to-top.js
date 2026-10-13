/**
 * "Back to top" button, visible once the page is scrolled.
 */
( function () {
	'use strict';

	window.AW.ready( () => {
		const button = document.getElementById( 'scrollToTopBtn' );

		if ( ! button ) {
			return;
		}

		let ticking = false;

		const update = () => {
			const visible = window.scrollY > 20;

			if ( button.hidden === visible ) {
				button.hidden = ! visible;
				button.classList.remove( 'animate' );
			}

			ticking = false;
		};

		window.addEventListener(
			'scroll',
			() => {
				if ( ! ticking ) {
					ticking = true;
					window.requestAnimationFrame( update );
				}
			},
			{ passive: true }
		);

		button.addEventListener( 'mouseenter', () => button.classList.add( 'animate' ) );
		button.addEventListener( 'animationend', () => button.classList.remove( 'animate' ) );
		button.addEventListener( 'click', () => {
			button.classList.add( 'animate' );
			window.scrollTo( { top: 0, behavior: 'smooth' } );
		} );

		update();
	} );
}() );
