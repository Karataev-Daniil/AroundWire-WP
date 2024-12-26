<?php
// Retrieve passed arguments
$post_class = isset($post_class) ? $post_class : 'default-class';
$show_excerpt = isset($show_excerpt) ? $show_excerpt : false;
$show_tags = isset($show_tags) ? $show_tags : true;
$title_class = isset($title_class) ? $title_class : '';
$title_tag = isset($title_tag) && $title_tag !== false && $title_tag !== 'false' ? $title_tag : 'h3';

$default_image_url = '/wp-content/uploads/2024/08/placeholder-for-blog-4.3.png';
if (has_post_thumbnail()) {
    $image_url = esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full'));
    $image_alt = esc_attr(get_the_title());
} else {
    $image_url = esc_url($default_image_url);
    $image_alt = 'default image';
}
?>
<div class="blog__block-post <?php echo esc_attr($post_class); ?>">
    <div class="blog__block-link__img">
        <a href="<?php the_permalink(); ?>" class="blog__block-link">
            <picture>
                <?php if (has_post_thumbnail()) : ?>
                    <source 
                        srcset="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>" 
                        media="(max-width: 440px)">
                    <img 
                        src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>" 
                        alt="<?php echo esc_attr(get_the_title()); ?>" 
                        class="responsive-image">
                <?php else : ?>
                    <source srcset="<?php echo esc_url($default_image_url); ?>" media="(max-width: 440px)">
                    <source srcset="<?php echo esc_url($default_image_url); ?>" media="(max-width: 1024px)">
                    <img src="<?php echo esc_url($default_image_url); ?>" alt="default image" class="responsive-image">
                <?php endif; ?>
            </picture>
        </a>
    </div>
    
    <div class="post-info">
        <<?php echo $title_tag; ?> class="<?php echo isset($title_class) && $title_class == 'big-post-title' ? 'title-largest' : ($post_class == 'full-width' ? 'title-large' : 'title-medium'); ?>">
            <a href="<?php the_permalink(); ?>" class="blog__block-link"><?php the_title(); ?></a>
        </<?php echo $title_tag; ?>>

        <?php if ($show_excerpt) : ?>
            <div class="post-excerpt body-medium-regular">
                <?php echo get_the_excerpt(); ?>
            </div>
        <?php endif; ?>

        <div class="post-meta">
            <span class="body-small-regular">
                <?php echo 'by <b class="link-small-underline">' . get_the_author() . '</b> • ' . get_the_date('F j, Y'); ?>
            </span>
        </div>
        <?php if ($show_tags) : ?>
            <div class="post-tags">
                <?php 
                $tags = wp_get_post_terms(get_the_ID(), 'blog_tag');
                if ($tags) {
                    foreach ($tags as $tag) {
                        echo '<a class="body-small-medium tag-link" href="' . get_term_link($tag->term_id) . '">' . esc_html($tag->name) . '</a>';
                    }
                    echo '<span class="body-small-medium show-more" style="display: none;">...</span>'; // Button to show more tags
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>
