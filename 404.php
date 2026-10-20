<?php
/**
 * Not found page.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="error404__wrapper">
	<div class="error404-block">
		<img class="error404-block-img" src="<?php echo esc_url( aw_media_url( '2024/08/arrow_404_v2-1.svg' ) ); ?>" alt="">
		<div class="error404-block-info">
			<p class="display-largest">404</p>
			<h1 class="display-small">Sorry! This Page Isn’t Available</h1>
			<p class="body-medium-medium">The page you were looking for couldn’t be found.</p>
			<a class="button-small primary-button-small" href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to Home Page</a>
		</div>
	</div>
</div>
<?php
get_footer();
