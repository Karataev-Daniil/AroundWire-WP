<?php
/**
 * Post content filters: typography classes and tidy headings.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'the_content', 'aw_add_typography_classes', 20 );

/**
 * Adds the design system classes to tags in post content.
 *
 * Tags inside a table get the smaller table styles.
 */
function aw_add_typography_classes( string $content ): string {
	if ( '' === $content || ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $content;
	}

	$general = array(
		'H2'     => 'title-large',
		'H3'     => 'title-medium',
		'H4'     => 'title-medium',
		'H5'     => 'title-medium',
		'P'      => 'body-medium-regular',
		'UL'     => 'body-medium-regular',
		'OL'     => 'body-medium-regular',
		'A'      => 'body-medium-regular',
		'B'      => 'body-medium-semibold',
		'STRONG' => 'body-medium-semibold',
	);

	$table = array(
		'B'      => 'body-small-semibold',
		'STRONG' => 'body-small-semibold',
		'TD'     => 'body-small-regular',
		'TH'     => 'body-small-semibold',
		'TR'     => 'table-row',
	);

	$processor   = new WP_HTML_Tag_Processor( $content );
	$table_depth = 0;

	while ( $processor->next_tag( array( 'tag_closers' => 'visit' ) ) ) {
		$tag = $processor->get_tag();

		if ( 'TABLE' === $tag ) {
			$table_depth += $processor->is_tag_closer() ? -1 : 1;
			continue;
		}

		if ( $processor->is_tag_closer() ) {
			continue;
		}

		$map = $table_depth > 0 ? $table : $general;

		if ( isset( $map[ $tag ] ) ) {
			$processor->add_class( $map[ $tag ] );
		}
	}

	return $processor->get_updated_html();
}

add_filter( 'the_content', 'aw_trim_headings', 30 );

/**
 * Removes stray whitespace inside h2–h5 on single posts, keeping inner markup.
 */
function aw_trim_headings( string $content ): string {
	if ( ! is_single() ) {
		return $content;
	}

	$content = (string) preg_replace( '/(<h[2-5]\b[^>]*>)\s+/i', '$1', $content );

	return (string) preg_replace( '/\s+(<\/h[2-5]>)/i', '$1', $content );
}
