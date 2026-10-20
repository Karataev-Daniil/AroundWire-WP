<?php
/**
 * Expand clientele, 6 versions: statistics picture and text.
 *
 * v1 is designed to sit inside success steps v1 ('nested' => true). Used on its own,
 * it gets its own section and container.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_number = (int) $args['number'];

$aw_picture = static function (): void {
	?>
	<div class="statistics-cards">
		<img src="<?php echo aw_url_field( 'expand-clientele__pic' ); ?>" alt="<?php echo aw_attr_field( 'expand-clientele__title' ); ?> look at the statistics">
	</div>
	<?php
};

$aw_text = static function (): void {
	?>
	<div class="text-statistics">
		<div class="label-large"<?php echo aw_style( array( 'color' => aw_color_field( 'expand-clientele__tagline_color' ) ) ); ?>>
			<?php aw_the_field( 'expand-clientele__tagline' ); ?>
		</div>
		<h2 class="display-medium"<?php echo aw_style( array( 'color' => aw_color_field( 'expand-clientele__title_color' ) ) ); ?>>
			<?php aw_the_field( 'expand-clientele__title' ); ?>
		</h2>
		<p class="body-medium-medium"<?php echo aw_style( array( 'color' => aw_color_field( 'expand-clientele__text_color' ) ) ); ?>>
			<?php aw_the_field( 'expand-clientele__text' ); ?>
		</p>
	</div>
	<?php
};

$aw_second_bg = aw_style( array( 'background-color' => aw_color_field( 'expand-clientele-block_color-two' ) ) );
?>

<?php if ( 1 === $aw_number ) : ?>
	<?php if ( empty( $args['nested'] ) ) : ?>
		<section class="<?php echo esc_attr( $args['classes'] ); ?>">
			<div class="container">
	<?php endif; ?>

	<div class="expand-clientele v1">
		<div class="block-wrapper"<?php echo $aw_second_bg; ?>>
			<?php $aw_picture(); ?>
			<?php $aw_text(); ?>
		</div>
	</div>

	<?php if ( empty( $args['nested'] ) ) : ?>
			</div>
		</section>
	<?php endif; ?>
<?php else : ?>
	<section class="<?php echo esc_attr( $args['classes'] ); ?>"<?php echo 5 === $aw_number ? '' : aw_style( array( 'background-color' => aw_color_field( 'expand-clientele-block_color' ) ) ); ?>>
		<div class="<?php echo 5 === $aw_number ? 'container-medium' : 'container'; ?>">
			<div class="expand-clientele">
				<?php if ( 3 === $aw_number ) : ?>
					<div class="expand-clientele__block"<?php echo $aw_second_bg; ?>>
				<?php endif; ?>

				<?php
				// v3–v5 show the picture first, the others after the text.
				if ( in_array( $aw_number, array( 3, 4, 5 ), true ) ) {
					$aw_picture();
					$aw_text();
				} else {
					$aw_text();
					$aw_picture();
				}
				?>

				<?php if ( 3 === $aw_number ) : ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>
