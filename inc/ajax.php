<?php
/**
 * Public AJAX endpoints of the blog: "load more" and the topic filter.
 *
 * Both are read-only, but still check a nonce and cast every parameter.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_aw_load_more_posts', 'aw_ajax_load_more_posts' );
add_action( 'wp_ajax_nopriv_aw_load_more_posts', 'aw_ajax_load_more_posts' );

/**
 * Next page of the blog archive grid.
 *
 * Request: page (already shown), offset (cards already on the page).
 * Response: { html, hasMore }.
 */
function aw_ajax_load_more_posts(): void {
	check_ajax_referer( 'aw_blog', 'nonce' );

	$per_page = 6;
	$page     = absint( $_POST['page'] ?? 1 ) + 1;
	$offset   = absint( $_POST['offset'] ?? 0 );

	$query = new WP_Query(
		array(
			'post_type'      => 'blog',
			'post_status'    => 'publish',
			'paged'          => $page,
			'posts_per_page' => $per_page,
		)
	);

	ob_start();
	aw_render_post_cards( $query, static fn( int $position ): array => aw_load_more_card_args( $offset + $position ) );

	wp_send_json_success(
		array(
			'html'    => ob_get_clean(),
			'hasMore' => $page < $query->max_num_pages,
		)
	);
}

add_action( 'wp_ajax_aw_filter_posts', 'aw_ajax_filter_posts' );
add_action( 'wp_ajax_nopriv_aw_filter_posts', 'aw_ajax_filter_posts' );

/**
 * One page of the "Articles by topic" grid.
 *
 * Request: tag_id (0 for all topics), page, width (viewport width, picks the page size).
 * Response: { html, currentPage, totalPages }.
 */
function aw_ajax_filter_posts(): void {
	check_ajax_referer( 'aw_blog', 'nonce' );

	$tag_id = absint( $_POST['tag_id'] ?? 0 );
	$page   = max( 1, absint( $_POST['page'] ?? 1 ) );
	$query  = aw_topic_query( $tag_id, $page, aw_topic_posts_per_page( absint( $_POST['width'] ?? 1200 ) ) );

	ob_start();

	if ( $query->have_posts() ) {
		aw_render_post_cards( $query, array( 'post_class' => 'default-post' ) );
	} else {
		get_template_part( 'template-parts/blog/no-results', null, array( 'variant' => 'error' ) );
	}

	wp_send_json_success(
		array(
			'html'        => ob_get_clean(),
			'currentPage' => $page,
			'totalPages'  => (int) $query->max_num_pages,
		)
	);
}
