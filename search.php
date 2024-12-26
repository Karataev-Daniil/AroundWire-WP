<?php
get_header('blog');
?>
    <section class="blog__wrapper">
        <div class="container-small">
            <?php get_template_part( 'template-parts/blog/form-search');?>
            
            <h1 class="page-title title-larger">
                <?php printf(esc_html__('Search Results for: %s', 'your-theme'), '<span>' . get_search_query() . '</span>'); ?>
            </h1>
            <?php if (have_posts()) : ?>
                <div class="search-results blog__three-columns">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="blog__block-post">
                            <div class="blog__block-link__img">
                                <a href="<?php the_permalink(); ?>" class="blog__block-link">
                                    <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                </a>
                            </div>
                            <div class="post-info">
                                <h3 class="title-medium"><a href="<?php the_permalink(); ?>" class="blog__block-link"><?php echo highlight_search_term(get_the_title()); ?></a></h3>
                                <div class="post-excerpt body-medium-regular">
                                    <?php
                                    $content = get_the_content(); 
                                    $search_query = get_search_query();
                                    echo get_highlighted_excerpt($content, $search_query);
                                    ?>
                                </div>
                                <div class="post-meta">
                                    <span class="body-small-regular"><?php echo 'by <b class="link-small-underline">' . get_the_author() . '</b> • ' . get_the_date('F j, Y'); ?></span>
                                </div>
                                <div class="post-tags">
                                <?php
                                $tags = wp_get_post_terms(get_the_ID(), 'blog_tag');
                                if ($tags && !is_wp_error($tags)) :
                                    foreach ($tags as $tag) :
                                        $search_query = get_search_query();
                                        $is_matching = stripos($tag->name, $search_query) !== false;

                                        // Check if the tag name matches the search query
                                        $class = $is_matching ? 'body-small-medium highlight-tag' : 'body-small-medium tag-link';  // Adjusted class names
                                        echo '<a class="' . esc_attr($class) . '" href="' . get_term_link($tag->term_id) . '">' . esc_html($tag->name) . '</a> ';
                                    endforeach;
                                    echo '<span class="body-small-medium show-more" style="display: none;">...</span>'; // Show more button (hidden by default)
                                endif;
                                ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <div class="blog-search-controls controls-pagination" id="search-pagination-controls">
                    <?php
                    global $wp_query;
                    $current_page = max(1, get_query_var('paged'));
                    $total_pages = $wp_query->max_num_pages;

                    function pagination_button($link, $class, $text, $is_disabled = false) {
                        if ($is_disabled) {
                            echo '<a class="pagination-btn ' . $class . ' button-small"><button class="button-small" disabled>' . $text . '</button></a>';
                        } else {
                            echo '<a href="' . esc_url($link) . '" class="pagination-btn ' . $class . ' button-small"><button class="button-small">' . $text . '</button></a>';
                        }
                    }
                
                    if ($total_pages > 1) {
                        $prev_link = get_pagenum_link($current_page - 1);
                        pagination_button($prev_link, 'prev-btn', '< Prev', $current_page <= 1);
                    
                        $args = array(
                            'total' => $total_pages,
                            'current' => $current_page,
                            'prev_text' => false,
                            'next_text' => false,
                            'before_page_number' => '<button class="pagination-btn page-btn button-small">',
                            'after_page_number' => '</button>',
                            'end_size' => 1,
                            'mid_size' => 2,
                            'type' => 'array',
                            'show_all' => false,
                        );
                        $pagination_links = paginate_links($args);
                    
                        if ($pagination_links) {
                            foreach ($pagination_links as $link) {
                                $link = strpos($link, 'current') !== false ? str_replace('page-btn', 'page-btn active button-small', $link) : $link;
                                echo $link;
                            }
                        }
                    
                        $next_link = get_pagenum_link($current_page + 1);
                        pagination_button($next_link, 'next-btn', 'Next >', $current_page >= $total_pages);
                    }
                    ?>
                </div>


                <?php else : ?>
                    <section class="no-results not-found">
                        <img src="/wp-content/uploads/2024/09/Small-illustrations.webp" alt="not-found-img">
                        <h2 class="title-medium"><?php esc_html_e('Oops, No Matches Found', 'your-theme'); ?></h2>
                        <p class="body-small-regular"><?php esc_html_e('Check back soon! We’re constantly updating with new blogs to keep you informed.', 'your-theme'); ?></p>
                    </section>
                <?php endif; ?>


            <?php get_template_part( 'template-parts/blog/posts/four-columns-slaider' );?>

            <?php get_template_part( 'template-parts/blog/form-newsletter' );?>
        </div>
    </section>
<?php
get_footer('footer-menu');
?>
