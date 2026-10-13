<?php
/**
 * "Articles by topic": tag filter and a paginated grid, both driven by blog.js over AJAX.
 * The first page is rendered here so the grid works without JavaScript.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_terms = get_terms(
	array(
		'taxonomy'   => 'blog_tag',
		'hide_empty' => true,
	)
);
$aw_query = aw_topic_query( 0, 1, aw_topic_posts_per_page( 1200 ) );
?>
<h2 class="title-larger">Articles by topic</h2>
<div class="all-post-tags post-tags" role="group" aria-label="Filter by topic">
	<button type="button" class="tag-filter body-small-medium active" data-term-id="0" aria-pressed="true">All</button>
	<?php if ( is_array( $aw_terms ) ) : ?>
		<?php foreach ( $aw_terms as $aw_term ) : ?>
			<button type="button" class="tag-filter body-small-medium" data-term-id="<?php echo esc_attr( (string) $aw_term->term_id ); ?>" aria-pressed="false"><?php echo esc_html( $aw_term->name ); ?></button>
		<?php endforeach; ?>
	<?php endif; ?>
</div>

<div class="blog__four-columns" id="posts-container" aria-live="polite">
	<?php
	if ( ! aw_render_post_cards( $aw_query, array( 'post_class' => 'default-post' ) ) ) {
		get_template_part( 'template-parts/blog/no-results', null, array( 'variant' => 'error' ) );
	}
	?>
</div>

<div class="blog-slider-controls controls-pagination" id="pagination-controls" data-total-pages="<?php echo esc_attr( (string) $aw_query->max_num_pages ); ?>" hidden>
	<button type="button" class="prev-page button-small" id="prev-page" disabled>&lt; Prev</button>
	<div class="page-numbers-container" id="page-numbers"></div>
	<button type="button" class="next-page button-small" id="next-page">Next &gt;</button>
</div>
