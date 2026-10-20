<?php
/**
 * "Let’s meet" call to action that opens the Brevo booking popup, 4 versions.
 *
 * v1 is a plain button, the others add the heading from the "fitback-text" field.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_meeting = aw_setting( 'brevo_meeting_url' );
$aw_button  = static function ( string $label ) use ( $aw_meeting ): void {
	?>
	<a class="fitback-button primary-button-medium button-large" href="<?php echo esc_url( $aw_meeting ); ?>" data-brevo-meeting="<?php echo esc_url( $aw_meeting ); ?>" target="_blank" rel="noopener"><?php echo wp_kses_post( $label ); ?></a>
	<?php
};
?>
<?php if ( 1 === (int) $args['number'] ) : ?>
	<section class="<?php echo esc_attr( $args['classes'] ); ?>">
		<?php $aw_button( 'Let’s Meet' ); ?>
	</section>
<?php else : ?>
	<section class="<?php echo esc_attr( $args['classes'] ); ?>">
		<div class="container">
			<div class="fitback">
				<h2 class="display-small"><?php aw_the_field( 'fitback-text' ); ?></h2>
				<?php $aw_button( aw_string( aw_field( 'fitback-button-title' ) ) ); ?>
			</div>
		</div>
	</section>
<?php endif; ?>
