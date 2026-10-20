<?php
/**
 * Top of the blog archive: the latest "top" post large, the next five small.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_query = new WP_Query(
	array(
		'post_type'      => 'blog',
		'posts_per_page' => 6,
		'no_found_rows'  => true,
		'category_name'  => 'top',
	)
);
?>
<div class="blog__hero-block">
	<?php
	$aw_rendered = aw_render_post_cards(
		$aw_query,
		static fn( int $position ): array => 1 === $position
			? array(
				'post_class'   => 'big-post',
				'show_excerpt' => true,
				'title_style'  => 'title-largest',
				'title_tag'    => 'h2',
			)
			: array( 'post_class' => 'small-posts' )
	);

	if ( ! $aw_rendered ) {
		echo '<p>No posts found.</p>';
	}
	?>
</div>
