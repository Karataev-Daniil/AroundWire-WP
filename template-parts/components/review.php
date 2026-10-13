<?php
/**
 * One video review.
 *
 * Arguments: review (see aw_reviews()), class (wrapper classes),
 * compact (photo, name and profession only, no video).
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_review = $args['review'];
?>
<div class="<?php echo esc_attr( $args['class'] ?? 'item-reviews' ); ?>">
	<?php if ( ! empty( $args['compact'] ) ) : ?>
		<img src="<?php echo esc_url( $aw_review['image'] ); ?>" loading="lazy" alt="<?php echo esc_attr( $aw_review['name'] ); ?>">
		<div>
			<h3><?php echo esc_html( $aw_review['name'] ); ?></h3>
			<p><?php echo esc_html( $aw_review['profession'] ); ?></p>
		</div>
	<?php else : ?>
		<button type="button" class="block-icon" data-video="<?php echo esc_url( $aw_review['video'] ); ?>" aria-label="<?php echo esc_attr( 'Watch the review by ' . $aw_review['name'] ); ?>">
			<img class="icon-reviews" src="<?php echo esc_url( $aw_review['image'] ); ?>" alt="<?php echo esc_attr( $aw_review['name'] ); ?>" loading="lazy">
			<img class="push-button" src="<?php echo esc_url( aw_media_url( '2024/08/Play-button.svg' ) ); ?>" alt="" loading="lazy">
		</button>
		<h3 class="title-larger"><?php echo esc_html( $aw_review['name'] ); ?></h3>
		<div class="uppercase-small">I’m a <?php echo esc_html( $aw_review['profession'] ); ?></div>
		<p class="body-medium-regular"><?php echo esc_html( $aw_review['text'] ); ?></p>
	<?php endif; ?>
</div>
