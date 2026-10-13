<?php
/**
 * <head> integrations, document titles and Open Graph images.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_head', 'aw_head_integrations', 1 );

/**
 * Google site verification, Google Tag Manager and the JivoSite widget.
 */
function aw_head_integrations(): void {
	$verification = aw_setting( 'site_verification' );
	$gtm          = aw_setting( 'gtm_id' );
	$jivo         = aw_setting( 'jivo_id' );

	if ( '' !== $verification ) {
		printf( '<meta name="google-site-verification" content="%s">' . "\n", esc_attr( $verification ) );
	}

	if ( '' !== $gtm ) {
		printf(
			"<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer',%s);</script>\n",
			wp_json_encode( $gtm )
		);
	}

	if ( '' !== $jivo ) {
		printf( '<script src="%s" async></script>' . "\n", esc_url( 'https://code.jivosite.com/widget/' . rawurlencode( $jivo ) ) );
	}
}

add_action( 'wp_body_open', 'aw_gtm_noscript' );

/**
 * Google Tag Manager fallback for visitors without JavaScript.
 */
function aw_gtm_noscript(): void {
	$gtm = aw_setting( 'gtm_id' );

	if ( '' !== $gtm ) {
		printf(
			'<noscript><iframe src="%s" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>',
			esc_url( 'https://www.googletagmanager.com/ns.html?id=' . rawurlencode( $gtm ) )
		);
	}
}

add_filter( 'pre_get_document_title', 'aw_document_title', 20 );

/**
 * Hand-written titles for blog listings, search and the 404 page.
 */
function aw_document_title( string $title ): string {
	if ( is_post_type_archive( 'blog' ) ) {
		return 'AroundWire Blog - Insights for Service Providers & Small Businesses';
	}

	if ( is_tax( 'blog_tag' ) ) {
		return single_term_title( '', false ) . ' Tag Articles - AroundWire';
	}

	if ( is_search() ) {
		return have_posts()
			? 'Stay Updated with the Latest Insights - Subscribe to Our Blog'
			: 'No Blogs Found - Stay Tuned for Updates';
	}

	if ( is_404() ) {
		return 'AroundWire';
	}

	return $title;
}

add_action( 'wp_head', 'aw_head_meta' );

/**
 * Description for search results and Open Graph tags for blog listings.
 */
function aw_head_meta(): void {
	if ( is_search() ) {
		$description = have_posts()
			? 'Get the latest tips, insights, and strategies to help your American business thrive. Subscribe to our blog for regular updates!'
			: 'It looks like there are no blogs available right now, but don’t worry! Check back soon as we regularly add new content!';

		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
		aw_og_image( aw_first_post_thumbnail( $GLOBALS['wp_query'] ) ?: aw_media_url( '2024/09/Small-illustrations.webp' ) );
		return;
	}

	if ( is_post_type_archive( 'blog' ) ) {
		printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( 'Blogs & Insights | AroundWire' ) );
		printf(
			'<meta property="og:description" content="%s">' . "\n",
			esc_attr( 'Stay updated with the latest insights on small business growth, service provider tips, and marketplace innovations at the AroundWire Blog.' )
		);
		aw_og_image( aw_media_url( '2024/11/blog_cover_image.webp' ) );
		return;
	}

	if ( is_tax( 'blog_tag' ) ) {
		$query = new WP_Query(
			array(
				'post_type'      => 'blog',
				'posts_per_page' => 1,
				'no_found_rows'  => true,
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'blog_tag',
						'field'    => 'term_id',
						'terms'    => get_queried_object_id(),
					),
				),
			)
		);

		aw_og_image( aw_first_post_thumbnail( $query ) );
	}
}

/**
 * Full-size thumbnail URL of the first post of a query.
 */
function aw_first_post_thumbnail( WP_Query $query ): string {
	$post = $query->posts[0] ?? null;

	return $post instanceof WP_Post ? (string) get_the_post_thumbnail_url( $post, 'full' ) : '';
}

/**
 * Prints og:image when a URL is given.
 */
function aw_og_image( string $url ): void {
	if ( '' !== $url ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $url ) );
	}
}
