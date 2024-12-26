<h2 class="title-larger">Articles by topic</h2>
<div class="all-post-tags post-tags">
    <span class="tag-filter body-small-medium active" data-term-id="all">All</span> 
    <?php
    $terms = get_terms([
        'taxonomy' => 'blog_tag',
        'hide_empty' => true,
    ]);

    if ($terms && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            echo '<span class="tag-filter body-small-medium" data-term-id="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</span> ';
        }
    } else {
        echo '<p>No tags found.</p>';
    }
    ?>
</div>

<div class="blog__four-columns " id="posts-container">
    <?php
    display_posts();

    function display_posts($tag_id = '') {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = array(
            'post_type' => 'blog',
            'posts_per_page' => 8,
            'paged' => $paged,
        );

        if ($tag_id) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'blog_tag',
                    'field' => 'term_id',
                    'terms' => $tag_id,
                ),
            );
        }

        $query = new WP_Query($args);
        if ($query->have_posts()) : ?>
            <div class="blog__more-posts">
                <h2 class="title-larger">More From This Topic</h2>
                <?php
                while ($query->have_posts()) : $query->the_post();
                    load_template_with_args('template-parts/components/modules/template-post', [
                        'post_class' => 'default-post',
                        'show_excerpt' => false,
                        'show_tags' => false
                    ]);
                endwhile;
                ?>
            </div>
            <?php
            wp_reset_postdata();
        endif;
    }
    ?>
</div>

<div class="blog-slider-controls controls-pagination" id="pagination-controls" style="display: none;">
    <button class="prev-page button-small" id="prev-page" disabled>< Prev</button>
    <div class="page-numbers-container" id="page-numbers"></div>
    <button class="next-page button-small" id="next-page">Next &#62;</button>
</div>