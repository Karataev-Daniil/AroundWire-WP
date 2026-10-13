<?php
/**
 * Success steps, 8 versions: a heading and numbered step cards.
 *
 * In v1 the expand-clientele v1 block can sit inside this section's container
 * ('nested_expand' => true, see aw_sections_are_paired()).
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_number = (int) $args['number'];
$aw_bg     = 7 === $aw_number ? array() : array( 'background-color' => aw_color_field( 'success-steps-block_color' ) );

$aw_container = 7 === $aw_number ? 'container-medium' : 'container';
$aw_numbered  = in_array( $aw_number, array( 2, 5, 6, 7, 8 ), true );
$aw_two_digit = in_array( $aw_number, array( 2, 7, 8 ), true );
$aw_framed    = in_array( $aw_number, array( 7, 8 ), true );
$aw_alt       = aw_attr_field( 'success-steps-title' );
?>
<section class="<?php echo esc_attr( $args['classes'] ); ?>"<?php echo aw_style( $aw_bg ); ?>>
	<div class="<?php echo esc_attr( $aw_container ); ?>">
		<div class="success-steps">
			<div class="success-steps__info<?php echo 7 === $aw_number ? ' container-xxsmall' : ''; ?>">
				<div class="label-large"<?php echo aw_style( array( 'color' => aw_color_field( 'success-steps-tagline_color' ) ) ); ?>><?php aw_the_field( 'success-steps-tagline' ); ?></div>
				<h2 class="display-medium"<?php echo aw_style( array( 'color' => aw_color_field( 'success-steps-title_color' ) ) ); ?>><?php aw_the_field( 'success-steps-title' ); ?></h2>
				<p class="body-medium-medium"<?php echo aw_style( array( 'color' => aw_color_field( 'success-steps-text_color' ) ) ); ?>><?php aw_the_field( 'success-steps-text' ); ?></p>
			</div>

			<div class="success-steps__block">
				<?php if ( 7 === $aw_number ) : ?>
					<div class="success-steps__images">
						<img src="<?php echo aw_url_field( 'success-steps-block_decorative-element' ); ?>" alt="<?php echo $aw_alt; ?>">
					</div>
				<?php endif; ?>

				<?php if ( $aw_framed ) : ?>
					<div class="success-steps__cards">
				<?php endif; ?>

				<?php
				$aw_step = 0;

				while ( function_exists( 'have_rows' ) && have_rows( 'success-steps__card-info' ) ) :
					the_row();
					++$aw_step;

					$aw_pic   = aw_url_field( 'success-steps__card-info__pic', true );
					$aw_title = aw_style( array( 'color' => aw_color_field( 'success-steps__card-info__title_color', true ) ) );
					$aw_card  = 4 === $aw_number
						? array( 'background-image' => aw_css_url( $aw_pic ) )
						: array( 'background-color' => aw_color_field( 'success-steps__card-info_color', true ) );
					?>
					<div class="card-info"<?php echo aw_style( $aw_card ); ?>>
						<?php if ( 1 === $aw_number ) : ?>
							<div<?php echo aw_style( array( 'background-image' => aw_css_url( $aw_pic ) ) ); ?>></div>
						<?php else : ?>
							<div class="<?php echo in_array( $aw_number, array( 5, 6, 7, 8 ), true ) ? 'display-large card-number' : 'title-large'; ?>"<?php echo in_array( $aw_number, array( 2, 3, 5 ), true ) ? aw_style( array( 'background-image' => aw_css_url( $aw_pic ) ) ) : ''; ?>>
								<?php
								if ( $aw_numbered ) {
									echo esc_html( $aw_two_digit ? str_pad( (string) $aw_step, 2, '0', STR_PAD_LEFT ) : (string) $aw_step );
								}
								?>
							</div>
						<?php endif; ?>

						<?php if ( $aw_framed ) : ?>
							<div>
						<?php endif; ?>

						<?php if ( 6 === $aw_number ) : ?>
							<div class="card-title">
								<img src="<?php echo $aw_pic; ?>" alt="<?php echo aw_attr_field( 'success-steps__card-info__title', true ); ?>">
								<h3 class="title-larger"<?php echo $aw_title; ?>><?php aw_the_sub_field( 'success-steps__card-info__title' ); ?></h3>
							</div>
						<?php else : ?>
							<h3 class="title-large"<?php echo $aw_title; ?>><?php aw_the_sub_field( 'success-steps__card-info__title' ); ?></h3>
						<?php endif; ?>

						<p class="body-small-medium"<?php echo aw_style( array( 'color' => aw_color_field( 'success-steps__card-info__text_color', true ) ) ); ?>>
							<?php aw_the_sub_field( 'success-steps__card-info__text' ); ?>
						</p>

						<?php if ( $aw_framed ) : ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>

				<?php if ( $aw_framed ) : ?>
					</div>
				<?php endif; ?>

				<?php if ( 8 === $aw_number ) : ?>
					<div class="success-steps__images">
						<img src="<?php echo aw_url_field( 'success-steps-block_decorative-element' ); ?>" alt="<?php echo $aw_alt; ?>">
					</div>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( 6 === $aw_number ) : ?>
			<div class="background-element">
				<div class="ball-border">
					<div class="ball"></div>
				</div>
			</div>
		<?php endif; ?>

		<?php
		if ( ! empty( $args['nested_expand'] ) ) {
			aw_render_section( 'expand-clientele', 'v1', array( 'nested' => true ) );
		}
		?>
	</div>
</section>
