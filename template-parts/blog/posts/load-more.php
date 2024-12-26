<div id="custom-posts-container" class="posts-grid">
    <?php
    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => 6
    );

    $query = new WP_Query($args);
    $post_count = 0;

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            $post_count++;

            // Determine the class and flag for showing excerpt based on post number
            $post_class = '';
            $show_excerpt = false;
            $big_post_title = false; // Flag to identify full-width post

            if ($post_count == 1) {
                $post_class = 'full-width'; // First post spans full width
                $show_excerpt = true; // Show excerpt
                $big_post_title = true; // Set flag to true for full-width post
            } elseif ($post_count >= 2 && $post_count <= 4) {
                $post_class = 'one-third'; // Next three posts take 1/3 width each
            } elseif ($post_count == 5 || $post_count == 6) {
                $post_class = 'half-width'; // Last two posts take 1/2 width each
            }

            // Pass arguments directly to the template
            load_template_with_args('template-parts/components/modules/template-post', [
                'post_class' => $post_class,
                'show_excerpt' => $show_excerpt,
                'show_tags' => false,
                'title_class' => $big_post_title
            ]);
        endwhile;
        wp_reset_postdata();
    else :
        echo 'No posts found.';
    endif;
    ?>
</div>

<button id="load-more" class="load-more tertiary-button-small button-small" data-page="1">Load More Articles</button>