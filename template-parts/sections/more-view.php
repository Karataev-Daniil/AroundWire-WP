<?php
/**
 * More views, 7 versions: heading, text and a looping muted video in different positions.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_number = (int) $args['number'];

$aw_video = static function (): void {
	?>
	<video playsinline autoplay loop muted preload="auto">
		<source src="<?php echo aw_url_field( 'more-views__video' ); ?>" type="video/mp4">
		<source src="<?php echo esc_url( aw_media_url( '2024/08/aw_video.webm' ) ); ?>" type="video/webm">
		<source src="<?php echo esc_url( aw_media_url( '2024/08/aw_video.mp4' ) ); ?>" type="video/mp4">
	</video>
	<?php
};
?>
<section class="<?php echo esc_attr( $args['classes'] ); ?>"<?php echo 6 === $aw_number ? '' : aw_style( array( 'background-color' => aw_color_field( 'more-views-block_color' ) ) ); ?>>
	<?php if ( 4 === $aw_number ) : ?>
		<div class="more-views__video"><?php $aw_video(); ?></div>
	<?php endif; ?>

	<div class="<?php echo 6 === $aw_number ? 'container-xxsmall' : 'container'; ?>">
		<div class="more-views">
			<?php if ( in_array( $aw_number, array( 3, 4, 7 ), true ) ) : ?>
				<div class="more-views__video">
					<?php
					// v4 keeps the empty column; its video is full width above.
					if ( 4 !== $aw_number ) {
						$aw_video();
					}
					?>
				</div>
			<?php endif; ?>

			<div class="more-views__info">
				<h2 class="display-medium"<?php echo aw_style( array( 'color' => aw_color_field( 'more-views__title_color' ) ) ); ?>><?php aw_the_field( 'more-views__title' ); ?></h2>

				<?php
				if ( 1 === $aw_number ) {
					$aw_video();
				}
				?>

				<div class="title-large"<?php echo aw_style( array( 'color' => aw_color_field( 'more-views__tagline_color' ) ) ); ?>><?php aw_the_field( 'more-views__tagline' ); ?></div>
				<p class="body-medium-medium"<?php echo aw_style( array( 'color' => aw_color_field( 'more-views__text_color' ) ) ); ?>><?php aw_the_field( 'more-views__text' ); ?></p>
			</div>

			<?php if ( in_array( $aw_number, array( 1, 2, 5, 6 ), true ) ) : ?>
				<div class="more-views__video">
					<?php if ( 5 === $aw_number ) : ?>
						<div class="video-wrapper"><?php $aw_video(); ?></div>
					<?php else : ?>
						<?php $aw_video(); ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
