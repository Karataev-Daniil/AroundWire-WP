<?php
/**
 * Contact Form 7 form. 'form' => 'home' uses the home page form, anything else the landing form.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_form_id = aw_setting( 'home' === ( $args['form'] ?? '' ) ? 'cf7_home_form' : 'cf7_landing_form' );
?>
<div class="form">
	<?php
	if ( '' !== $aw_form_id ) {
		echo do_shortcode( sprintf( '[contact-form-7 id="%s"]', esc_attr( $aw_form_id ) ) );
	}
	?>
</div>
