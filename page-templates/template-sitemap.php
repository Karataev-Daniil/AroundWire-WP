<?php
/*
Template Name: Sitemap
*/
get_header('blog'); 
?>

<section class="blog__wrapper">
    <div class="container-xxsmall">
        <div class="content">
            <h1 class="title-largest">Sitemap</h1>
            <h2 class="title-larger">Pages</h2>
            <ul>
                <?php 
                $current_page_id = get_the_ID();
                $pages = get_pages();

                foreach ($pages as $page) {
                    if ($page->ID == $current_page_id) {
                        continue;
                    }
                    echo '<li><a class="link-medium-underline" href="' . get_permalink($page->ID) . '">' . $page->post_title . '</a></li>';
                }
                ?>
            </ul>

            <h2 class="title-larger">Blog</h2>
            <ul>
                <?php 
                $blog_posts = new WP_Query(array(
                    'post_type' => 'blog', 
                    'posts_per_page' => -1, 
                    'post_status' => 'publish',
                ));

                if ($blog_posts->have_posts()) :
                    while ($blog_posts->have_posts()) : $blog_posts->the_post();
                        echo '<li><a class="link-medium-underline" href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                    endwhile;
                    wp_reset_postdata();
                else :
                endif;
                ?>
            </ul>
        </div>
    </div>
</section>

<?php
get_footer();
?>