<?php
/**
 * Video reviews: an autoplaying Splide carousel, each photo opens the video modal.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

aw_reviews_schema();
?>
<section class="buyer-stories__wrapper"<?php echo aw_style( array( 'background-color' => aw_color_field( 'buyer-stories_color' ) ) ); ?>>
	<div class="container">
		<div class="item-reviews splide" data-reviews-slider aria-label="Video reviews">
			<div class="splide__track">
				<div class="splide__list">
					<?php
					foreach ( aw_reviews() as $aw_review ) {
						get_template_part(
							'template-parts/components/review',
							null,
							array(
								'review' => $aw_review,
								'class'  => 'item-review splide__slide',
							)
						);
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
get_template_part( 'template-parts/components/video-modal' );
