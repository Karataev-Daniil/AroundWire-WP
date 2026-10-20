<?php
/**
 * Posts of the current blog tag.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_term  = get_queried_object();
$aw_query = new WP_Query(
	array(
		'post_type'      => 'blog',
		'posts_per_page' => 12,
		'no_found_rows'  => true,
		'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'blog_tag',
				'field'    => 'term_id',
				'terms'    => $aw_term instanceof WP_Term ? $aw_term->term_id : 0,
			),
		),
	)
);
?>
<div class="blog__three-columns">
	<h2 class="title-larger">Posts Related to “<?php single_term_title(); ?>”</h2>
	<?php if ( ! aw_render_post_cards( $aw_query ) ) : ?>
		<p>No posts found.</p>
	<?php endif; ?>
</div>
