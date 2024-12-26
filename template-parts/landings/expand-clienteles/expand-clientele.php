<?php
// Get the version passed from the $args array (e.g., v1, v2, etc.)
$expand_clientele_version = isset($args['version']) ? $args['version'] : 'v1'; // Default to 'v1' if not passed

// Remove the 'v' prefix for comparison purposes (if needed)
$expand_clientele_version_number = substr($expand_clientele_version, 1); // This will extract the number part (e.g., 'v1' becomes '1')

// Example of applying version to classes and styles
$block_class = "expand-clientele__wrapper {$expand_clientele_version}"; // 'v1', 'v2', etc.
?>

<?php if ($expand_clientele_version_number === '1') : ?>
    <div class="expand-clientele v1">
            <div class="block-wrapper" style="background-color: <?php the_field('expand-clientele-block_color-two') ?>">
                <div class="statistics-cards">
                   <img src="<?php the_field('expand-clientele__pic'); ?>" alt="<?php the_field('expand-clientele__title'); ?> look at the statistics">
                </div>
                <div class="text-statistics">
                    <div class="label-large"      style="color: <?php the_field('expand-clientele__tagline_color') ?>;">
                        <?php the_field('expand-clientele__tagline'); ?>
                    </div>
                    <h2 class="display-medium"    style="color: <?php the_field('expand-clientele__title_color') ?>;">
                        <?php the_field('expand-clientele__title'); ?>
                    </h2>
                    <p class="body-medium-medium" style="color: <?php the_field('expand-clientele__text_color') ?>;">
                        <?php the_field('expand-clientele__text'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php else : ?>
<section class="<?php echo $block_class; ?>" <?php if (in_array($expand_clientele_version_number, ['5'])) : ?><?php else : ?>style="background-color: <?php the_field('expand-clientele-block_color') ?>"<?php endif; ?>>
    <div class="<?php if (in_array($expand_clientele_version_number, ['5'])) : ?>container-medium<?php else : ?>container<?php endif; ?>">
        <div class="expand-clientele">

            <?php if (in_array($expand_clientele_version_number, ['3'])) : ?>
            <div class="expand-clientele__block" style="background-color: <?php the_field('expand-clientele-block_color-two') ?>">
            <?php endif; ?>

                <?php if (in_array($expand_clientele_version_number, ['3', '4', '5'])) : ?>
                    <div class="statistics-cards">
                        <img src="<?php the_field('expand-clientele__pic'); ?>" alt="<?php the_field('expand-clientele__title'); ?> look at the statistics">
                    </div>
                <?php endif; ?>

                <div class="text-statistics">
                    <div class="label-large" style="color: <?php the_field('expand-clientele__tagline_color') ?>;">
                        <?php the_field('expand-clientele__tagline'); ?>
                    </div>
                    <h2 class="display-medium" style="color: <?php the_field('expand-clientele__title_color') ?>;">
                        <?php the_field('expand-clientele__title'); ?>
                    </h2>
                    <p class="body-medium-medium" style="color: <?php the_field('expand-clientele__text_color') ?>;">
                        <?php the_field('expand-clientele__text'); ?>
                    </p>
                </div>

                <?php if (!in_array($expand_clientele_version_number, ['3', '4', '5'])) : ?>
                    <div class="statistics-cards">
                        <img src="<?php the_field('expand-clientele__pic'); ?>" alt="<?php the_field('expand-clientele__title'); ?> look at the statistics">
                    </div>
                <?php endif; ?>

            <?php if (in_array($expand_clientele_version_number, ['3'])) : ?>
            </div>
            <?php endif; ?>

        </div>
    </div>
</section>
<?php endif; ?>