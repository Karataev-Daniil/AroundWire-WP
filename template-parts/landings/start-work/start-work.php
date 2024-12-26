<?php
// Get the version passed from the $args array (e.g., v1, v2, etc.)
$start_work_version = isset($args['version']) ? $args['version'] : 'v1'; // Default to 'v1' if not passed

// Remove the 'v' prefix for comparison purposes (if needed)
$start_work_version_number = substr($start_work_version, 1); // This will extract the number part (e.g., 'v1' becomes '1')

// Example of applying version to classes and styles
$block_class = "start-work__wrapper {$start_work_version}"; // 'v1', 'v2', etc.
?>

<section class="<?php echo $block_class; ?>" <?php if (!in_array($start_work_version_number, ['4', '6', '7'])) : ?>style="background-color: <?php the_field('start-work-block_color') ?>;"<?php endif; ?>>
    <div class="<?php if (in_array($start_work_version_number, ['6'])) : ?>container-medium<?php else : ?>container<?php endif; ?>">
        <div class="start-work">
            <div class="start-work__block-form">
                <?php load_component_with_assets('template-parts/components/ui-elements/form-messeng');?>
            </div>
            <div class="start-work__block">
                <h2 class="display-medium" style="color: <?php the_field('start-work__title_color') ?>;"><?php the_field('start-work__title'); ?></h2>
                <?php 
                $svg_paths = [
                    ABSPATH . 'wp-content/uploads/2024/12/icon-start-work-v1.svg',
                    ABSPATH . 'wp-content/uploads/2024/12/icon-start-work-v2.svg',
                    ABSPATH . 'wp-content/uploads/2024/12/icon-start-work-v3.svg',
                ]; 

                $svg_index = 0;
                if (have_rows('start-work__element')) :?>
                    <?php while (have_rows('start-work__element')) : the_row(); ?>
                        <div class="start-work__element" style="background-color: <?php the_sub_field('start-work__element_color') ?>;">
                            <?php 
                            if (isset($svg_paths[$svg_index])) : 
                                echo file_get_contents($svg_paths[$svg_index]);
                                $svg_index = ($svg_index + 1) % count($svg_paths);
                            endif;
                            ?>
                            <div class="start-work__content">
                                <h3 class="title-larger" style="color: <?php the_sub_field('start-work__element-title_color') ?>;"><?php the_sub_field('start-work__element-title'); ?></h3>
                                <p class="body-medium-medium" style="color: <?php the_sub_field('start-work__element-text_color') ?>;"><?php the_sub_field('start-work__element-text'); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif;?>
            </div>
        </div>

        <?php if (in_array($start_work_version_number, ['5'])) : ?>
        <div class="background-element">
            <div class="ball-border">
                <div class="ball"></div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>