<?php
/**
 * Template Name: Sitemap Near Me
 *
 * List of every "… Near Me" page.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

get_header();

$aw_pages = get_posts(
	array(
		'post_type'      => 'page',
		'posts_per_page' => -1,
		's'              => 'Near Me',
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post__not_in'   => array( get_the_ID() ), // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
	)
);
?>
<section class="blog__wrapper">
	<div class="container-xxsmall">
		<div class="content">
			<h1 class="title-largest">Find a job near me</h1>
			<ul>
				<?php foreach ( $aw_pages as $aw_page ) : ?>
					<li><a class="link-medium-underline" href="<?php echo esc_url( get_permalink( $aw_page ) ); ?>"><?php echo esc_html( get_the_title( $aw_page ) ); ?></a></li>
				<?php endforeach; ?>

				<?php if ( ! $aw_pages ) : ?>
					<li>No “Near Me” pages yet.</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</section>
<?php
get_footer();
