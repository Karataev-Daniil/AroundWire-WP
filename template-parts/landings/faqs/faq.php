<?php
// Get the version passed from the $args array (e.g., v1, v2, etc.)
$faq_version = isset($args['version']) ? $args['version'] : 'v1'; // Default to 'v1' if not passed

// Remove the 'v' prefix for comparison purposes (if needed)
$faq_version_number = substr($faq_version, 1); // This will extract the number part (e.g., 'v1' becomes '1')

// Example of applying version to classes and styles
$block_class = "faqs__wrapper {$faq_version}"; // 'v1', 'v2', etc.
?>

<section class="<?php echo $block_class; ?>" <?php if (!in_array($faq_version_number, ['4'])) : ?>style="background-color: <?php the_field('faqs-block_color') ?>;"<?php endif; ?>>
    <div class="<?php 
                if (in_array($faq_version_number, ['5', '2'])) : 
                    echo 'container-xxsmall';
                elseif (in_array($faq_version_number, ['6'])) : 
                    echo 'container-small';
                else : 
                    echo 'container';
                endif;
                ?>
                ">
        <div class="faqs__block" itemscope itemtype="https://schema.org/FAQPage">
            <div class="faqs__content">
                <div class="label-large faqs-tagline"  style="color: <?php the_field('faqs-tagline_color') ?>;"><?php the_field('faqs-tagline'); ?></div>
                <h2  class="display-medium faqs-title" style="color: <?php the_field('faqs-title_color') ?>;"><?php the_field('faqs-title'); ?></h2>
            </div>
            <div class="faqs__items">
                <?php
                $category_name = get_field('faqs-category');

                if ($category_name) {
                    $term = get_term_by('name', $category_name, 'faq_category'); 

                    if ($term) {
                        $term_id = $term->term_id;
                    
                        $args = array(
                            'post_type'      => 'faqs',
                            'posts_per_page' => 4,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'faq_category',
                                    'field'    => 'term_id',
                                    'terms'    => $term_id,
                                ),
                            ),
                        );
                    
                        $query = new WP_Query($args);
                    
                        if ($query->have_posts()) {
                            while ($query->have_posts()) {
                                $query->the_post(); ?>
                                <div class="faq-item" style="background-color: <?php echo esc_attr(get_field('faq-item_color')); ?>;" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                                    <h3 class="title-medium faq-title" itemprop="name">
                                        <?php the_title(); ?>
                                        <img class="toggle-image" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAGhJREFUSIljYBgFo4CuwMDIpN7AyKSeFD0spChmZGRsgDIbidXDRIoF5IChbwEjLgkDI5N6pDDHC/7//99w4dwZrPFCcx+QBAyNTf8bGpv+J0XP0I9kmltAUk7+//9/A43cMQpGAR4AANzJEfOOAm+9AAAAAElFTkSuQmCC" alt="picture-plus">
                                    </h3>
                                    <div class="body-small-regular faq-content" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                        <p itemprop="text">
                                            <?php echo get_the_content(); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php }
                            wp_reset_postdata();
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>