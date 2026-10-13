/**
 * Blog archive: "Load More Articles" appends the next six posts.
 */
( function () {
	'use strict';

	const { AW } = window;

	AW.ready( () => {
		const button = document.getElementById( 'load-more' );
		const container = document.getElementById( 'custom-posts-container' );

		if ( ! button || ! container ) {
			return;
		}

		const label = button.textContent;

		button.addEventListener( 'click', () => {
			const page = Number( button.dataset.page ) || 1;

			button.disabled = true;
			button.textContent = 'Loading…';

			AW.blogRequest( 'aw_load_more_posts', {
				page,
				offset: container.querySelectorAll( '.blog__block-post' ).length,
			} )
				.then( ( data ) => {
					container.insertAdjacentHTML( 'beforeend', data.html );
					AW.revealPosts?.( container );
					AW.initTags?.( container );

					button.dataset.page = String( page + 1 );

					if ( ! data.hasMore ) {
						button.remove();
					}
				} )
				.catch( () => {} )
				.finally( () => {
					button.disabled = false;
					button.textContent = label;
				} );
		} );
	} );
}() );
