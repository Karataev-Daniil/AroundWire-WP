<?php
function custom_search_rewrite_rule() {
    add_rewrite_rule('^blog/?$', 'index.php?s=', 'top'); 
}
add_action('init', 'custom_search_rewrite_rule');

function modify_posts_per_page($query) {
    if (!is_admin() && $query->is_main_query()) {
        if ($query->is_search()) {
            $query->set('posts_per_page', 12);
        }
    }
}
add_action('pre_get_posts', 'modify_posts_per_page');

// Function to highlight the search query
function highlight_search_term($text) {
    if (is_search()) {
        $search_query = get_search_query();
        $search_query = preg_quote($search_query, '/'); 

        // Use <span> with the highlight class to highlight the search term
        $text = preg_replace('/(' . $search_query . ')/i', '<span class="highlight">$1</span>', $text);
    }
    return $text;
}

function get_highlighted_excerpt($content, $search_query) {
    if (empty($search_query)) {
        return '';
    }

    // Remove HTML tags
    $content = strip_tags($content);

    // Split the text into sentences
    $sentences = preg_split('/(?<=[.!?])\s+(?=[A-Z])/', $content);

    // Create variables to store the result
    $highlighted = '';

    foreach ($sentences as $sentence) {
        // Check if the keyword exists
        if (stripos($sentence, $search_query) !== false) {
            // Add the sentence with the keyword, highlighting the matches
            $highlighted_sentence = str_ireplace($search_query, '<span class="highlight">' . $search_query . '</span>', $sentence);
            $highlighted .= '<p>' . $highlighted_sentence . '</p>';

            // Add the next 2-3 sentences for context
            $sentence_index = array_search($sentence, $sentences);
            for ($i = $sentence_index + 1; $i < $sentence_index + 4 && $i < count($sentences); $i++) {
                $highlighted .= '<p>' . $sentences[$i] . '</p>';
            }
            break; // Exit the loop after the first match
        }
    }

    return wp_kses_post($highlighted);
}

// Function to truncate to the first three sentences
function truncate_to_first_three_sentences($text) {
    // Split the text into sentences
    $sentences = preg_split('/(?<=[.!?])\s+(?=[A-Z])/', $text, -1, PREG_SPLIT_NO_EMPTY);
    // Keep only the first three sentences
    $sentences = array_slice($sentences, 0, 3);
    return implode(' ', $sentences);
}

function set_og_image_for_search_results() {
    if (is_search()) {
        global $wp_query;

        $original_query = $wp_query;

        $temp_query = clone $wp_query;

        if ($temp_query->have_posts()) {
            $temp_query->the_post();
            $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'full');

            if ($thumbnail) {
                echo '<meta property="og:image" content="' . esc_url($thumbnail) . '">';
            }
        } else {
            $fallback_image = get_template_directory_uri() . '/wp-content/uploads/2024/09/Small-illustrations.webp';
            echo '<meta property="og:image" content="' . esc_url($fallback_image) . '">';
        }

        wp_reset_postdata();
        $wp_query = $original_query;
    }
}
add_action('wp_head', 'set_og_image_for_search_results');

?>