/**
 * Home page: "Grow With Us" video controls, profession cards highlighted while
 * scrolling on phones, and the steps row that becomes a carousel below 1300px.
 */
( function () {
	'use strict';

	const { AW } = window;

	function initVideo() {
		const video = document.getElementById( 'video' );
		const play = document.getElementById( 'playPauseButton' );
		const mute = document.getElementById( 'muteButton' );
		const controls = document.querySelector( '.video-controls' );

		if ( ! video || ! play || ! mute || ! controls ) {
			return;
		}

		let hideTimer;

		const setIcon = ( button, src ) => {
			const image = button.querySelector( 'img' );

			image.classList.add( 'hide' );
			window.setTimeout( () => {
				image.src = src;
				image.classList.remove( 'hide' );
			}, 300 );
		};

		const showControls = () => {
			controls.classList.remove( 'hidden' );
			window.clearTimeout( hideTimer );

			if ( window.innerWidth <= 440 ) {
				hideTimer = window.setTimeout( () => ! video.paused && controls.classList.add( 'hidden' ), 2000 );
			}
		};

		play.addEventListener( 'click', () => {
			if ( video.paused ) {
				video.play();
			} else {
				video.pause();
			}

			showControls();
		} );

		video.addEventListener( 'play', () => setIcon( play, play.dataset.iconPause ) );
		video.addEventListener( 'pause', () => setIcon( play, play.dataset.iconPlay ) );

		mute.addEventListener( 'click', () => {
			video.muted = ! video.muted;
			setIcon( mute, video.muted ? mute.dataset.iconUnmute : mute.dataset.iconMute );
		} );

		video.addEventListener( 'click', showControls );
	}

	/**
	 * On phones the card closest to the top of the viewport gets the "hover" look.
	 */
	function initProfessionCards() {
		const cards = Array.from( document.querySelectorAll( '.profession-card' ) );
		const phone = window.matchMedia( '(max-width: 499px)' );

		if ( ! cards.length || ! ( 'IntersectionObserver' in window ) ) {
			return;
		}

		const visible = new Set();
		const observer = new IntersectionObserver(
			( entries ) => {
				entries.forEach( ( entry ) => ( entry.isIntersecting ? visible.add( entry.target ) : visible.delete( entry.target ) ) );

				const active = phone.matches ? cards.find( ( card ) => visible.has( card ) ) : null;

				cards.forEach( ( card ) => card.classList.toggle( 'hover', card === active ) );
			},
			{ rootMargin: '-100px 0px -100px' }
		);

		cards.forEach( ( card ) => observer.observe( card ) );
	}

	AW.ready( () => {
		initVideo();
		initProfessionCards();

		const steps = document.querySelector( '[data-steps-slider]' );

		if ( steps ) {
			AW.responsiveSlider( steps, '(max-width: 1299px)', {
				type: 'loop',
				autoWidth: true,
				autoplay: true,
				interval: 2000,
			} );
		}
	} );
}() );
