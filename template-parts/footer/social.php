<?php
/**
 * Social links and copyright, two list items of the footer menu.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_social = array(
	'facebook-logo'  => array( 'facebook', 'https://www.facebook.com/aroundwire' ),
	'Instagram-logo' => array( 'Instagram', 'https://www.instagram.com/aroundwire' ),
	'twitter-logo'   => array( 'twitter', 'https://twitter.com/aroundwire' ),
);
?>
<li class="footer-menu__mass-media no-link">
	<ul class="sub-menu">
		<?php foreach ( $aw_social as $aw_class => [ $aw_label, $aw_url ] ) : ?>
			<li class="<?php echo esc_attr( $aw_class ); ?>">
				<a href="<?php echo esc_url( $aw_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $aw_label ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</li>
<li class="footer-copyright body-small-regular no-link">
	<p>© 2016 – <?php echo esc_html( gmdate( 'Y' ) ); ?> • AroundWire All Rights Reserved</p>
</li>
