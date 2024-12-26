<div class="blog__three-columns">
    <h2 class="title-larger">Latest articles</h2>
    <?php
    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => 6
    );
    $query = new WP_Query( $args );
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); 
            load_template_with_args('template-parts/components/modules/template-post', [
                'post_class' => 'default-post',
                'show_excerpt' => false,
                'show_tags' => false
            ]);
        endwhile;
        wp_reset_postdata();
    else : ?>
        <p><?php esc_html_e( 'No posts found.', 'your-theme-textdomain' ); ?></p>
    <?php endif; ?>
</div>
