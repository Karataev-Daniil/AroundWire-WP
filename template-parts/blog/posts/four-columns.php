<?php
global $post;
$current_post_id = $post->ID;
$tags = wp_get_post_terms($current_post_id, 'blog_tag');

if (!empty($tags)) {
    $tag_ids = wp_list_pluck($tags, 'term_id');
    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => 4,
        'post__not_in' => array($current_post_id),
        'tax_query' => array(
            array(
                'taxonomy' => 'blog_tag',
                'field' => 'term_id',
                'terms' => $tag_ids,
            ),
        ),
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) : ?>
        <div class="blog__more-posts">
            <span class="title-larger">More From This Topic</span>
            <?php
            while ($query->have_posts()) : $query->the_post();
                load_template_with_args('template-parts/components/modules/template-post', [
                    'post_class' => 'default-post',
                    'show_excerpt' => false,
                    'show_tags' => false,
                    'title_tag' => 'span'
                ]);
            endwhile;
            ?>
        </div>
        <?php
        wp_reset_postdata();
    endif;
}
?>
