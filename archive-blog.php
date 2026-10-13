<?php
/**
 * Blog archive (/blog/).
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="blog__wrapper">
	<div class="container-small">
		<?php get_template_part( 'template-parts/blog/search-form' ); ?>

		<div class="title-block">
			<p class="label-large">AroundWire Blog</p>
			<h1 class="display-large">Get Expert Tips, Tricks, and Practical Guidelines to Scale Your Business!</h1>
			<p class="body-large-medium">Learn the ins and outs of your industry, as well as best practices and recommendations from seasoned experts.</p>
		</div>

		<?php get_template_part( 'template-parts/blog/posts/hero' ); ?>

		<div class="divider"></div>

		<?php get_template_part( 'template-parts/blog/posts/popular' ); ?>

		<div class="divider"></div>

		<?php get_template_part( 'template-parts/blog/posts/load-more' ); ?>

		<?php get_template_part( 'template-parts/blog/newsletter' ); ?>
	</div>
</section>
<?php
get_footer();
