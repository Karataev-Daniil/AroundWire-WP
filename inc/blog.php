<?php
/**
 * Blog: view counter, post card rendering and related landing pages.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

const AW_VIEWS_META = 'post_views_count';

add_action( 'template_redirect', 'aw_count_post_view' );

/**
 * Counts a view of a single blog post (used for "Popular Articles").
 */
function aw_count_post_view(): void {
	if ( ! is_singular( 'blog' ) || is_preview() ) {
		return;
	}

	$post_id = get_queried_object_id();
	$views   = (int) get_post_meta( $post_id, AW_VIEWS_META, true );

	update_post_meta( $post_id, AW_VIEWS_META, $views + 1 );
}

/**
 * Renders one post card for the current post in the loop.
 *
 * @param array{post_class?: string, show_excerpt?: bool, show_tags?: bool, title_style?: string, title_tag?: string} $args Card options.
 */
function aw_post_card( array $args = array() ): void {
	get_template_part( 'template-parts/components/post-card', null, $args );
}

/**
 * Renders every post of a query as a card, then restores the global post.
 *
 * @param WP_Query                                    $query Query to render.
 * @param array<string, mixed>|callable(int): array $args  Card options, or a callback that gets the 1-based position.
 * @return int Number of rendered posts.
 */
function aw_render_post_cards( WP_Query $query, $args = array() ): int {
	$position = 0;

	while ( $query->have_posts() ) {
		$query->the_post();
		++$position;
		aw_post_card( is_callable( $args ) ? $args( $position ) : $args );
	}

	wp_reset_postdata();

	return $position;
}

/**
 * Card options in the "load more" grid, which repeats every six posts:
 * one full-width post with an excerpt, three thirds, two halves.
 *
 * @param int $position 1-based position of the post in the whole list.
 * @return array{post_class: string, show_excerpt: bool, show_tags: bool, title_style: string}
 */
function aw_load_more_card_args( int $position ): array {
	$slot = ( $position - 1 ) % 6;

	if ( 0 === $slot ) {
		return array(
			'post_class'   => 'full-width',
			'show_excerpt' => true,
			'show_tags'    => false,
			'title_style'  => 'title-large',
		);
	}

	return array(
		'post_class'   => $slot <= 3 ? 'one-third' : 'half-width',
		'show_excerpt' => false,
		'show_tags'    => false,
		'title_style'  => 'title-medium',
	);
}

/**
 * Posts per page of the topic filter grid for a viewport width.
 */
function aw_topic_posts_per_page( int $width ): int {
	if ( $width < 440 ) {
		return 6;
	}

	if ( $width < 700 ) {
		return 4;
	}

	return $width < 1025 ? 6 : 8;
}

/**
 * Query for the topic filter grid.
 */
function aw_topic_query( int $tag_id, int $page, int $per_page ): WP_Query {
	$args = array(
		'post_type'      => 'blog',
		'posts_per_page' => $per_page,
		'paged'          => max( 1, $page ),
	);

	if ( $tag_id > 0 ) {
		$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'blog_tag',
				'field'    => 'term_id',
				'terms'    => $tag_id,
			),
		);
	}

	return new WP_Query( $args );
}

/**
 * Landing page title without the call to action, e.g.
 * "Plumber Jobs in Las Vegas – Apply Now!" => "Plumber Jobs in Las Vegas".
 */
function aw_short_landing_title( string $title ): string {
	foreach ( array( ' Apply Today!', ' Apply Now!', ' Get Your Customers Now!', ' Get Your Costumers Now!' ) as $phrase ) {
		if ( str_ends_with( $title, $phrase ) ) {
			$title = substr( $title, 0, -strlen( $phrase ) );
		}
	}

	$title = trim( (string) preg_replace( '/\s*[-–—]\s*$/u', '', $title ) );
	$city  = strpos( $title, 'Las Vegas' );

	return false === $city ? $title : substr( $title, 0, $city + strlen( 'Las Vegas' ) );
}

/**
 * Up to three landing pages related to a blog post, cached for 12 hours.
 *
 * Pages whose title matches the post tags (and their synonyms) come first,
 * then pages whose ACF text is most similar to the post content.
 *
 * @return array<int, array{url: string, title: string, class: string}>
 */
function aw_related_pages( int $post_id ): array {
	$cache_key = 'aw_related_pages_' . $post_id;
	$cached    = get_transient( $cache_key );

	if ( is_array( $cached ) ) {
		return $cached;
	}

	$limit    = 3;
	$links    = array();
	$synonyms = array(
		'Cleaners'                => array( 'cleaner', 'cleaners', 'cleaning' ),
		'Contractors'             => array( 'contractor', 'contractors', 'contracting' ),
		'Electricians'            => array( 'electrician', 'electricians', 'electric' ),
		'General Contractors'     => array( 'general contractor', 'general contractors', 'general contracting' ),
		'Handyman'                => array( 'handyman', 'handymen' ),
		'HVAC'                    => array( 'hvac', 'heating', 'cooling' ),
		'Landscapers'             => array( 'landscaper', 'landscapers', 'landscaping' ),
		'Locksmiths'              => array( 'locksmith', 'locksmiths' ),
		'Movers'                  => array( 'mover', 'movers', 'moving' ),
		'Plumbers'                => array( 'plumber', 'plumbers' ),
		'Roofers'                 => array( 'roofer', 'roofers', 'roofing' ),
		'Service Providers'       => array( 'service provider', 'service providers', 'services' ),
		'Solar'                   => array( 'solar', 'solar panel', 'solar panels' ),
		'Solar Panel Technicians' => array( 'solar panel technician', 'solar panel technicians', 'solar panel' ),
	);

	$terms = array();

	foreach ( wp_get_post_terms( $post_id, 'blog_tag', array( 'fields' => 'names' ) ) as $tag ) {
		$terms = array_merge( $terms, $synonyms[ $tag ] ?? array( $tag ) );
	}

	foreach ( array_unique( $terms ) as $term ) {
		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 5,
				's'              => $term,
			)
		);

		foreach ( $pages as $page ) {
			$url = (string) get_permalink( $page );

			if ( count( $links ) >= $limit ) {
				break 2;
			}

			if ( isset( $links[ $url ] ) || false !== stripos( $page->post_title, 'near me' ) ) {
				continue;
			}

			$links[ $url ] = array(
				'url'   => $url,
				'title' => aw_short_landing_title( $page->post_title ),
				'class' => '',
			);
		}
	}

	if ( count( $links ) < $limit && function_exists( 'get_fields' ) ) {
		$content = wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) );
		$similar = array();

		$landings = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => AW_LANDING_TEMPLATE, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);

		foreach ( $landings as $page ) {
			$url = (string) get_permalink( $page );

			if ( isset( $links[ $url ] ) ) {
				continue;
			}

			$best = 0.0;

			foreach ( (array) get_fields( $page->ID ) as $value ) {
				if ( is_string( $value ) && '' !== $value ) {
					similar_text( $content, wp_strip_all_tags( $value ), $percent );
					$best = max( $best, (float) $percent );
				}
			}

			if ( $best > 2 ) {
				$similar[] = array(
					'url'   => $url,
					'title' => aw_short_landing_title( $page->post_title ),
					'class' => 'similarity-' . (int) floor( $best ),
					'score' => $best,
				);
			}
		}

		usort( $similar, static fn( array $a, array $b ): int => $b['score'] <=> $a['score'] );

		foreach ( array_slice( $similar, 0, $limit - count( $links ) ) as $page ) {
			unset( $page['score'] );
			$links[ $page['url'] ] = $page;
		}
	}

	$links = array_values( $links );
	set_transient( $cache_key, $links, 12 * HOUR_IN_SECONDS );

	return $links;
}

add_action( 'save_post', 'aw_flush_related_pages_cache' );

/**
 * Drops cached related pages when posts or landing pages change.
 */
function aw_flush_related_pages_cache( int $post_id ): void {
	$type = get_post_type( $post_id );

	if ( 'blog' === $type ) {
		delete_transient( 'aw_related_pages_' . $post_id );
	} elseif ( 'page' === $type ) {
		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '\_transient\_aw\_related\_pages\_%' OR option_name LIKE '\_transient\_timeout\_aw\_related\_pages\_%'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	}
}

/**
 * "Jobs" landing pages for the home page catalog, with titles shortened to the trade:
 * "Plumber Jobs in Las Vegas – Apply Now!" => "Plumber".
 *
 * @return array<int, array{url: string, title: string}>
 */
function aw_job_pages(): array {
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			's'              => 'Jobs',
		)
	);

	$jobs = array();

	foreach ( $pages as $page ) {
		$title = $page->post_title;

		if ( ! str_contains( $title, 'Jobs' ) || str_contains( $title, 'Leads' ) ) {
			continue;
		}

		$short = preg_replace( '/\bJobs\b\s*|\s?in Las Vegas\s[–-]\s(?:Apply Now!|Apply Today!)$/iu', '', $title );

		$jobs[] = array(
			'url'   => (string) get_permalink( $page ),
			'title' => trim( (string) $short ),
		);
	}

	return $jobs;
}
