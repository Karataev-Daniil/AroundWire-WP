<?php
/**
 * Three most viewed blog posts.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_query = new WP_Query(
	array(
		'post_type'      => 'blog',
		'posts_per_page' => 3,
		'no_found_rows'  => true,
		'meta_key'       => AW_VIEWS_META, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'orderby'        => 'meta_value_num',
		'order'          => 'DESC',
	)
);
?>
<div class="blog__three-columns-popular">
	<h2 class="title-larger">Popular Articles</h2>
	<?php if ( ! aw_render_post_cards( $aw_query ) ) : ?>
		<p>No popular posts yet.</p>
	<?php endif; ?>
</div>
