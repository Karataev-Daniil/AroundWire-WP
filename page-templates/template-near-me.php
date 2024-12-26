<?php
/* Template Name: Near me Template */
get_header(); 
?>
<div class="hero-block__wrapper">
    <div class="container-xxsmall">
        <div class="hero-block">
            <h1 class="display-medium"><?php the_title(); ?></h1>

            <div class="content body-medium-medium">
                <?php the_content(); ?>
            </div>
            <button class="primary-button-medium button-medium form-popup-button">Apply Now</button>
        </div>

        <div class="divider"></div>

        <?php
        $search_key = get_post_meta(get_the_ID(), '_related_jobs_key', true);

        if ($search_key) {
            $args = array(
                'post_type'      => 'page',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                's'              => $search_key ? $search_key . ' jobs' : '' ,
                'post__not_in'   => array(get_the_ID()),
            );

            $query = new WP_Query($args);
        }
        ?>

        <ul class="related-pages-list">
            <?php if ($query->have_posts()) : ?>
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <li>
                        <?php 
                        $custom_title = get_post_meta(get_the_ID(), 'custom_related_job_title', true);
                        if ($custom_title) :
                        ?>
                            <h2 class="title-large"><?php echo esc_html($custom_title); ?></h2>
                        <?php else : ?>
                            <h2 class="title-large"><?php echo esc_html(get_the_title()); ?></h2>
                        <?php endif; ?>
                        
                        <?php 
                        $description = get_post_meta(get_the_ID(), 'description', true);
                        if ($description) : ?>
                            <p class="body-medium-regular"><?php echo esc_html($description); ?></p>
                        <?php endif; ?>
                        <div class="button-group">
                            <button class="accent-button-small button-small form-popup-button">Quick Apply</button>
                            <button class="secondary-button-small button-small" onclick="window.location.href='<?php echo esc_url(get_permalink()); ?>'">View Page</button>
                        </div>
                    </li>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>

            <?php if (have_rows('custom-related-pages')) : ?>
                <?php while (have_rows('custom-related-pages')) : the_row(); ?>
                    <li>
                        <h2 class="title-large"><?php the_sub_field('title'); ?></h2>
                        <p class="body-medium-regular"><?php the_sub_field('description'); ?></p>
                        <button class="accent-button-small button-small form-popup-button">Quick Apply</button>
                    </li>
                <?php endwhile; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>

<div class="faqs-block__wrapper">    
    <div class="container-xxsmall">    
        <div class="faqs-block" itemscope itemtype="https://schema.org/FAQPage">
            <h3 class="display-small">FAQs about <?php echo esc_html($search_key); ?> Jobs</h2>
            <div class="faqs-list">
                <?php if (have_rows('faqs')) :
                    while (have_rows('faqs')) : the_row(); ?>
                        <div class="faq" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                            <h4 class="title-medium faq-title active" itemprop="name">
                                <?php the_sub_field('title'); ?>
                                <img class="toggle-image" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAADZJREFUSIljYBgFo2AUjAKCgBGXhKGx6XUGBgYNokz5z7Dp/LnT/tikmMhz1ygYBaNgFFAVAAA5ZQYE1i79SgAAAABJRU5ErkJggg==" alt="picture-minus">
                            </h4>
                            <div class="faq-content show" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                <p class="body-small-regular" style="max-height: none;" itemprop="text">
                                    <?php the_sub_field('text'); ?>
                                </p>
                            </div>
                            <div class="divider"></div>
                        </div>
                    <?php endwhile; ?>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

<?php 
load_template_with_args('template-parts/components/ui-elements/form-popup');

get_footer(); 
?>