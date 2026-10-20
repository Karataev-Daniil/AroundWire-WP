<?php
/**
 * Modal with a YouTube player, opened by any [data-video] button.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="videoModal" class="video-modal" role="dialog" aria-modal="true" aria-label="Video review" hidden>
	<button type="button" class="close" data-video-close aria-label="Close video"></button>
	<div class="video-container">
		<iframe id="videoFrame" title="Video review" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin"></iframe>
	</div>
</div>
