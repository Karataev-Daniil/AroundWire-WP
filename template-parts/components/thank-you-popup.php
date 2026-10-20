<?php
/**
 * "Thank you!" popup shown after a Contact Form 7 form is sent.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="thank-you-popup" class="thank-you-popup" role="dialog" aria-modal="true" aria-labelledby="thank-you-title" hidden>
	<div class="thank-you-popup-content">
		<button type="button" class="close-popup" aria-label="Close"><img src="<?php echo esc_url( aw_media_url( '2024/08/close-button.svg' ) ); ?>" alt=""></button>
		<img src="<?php echo esc_url( aw_media_url( '2024/08/thank-you-popup-img.svg' ) ); ?>" alt="" loading="lazy">
		<h2 class="display-small" id="thank-you-title">Thank you!</h2>
		<p class="body-medium-medium">Welcome to AroundWire, where small businesses come to succeed.</p>
		<a class="nofollow primary-button-small button-small" href="<?php echo esc_url( home_url( '/' ) ); ?>">Continue</a>
	</div>
</div>
