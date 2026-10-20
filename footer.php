<?php
/**
 * Footer menu, the form popup and the closing tags.
 *
 * Landings show social links in the top row; other pages show a call to action
 * there and move social links to a bottom row.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_landing = aw_is_landing();
$aw_variant = $aw_landing ? aw_version_from_field( 'footer_menu_version', 5 ) : 'default';
$aw_links   = array(
	'Explore Marketplace' => 'https://aroundwire.com/marketplace?view=grid',
	'Privacy Policy'      => 'https://aroundwire.com/privacy-policy',
	'Terms of Service'    => 'https://aroundwire.com/terms-of-service',
);

get_template_part( 'template-parts/components/form-popup' );
?>
<footer>
	<nav aria-label="Footer menu" class="footer-menu__wrapper <?php echo esc_attr( $aw_variant ); ?>">
		<div class="container">
			<ul class="footer-menu">
				<li class="footer-menu_flex-top no-link">
					<ul class="sub-menu">
						<li class="footer-menu__block no-link">
							<ul class="sub-menu">
								<li class="footer-menu__block__item body-medium-regular no-link">
									<p>Grow Your Business with AroundWire<br> Find Jobs Faster, Get Gigs Effortlessly!</p>
								</li>
								<li class="footer-menu__logo">
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Be a Pro with AroundWire and Get More Clients</a>
								</li>
							</ul>
						</li>
						<li class="footer-menu__block">
							<ul class="sub-menu">
								<?php foreach ( $aw_links as $aw_label => $aw_url ) : ?>
									<li class="footer-menu__block__item link-medium-default nofollow menu-item">
										<a href="<?php echo esc_url( $aw_url ); ?>" rel="nofollow"><?php echo esc_html( $aw_label ); ?></a>
									</li>
								<?php endforeach; ?>
								<?php if ( aw_is_blog_context() ) : ?>
									<li class="footer-menu__block__item link-medium-default menu-item">
										<a href="<?php echo esc_url( home_url( '/sitemap/' ) ); ?>">Sitemap</a>
									</li>
								<?php endif; ?>
							</ul>
						</li>
						<li class="footer-menu__block">
							<ul class="sub-menu">
								<?php if ( $aw_landing ) : ?>
									<?php get_template_part( 'template-parts/footer/social' ); ?>
								<?php else : ?>
									<li class="footer-menu__block__item body-medium-regular no-link">
										<p>Start Building Your Professional Presence on AroundWire.</p>
									</li>
									<li class="footer-menu__block__item button-larger nofollow">
										<button type="button" class="noLink link-button form-popup-button">List your services today!</button>
									</li>
								<?php endif; ?>
							</ul>
						</li>
					</ul>
				</li>
				<?php if ( ! $aw_landing ) : ?>
					<li class="footer-menu_flex-bottom no-link">
						<ul class="sub-menu">
							<?php get_template_part( 'template-parts/footer/social' ); ?>
						</ul>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</nav>
</footer>
<?php wp_footer(); ?>
</body>
</html>
