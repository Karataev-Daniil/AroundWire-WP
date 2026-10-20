/**
 * Shared helpers of the theme, exposed as window.AW:
 * scroll lock, dialogs, accordions, the video modal and a Splide wrapper.
 *
 * Accordions and video buttons are wired up from data attributes:
 * - [data-accordion="exclusive|independent"] container, optional [data-accordion-open]
 *   (all items start open) and [data-icon-closed] / [data-icon-open] for the toggle image;
 *   inside it [data-accordion-trigger] followed by its [data-accordion-panel].
 * - [data-video="<embed url>"] opens #videoModal, [data-video-close] closes it.
 */
( function () {
	'use strict';

	const AW = {};
	let lockCount = 0;
	let scrollY = 0;

	AW.ready = function ( callback ) {
		if ( document.readyState === 'loading' ) {
			document.addEventListener( 'DOMContentLoaded', callback, { once: true } );
		} else {
			callback();
		}
	};

	AW.lockScroll = function () {
		if ( lockCount++ > 0 ) {
			return;
		}

		scrollY = window.scrollY;
		document.body.style.top = `-${ scrollY }px`;
		document.body.classList.add( 'no-scroll' );
	};

	AW.unlockScroll = function () {
		if ( lockCount === 0 || --lockCount > 0 ) {
			return;
		}

		document.body.classList.remove( 'no-scroll' );
		document.body.style.top = '';
		window.scrollTo( 0, scrollY );
	};

	/**
	 * Dialogs are hidden elements shown with the "is-open" class.
	 * Escape and a click on the backdrop close the topmost one.
	 */
	const openDialogs = [];

	AW.openDialog = function ( dialog ) {
		if ( ! dialog || openDialogs.includes( dialog ) ) {
			return;
		}

		dialog.hidden = false;
		dialog.classList.add( 'is-open' );
		dialog.awReturnFocus = document.activeElement;
		openDialogs.push( dialog );
		AW.lockScroll();

		const focusable = dialog.querySelector( 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])' );
		focusable?.focus( { preventScroll: true } );
		dialog.dispatchEvent( new CustomEvent( 'aw:open' ) );
	};

	AW.closeDialog = function ( dialog ) {
		const index = openDialogs.indexOf( dialog );

		if ( index === -1 ) {
			return;
		}

		openDialogs.splice( index, 1 );
		dialog.classList.remove( 'is-open' );
		dialog.hidden = true;
		AW.unlockScroll();
		dialog.awReturnFocus?.focus?.( { preventScroll: true } );
		dialog.dispatchEvent( new CustomEvent( 'aw:close' ) );
	};

	document.addEventListener( 'keydown', ( event ) => {
		if ( event.key === 'Escape' && openDialogs.length ) {
			AW.closeDialog( openDialogs[ openDialogs.length - 1 ] );
		}
	} );

	document.addEventListener( 'click', ( event ) => {
		const dialog = openDialogs[ openDialogs.length - 1 ];

		if ( dialog && event.target === dialog ) {
			AW.closeDialog( dialog );
		}
	} );

	/**
	 * Accordions.
	 */
	function setAccordionItem( container, trigger, open ) {
		const panel = trigger.nextElementSibling;
		const icon = trigger.querySelector( '.toggle-image' );
		const iconSrc = open ? container.dataset.iconOpen : container.dataset.iconClosed;

		trigger.setAttribute( 'aria-expanded', String( open ) );
		trigger.classList.toggle( 'active', open );
		trigger.parentElement.classList.toggle( 'show', open );
		panel.classList.toggle( 'show', open );
		panel.style.maxHeight = open ? `${ panel.scrollHeight }px` : '';

		if ( icon && iconSrc ) {
			icon.src = iconSrc;
		}
	}

	AW.initAccordion = function ( container ) {
		if ( container.awAccordion ) {
			return;
		}

		container.awAccordion = true;

		const exclusive = container.dataset.accordion === 'exclusive';
		const startOpen = container.hasAttribute( 'data-accordion-open' );
		const triggers = Array.from( container.querySelectorAll( '[data-accordion-trigger]' ) );

		triggers.forEach( ( trigger, index ) => {
			const panel = trigger.nextElementSibling;

			panel.id ||= `aw-panel-${ Math.random().toString( 36 ).slice( 2, 9 ) }`;
			trigger.setAttribute( 'role', 'button' );
			trigger.setAttribute( 'tabindex', '0' );
			trigger.setAttribute( 'aria-controls', panel.id );
			setAccordionItem( container, trigger, startOpen && ( ! exclusive || index === 0 ) );

			const toggle = () => {
				const open = trigger.getAttribute( 'aria-expanded' ) !== 'true';

				if ( exclusive && open ) {
					triggers.forEach( ( other ) => other !== trigger && setAccordionItem( container, other, false ) );
				}

				setAccordionItem( container, trigger, open );
			};

			trigger.addEventListener( 'click', toggle );
			trigger.addEventListener( 'keydown', ( event ) => {
				if ( event.key === 'Enter' || event.key === ' ' ) {
					event.preventDefault();
					toggle();
				}
			} );
		} );

		// Open panels need a new height after the layout changes.
		window.addEventListener( 'resize', () => {
			triggers.forEach( ( trigger ) => {
				if ( trigger.getAttribute( 'aria-expanded' ) === 'true' ) {
					trigger.nextElementSibling.style.maxHeight = `${ trigger.nextElementSibling.scrollHeight }px`;
				}
			} );
		} );
	};

	/**
	 * Video modal.
	 */
	AW.openVideo = function ( url ) {
		const modal = document.getElementById( 'videoModal' );
		const frame = document.getElementById( 'videoFrame' );

		if ( ! modal || ! frame ) {
			return;
		}

		const src = new URL( url, window.location.href );
		src.searchParams.set( 'autoplay', '1' );
		frame.src = src.toString();
		AW.openDialog( modal );
	};

	document.addEventListener( 'click', ( event ) => {
		const opener = event.target.closest( '[data-video]' );

		if ( opener ) {
			event.preventDefault();
			AW.openVideo( opener.dataset.video );
			return;
		}

		if ( event.target.closest( '[data-video-close]' ) ) {
			AW.closeDialog( document.getElementById( 'videoModal' ) );
		}
	} );

	AW.ready( () => {
		document.getElementById( 'videoModal' )?.addEventListener( 'aw:close', () => {
			document.getElementById( 'videoFrame' ).src = '';
		} );
	} );

	/**
	 * Mounts Splide on an element and returns the instance, or null without Splide.
	 */
	AW.slider = function ( element, options ) {
		if ( ! element || typeof window.Splide === 'undefined' ) {
			return null;
		}

		return new window.Splide( element, Object.assign( { arrows: false, pagination: false }, options ) ).mount();
	};

	/**
	 * Splide that only exists while the media query matches, e.g. a grid on desktop
	 * that becomes a swipeable row on phones.
	 */
	AW.responsiveSlider = function ( element, query, options ) {
		const media = window.matchMedia( query );
		let instance = null;

		const update = () => {
			if ( media.matches && ! instance ) {
				instance = AW.slider( element, options );
			} else if ( ! media.matches && instance ) {
				instance.destroy( true );
				instance = null;
				element.classList.remove( 'is-initialized', 'is-rendered', 'is-active', 'is-overflow' );
			}
		};

		media.addEventListener( 'change', update );
		update();
	};

	/**
	 * Pauses a Splide autoplay while the video modal is open.
	 */
	AW.pauseWithVideo = function ( instance ) {
		const modal = document.getElementById( 'videoModal' );
		const autoplay = instance?.Components?.Autoplay;

		if ( ! modal || ! autoplay ) {
			return;
		}

		modal.addEventListener( 'aw:open', () => autoplay.pause() );
		modal.addEventListener( 'aw:close', () => autoplay.play() );
	};

	AW.ready( () => {
		document.querySelectorAll( '[data-accordion]' ).forEach( AW.initAccordion );

		document
			.querySelectorAll( 'input:is([type="text"], [type="email"], [type="tel"], [type="url"], [type="search"], [type="number"]):not([maxlength])' )
			.forEach( ( input ) => input.setAttribute( 'maxlength', '140' ) );
	} );

	window.AW = AW;
}() );
