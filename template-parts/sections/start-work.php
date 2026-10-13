<?php
/**
 * Start work, 7 versions: the contact form next to a list of steps.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_number = (int) $args['number'];
$aw_icons  = array( 'icon-start-work-v1', 'icon-start-work-v2', 'icon-start-work-v3' );
$aw_bg     = in_array( $aw_number, array( 4, 6, 7 ), true ) ? array() : array( 'background-color' => aw_color_field( 'start-work-block_color' ) );
?>
<section class="<?php echo esc_attr( $args['classes'] ); ?>"<?php echo aw_style( $aw_bg ); ?>>
	<div class="<?php echo 6 === $aw_number ? 'container-medium' : 'container'; ?>">
		<div class="start-work">
			<div class="start-work__block-form">
				<?php get_template_part( 'template-parts/components/contact-form' ); ?>
			</div>
			<div class="start-work__block">
				<h2 class="display-medium"<?php echo aw_style( array( 'color' => aw_color_field( 'start-work__title_color' ) ) ); ?>><?php aw_the_field( 'start-work__title' ); ?></h2>
				<?php
				$aw_index = 0;

				while ( function_exists( 'have_rows' ) && have_rows( 'start-work__element' ) ) :
					the_row();
					$aw_icon = aw_inline_svg( '2024/12/' . $aw_icons[ $aw_index % count( $aw_icons ) ] . '.svg' );
					++$aw_index;
					?>
					<div class="start-work__element"<?php echo aw_style( array( 'background-color' => aw_color_field( 'start-work__element_color', true ) ) ); ?>>
						<?php echo $aw_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised by aw_inline_svg(). ?>
						<div class="start-work__content">
							<h3 class="title-larger"<?php echo aw_style( array( 'color' => aw_color_field( 'start-work__element-title_color', true ) ) ); ?>><?php aw_the_sub_field( 'start-work__element-title' ); ?></h3>
							<p class="body-medium-medium"<?php echo aw_style( array( 'color' => aw_color_field( 'start-work__element-text_color', true ) ) ); ?>><?php aw_the_sub_field( 'start-work__element-text' ); ?></p>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		</div>

		<?php if ( 5 === $aw_number ) : ?>
			<div class="background-element">
				<div class="ball-border">
					<div class="ball"></div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
