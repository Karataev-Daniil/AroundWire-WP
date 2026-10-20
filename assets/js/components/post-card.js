/**
 * Blog post cards fade in as they enter the viewport.
 * AW.revealPosts( root ) is also called for cards added over AJAX.
 */
( function () {
	'use strict';

	const { AW } = window;

	const observer =
		'IntersectionObserver' in window
			? new IntersectionObserver(
				( entries ) => {
					entries.forEach( ( entry ) => {
						if ( entry.isIntersecting ) {
							entry.target.classList.add( 'visible' );
							observer.unobserve( entry.target );
						}
					} );
				},
				{ rootMargin: '0px 0px -40px' }
			)
			: null;

	AW.revealPosts = function ( root = document ) {
		root.querySelectorAll( '.blog__block-post:not(.visible)' ).forEach( ( post, index ) => {
			if ( observer ) {
				post.style.transitionDelay = `${ ( index % 4 ) * 60 }ms`;
				observer.observe( post );
			} else {
				post.classList.add( 'visible' );
			}
		} );
	};

	AW.ready( () => AW.revealPosts() );
}() );
