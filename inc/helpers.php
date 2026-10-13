<?php
/**
 * Small helpers shared by templates and modules.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

/*
 * Plus and minus icons of the FAQ accordions (24×24 PNG). The scripts swap them on toggle.
 */
const AW_ICON_PLUS  = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAGhJREFUSIljYBgFo4CuwMDIpN7AyKSeFD0spChmZGRsgDIbidXDRIoF5IChbwEjLgkDI5N6pDDHC/7//99w4dwZrPFCcx+QBAyNTf8bGpv+J0XP0I9kmltAUk7+//9/A43cMQpGAR4AANzJEfOOAm+9AAAAAElFTkSuQmCC';
const AW_ICON_MINUS = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAADZJREFUSIljYBgFo2AUjAKCgBGXhKGx6XUGBgYNokz5z7Dp/LnT/tikmMhz1ygYBaNgFFAVAAA5ZQYE1i79SgAAAABJRU5ErkJggg==';

/**
 * URL of a theme file, relative to the theme root.
 */
function aw_asset_uri( string $relative ): string {
	return AW_URI . '/' . ltrim( $relative, '/' );
}

/**
 * Cache-busting version: the file's modification time, or the theme version.
 */
function aw_asset_version( string $relative ): string {
	$file = AW_DIR . '/' . ltrim( $relative, '/' );

	return is_readable( $file ) ? (string) filemtime( $file ) : AW_VERSION;
}

/**
 * Whether a theme file exists.
 */
function aw_asset_exists( string $relative ): bool {
	return is_readable( AW_DIR . '/' . ltrim( $relative, '/' ) );
}

/**
 * URL of a file in the media library uploads folder, e.g. aw_media_url( '2024/08/Play-button.svg' ).
 */
function aw_media_url( string $path ): string {
	return trailingslashit( wp_get_upload_dir()['baseurl'] ) . ltrim( $path, '/' );
}

/**
 * ACF get_field() that degrades to null when ACF is inactive.
 *
 * @param string    $name    Field name.
 * @param int|false $post_id Post ID, or false for the current post.
 * @return mixed
 */
function aw_field( string $name, $post_id = false ) {
	return function_exists( 'get_field' ) ? get_field( $name, $post_id ) : null;
}

/**
 * ACF get_sub_field() that degrades to null when ACF is inactive.
 *
 * @return mixed
 */
function aw_sub_field( string $name ) {
	return function_exists( 'get_sub_field' ) ? get_sub_field( $name ) : null;
}

/**
 * Field value as a string, or an empty string for arrays and missing values.
 */
function aw_string( $value ): string {
	return is_scalar( $value ) ? (string) $value : '';
}

/**
 * Prints a text field that may contain simple markup (line breaks, bold text, links).
 */
function aw_the_field( string $name, bool $sub = false ): void {
	$value = $sub ? aw_sub_field( $name ) : aw_field( $name );
	echo wp_kses_post( aw_string( $value ) );
}

/**
 * Same as aw_the_field() for repeater sub fields.
 */
function aw_the_sub_field( string $name ): void {
	aw_the_field( $name, true );
}

/**
 * Plain text value of a field for attributes such as alt.
 */
function aw_attr_field( string $name, bool $sub = false ): string {
	$value = $sub ? aw_sub_field( $name ) : aw_field( $name );

	return esc_attr( wp_strip_all_tags( aw_string( $value ) ) );
}

/**
 * URL value of an image or file field. Accepts both "URL" and "array" return formats.
 */
function aw_url_field( string $name, bool $sub = false ): string {
	$value = $sub ? aw_sub_field( $name ) : aw_field( $name );

	if ( is_array( $value ) ) {
		$value = $value['url'] ?? '';
	}

	return esc_url( aw_string( $value ) );
}

/**
 * Validates a CSS colour coming from the colour picker or an options field.
 *
 * Allows hex colours, rgb(), rgba(), hsl(), hsla() and the transparent keyword.
 */
function aw_css_color( $value ): string {
	$value = trim( aw_string( $value ) );

	if ( '' === $value ) {
		return '';
	}

	if ( preg_match( '/^#(?:[0-9a-f]{3,4}|[0-9a-f]{6}|[0-9a-f]{8})$/i', $value ) ) {
		return $value;
	}

	if ( preg_match( '/^(?:rgba?|hsla?)\(\s*[0-9.,%\s\/]+\)$/i', $value ) ) {
		return $value;
	}

	return 'transparent' === strtolower( $value ) ? 'transparent' : '';
}

/**
 * Colour field value, validated.
 */
function aw_color_field( string $name, bool $sub = false ): string {
	return aw_css_color( $sub ? aw_sub_field( $name ) : aw_field( $name ) );
}

/**
 * CSS url() for a background image, or an empty string.
 */
function aw_css_url( string $url ): string {
	$url = esc_url_raw( $url );

	return '' === $url ? '' : "url('" . $url . "')";
}

/**
 * Builds a style attribute from declarations, skipping empty values.
 *
 * Values must already be validated (aw_css_color, aw_css_url).
 *
 * @param array<string, string> $declarations Property => value.
 * @return string Attribute with a leading space, or an empty string.
 */
function aw_style( array $declarations ): string {
	$parts = array();

	foreach ( $declarations as $property => $value ) {
		if ( '' !== $value ) {
			$parts[] = $property . ': ' . $value;
		}
	}

	return $parts ? ' style="' . esc_attr( implode( '; ', $parts ) . ';' ) . '"' : '';
}

/**
 * Version number from a version key: 'v3' => 3.
 */
function aw_version_number( string $version ): int {
	return (int) ltrim( $version, 'v' );
}

/**
 * Inline SVG stored in the uploads folder, sanitised and cached per request.
 *
 * @param string $path Path inside the uploads folder, e.g. '2024/12/icon-benefit-v1.svg'.
 */
function aw_inline_svg( string $path ): string {
	static $cache = array();

	if ( isset( $cache[ $path ] ) ) {
		return $cache[ $path ];
	}

	$base = wp_normalize_path( wp_get_upload_dir()['basedir'] );
	$file = wp_normalize_path( realpath( $base . '/' . ltrim( $path, '/' ) ) ?: '' );

	if ( '' === $file || ! str_starts_with( $file, $base . '/' ) || ! str_ends_with( strtolower( $file ), '.svg' ) ) {
		$cache[ $path ] = '';
		return '';
	}

	$cache[ $path ] = wp_kses( (string) file_get_contents( $file ), aw_svg_allowed_html() ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	return $cache[ $path ];
}

/**
 * Allowed tags and attributes for inline SVG icons.
 *
 * @return array<string, array<string, bool>>
 */
function aw_svg_allowed_html(): array {
	$common = array(
		'id'                => true,
		'class'             => true,
		'fill'              => true,
		'fill-rule'         => true,
		'clip-rule'         => true,
		'stroke'            => true,
		'stroke-width'      => true,
		'stroke-linecap'    => true,
		'stroke-linejoin'   => true,
		'stroke-miterlimit' => true,
		'opacity'           => true,
		'fill-opacity'      => true,
		'transform'         => true,
		'clip-path'         => true,
		'mask'              => true,
		'style'             => true,
	);

	return array(
		'svg'            => $common + array(
			'xmlns'       => true,
			'width'       => true,
			'height'      => true,
			'viewbox'     => true,
			'aria-hidden' => true,
			'role'        => true,
			'focusable'   => true,
		),
		'g'              => $common,
		'path'           => $common + array( 'd' => true ),
		'circle'         => $common + array(
			'cx' => true,
			'cy' => true,
			'r'  => true,
		),
		'ellipse'        => $common + array(
			'cx' => true,
			'cy' => true,
			'rx' => true,
			'ry' => true,
		),
		'rect'           => $common + array(
			'x'      => true,
			'y'      => true,
			'width'  => true,
			'height' => true,
			'rx'     => true,
			'ry'     => true,
		),
		'line'           => $common + array(
			'x1' => true,
			'y1' => true,
			'x2' => true,
			'y2' => true,
		),
		'polygon'        => $common + array( 'points' => true ),
		'polyline'       => $common + array( 'points' => true ),
		'defs'           => array(),
		'title'          => array(),
		'clippath'       => array( 'id' => true ),
		'mask'           => array(
			'id'        => true,
			'maskunits' => true,
			'x'         => true,
			'y'         => true,
			'width'     => true,
			'height'    => true,
		),
		'lineargradient' => array(
			'id'            => true,
			'x1'            => true,
			'y1'            => true,
			'x2'            => true,
			'y2'            => true,
			'gradientunits' => true,
		),
		'stop'           => array(
			'offset'       => true,
			'stop-color'   => true,
			'stop-opacity' => true,
		),
	);
}
