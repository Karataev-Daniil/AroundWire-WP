<div class="blog__hero-block">
    <?php
    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => 6,
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => 'top',
            ),
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    );

    $query = new WP_Query($args);
    $post_count = 0;
    $big_post_displayed = false;

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            $post_count++;

            if ($post_count == 1 && !$big_post_displayed) :
                // Display the big post
                load_template_with_args('template-parts/components/modules/template-post', [
                    'post_class' => 'big-post',
                    'show_excerpt' => true,
                    'show_tags' => false,
                    'title_class' => 'big-post-title'
                ]);

                $big_post_displayed = true;

                // Display the tag cloud block immediately after the big post
                ?>
                <!-- <div class="blog__block-tags">
                    <p class="title-medium">Tags:</p>
                    <?php
                    $terms = get_terms([
                        'taxonomy' => 'blog_tag',
                        'hide_empty' => true,
                    ]);

                    if ($terms && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            $term_link = get_term_link($term);
                            if (!is_wp_error($term_link)) {
                                echo '<a class="body-small-medium" href="' . esc_url($term_link) . '">' . esc_html($term->name) . '</a> ';
                            }
                        }
                    } else {
                        echo '<p>No tags found.</p>';
                    }
                    ?>
                </div> -->
                <?php
            elseif ($post_count <= 6) :
                load_template_with_args('template-parts/components/modules/template-post', [
                    'post_class' => 'small-posts',
                    'show_excerpt' => false,
                    'show_tags' => false
                ]);                
            endif;

        endwhile;

        wp_reset_postdata();
    else :
        echo '<p>No posts found.</p>';
    endif;
    ?>
</div>