<?php
/**
 * Blog search: results include blog posts, 12 per page, with highlighted matches.
 *
 * The search form submits to /blog/?s=…, which WordPress resolves as a search
 * (search.php) restricted by aw_search_post_types().
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

add_action( 'pre_get_posts', 'aw_search_post_types' );

/**
 * Searches regular posts and blog posts, 12 per page.
 */
function aw_search_post_types( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}

	$query->set( 'post_type', array( 'post', 'blog' ) );
	$query->set( 'posts_per_page', 12 );
}

/**
 * Escapes text and wraps every match of the search query in <span class="highlight">.
 */
function aw_highlight( string $text, string $query ): string {
	$text = esc_html( $text );

	if ( '' === $query ) {
		return $text;
	}

	return (string) preg_replace(
		'/(' . preg_quote( esc_html( $query ), '/' ) . ')/iu',
		'<span class="highlight">$1</span>',
		$text
	);
}

/**
 * The first sentence that contains the query, highlighted, plus up to three following sentences.
 */
function aw_search_excerpt( string $content, string $query ): string {
	if ( '' === $query ) {
		return '';
	}

	$sentences = preg_split( '/(?<=[.!?])\s+(?=[A-Z])/', wp_strip_all_tags( $content ) ) ?: array();

	foreach ( $sentences as $index => $sentence ) {
		if ( false === stripos( $sentence, $query ) ) {
			continue;
		}

		$html = '<p>' . aw_highlight( $sentence, $query ) . '</p>';

		foreach ( array_slice( $sentences, $index + 1, 3 ) as $next ) {
			$html .= '<p>' . esc_html( $next ) . '</p>';
		}

		return $html;
	}

	return '';
}

/**
 * Previous / next button of the search pagination. Disabled buttons have no link.
 */
function aw_pagination_button( string $url, string $class, string $label, bool $disabled ): void {
	if ( $disabled ) {
		printf(
			'<span class="pagination-btn %1$s button-small"><button class="button-small" type="button" disabled>%2$s</button></span>',
			esc_attr( $class ),
			esc_html( $label )
		);
		return;
	}

	printf(
		'<a href="%1$s" class="pagination-btn %2$s button-small">%3$s</a>',
		esc_url( $url ),
		esc_attr( $class ),
		esc_html( $label )
	);
}
