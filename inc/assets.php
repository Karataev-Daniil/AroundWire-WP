<?php
/**
 * Front-end assets. Everything is enqueued before wp_head, per template.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueues a theme stylesheet if the file exists. Handles get the aw- prefix.
 *
 * @param string[] $deps Dependencies.
 */
function aw_enqueue_style( string $handle, string $relative, array $deps = array() ): void {
	if ( aw_asset_exists( $relative ) ) {
		wp_enqueue_style( 'aw-' . $handle, aw_asset_uri( $relative ), $deps, aw_asset_version( $relative ) );
	}
}

/**
 * Enqueues a deferred theme script in the footer if the file exists.
 *
 * @param string[] $deps Dependencies.
 */
function aw_enqueue_script( string $handle, string $relative, array $deps = array( 'aw-core' ) ): void {
	if ( aw_asset_exists( $relative ) ) {
		wp_enqueue_script(
			'aw-' . $handle,
			aw_asset_uri( $relative ),
			$deps,
			aw_asset_version( $relative ),
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}
}

/**
 * Whether the current request is part of the blog (shares the blog header and styles).
 */
function aw_is_blog_context(): bool {
	return is_search()
		|| is_post_type_archive( 'blog' )
		|| is_singular( 'blog' )
		|| is_tax( 'blog_tag' )
		|| is_page_template( 'page-templates/template-sitemap.php' );
}

add_action( 'wp_enqueue_scripts', 'aw_enqueue_assets' );

/**
 * Global assets, then assets of the current template.
 */
function aw_enqueue_assets(): void {
	wp_register_style( 'aw-splide', aw_asset_uri( 'assets/vendor/splide/splide-core.min.css' ), array(), '4.1.4' );
	wp_register_script(
		'aw-splide',
		aw_asset_uri( 'assets/vendor/splide/splide.min.js' ),
		array(),
		'4.1.4',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	// Layout goes before buttons and typography, as in the original theme: their rules must win over its resets.
	foreach ( array( 'reset', 'fonts', 'palette', 'layout', 'buttons', 'typography' ) as $base ) {
		aw_enqueue_style( $base, "assets/css/base/{$base}.css" );
	}

	aw_enqueue_style( 'contact-form', 'assets/css/components/contact-form.css' );
	aw_enqueue_style( 'form-popup', 'assets/css/components/form-popup.css' );

	aw_enqueue_script( 'core', 'assets/js/core/core.js', array() );
	aw_enqueue_script( 'contact-form', 'assets/js/components/contact-form.js' );
	aw_enqueue_script( 'form-popup', 'assets/js/components/form-popup.js' );
	aw_enqueue_script( 'tracking', 'assets/js/components/form-tracking.js' );

	wp_add_inline_style( 'aw-layout', aw_inline_css() );

	if ( is_page_template( 'page-templates/template-home.php' ) ) {
		aw_enqueue_home_assets();
	} elseif ( aw_is_landing() ) {
		aw_enqueue_landing_assets();
	} elseif ( is_page_template( 'page-templates/template-near-me.php' ) ) {
		aw_enqueue_style( 'near-me', 'assets/css/templates/near-me.css' );
	} elseif ( is_page_template( 'template-deploy.php' ) ) {
		aw_enqueue_style( 'deploy', 'assets/css/templates/deploy.css' );
	} elseif ( is_404() ) {
		aw_enqueue_style( 'error-404', 'assets/css/templates/error-404.css' );
	}

	if ( is_page_template( 'page-templates/template-sitemap.php' ) || is_page_template( 'page-templates/template-sitemap-near-me.php' ) ) {
		aw_enqueue_style( 'sitemap', 'assets/css/templates/sitemap.css' );
	}

	if ( aw_is_blog_context() ) {
		aw_enqueue_blog_assets();
	}
}

/**
 * Home page.
 */
function aw_enqueue_home_assets(): void {
	wp_enqueue_style( 'aw-splide' );
	aw_enqueue_style( 'home', 'assets/css/templates/home.css' );
	aw_enqueue_style( 'post-card', 'assets/css/components/post-card.css' );
	aw_enqueue_style( 'scroll-to-top', 'assets/css/components/scroll-to-top.css' );

	aw_enqueue_script( 'post-card', 'assets/js/components/post-card.js' );
	aw_enqueue_script( 'scroll-to-top', 'assets/js/components/scroll-to-top.js' );
	aw_enqueue_script( 'thank-you-popup', 'assets/js/components/thank-you-popup.js' );
	aw_enqueue_script( 'section-reviews', 'assets/js/sections/reviews.js', array( 'aw-core', 'aw-splide' ) );
	aw_enqueue_script( 'home', 'assets/js/templates/home.js', array( 'aw-core', 'aw-splide' ) );
}

/**
 * Landing template: shared layout styles plus the chosen version of every section.
 */
function aw_enqueue_landing_assets(): void {
	aw_enqueue_style( 'landing-menu', 'assets/css/layout/landing-menu.css' );
	aw_enqueue_style( 'landing-footer', 'assets/css/layout/landing-footer.css' );

	$sections = aw_sections();

	foreach ( aw_landing_layout( get_queried_object_id() ) as $key => $version ) {
		aw_enqueue_style( "section-{$key}", "assets/css/sections/{$key}/common.css" );
		aw_enqueue_style( "section-{$key}-{$version}", "assets/css/sections/{$key}/{$version}.css" );

		if ( ! empty( $sections[ $key ]['script'] ) ) {
			$script = $sections[ $key ]['script'];
			wp_enqueue_style( 'aw-splide' );
			aw_enqueue_script( "section-{$script}", "assets/js/sections/{$script}.js", array( 'aw-core', 'aw-splide' ) );
		}
	}

	// "Let’s meet" button: Brevo booking popup.
	wp_enqueue_style( 'aw-brevo', 'https://meetings.brevo.com/assets/styles/popup.css', array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	wp_enqueue_script( 'aw-brevo', 'https://meetings.brevo.com/assets/libs/popup.min.js', array(), null, array( 'in_footer' => true ) ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
}

/**
 * Blog archive, single posts, tags, search and the sitemap page.
 */
function aw_enqueue_blog_assets(): void {
	aw_enqueue_style( 'blog', 'assets/css/templates/blog.css' );
	aw_enqueue_style( 'search', 'assets/css/templates/search.css' );
	aw_enqueue_style( 'post-card', 'assets/css/components/post-card.css' );

	aw_enqueue_script( 'post-card', 'assets/js/components/post-card.js' );
	aw_enqueue_script( 'blog', 'assets/js/templates/blog.js' );

	wp_localize_script(
		'aw-blog',
		'awBlog',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'aw_blog' ),
		)
	);

	if ( is_post_type_archive( 'blog' ) ) {
		aw_enqueue_script( 'load-more', 'assets/js/templates/blog-load-more.js', array( 'aw-core', 'aw-blog' ) );
	}

	if ( is_singular( 'blog' ) ) {
		aw_enqueue_style( 'scroll-to-top', 'assets/css/components/scroll-to-top.css' );
		aw_enqueue_script( 'scroll-to-top', 'assets/js/components/scroll-to-top.js' );
	}
}

/**
 * CSS custom properties: the logo for every page, plus the section colours of
 * the home and landing pages, all validated before output.
 */
function aw_inline_css(): string {
	$vars = array(
		'--menu-logo-black' => aw_css_url( aw_media_url( '2024/07/logo_black.svg' ) ),
	);

	if ( aw_is_landing() || is_page_template( 'page-templates/template-home.php' ) ) {
		$colors = array(
			'--menu-color'                      => 'main-menu_color',
			'--hero-block-color'                => 'hero-block_color',
			'--hero-block-color-two'            => 'hero-block_color-two',
			'--benefits-block-color'            => 'benefits-block_color',
			'--benefits-icon-color-basic'       => 'benefit_icon_basic_color',
			'--benefits-icon-color-second'      => 'benefit_icon_second_color',
			'--success-steps-background-color'  => 'success-steps-block_color',
			'--success-steps-number-color'      => 'card-number-color',
			'--expand-clientele-background-color' => 'expand-clientele-block_color',
			'--more-views-block-color'          => 'more-views-block_color',
			'--start-work-block-color'          => 'start-work-block_color',
			'--start-work-icon-color-basic'     => 'start-work__icon__basic-color',
			'--start-work-icon-color-second'    => 'start-work__icon__second-color',
			'--faqs-background-color'           => 'faqs-block_color',
			'--fitback-block-color'             => 'fitback-block_color',
			'--fitback-block-color-2'           => 'fitback-block_color_2',
			'--fitback-text-color'              => 'fitback-text_color',
			'--footer-menu-color'               => 'footer-menu_color',
		);

		foreach ( $colors as $var => $field ) {
			$vars[ $var ] = aw_color_field( $field );
		}

		$vars['--menu-logo'] = aw_css_url( aw_url_field( 'main-menu_logo' ) );
	}

	$declarations = array();

	foreach ( $vars as $var => $value ) {
		if ( '' !== $value ) {
			$declarations[] = $var . ': ' . $value . ';';
		}
	}

	return ':root {' . implode( ' ', $declarations ) . '}';
}
