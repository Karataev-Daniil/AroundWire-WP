<?php
/**
 * Template Name: Homepages 2
 *
 * Home page: hero, trade catalog, benefits, steps, video, app, reviews with the
 * contact form, FAQs, latest blog posts and video reviews.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

get_header();

$aw_join_button = static function ( string $label, string $size = 'larger' ): void {
	printf(
		'<button type="button" class="noLink link-button nofollow button-%1$s primary-button-%1$s form-popup-button">%2$s</button>',
		esc_attr( $size ),
		wp_kses_post( $label )
	);
};
?>
<section class="homepages__wrapper">
	<div class="container-big">
		<div class="hero-block__flex">
			<h1 class="display-largest">
				<b><?php aw_the_field( 'hero-block-title-red' ); ?></b>
				<br>
				<?php aw_the_field( 'hero-block-title' ); ?>
			</h1>
			<div>
				<p class="title-largest"><?php aw_the_field( 'hero-block-tagline' ); ?></p>
				<p class="body-larger-medium"><?php aw_the_field( 'hero-block-text' ); ?></p>
			</div>
		</div>
	</div>
	<div class="hero-block__relative">
		<?php $aw_join_button( aw_string( aw_field( 'hero-block-button' ) ) ); ?>
	</div>
</section>

<section class="homepages__wrapper">
	<div class="container-big">
		<div class="catalog">
			<?php foreach ( array( 'catalog-top', 'catalog-bottom' ) as $aw_catalog ) : ?>
				<div class="<?php echo esc_attr( $aw_catalog ); ?>">
					<?php while ( function_exists( 'have_rows' ) && have_rows( $aw_catalog ) ) : ?>
						<?php the_row(); ?>
						<div class="profession-card">
							<picture>
								<source media="(min-width: 1300px)" srcset="<?php echo aw_url_field( 'profession-img', true ); ?>">
								<img src="<?php echo aw_url_field( 'profession-img-mobile', true ); ?>" alt="<?php echo aw_attr_field( 'profession-title', true ); ?>" width="600" height="480" loading="lazy">
							</picture>
							<div>
								<h2 class="title-larger"><?php aw_the_sub_field( 'profession-title' ); ?></h2>
								<h3 class="body-large-medium"><?php aw_the_sub_field( 'profession-text' ); ?></h3>
								<a class="button-medium primary-button-medium" href="<?php echo aw_url_field( 'profession-url', true ); ?>">Get Started</a>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			<?php endforeach; ?>

			<div class="catalog-more">
				<p class="title-large">Join a vibrant community of skilled trade professionals and get the opportunity to shine!</p>
				<p>
					<?php $aw_jobs = aw_job_pages(); ?>
					<?php foreach ( $aw_jobs as $aw_index => $aw_job ) : ?>
						<span class="link-wrapper link-medium-underline">
							<a href="<?php echo esc_url( $aw_job['url'] ); ?>"><?php echo esc_html( $aw_job['title'] ); ?></a><?php if ( $aw_index < count( $aw_jobs ) - 1 ) : ?><span class="comma">,</span><?php endif; ?>
						</span>
					<?php endforeach; ?>
					<span class="body-medium-medium">and more – enlist today!</span>
				</p>
			</div>
		</div>
	</div>
</section>

<section class="homepages__wrapper">
	<div class="container-xsmall">
		<div class="benefits">
			<div class="benefits-flex">
				<h2 class="display-large"><?php aw_the_field( 'benefits-title' ); ?></h2>
				<p class="body-larger-medium"><?php aw_the_field( 'benefits-text' ); ?></p>
			</div>
			<div class="benefits-items">
				<?php while ( function_exists( 'have_rows' ) && have_rows( 'benefits-item' ) ) : ?>
					<?php the_row(); ?>
					<div class="benefits-item">
						<h3 class="body-larger-semibold"><?php aw_the_sub_field( 'benefits-item-text' ); ?></h3>
					</div>
				<?php endwhile; ?>
			</div>
			<div class="benefits-button">
				<?php $aw_join_button( 'Get Started' ); ?>
			</div>
			<div class="benefits-video">
				<video id="benefits-video" playsinline autoplay loop muted preload="auto">
					<source src="<?php echo esc_url( aw_media_url( '2024/08/aw_video.webm' ) ); ?>" type="video/webm">
					<source src="<?php echo esc_url( aw_media_url( '2024/08/aw_video.mp4' ) ); ?>" type="video/mp4">
				</video>
			</div>
		</div>
	</div>
</section>

<section class="homepages__wrapper">
	<div class="container-big">
		<div class="success-steps">
			<h2 class="display-large"><?php aw_the_field( 'sussess-steps-title' ); ?></h2>
			<p class="body-larger-medium"><?php aw_the_field( 'sussess-steps-text' ); ?></p>
			<div class="steps-items splide" data-steps-slider aria-label="How it works">
				<div class="splide__track">
					<div class="splide__list">
						<?php while ( function_exists( 'have_rows' ) && have_rows( 'steps' ) ) : ?>
							<?php the_row(); ?>
							<div class="steps-item splide__slide">
								<h3 class="title-larger"><?php aw_the_sub_field( 'steps-title' ); ?></h3>
								<img src="<?php echo aw_url_field( 'steps-img', true ); ?>" alt="<?php echo aw_attr_field( 'steps-title', true ); ?>" loading="lazy">
							</div>
						<?php endwhile; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="homepages__wrapper">
	<div class="container-xsmall">
		<div class="success-steps">
			<p class="body-larger-medium">Grow With Us</p>
			<div class="success-steps-video">
				<video id="video" playsinline loop preload="auto" poster="<?php echo esc_url( aw_media_url( '2024/09/video_placeholder.webp' ) ); ?>">
					<source src="<?php echo aw_url_field( 'sussess-video' ); ?>" type="video/mp4">
				</video>
				<div class="video-controls">
					<button type="button" id="playPauseButton" aria-label="Play or pause the video" data-icon-play="<?php echo esc_url( aw_media_url( '2024/08/Play-Button-1.svg' ) ); ?>" data-icon-pause="<?php echo esc_url( aw_media_url( '2024/08/Stop-Button.svg' ) ); ?>">
						<img src="<?php echo esc_url( aw_media_url( '2024/08/Play-Button-1.svg' ) ); ?>" alt="" loading="lazy">
					</button>
					<button type="button" id="muteButton" aria-label="Mute or unmute the video" data-icon-mute="<?php echo esc_url( aw_media_url( '2024/08/Mute-Button.svg' ) ); ?>" data-icon-unmute="<?php echo esc_url( aw_media_url( '2024/08/Unmute-Button.svg' ) ); ?>">
						<img src="<?php echo esc_url( aw_media_url( '2024/08/Mute-Button.svg' ) ); ?>" alt="" loading="lazy">
					</button>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="homepages__wrapper">
	<div class="container-xsmall">
		<div class="use-app">
			<h2 class="display-large"><?php aw_the_field( 'use-app-title' ); ?></h2>
			<img src="<?php echo aw_url_field( 'use-app-img' ); ?>" alt="<?php echo aw_attr_field( 'use-app-title' ); ?>" loading="lazy" width="600" height="480">
			<p class="body-larger-regular"><?php aw_the_field( 'use-app-text' ); ?></p>
			<div class="use-app-button">
				<?php $aw_join_button( 'Join Now!' ); ?>
			</div>
		</div>
	</div>
</section>

<section class="homepages__wrapper">
	<div class="container-big">
		<div class="start-work_grid">
			<div class="start-work__reviews">
				<?php
				foreach ( aw_reviews() as $aw_review ) {
					get_template_part(
						'template-parts/components/review',
						null,
						array(
							'review'  => $aw_review,
							'compact' => true,
						)
					);
				}
				?>
			</div>
			<div class="start-work__form">
				<?php get_template_part( 'template-parts/components/contact-form', null, array( 'form' => 'home' ) ); ?>
			</div>
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/home/faqs' ); ?>

<section class="homepages__wrapper">
	<div class="container-big">
		<div class="blog">
			<h2 class="display-large"><?php aw_the_field( 'blog-title' ); ?></h2>
			<div class="blog__more-links">
				<p class="body-larger-medium"><?php aw_the_field( 'blog-text' ); ?></p>
				<a class="link-large-default" href="<?php echo esc_url( (string) get_post_type_archive_link( 'blog' ) ); ?>">More Posts ></a>
			</div>
			<div class="blog_grid">
				<?php
				$aw_posts = new WP_Query(
					array(
						'post_type'      => 'blog',
						'posts_per_page' => 3,
						'no_found_rows'  => true,
						'category_name'  => 'top',
					)
				);

				if ( ! aw_render_post_cards( $aw_posts ) ) {
					echo '<p>No posts found.</p>';
				}
				?>
			</div>
			<a class="link-large-default" href="<?php echo esc_url( (string) get_post_type_archive_link( 'blog' ) ); ?>">More Posts ></a>
		</div>
	</div>
</section>

<section class="homepages__wrapper">
	<div class="container-big">
		<div class="buyer-stories">
			<h2 class="display-large"><?php aw_the_field( 'buyer-stories-title' ); ?></h2>
			<p class="body-larger-medium"><?php aw_the_field( 'buyer-stories-text' ); ?></p>
		</div>
	</div>
	<?php aw_reviews_schema(); ?>
	<div class="buyer-stories container-big">
		<div class="buyer-stories__cards splide" data-reviews-slider aria-label="Video reviews">
			<div class="splide__track">
				<div class="splide__list">
					<?php
					foreach ( aw_reviews() as $aw_review ) {
						get_template_part(
							'template-parts/components/review',
							null,
							array(
								'review' => $aw_review,
								'class'  => 'item-reviews splide__slide',
							)
						);
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="homepages__wrapper">
	<div class="container-big">
		<div class="get-start">
			<?php $aw_join_button( 'Join Now' ); ?>
		</div>
	</div>
</section>

<?php
get_template_part( 'template-parts/components/video-modal' );
get_template_part( 'template-parts/components/scroll-to-top' );
get_template_part( 'template-parts/components/thank-you-popup' );

get_footer();
