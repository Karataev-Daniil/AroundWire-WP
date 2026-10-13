<?php
/**
 * Benefits, 8 versions. Cards become a Splide slider on small screens
 * (always in v1), see assets/js/sections/benefits.js.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_number  = (int) $args['number'];
$aw_bg      = aw_color_field( 'benefits-block_color' );
$aw_card_bg = aw_color_field( 'benefits__slider-block_color' );
$aw_wrapped = in_array( $aw_number, array( 5, 6 ), true );
$aw_alt     = aw_attr_field( 'benefits-title' );

// Slider mode: v1 always, v5/v6 below 650px, the rest below 1025px. v8 reuses the v7 slider styles.
if ( 1 === $aw_number ) {
	$aw_mode = 'always';
} elseif ( $aw_wrapped ) {
	$aw_mode = '650';
} else {
	$aw_mode = '1025';
}

$aw_slider_class = 'benefits__slider splide ' . $args['version'] . ( 8 === $aw_number ? ' v7' : '' );

$aw_icons = array( 'icon-benefit-v1', 'icon-benefit-v2', 'icon-benefit-v3', 'icon-benefit-v4', 'icon-benefit-v5', 'icon-benefit-v1' );
?>
<section class="<?php echo esc_attr( $args['classes'] ); ?>"<?php echo aw_style( array( 'background-color' => $aw_bg ) ); ?>>

	<?php if ( 5 === $aw_number ) : ?>
		<div class="border-element"<?php echo aw_style( array( 'background-color' => aw_color_field( 'hero-block_color' ) ) ); ?>></div>
	<?php endif; ?>

	<div class="container">
		<div class="benefits">
			<?php if ( 6 === $aw_number ) : ?>
				<div class="benefits__img">
					<div class="benefits__img-border">
						<img src="<?php echo aw_url_field( 'benefits__block_background-image' ); ?>" alt="<?php echo $aw_alt; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $aw_wrapped ) : ?>
				<div class="benefits__content"<?php echo aw_style( array( 'background-color' => $aw_bg ) ); ?>>
			<?php endif; ?>

			<h2 class="display-medium"<?php echo aw_style( array( 'color' => aw_color_field( 'benefits-title_color' ) ) ); ?>><?php aw_the_field( 'benefits-title' ); ?></h2>
			<p class="body-medium-medium"<?php echo aw_style( array( 'color' => aw_color_field( 'benefits-text_color' ) ) ); ?>><?php aw_the_field( 'benefits-text' ); ?></p>

			<div class="<?php echo esc_attr( $aw_slider_class ); ?>" data-slider="<?php echo esc_attr( $aw_mode ); ?>">
				<div class="splide__track">
					<div class="splide__list">
						<?php
						$aw_index = 0;

						while ( function_exists( 'have_rows' ) && have_rows( 'benefits__slider-block' ) ) :
							the_row();
							$aw_icon = aw_inline_svg( '2024/12/' . $aw_icons[ $aw_index % count( $aw_icons ) ] . '.svg' );
							++$aw_index;
							?>
							<div class="benefits__slider-block splide__slide"<?php echo in_array( $aw_number, array( 1, 5 ), true ) ? aw_style( array( 'background-color' => $aw_card_bg ) ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<?php
								if ( in_array( $aw_number, array( 2, 3, 4, 7 ), true ) ) :
									$aw_border = 3 === $aw_number ? aw_color_field( 'benefits__slider-block_border-color' ) : '';
									?>
									<div<?php echo aw_style( array( 'background-color' => $aw_card_bg, 'border' => '' === $aw_border ? '' : '1px solid ' . $aw_border ) ); ?>>
								<?php endif; ?>

								<?php echo $aw_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised by aw_inline_svg(). ?>

								<h3 class="title-larger"<?php echo aw_style( array( 'color' => aw_color_field( 'benefits__slider-title_color', true ) ) ); ?>>
									<?php aw_the_sub_field( 'benefits__slider-title' ); ?>
								</h3>
								<p class="body-medium-medium"<?php echo aw_style( array( 'color' => aw_color_field( 'benefits__slider-text_color', true ) ) ); ?>>
									<?php aw_the_sub_field( 'benefits__slider-text' ); ?>
								</p>

								<?php if ( in_array( $aw_number, array( 2, 3, 4, 7 ), true ) ) : ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endwhile; ?>
					</div>
				</div>
			</div>

			<?php if ( $aw_wrapped ) : ?>
				</div>
			<?php endif; ?>

			<?php if ( 5 === $aw_number ) : ?>
				<div class="benefits__image">
					<img src="<?php echo aw_url_field( 'benefits__block_background-image' ); ?>" alt="<?php echo $aw_alt; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
