<?php
/**
 * Single blog post: title, cover, table of contents (built by blog.js), content,
 * FAQs, related landing pages, share links and more posts on the same topic.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

get_header();

the_post();

$aw_url   = (string) get_permalink();
$aw_title = get_the_title();
$aw_share = array(
	'facebook' => array( 'Share on Facebook', 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $aw_url ) ),
	'email'    => array( 'Share by email', 'mailto:?subject=' . rawurlencode( $aw_title ) . '&body=' . rawurlencode( $aw_url ) ),
	'twitter'  => array( 'Share on X', 'https://twitter.com/intent/tweet?url=' . rawurlencode( $aw_url ) . '&text=' . rawurlencode( $aw_title ) ),
	'linkedin' => array( 'Share on LinkedIn', 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( $aw_url ) ),
);
?>
<section class="blog__wrapper">
	<div class="container-small">
		<?php get_template_part( 'template-parts/blog/search-form' ); ?>

		<div class="blog-post__title">
			<h1 class="display-small"><?php the_title(); ?></h1>
			<?php get_template_part( 'template-parts/blog/post-meta' ); ?>
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'full', array( 'alt' => $aw_title ) ); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( aw_media_url( '2024/08/placeholder-for-blog-16.9.png' ) ); ?>" alt="">
			<?php endif; ?>
		</div>

		<div class="container-xxsmall blog-post">
			<nav class="content-navigation" aria-label="Table of contents" hidden>
				<p class="content-toggle title-large">Content</p>
				<ul id="content-list" class="content-list link-medium-underline"></ul>
			</nav>

			<div class="blog-post-content">
				<?php the_content(); ?>
				<?php get_template_part( 'template-parts/blog/faqs' ); ?>
			</div>

			<p class="blog-post__tagline">Welcome to the future of online marketplaces – welcome to AroundWire.</p>

			<?php get_template_part( 'template-parts/blog/related-pages' ); ?>

			<div class="blog-post-link">
				<?php foreach ( $aw_share as $aw_network => [ $aw_label, $aw_href ] ) : ?>
					<a class="share-button <?php echo esc_attr( $aw_network ); ?>-share" href="<?php echo esc_url( $aw_href, array( 'https', 'mailto' ) ); ?>" target="_blank" rel="noopener nofollow" aria-label="<?php echo esc_attr( $aw_label ); ?>"></a>
				<?php endforeach; ?>
				<button type="button" class="share-button copy-share" data-copy-link="<?php echo esc_url( $aw_url ); ?>" aria-label="Copy link"></button>
				<span class="copy-message" role="status" hidden>Link copied!</span>
			</div>
		</div>

		<?php get_template_part( 'template-parts/blog/posts/more-from-topic' ); ?>

		<?php get_template_part( 'template-parts/blog/newsletter' ); ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/components/scroll-to-top' );

get_footer();
