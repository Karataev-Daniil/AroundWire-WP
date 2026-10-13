<?php
/**
 * FAQs at the end of a blog post ("post-faqs" repeater) with FAQPage structured data.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_faqs = array();

while ( function_exists( 'have_rows' ) && have_rows( 'post-faqs' ) ) {
	the_row();
	$aw_faqs[] = array(
		'title' => aw_string( aw_sub_field( 'faq-title' ) ),
		'text'  => aw_string( aw_sub_field( 'faq-text' ) ),
	);
}

if ( ! $aw_faqs ) {
	return;
}
?>
<div class="faqs">
	<h2 class="title-large">FAQ</h2>
	<?php foreach ( $aw_faqs as $aw_faq ) : ?>
		<div class="faq-item">
			<h3 class="body-medium-semibold faq-title"><?php echo wp_kses_post( $aw_faq['title'] ); ?></h3>
			<div class="faq-content">
				<p class="body-medium-regular"><?php echo wp_kses_post( $aw_faq['text'] ); ?></p>
			</div>
		</div>
	<?php endforeach; ?>
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
