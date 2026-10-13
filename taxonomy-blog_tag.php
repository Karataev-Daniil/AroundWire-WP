<?php
/**
 * Blog tag archive.
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
			<h1 class="display-large">Unleash your home’s potential.<br> Discover expert tips &amp; tricks</h1>
			<p class="body-large-medium">You can find almost any product or service you need from local providers online</p>
		</div>

		<?php get_template_part( 'template-parts/blog/posts/by-tag' ); ?>

		<?php get_template_part( 'template-parts/blog/posts/by-topic' ); ?>

		<?php get_template_part( 'template-parts/blog/newsletter' ); ?>
	</div>
</section>
<?php
get_footer();
