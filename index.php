<?php
/**
 * Fallback template for anything without a dedicated one.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main class="blog__wrapper">
	<div class="container-xxsmall">
		<div class="content">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<article <?php post_class(); ?>>
						<?php if ( is_singular() ) : ?>
							<h1 class="title-largest"><?php the_title(); ?></h1>
							<?php the_content(); ?>
						<?php else : ?>
							<h2 class="title-larger"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php the_excerpt(); ?>
						<?php endif; ?>
					</article>
				<?php endwhile; ?>
				<?php the_posts_pagination(); ?>
			<?php else : ?>
				<p>Nothing found.</p>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php
get_footer();
