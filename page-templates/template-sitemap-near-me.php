<?php
/*
Template Name: Sitemap Near Me
*/
get_header(); 
?>

<section class="blog__wrapper">
    <div class="container-xxsmall">
        <div class="content">
            <h1 class="title-largest">Find a job near me</h1>
            <ul>
                <?php 
                $current_page_id = get_the_ID();

                $args = array(
                    'post_type'      => 'page',
                    'post_status'    => 'publish',
                    's'              => 'Near Me',
                    'post__not_in'   => array($current_page_id), 
                );

                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                        echo '<li><a class="link-medium-underline" href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<li>' . __('No pages found with "Near Me" in the title.', 'textdomain') . '</li>';
                endif;
                ?>
            </ul>
        </div>
    </div>
</section>


<?php
get_footer();
?>
