<?php
/**
 * Hero block, 7 versions.
 *
 * v1: preview images inside the text column (desktop + mobile);
 * v2–v6: preview column, v3 also paints the text column;
 * v7: full-width preview behind the text.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_number  = (int) $args['number'];
$aw_alt     = aw_attr_field( 'hero-block-title' );
$aw_bg      = aw_color_field( 'hero-block_color' );
$aw_tagline = aw_style( array( 'color' => aw_color_field( 'hero-block-tagline_color' ) ) );
$aw_text    = aw_style( array( 'color' => aw_color_field( 'hero-block-text_color' ) ) );

$aw_info = static function ( string $tagline_class, string $before_text = '' ) use ( $aw_tagline, $aw_text ): void {
	?>
	<div class="<?php echo esc_attr( $tagline_class ); ?>"<?php echo $aw_tagline; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built by aw_style(). ?>>
		<?php aw_the_field( 'hero-block-tagline' ); ?>
	</div>
	<h1 class="display-large"<?php echo $aw_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php aw_the_field( 'hero-block-title' ); ?>
	</h1>
	<?php echo $before_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by the caller. ?>
	<p class="body-large-medium"<?php echo $aw_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php aw_the_field( 'hero-block-text' ); ?>
	</p>
	<button type="button" class="noLink link-button nofollow primary-button-large button-large hero-block__button form-popup-button">
		<?php aw_the_field( 'hero-block-button' ); ?>
	</button>
	<?php
};

$aw_img = static function ( string $class, string $field, string $suffix ) use ( $aw_alt ): string {
	return sprintf(
		'<img class="%1$s" src="%2$s" alt="%3$s">',
		esc_attr( $class ),
		aw_url_field( $field ),
		esc_attr( $aw_alt . ' ' . $suffix )
	);
};
?>

<?php if ( 7 === $aw_number ) : ?>
	<section class="<?php echo esc_attr( $args['classes'] ); ?>">
		<div class="hero-block">
			<div class="hero-block__preview">
				<?php echo $aw_img( 'preview-pic-one', 'hero-block-pic', 'desktop' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="container">
				<div class="hero-block__info">
					<?php $aw_info( 'uppercase-small' ); ?>
				</div>
			</div>
		</div>
	</section>
<?php else : ?>
	<section class="<?php echo esc_attr( $args['classes'] ); ?>"<?php echo $aw_number <= 5 ? aw_style( array( 'background-color' => $aw_bg ) ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="container">
			<div class="hero-block">
				<div class="hero-block__info"<?php echo 3 === $aw_number ? aw_style( array( 'background-color' => $aw_bg ) ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php
					$aw_preview = '';

					if ( 1 === $aw_number ) {
						$aw_preview = '<div class="preview-block">'
							. $aw_img( 'preview-statistics', 'hero-block-pic', 'desktop' )
							. $aw_img( 'preview-statistics-mobile', 'hero-block-pic-mobile', 'mobile' )
							. '</div>';
					}

					$aw_info( 6 === $aw_number ? 'uppercase-small' : 'label-large', $aw_preview );
					?>
				</div>
				<div class="hero-block__preview">
					<?php
					echo $aw_img( 'pic-desktop preview-statistics', 'hero-block-pic', 'desktop' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

					if ( $aw_number >= 2 ) {
						echo $aw_img( 'pic-tablet preview-statistics-mobile', 'hero-block-pic-mobile', 'mobile' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}

					if ( in_array( $aw_number, array( 2, 3, 4, 6 ), true ) ) {
						echo $aw_img( 'pic-mobile preview-pic-more', 'hero-block-pic-more', 'more' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}

					if ( 6 === $aw_number ) {
						echo $aw_img( 'preview-pic-fore', 'hero-block-pic-fore', 'fore' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>
