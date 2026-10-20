<?php
/**
 * Blog post card for the current post in the loop.
 *
 * Arguments:
 * - post_class   string  layout class: default-post, big-post, small-posts, full-width, one-third, half-width.
 * - show_excerpt bool    show the excerpt.
 * - show_tags    bool    show tag links (the first two, "..." reveals the rest).
 * - title_style  string  typography class of the title (title-medium by default).
 * - title_tag    string  h2, h3 (default), h4 or span.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_args = wp_parse_args(
	$args,
	array(
		'post_class'   => 'default-post',
		'show_excerpt' => false,
		'show_tags'    => false,
		'title_style'  => 'title-medium',
		'title_tag'    => 'h3',
	)
);

$aw_title_tag = in_array( $aw_args['title_tag'], array( 'h2', 'h3', 'h4', 'span' ), true ) ? $aw_args['title_tag'] : 'h3';
$aw_title     = get_the_title();
?>
<div class="blog__block-post <?php echo esc_attr( $aw_args['post_class'] ); ?>">
	<div class="blog__block-link__img">
		<a href="<?php the_permalink(); ?>" class="blog__block-link" tabindex="-1" aria-hidden="true">
			<?php if ( has_post_thumbnail() ) : ?>
				<picture>
					<source srcset="<?php echo esc_url( (string) get_the_post_thumbnail_url( null, 'medium' ) ); ?>" media="(max-width: 440px)">
					<img src="<?php echo esc_url( (string) get_the_post_thumbnail_url( null, 'full' ) ); ?>" alt="<?php echo esc_attr( $aw_title ); ?>" class="responsive-image" loading="lazy">
				</picture>
			<?php else : ?>
				<img src="<?php echo esc_url( aw_media_url( '2024/08/placeholder-for-blog-4.3.png' ) ); ?>" alt="" class="responsive-image" loading="lazy">
			<?php endif; ?>
		</a>
	</div>

	<div class="post-info">
		<<?php echo tag_escape( $aw_title_tag ); ?> class="post-card__title <?php echo esc_attr( $aw_args['title_style'] ); ?>">
			<a href="<?php the_permalink(); ?>" class="blog__block-link"><?php echo esc_html( $aw_title ); ?></a>
		</<?php echo tag_escape( $aw_title_tag ); ?>>

		<?php if ( $aw_args['show_excerpt'] ) : ?>
			<div class="post-excerpt body-medium-regular"><?php echo esc_html( get_the_excerpt() ); ?></div>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/blog/post-meta' ); ?>

		<?php if ( $aw_args['show_tags'] ) : ?>
			<?php get_template_part( 'template-parts/blog/post-tags' ); ?>
		<?php endif; ?>
	</div>
</div>
