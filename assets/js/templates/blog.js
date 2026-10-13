/**
 * Blog pages: search panel, tag lists, "Articles by topic" filter,
 * and on single posts the table of contents, image alts, outbound links and the copy-link button.
 */
( function () {
	'use strict';

	const { AW, awBlog } = window;

	function post( action, data ) {
		const body = new URLSearchParams( { action, nonce: awBlog.nonce, ...data } );

		return fetch( awBlog.ajaxUrl, { method: 'POST', credentials: 'same-origin', body } )
			.then( ( response ) => response.json() )
			.then( ( json ) => {
				if ( ! json.success ) {
					throw new Error( `${ action } failed` );
				}

				return json.data;
			} );
	}

	AW.blogRequest = post;

	/**
	 * Search panel under the blog menu.
	 */
	function initSearch() {
		const panel = document.getElementById( 'searchPanel' );
		const field = panel?.querySelector( '.search-field' );
		const clear = panel?.querySelector( '[data-search-clear]' );

		if ( ! panel || ! field ) {
			return;
		}

		const setOpen = ( open ) => {
			panel.classList.toggle( 'is-open', open );
			document.querySelectorAll( '[data-search-toggle]' ).forEach( ( button ) => button.setAttribute( 'aria-expanded', String( open ) ) );

			if ( open ) {
				window.setTimeout( () => field.focus(), 50 );
			}
		};

		const update = () => {
			const filled = field.value.trim() !== '';

			panel.classList.toggle( 'has-content', filled );

			if ( clear ) {
				clear.hidden = ! filled;
			}
		};

		document.querySelectorAll( '[data-search-toggle]' ).forEach( ( button ) => {
			button.addEventListener( 'click', () => setOpen( ! panel.classList.contains( 'is-open' ) ) );
		} );

		field.addEventListener( 'input', update );

		clear?.addEventListener( 'click', () => {
			field.value = '';
			update();
			field.focus();
		} );

		panel.querySelector( 'form' )?.addEventListener( 'submit', ( event ) => {
			if ( ! field.value.trim() ) {
				event.preventDefault();
				field.focus();
			}
		} );

		setOpen( field.value.trim() !== '' );
		update();
	}

	/**
	 * Only the first two tags of a card are visible until "..." is clicked.
	 */
	AW.initTags = function ( root = document ) {
		root.querySelectorAll( '[data-tags]:not([data-tags-ready])' ).forEach( ( container ) => {
			const tags = Array.from( container.querySelectorAll( 'a' ) );
			const more = container.querySelector( '.show-more' );

			container.setAttribute( 'data-tags-ready', '' );

			if ( tags.length <= 2 || ! more ) {
				return;
			}

			tags.slice( 2 ).forEach( ( tag ) => ( tag.hidden = true ) );
			more.hidden = false;
			more.addEventListener( 'click', () => {
				tags.forEach( ( tag ) => ( tag.hidden = false ) );
				more.hidden = true;
			} );
		} );
	};

	/**
	 * "Articles by topic": tag buttons and numbered pages, loaded over AJAX.
	 */
	function initTopicFilter() {
		const container = document.getElementById( 'posts-container' );
		const controls = document.getElementById( 'pagination-controls' );

		if ( ! container || ! controls ) {
			return;
		}

		const prev = document.getElementById( 'prev-page' );
		const next = document.getElementById( 'next-page' );
		const numbers = document.getElementById( 'page-numbers' );
		const filters = Array.from( document.querySelectorAll( '.tag-filter' ) );
		const state = { tag: 0, page: 1, total: Number( controls.dataset.totalPages ) || 1 };
		let request = 0;

		const pageButton = ( page ) => {
			const button = document.createElement( 'button' );

			button.type = 'button';
			button.className = 'page-number button-small';
			button.textContent = String( page );

			if ( page === state.page ) {
				button.classList.add( 'active' );
				button.setAttribute( 'aria-current', 'page' );
			}

			button.addEventListener( 'click', () => load( state.tag, page ) );

			return button;
		};

		const renderPagination = () => {
			controls.hidden = state.total <= 1;
			controls.classList.toggle( 'visible', state.total > 1 );

			if ( state.total <= 1 ) {
				return;
			}

			prev.disabled = state.page <= 1;
			next.disabled = state.page >= state.total;

			const limit = window.innerWidth <= 701 ? 4 : 9;
			let start = Math.max( 1, state.page - Math.floor( limit / 2 ) );
			const end = Math.min( state.total, start + limit - 1 );

			start = Math.max( 1, end - limit + 1 );
			numbers.replaceChildren();

			for ( let page = start; page <= end; page++ ) {
				numbers.append( pageButton( page ) );
			}

			if ( end < state.total ) {
				const ellipsis = document.createElement( 'span' );

				ellipsis.className = 'ellipsis button-small';
				ellipsis.textContent = '…';
				numbers.append( ellipsis, pageButton( state.total ) );
			}
		};

		const load = ( tag, page ) => {
			const current = ++request;

			container.setAttribute( 'aria-busy', 'true' );

			post( 'aw_filter_posts', { tag_id: tag, page, width: window.innerWidth } )
				.then( ( data ) => {
					if ( current !== request ) {
						return;
					}

					Object.assign( state, { tag, page: data.currentPage, total: data.totalPages } );
					container.innerHTML = data.html;
					AW.revealPosts?.( container );
					AW.initTags( container );
					renderPagination();
				} )
				.catch( () => {} )
				.finally( () => container.removeAttribute( 'aria-busy' ) );
		};

		filters.forEach( ( filter ) => {
			filter.addEventListener( 'click', () => {
				filters.forEach( ( other ) => {
					other.classList.toggle( 'active', other === filter );
					other.setAttribute( 'aria-pressed', String( other === filter ) );
				} );
				load( Number( filter.dataset.termId ) || 0, 1 );
			} );
		} );

		prev.addEventListener( 'click', () => load( state.tag, state.page - 1 ) );
		next.addEventListener( 'click', () => load( state.tag, state.page + 1 ) );

		// The page size depends on the viewport width, so reload when it changes noticeably.
		let width = window.innerWidth;
		let timer;

		window.addEventListener( 'resize', () => {
			window.clearTimeout( timer );
			timer = window.setTimeout( () => {
				if ( Math.abs( window.innerWidth - width ) > 50 ) {
					width = window.innerWidth;
					load( state.tag, 1 );
				}
			}, 300 );
		} );

		// The server rendered the first page for a 1200px screen; match the real width.
		if ( window.innerWidth < 1025 ) {
			load( 0, 1 );
		} else {
			renderPagination();
		}
	}

	/**
	 * Single post: table of contents from the headings of the content.
	 */
	function initTableOfContents() {
		const nav = document.querySelector( '.content-navigation' );
		const list = document.getElementById( 'content-list' );
		const headings = document.querySelectorAll( '.blog-post-content :is(h2, h3, h4, h5, h6)' );

		if ( ! nav || ! list || ! headings.length ) {
			return;
		}

		const parents = {};

		headings.forEach( ( heading, index ) => {
			const level = Number( heading.tagName[ 1 ] );
			const item = document.createElement( 'li' );
			const link = document.createElement( 'a' );

			heading.id ||= `heading-${ index + 1 }`;
			link.href = `#${ heading.id }`;
			link.textContent = heading.textContent.trim();
			link.className = 'content-link link-small-underline';
			item.className = `level-${ level }`;
			item.append( link );

			let parent = list;

			for ( let up = level - 1; up >= 2; up-- ) {
				if ( parents[ up ] ) {
					parent = parents[ up ].querySelector( ':scope > ul' ) || parents[ up ].appendChild( document.createElement( 'ul' ) );
					break;
				}
			}

			parent.append( item );
			parents[ level ] = item;

			for ( let deeper = level + 1; deeper <= 6; deeper++ ) {
				delete parents[ deeper ];
			}
		} );

		list.addEventListener( 'click', ( event ) => {
			const link = event.target.closest( '.content-link' );
			const target = link && document.getElementById( link.hash.slice( 1 ) );

			if ( ! target ) {
				return;
			}

			event.preventDefault();
			window.scrollTo( { top: target.getBoundingClientRect().top + window.scrollY - window.innerHeight * 0.02, behavior: 'smooth' } );
			window.history.replaceState( null, '', link.hash );

			target.classList.add( 'highlighted' );
			window.setTimeout( () => {
				target.classList.add( 'removed' );
				window.setTimeout( () => target.classList.remove( 'highlighted', 'removed' ), 300 );
			}, 1000 );
		} );

		nav.hidden = false;
	}

	/**
	 * Single post: alt text for images without one, external links in a new tab.
	 */
	function initContent() {
		const content = document.querySelector( '.blog-post-content' );
		const title = document.querySelector( '.blog-post__title h1' )?.textContent.trim();

		if ( ! content ) {
			return;
		}

		content.querySelectorAll( 'img:not([alt]), img[alt=""]' ).forEach( ( image, index ) => {
			image.alt = `${ title } ${ index + 1 }`;
		} );

		content.querySelectorAll( 'a[href^="http"]' ).forEach( ( link ) => {
			if ( new URL( link.href ).host !== window.location.host ) {
				link.target = '_blank';
				link.rel = 'noopener';
			}
		} );
	}

	function initCopyLink() {
		const button = document.querySelector( '[data-copy-link]' );
		const message = document.querySelector( '.copy-message' );

		button?.addEventListener( 'click', () => {
			navigator.clipboard?.writeText( button.dataset.copyLink ).then( () => {
				if ( message ) {
					message.hidden = false;
					window.setTimeout( () => ( message.hidden = true ), 2000 );
				}
			} );
		} );
	}

	AW.ready( () => {
		initSearch();
		AW.initTags();
		initTopicFilter();
		initTableOfContents();
		initContent();
		initCopyLink();
	} );
}() );
