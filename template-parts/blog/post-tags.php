<?php
/**
 * Tag links of the current post. On search results, tags matching the query are highlighted.
 * Only the first two tags are visible until "..." is clicked (assets/js/templates/blog.js).
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_tags = wp_get_post_terms( get_the_ID(), 'blog_tag' );

if ( ! $aw_tags || is_wp_error( $aw_tags ) ) {
	return;
}

$aw_query = is_search() ? get_search_query( false ) : '';
?>
<div class="post-tags" data-tags>
	<?php foreach ( $aw_tags as $aw_tag ) : ?>
		<?php $aw_match = '' !== $aw_query && false !== stripos( $aw_tag->name, $aw_query ); ?>
		<a class="body-small-medium <?php echo $aw_match ? 'highlight-tag' : 'tag-link'; ?>" href="<?php echo esc_url( (string) get_term_link( $aw_tag ) ); ?>"><?php echo esc_html( $aw_tag->name ); ?></a>
	<?php endforeach; ?>
	<button type="button" class="body-small-medium show-more" hidden>...</button>
</div>
