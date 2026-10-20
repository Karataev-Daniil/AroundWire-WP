<?php
/**
 * Blog menu: logo, search toggle and the blog link.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;
?>
<nav aria-label="Blog menu" class="main-menu blog-menu">
	<ul class="menu">
		<li class="header-menu__logo menu-item">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Be a Pro with AroundWire and Get More Clients</a>
		</li>
		<li class="header-menu__flex no-link menu-item">
			<ul class="sub-menu">
				<li class="menu-item">
					<button type="button" class="search-button" data-search-toggle aria-controls="searchPanel" aria-label="Search the blog"></button>
				</li>
				<li class="header-menu__blog menu-item">
					<a href="<?php echo esc_url( (string) get_post_type_archive_link( 'blog' ) ); ?>"<?php echo is_post_type_archive( 'blog' ) ? ' aria-current="page"' : ''; ?>>Blog</a>
				</li>
			</ul>
		</li>
	</ul>
</nav>
