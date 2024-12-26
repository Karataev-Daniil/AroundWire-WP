<?php
function load_more_posts() {
    // Get the current page and increment by 1 for loading the next set of posts
    $paged = $_POST['page'] + 1;
    $posts_per_page = 6;
    $post_count = $_POST['post_count'];

    // Set up query arguments to load posts by type 'blog'
    $args = array(
        'post_type' => 'blog',
        'paged' => $paged,
        'posts_per_page' => $posts_per_page,
    );

    // Create a new query for posts
    $query = new WP_Query($args);

    // Check if there are posts to display
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            // Increment the post count
            $post_count++;

            // Determine the class based on the post's order number in a 6-item cycle
            $post_class = '';
            $show_excerpt = false;
            $position_in_cycle = ($post_count - 1) % 6;
            $big_post_title = false; // Flag to identify full-width post

            if ($position_in_cycle == 0) {
                $post_class = 'full-width'; // The first post spans the full width
                $show_excerpt = true;
                $big_post_title = true;
            } elseif ($position_in_cycle >= 1 && $position_in_cycle <= 3) {
                $post_class = 'one-third'; // The next three posts take 1/3 width each
            } else {
                $post_class = 'half-width'; // The last two posts take 1/2 width each
            }
            
            // Pass arguments directly to the template
            load_template_with_args('template-parts/components/modules/template-post', [
                'post_class' => $post_class,
                'show_excerpt' => $show_excerpt,
                'show_tags' => false,
                'title_class' => $big_post_title
            ]);
        endwhile;

        // Reset post data after custom query
        wp_reset_postdata();
    else :
        // If no posts are found, output an empty string
        echo '';
    endif;

    // End AJAX request
    wp_die();
}

// Hook the load_more_posts function into WordPress AJAX actions
add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');

function filter_posts() {
    $paged = (isset($_POST['page'])) ? intval($_POST['page']) : 1;
    $tag_id = isset($_POST['tag_id']) ? intval($_POST['tag_id']) : '';
    $screen_width = isset($_POST['screen_width']) ? intval($_POST['screen_width']) : 1200; 

    $posts_per_page = 8; 

    if ($screen_width < 440) {
        $posts_per_page = 6;
    } elseif ($screen_width < 700) {
        $posts_per_page = 4; 
    } elseif ($screen_width < 1025) {
        $posts_per_page = 6; 
    } else {
        $posts_per_page = 8; 
    }

    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => $posts_per_page,
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

    ob_start();
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); 
            load_component_with_assets('template-parts/components/modules/template-post', [
                'post_class' => 'default-post',
                'show_excerpt' => false
            ]);
        endwhile;

        $has_more_posts = $query->found_posts > ($paged * $posts_per_page);
        $total_pages = ceil($query->found_posts / $posts_per_page);

        wp_reset_postdata();

        echo wp_json_encode([
            'posts_html' => ob_get_clean(),
            'has_more_posts' => $has_more_posts,
            'current_page' => $paged,
            'total_pages' => $total_pages,
        ]);
    else:
        ob_start(); ?>
        <section class="no-results not-found">
            <img src="/wp-content/uploads/2024/09/Small-illustrations.webp" alt="not-found-img">
            <h2 class="title-medium"><?php esc_html_e('It seems something went wrong.', 'your-theme'); ?></h2>
            <p class="body-small-regular"><?php esc_html_e('Please try again or refresh the page to continue.', 'your-theme'); ?></p>
        </section>
        <?php 
        $no_results_html = ob_get_clean();

        echo wp_json_encode([
            'posts_html' => $no_results_html,
            'has_more_posts' => false,
            'current_page' => $paged,
            'total_pages' => 0,
        ]);
    endif;

    wp_die();
}
add_action('wp_ajax_filter_posts', 'filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'filter_posts');
?>