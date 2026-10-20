<?php
/**
 * "by Author • Month day, year" line of the current post.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="post-meta">
	<span class="body-small-regular">by <b class="link-small-underline"><?php echo esc_html( get_the_author() ); ?></b> • <?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
</div>
