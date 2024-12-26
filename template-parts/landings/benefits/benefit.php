<?php
// Get the version passed from the $args array (e.g., v1, v2, etc.)
$benefit_version = isset($args['version']) ? $args['version'] : 'v1'; // Default to 'v1' if not passed

// Remove the 'v' prefix for comparison purposes (if needed)
$benefit_version_number = substr($benefit_version, 1); // This will extract the number part (e.g., 'v1' becomes '1')

// Example of applying version to classes and styles
$block_class = "benefits__wrapper {$benefit_version}"; // 'v1', 'v2', etc.
?>

<section class="<?php echo $block_class; ?>" style="background-color: <?php the_field('benefits-block_color') ?>;">

    <?php if (in_array($benefit_version_number, ['5'])) : ?>
        <div class="border-element" style="background-color: <?php the_field('hero-block_color') ?>;"></div>
    <?php endif; ?>

    <div class="container">
        <div class="benefits">
            <?php if (in_array($benefit_version_number, ['6'])) : ?>
            <div class="benefits__img">
                <div class="benefits__img-border">
                    <img src="<?php the_field('benefits__block_background-image') ?>" alt="<?php the_field('benefits-title'); ?>">
                </div>
            </div>
            <?php endif; ?>
            <?php if (in_array($benefit_version_number, ['5', '6'])) : ?>
            <div class="benefits__content" style="background-color: <?php the_field('benefits-block_color') ?>;">
            <?php endif; ?>
                <h2 class="display-medium"    style="color: <?php the_field('benefits-title_color') ?>;"><?php the_field('benefits-title'); ?></h2>
                <p class="body-medium-medium" style="color: <?php the_field('benefits-text_color') ?>;"><?php the_field('benefits-text'); ?></p>
                <div class="benefits__slider <?php echo $benefit_version; ?> 
                <?php if (in_array($benefit_version_number, ['8'])) : ?>
                v7
                <?php endif; ?>
                    ">
                    <?php 
                    $svg_paths = [
                        ABSPATH . 'wp-content/uploads/2024/12/icon-benefit-v1.svg',
                        ABSPATH . 'wp-content/uploads/2024/12/icon-benefit-v2.svg',
                        ABSPATH . 'wp-content/uploads/2024/12/icon-benefit-v3.svg',
                        ABSPATH . 'wp-content/uploads/2024/12/icon-benefit-v4.svg',
                        ABSPATH . 'wp-content/uploads/2024/12/icon-benefit-v5.svg',
                        ABSPATH . 'wp-content/uploads/2024/12/icon-benefit-v1.svg',
                    ];            

                    $svg_index = 0;

                    if (have_rows('benefits__slider-block')) : ?>
                        <?php while (have_rows('benefits__slider-block')) : the_row(); ?>

                            <div class="benefits__slider-block" 
                                <?php if (in_array($benefit_version_number, ['1', '5'])) : ?>
                                    style="background-color: <?php the_field('benefits__slider-block_color') ?>;"
                                <?php endif; ?>>

                                <?php if (in_array($benefit_version_number, ['2', '3', '4', '7'])) : ?>
                                    <div style="background-color: <?php the_field('benefits__slider-block_color'); ?>; 
                                        <?php if (in_array($benefit_version_number, ['3'])) : ?>
                                            border: 1px solid <?php the_field('benefits__slider-block_border-color'); ?>;
                                        <?php endif; ?> 
                                    ">
                                <?php endif; ?> 

                                    <?php 
                                    if (isset($svg_paths[$svg_index])) : 
                                        echo file_get_contents($svg_paths[$svg_index]);
                                        $svg_index = ($svg_index + 1) % count($svg_paths);
                                    endif;
                                    ?>

                                    <h3 class="title-larger" style="color: <?php the_sub_field('benefits__slider-title_color'); ?>;">
                                        <?php the_sub_field('benefits__slider-title'); ?>
                                    </h3>
                                    <p class="body-medium-medium" style="color: <?php the_sub_field('benefits__slider-text_color'); ?>;">
                                        <?php the_sub_field('benefits__slider-text'); ?>
                                    </p>

                                <?php if (in_array($benefit_version_number, ['2', '3', '4', '7'])) : ?>
                                </div>
                                <?php endif; ?> 
                            </div>

                        <?php endwhile; ?>
                    <?php endif; ?>

                </div>
            <?php if (in_array($benefit_version_number, ['5', '6'])) : ?>
            </div>

            <?php if (in_array($benefit_version_number, ['5'])) : ?>    
            <div class="benefits__image">
                <img src="<?php the_field('benefits__block_background-image') ?>" alt="<?php the_field('benefits-title'); ?>">
            </div>      
            <?php endif; ?>  
                
            <?php endif; ?>
        </div>
    </div>
</section>