<?php
/**
 * Main menu: logo, phone and the "Join Us Today!" button that opens the form popup.
 *
 * The variant class picks the layout, the menu_version field (landings) picks the design.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

if ( is_page_template( 'page-templates/template-home.php' ) ) {
	$aw_variant = 'homepage-menu';
} elseif (
	aw_is_landing()
	|| is_404()
	|| is_page_template( 'page-templates/template-near-me.php' )
	|| is_page_template( 'page-templates/template-sitemap-near-me.php' )
) {
	$aw_variant = 'landing-and-error-menu';
} else {
	$aw_variant = 'default-menu';
}

$aw_design = aw_is_landing() ? aw_version_from_field( 'menu_version', 8 ) : 'default-menu';
$aw_phone  = aw_setting( 'phone' );
?>
<nav aria-label="Main menu" class="main-menu <?php echo esc_attr( $aw_variant . ' ' . $aw_design ); ?>">
	<ul class="menu">
		<li class="header-menu__logo menu-item">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"<?php echo is_front_page() ? ' aria-current="page"' : ''; ?>>Be a Pro with AroundWire and Get More Clients</a>
		</li>
		<li class="header-menu__flex no-link menu-item">
			<ul class="sub-menu">
				<?php if ( '' !== $aw_phone ) : ?>
					<li class="header-menu__number menu-item">
						<a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^\d+]/', '', $aw_phone ) ); ?>"><?php echo esc_html( aw_setting( 'phone_label' ) ); ?></a>
					</li>
				<?php endif; ?>
				<li class="header-menu__button nofollow menu-item">
					<button type="button" class="noLink link-button form-popup-button">Join Us Today!</button>
				</li>
			</ul>
		</li>
	</ul>
</nav>
