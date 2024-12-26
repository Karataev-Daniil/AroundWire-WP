<?php
get_header('blog');
?>
    <section class="blog__wrapper">
        <div class="container-small">
            <?php get_template_part( 'template-parts/blog/form-search');?>

            <?php
            if (is_single()) {
                set_post_views(get_the_ID());
            }
            ?>       
            <div class="blog-post__title">
                <h1 class="display-small"><?php the_title() ?></h1>
                <div class="post-meta">
                    <span class="body-small-regular"><?php echo 'by <b class="link-small-underline">' . get_the_author() . '</b> • ' . get_the_date('F j, Y'); ?></span>
                </div>
                <?php
                $default_image_url = '/wp-content/uploads/2024/08/placeholder-for-blog-16.9.png';

                if (has_post_thumbnail()) {
                    $image_url = esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full'));
                    $image_alt = esc_attr(get_the_title());
                } else {
                    $image_url = esc_url($default_image_url);
                    $image_alt = 'default image'; 
                }
                ?>
                <img src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>">
            </div>
            <div class="container-xxsmall blog-post">
                <div class="content-navigation">
                    <div id="toggle-content" class="content-toggle"><span class="title-large">Content</span></div>
                    <div id="content-list" class="content-list link-medium-underline"></div>
                </div>

                <div class="blog-post-content">
                    <?php the_content();?>
                    <?php get_template_part( 'template-parts/blog/blog-faqs');?>
                </div>
                <div class="blog-post__tagline">Welcome to the future of online marketplaces – welcome to AroundWire.</div>
                <!-- <div class="blog-post-tags">
                    <?php
                        $terms = wp_get_post_terms(get_the_ID(), 'blog_tag');

                        if (!empty($terms) && !is_wp_error($terms)) {
                            echo '<h3 class="title-small">Topics: </h3>';
                            $output = [];
                            foreach ($terms as $term) {
                                $output[] = '<a class="body-small-semibold" href="' . get_term_link($term) . '">' . $term->name . '</a>';
                            }
                            echo implode(' ', $output);
                        }
                    ?>
                </div> -->

                <?php get_template_part( 'template-parts/blog/blog-related-pages');?>

                <div class="blog-post-link">
                    <a class="share-button facebook-share" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank"></a>
                    <a class="share-button email-share"    href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>" target="_blank"></a>
                    <a class="share-button twitter-share"  href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank"></a>
                    <a class="share-button linkedin-share" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank"></a>
                    <a class="share-button copy-share"     href="javascript:void(0);" data-link="<?php echo get_permalink(); ?>" id="copyButton"></a>
                    <span id="copyMessage" style="display: none;">Link copied!</span>
                </div>
            </div>

            <?php get_template_part( 'template-parts/blog/posts/four-columns');?>
                    
            <?php get_template_part( 'template-parts/blog/form-newsletter');?>

        </div>
    </section>
<?php 
load_component_with_assets( 'template-parts/components/ui-elements/scroll-to-top' );

get_footer();
?>