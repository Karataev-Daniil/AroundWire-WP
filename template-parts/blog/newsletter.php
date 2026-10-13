<?php
/**
 * MailPoet newsletter form under the blog pages.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_form = absint( aw_setting( 'mailpoet_form' ) );

if ( ! $aw_form || ! shortcode_exists( 'mailpoet_form' ) ) {
	return;
}
?>
<div class="blogs-form">
	<div class="blogs-form__info">
		<h2 class="display-small">Subscribe to Never Miss New Articles</h2>
		<p class="body-large-regular">Stay updated with AroundWire and keep your hand on your industry’s pulse!</p>
	</div>
	<?php echo do_shortcode( '[mailpoet_form id="' . $aw_form . '"]' ); ?>
</div>
