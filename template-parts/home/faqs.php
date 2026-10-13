<?php
/**
 * Home page FAQs (category "home-page", or the latest FAQs when it does not exist),
 * with FAQPage structured data.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_args = array(
	'post_type'      => 'faqs',
	'posts_per_page' => 5,
	'no_found_rows'  => true,
);

if ( term_exists( 'home-page', 'faq_category' ) ) {
	$aw_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		array(
			'taxonomy' => 'faq_category',
			'field'    => 'slug',
			'terms'    => 'home-page',
		),
	);
}

$aw_faqs = get_posts( $aw_args );
?>
<section class="homepages__wrapper">
	<div class="container-xsmall">
		<div class="faqs">
			<h2 class="display-large"><?php aw_the_field( 'faqs-title' ); ?></h2>
			<p class="body-larger-medium"><?php aw_the_field( 'faqs-text' ); ?></p>
			<div class="faqs_flex" data-accordion="exclusive" data-icon-closed="<?php echo esc_url( aw_media_url( '2024/12/open-button.svg' ) ); ?>" data-icon-open="<?php echo esc_url( aw_media_url( '2024/12/close-button.svg' ) ); ?>">
				<?php if ( ! $aw_faqs ) : ?>
					<p>No FAQs found.</p>
				<?php endif; ?>

				<?php foreach ( $aw_faqs as $aw_faq ) : ?>
					<div class="faq-item">
						<h3 class="title-larger" data-accordion-trigger>
							<?php echo esc_html( get_the_title( $aw_faq ) ); ?>
							<img class="toggle-image" src="<?php echo esc_url( aw_media_url( '2024/12/open-button.svg' ) ); ?>" alt="" loading="lazy">
						</h3>
						<div class="body-medium-regular" data-accordion-panel>
							<?php echo wp_kses_post( apply_filters( 'the_content', $aw_faq->post_content ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core filter. ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php
	if ( $aw_faqs ) {
		$aw_schema = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => array_map(
				static fn( WP_Post $faq ): array => array(
					'@type'          => 'Question',
					'name'           => wp_strip_all_tags( get_the_title( $faq ) ),
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => wp_strip_all_tags( $faq->post_content ),
					),
				),
				$aw_faqs
			),
		);

		wp_print_inline_script_tag( (string) wp_json_encode( $aw_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ), array( 'type' => 'application/ld+json' ) );
	}
	?>
</section>
