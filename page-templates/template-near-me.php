<?php
/**
 * Template Name: Near me Template
 *
 * "Jobs near me" page: intro, related job pages (found by the key from the
 * "Related jobs" box plus the custom-related-pages rows) and FAQs.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

get_header();

$aw_key     = (string) get_post_meta( get_the_ID(), '_related_jobs_key', true );
$aw_related = aw_related_job_pages( get_the_ID(), $aw_key );
$aw_faqs    = array();

if ( function_exists( 'have_rows' ) && have_rows( 'faqs' ) ) {
	while ( have_rows( 'faqs' ) ) {
		the_row();
		$aw_faqs[] = array(
			'title' => aw_string( aw_sub_field( 'title' ) ),
			'text'  => aw_string( aw_sub_field( 'text' ) ),
		);
	}
}
?>
<div class="hero-block__wrapper">
	<div class="container-xxsmall">
		<div class="hero-block">
			<h1 class="display-medium"><?php the_title(); ?></h1>
			<div class="content body-medium-medium">
				<?php the_content(); ?>
			</div>
			<button type="button" class="primary-button-medium button-medium form-popup-button">Apply Now</button>
		</div>

		<div class="divider"></div>

		<ul class="related-pages-list">
			<?php foreach ( $aw_related as $aw_page ) : ?>
				<?php
				$aw_title       = (string) get_post_meta( $aw_page->ID, 'custom_related_job_title', true );
				$aw_description = (string) get_post_meta( $aw_page->ID, 'description', true );
				?>
				<li>
					<h2 class="title-large"><?php echo esc_html( '' !== $aw_title ? $aw_title : get_the_title( $aw_page ) ); ?></h2>
					<?php if ( '' !== $aw_description ) : ?>
						<p class="body-medium-regular"><?php echo esc_html( $aw_description ); ?></p>
					<?php endif; ?>
					<div class="button-group">
						<button type="button" class="accent-button-small button-small form-popup-button">Quick Apply</button>
						<a class="secondary-button-small button-small" href="<?php echo esc_url( get_permalink( $aw_page ) ); ?>">View Page</a>
					</div>
				</li>
			<?php endforeach; ?>

			<?php while ( function_exists( 'have_rows' ) && have_rows( 'custom-related-pages' ) ) : ?>
				<?php the_row(); ?>
				<li>
					<h2 class="title-large"><?php aw_the_sub_field( 'title' ); ?></h2>
					<p class="body-medium-regular"><?php aw_the_sub_field( 'description' ); ?></p>
					<button type="button" class="accent-button-small button-small form-popup-button">Quick Apply</button>
				</li>
			<?php endwhile; ?>
		</ul>
	</div>
</div>

<?php if ( $aw_faqs ) : ?>
	<div class="faqs-block__wrapper">
		<div class="container-xxsmall">
			<div class="faqs-block">
				<h2 class="display-small">FAQs about <?php echo esc_html( $aw_key ); ?> Jobs</h2>
				<div class="faqs-list" data-accordion="independent" data-accordion-open data-icon-closed="<?php echo esc_attr( AW_ICON_PLUS ); ?>" data-icon-open="<?php echo esc_attr( AW_ICON_MINUS ); ?>">
					<?php foreach ( $aw_faqs as $aw_faq ) : ?>
						<div class="faq">
							<h3 class="title-medium faq-title" data-accordion-trigger>
								<?php echo wp_kses_post( $aw_faq['title'] ); ?>
								<img class="toggle-image" src="<?php echo esc_attr( AW_ICON_MINUS ); ?>" alt="" width="24" height="24">
							</h3>
							<div class="faq-content" data-accordion-panel>
								<p class="body-small-regular"><?php echo wp_kses_post( $aw_faq['text'] ); ?></p>
							</div>
							<div class="divider"></div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
		$aw_schema = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => array_map(
				static fn( array $faq ): array => array(
					'@type'          => 'Question',
					'name'           => wp_strip_all_tags( $faq['title'] ),
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => wp_strip_all_tags( $faq['text'] ),
					),
				),
				$aw_faqs
			),
		);

		wp_print_inline_script_tag( (string) wp_json_encode( $aw_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ), array( 'type' => 'application/ld+json' ) );
		?>
	</div>
<?php endif; ?>
<?php
get_footer();
