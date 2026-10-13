<?php
/**
 * Landing pages related to the current blog post.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_links = aw_related_pages( get_the_ID() );

if ( ! $aw_links ) {
	return;
}
?>
<div class="blog-related-pages">
	<p class="title-medium">Related Pages:</p>
	<?php foreach ( $aw_links as $aw_link ) : ?>
		<a class="<?php echo esc_attr( trim( 'link-small-underline ' . $aw_link['class'] ) ); ?>" href="<?php echo esc_url( $aw_link['url'] ); ?>"><?php echo esc_html( $aw_link['title'] ); ?></a>
	<?php endforeach; ?>
</div>
