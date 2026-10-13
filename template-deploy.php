<?php
/**
 * Template Name: deploy
 *
 * "Under maintenance" placeholder page.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="deploy-page">
	<div class="deploy-page-info">
		<h1 class="display-small">Under Maintenance</h1>
		<p class="body-medium-medium">The page you’re looking for is currently under maintenance and will be back soon.</p>
	</div>
	<img src="<?php echo esc_url( aw_media_url( '2024/11/Frame-15.svg' ) ); ?>" alt="">
</div>
<?php
get_footer();
