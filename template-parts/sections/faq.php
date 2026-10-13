<?php
/**
 * FAQ, 6 versions: four FAQs from the category named in the "faqs-category" field.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_number = (int) $args['number'];

if ( in_array( $aw_number, array( 2, 5 ), true ) ) {
	$aw_container = 'container-xxsmall';
} elseif ( 6 === $aw_number ) {
	$aw_container = 'container-small';
} else {
	$aw_container = 'container';
}

$aw_term = get_term_by( 'name', aw_string( aw_field( 'faqs-category' ) ), 'faq_category' );
$aw_faqs = $aw_term instanceof WP_Term
	? new WP_Query(
		array(
			'post_type'      => 'faqs',
			'posts_per_page' => 4,
			'no_found_rows'  => true,
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'faq_category',
					'field'    => 'term_id',
					'terms'    => $aw_term->term_id,
				),
			),
		)
	)
	: null;
?>
<section class="<?php echo esc_attr( $args['classes'] ); ?>"<?php echo 4 === $aw_number ? '' : aw_style( array( 'background-color' => aw_color_field( 'faqs-block_color' ) ) ); ?>>
	<div class="<?php echo esc_attr( $aw_container ); ?>">
		<div class="faqs__block" itemscope itemtype="https://schema.org/FAQPage">
			<div class="faqs__content">
				<div class="label-large faqs-tagline"<?php echo aw_style( array( 'color' => aw_color_field( 'faqs-tagline_color' ) ) ); ?>><?php aw_the_field( 'faqs-tagline' ); ?></div>
				<h2 class="display-medium faqs-title"<?php echo aw_style( array( 'color' => aw_color_field( 'faqs-title_color' ) ) ); ?>><?php aw_the_field( 'faqs-title' ); ?></h2>
			</div>
			<div class="faqs__items" data-accordion="exclusive" data-icon-closed="<?php echo esc_attr( AW_ICON_PLUS ); ?>" data-icon-open="<?php echo esc_attr( AW_ICON_MINUS ); ?>">
				<?php while ( $aw_faqs && $aw_faqs->have_posts() ) : ?>
					<?php $aw_faqs->the_post(); ?>
					<div class="faq-item"<?php echo aw_style( array( 'background-color' => aw_color_field( 'faq-item_color' ) ) ); ?> itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
						<h3 class="title-medium faq-title" itemprop="name" data-accordion-trigger>
							<?php echo esc_html( get_the_title() ); ?>
							<img class="toggle-image" src="<?php echo esc_attr( AW_ICON_PLUS ); ?>" alt="">
						</h3>
						<div class="body-small-regular faq-content" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer" data-accordion-panel>
							<div itemprop="text"><?php echo wp_kses_post( wpautop( get_the_content() ) ); ?></div>
						</div>
					</div>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		</div>
	</div>
</section>
