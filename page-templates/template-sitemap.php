<?php
/**
 * Template Name: Sitemap
 *
 * HTML sitemap: every published page except this one, then every blog post.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

get_header();

$aw_groups = array(
	'Pages' => get_pages(
		array(
			'exclude'     => array( get_the_ID() ),
			'sort_column' => 'post_title',
		)
	),
	'Blog'  => get_posts(
		array(
			'post_type'      => 'blog',
			'posts_per_page' => -1,
			'no_found_rows'  => true,
		)
	),
);
?>
<section class="blog__wrapper">
	<div class="container-xxsmall">
		<div class="content">
			<h1 class="title-largest">Sitemap</h1>
			<?php foreach ( $aw_groups as $aw_heading => $aw_items ) : ?>
				<?php if ( $aw_items ) : ?>
					<h2 class="title-larger"><?php echo esc_html( $aw_heading ); ?></h2>
					<ul>
						<?php foreach ( $aw_items as $aw_item ) : ?>
							<li><a class="link-medium-underline" href="<?php echo esc_url( get_permalink( $aw_item ) ); ?>"><?php echo esc_html( get_the_title( $aw_item ) ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php
get_footer();
