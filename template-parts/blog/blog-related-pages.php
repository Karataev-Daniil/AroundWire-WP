<?php
$tag_variants = [
    'Cleaners'                => ['cleaner', 'cleaners', 'cleaning'],
    'Contractors'             => ['contractor', 'contractors', 'contracting'],
    'Electricians'            => ['electrician', 'electricians', 'electric'],
    'General Contractors'     => ['general contractor', 'general contractors', 'general contracting'],
    'Handyman'                => ['handyman', 'handymen'],
    'HVAC'                    => ['hvac', 'heating', 'cooling'],
    'Landscapers'             => ['landscaper', 'landscapers', 'landscaping'],
    'Locksmiths'              => ['locksmith', 'locksmiths'],
    'Movers'                  => ['mover', 'movers', 'moving'],
    'Plumbers'                => ['plumber', 'plumbers'],
    'Roofers'                 => ['roofer', 'roofers', 'roofing'],
    'Service Providers'       => ['service provider', 'service providers', 'services'],
    'Solar'                   => ['solar', 'solar panel', 'solar panels'],
    'Solar Panel Technicians' => ['solar panel technician', 'solar panel technicians', 'solar panel']
];

$tags = wp_get_post_terms(get_the_ID(), 'blog_tag');
$tags_names = [];
foreach ($tags as $tag) {
    $tags_names[] = $tag->name;
}

$search_terms = [];
foreach ($tags_names as $tag_name) {
    if (isset($tag_variants[$tag_name])) {
        $search_terms = array_merge($search_terms, $tag_variants[$tag_name]);
    } else {
        $search_terms[] = $tag_name;
    }
}

$visited_pages = [];
$remove_phrases = [
    ' Apply Today!', ' Apply Now!', ' Get Your Customers Now!',
    ' –', ' —', ' -'
];
$related_links = [];
$max_pages_to_display = 3;
$page_count = 0;

foreach ($search_terms as $search_term) {
    $args = [
        'post_type' => 'page',
        'posts_per_page' => 5,
        'post_status' => 'publish',
        's' => $search_term,
        'orderby' => 'date',
        'order' => 'DESC'
    ];

    $related_pages_query = new WP_Query($args);

    if ($related_pages_query->have_posts()) {
        while ($related_pages_query->have_posts()) : $related_pages_query->the_post();
            if ($page_count >= $max_pages_to_display) break 2;
            
            $title = get_the_title();
            if (stripos($title, 'near me') !== false) {
                continue;
            }

            foreach ($remove_phrases as $phrase) {
                $title = rtrim($title, $phrase);
            }
            $title = preg_replace('/\s*[-–—]?\s*$/', '', $title);
            $title = trim($title);

            $las_vegas_pos = strpos($title, 'Las Vegas');
            if ($las_vegas_pos !== false) {
                $title = substr($title, 0, $las_vegas_pos + strlen('Las Vegas'));
            }

            $page_link = get_permalink();
            if (in_array($page_link, $visited_pages)) continue;

            $visited_pages[] = $page_link;
            $related_links[] = '<a class="link-small-underline" href="' . esc_url($page_link) . '">' . esc_html($title) . '</a>';

            $page_count++;
        endwhile;
        wp_reset_postdata();
    }
}

if ($page_count < $max_pages_to_display) {
    $post_content = get_post_field('post_content', get_the_ID());
    $all_pages = get_posts([
        'post_type' => 'page',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ]);

    $similar_pages = [];

    foreach ($all_pages as $page) {
        if ($page_count >= $max_pages_to_display) break;
        $page_link = get_permalink($page->ID);
        if (in_array($page_link, $visited_pages)) continue;

        $acf_fields = get_fields($page->ID);
        if (!$acf_fields) continue;

        $title = get_the_title($page->ID);
        $las_vegas_pos = strpos($title, 'Las Vegas');
        if ($las_vegas_pos !== false) {
            $title = substr($title, 0, $las_vegas_pos + strlen('Las Vegas'));
        }

        foreach ($acf_fields as $field_value) {
            if (is_string($field_value)) {
                $similarity = 0;
                similar_text($post_content, $field_value, $similarity);

                if ($similarity > 2) {
                    $similar_pages[] = [
                        'link' => $page_link,
                        'title' => $title,
                        'similarity' => $similarity
                    ];
                }
            }
        }
    }

    usort($similar_pages, function($a, $b) {
        return $b['similarity'] - $a['similarity'];
    });

    foreach ($similar_pages as $page) {
        if ($page_count >= $max_pages_to_display) break;

        $visited_pages[] = $page['link'];

        $similarity_class = 'similarity-' . floor($page['similarity']); 

        $related_links[] = '<a class="link-small-underline ' . esc_attr($similarity_class) . '" href="' . esc_url($page['link']) . '">' . esc_html($page['title']) . '</a>';

        $page_count++;
    }
}

if (!empty($related_links)) {
    echo '<div class="blog-related-pages"><p class="title-medium">Related Pages:</p>';
    echo implode($related_links);
    echo '</div>';
}
wp_reset_postdata();
?>
