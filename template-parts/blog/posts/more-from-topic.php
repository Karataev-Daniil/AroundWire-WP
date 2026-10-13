<?php
/**
 * Four other posts sharing a tag with the current post.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_tags = wp_get_post_terms( get_the_ID(), 'blog_tag', array( 'fields' => 'ids' ) );

if ( ! $aw_tags || is_wp_error( $aw_tags ) ) {
	return;
}

$aw_query = new WP_Query(
	array(
		'post_type'      => 'blog',
		'posts_per_page' => 4,
		'no_found_rows'  => true,
		'post__not_in'   => array( get_the_ID() ), // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
		'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'blog_tag',
				'field'    => 'term_id',
				'terms'    => $aw_tags,
			),
		),
	)
);

if ( ! $aw_query->have_posts() ) {
	return;
}
?>
<div class="blog__more-posts">
	<h2 class="title-larger">More From This Topic</h2>
	<?php aw_render_post_cards( $aw_query, array( 'title_tag' => 'span' ) ); ?>
</div>
