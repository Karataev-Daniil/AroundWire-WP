<?php
/**
 * All blog posts in the repeating 1 / 3 / 2 grid; "Load More" fetches the next six over AJAX.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_query = new WP_Query(
	array(
		'post_type'      => 'blog',
		'posts_per_page' => 6,
	)
);

$aw_has_more = $aw_query->max_num_pages > 1;
?>
<div id="custom-posts-container" class="posts-grid">
	<?php if ( ! aw_render_post_cards( $aw_query, 'aw_load_more_card_args' ) ) : ?>
		<p>No posts found.</p>
	<?php endif; ?>
</div>

<?php if ( $aw_has_more ) : ?>
	<button type="button" id="load-more" class="load-more tertiary-button-small button-small" data-page="1">Load More Articles</button>
<?php endif; ?>
