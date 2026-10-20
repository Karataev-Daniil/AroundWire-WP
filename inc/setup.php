<?php
/**
 * Theme supports, feeds, uploads and response headers.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'aw_setup' );

/**
 * Theme supports.
 */
function aw_setup(): void {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
}

/*
 * Feeds are not used: their links are removed from <head> and the URLs return 404.
 */
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'rsd_link' );

add_action( 'template_redirect', 'aw_disable_feeds' );

/**
 * Answers feed requests with a 404.
 */
function aw_disable_feeds(): void {
	if ( ! is_feed() ) {
		return;
	}

	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	nocache_headers();
}

add_filter( 'upload_mimes', 'aw_allow_svg_uploads' );

/**
 * SVG uploads for administrators only: an SVG can carry scripts.
 *
 * @param array<string, string> $mimes Allowed types.
 * @return array<string, string>
 */
function aw_allow_svg_uploads( array $mimes ): array {
	if ( current_user_can( 'manage_options' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}

	return $mimes;
}

add_action( 'send_headers', 'aw_security_headers' );

/**
 * Basic hardening headers.
 */
function aw_security_headers(): void {
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'X-Content-Type-Options: nosniff' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
}

add_filter( 'excerpt_more', 'aw_excerpt_more' );

/**
 * Ellipsis instead of the default "[…]".
 */
function aw_excerpt_more(): string {
	return '...';
}

add_action( 'wp_head', 'aw_js_class', 1 );

/**
 * Marks the document as scripted before the first paint, so styles that wait
 * for the deferred scripts (e.g. post cards fading in) never flash.
 */
function aw_js_class(): void {
	wp_print_inline_script_tag( "document.documentElement.classList.add('aw-js');" );
}
