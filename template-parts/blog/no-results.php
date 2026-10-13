<?php
/**
 * Empty state of the search results ("search") and of the topic filter ("error").
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_search = 'search' === ( $args['variant'] ?? 'search' );
?>
<div class="no-results not-found">
	<img src="<?php echo esc_url( aw_media_url( '2024/09/Small-illustrations.webp' ) ); ?>" alt="" loading="lazy">
	<h2 class="title-medium"><?php echo $aw_search ? 'Oops, No Matches Found' : 'No Articles Yet'; ?></h2>
	<p class="body-small-regular">Check back soon! We’re constantly updating with new blogs to keep you informed.</p>
</div>
