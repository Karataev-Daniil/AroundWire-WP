<?php
/**
 * Search results among posts and blog posts, with highlighted matches.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $wp_query;

$aw_query   = get_search_query( false );
$aw_current = max( 1, (int) get_query_var( 'paged' ) );
$aw_total   = (int) $wp_query->max_num_pages;
?>
<section class="blog__wrapper">
	<div class="container-small">
		<?php get_template_part( 'template-parts/blog/search-form' ); ?>

		<h1 class="page-title title-larger">Search Results for: <span><?php echo esc_html( $aw_query ); ?></span></h1>

		<?php if ( have_posts() ) : ?>
			<div class="search-results blog__three-columns">
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<div class="blog__block-post">
						<div class="blog__block-link__img">
							<a href="<?php the_permalink(); ?>" class="blog__block-link" tabindex="-1" aria-hidden="true">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium_large', array( 'alt' => '' ) ); ?>
								<?php else : ?>
									<img src="<?php echo esc_url( aw_media_url( '2024/08/placeholder-for-blog-4.3.png' ) ); ?>" alt="" loading="lazy">
								<?php endif; ?>
							</a>
						</div>
						<div class="post-info">
							<h2 class="title-medium">
								<a href="<?php the_permalink(); ?>" class="blog__block-link"><?php echo wp_kses( aw_highlight( get_the_title(), $aw_query ), array( 'span' => array( 'class' => true ) ) ); ?></a>
							</h2>
							<div class="post-excerpt body-medium-regular">
								<?php echo wp_kses( aw_search_excerpt( get_the_content(), $aw_query ), array( 'p' => array(), 'span' => array( 'class' => true ) ) ); ?>
							</div>
							<?php get_template_part( 'template-parts/blog/post-meta' ); ?>
							<?php get_template_part( 'template-parts/blog/post-tags' ); ?>
						</div>
					</div>
				<?php endwhile; ?>
			</div>

			<?php if ( $aw_total > 1 ) : ?>
				<nav class="blog-search-controls controls-pagination" aria-label="Search results pages">
					<?php
					aw_pagination_button( get_pagenum_link( $aw_current - 1 ), 'prev-btn', '< Prev', $aw_current <= 1 );

					$aw_links = paginate_links(
						array(
							'total'     => $aw_total,
							'current'   => $aw_current,
							'prev_next' => false,
							'end_size'  => 1,
							'mid_size'  => 2,
							'type'      => 'array',
						)
					);

					foreach ( (array) $aw_links as $aw_link ) {
						echo wp_kses_post( str_replace( 'page-numbers', 'pagination-btn page-btn button-small page-numbers', $aw_link ) );
					}

					aw_pagination_button( get_pagenum_link( $aw_current + 1 ), 'next-btn', 'Next >', $aw_current >= $aw_total );
					?>
				</nav>
			<?php endif; ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/blog/no-results', null, array( 'variant' => 'search' ) ); ?>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/blog/posts/by-topic' ); ?>

		<?php get_template_part( 'template-parts/blog/newsletter' ); ?>
	</div>
</section>
<?php
get_footer();
