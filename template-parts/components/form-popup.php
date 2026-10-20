<?php
/**
 * Popup with the contact form, opened by any .form-popup-button.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="form-popup" class="popup-overlay" role="dialog" aria-modal="true" aria-label="Contact form" hidden>
	<div class="popup-content">
		<button type="button" id="close-popup" class="close-button" aria-label="Close"></button>
		<?php get_template_part( 'template-parts/components/contact-form' ); ?>
	</div>
</div>
