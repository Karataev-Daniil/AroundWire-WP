<?php
// Get the version passed from the $args array (e.g., v1, v2, etc.)
$success_step_version = isset($args['version']) ? $args['version'] : 'v1'; // Default to 'v1' if not passed

// Remove the 'v' prefix for comparison purposes (if needed)
$success_step_version_number = substr($success_step_version, 1); // This will extract the number part (e.g., 'v1' becomes '1')

// Example of applying version to classes and styles
$block_class = "success-steps__wrapper {$success_step_version}"; // 'v1', 'v2', etc.
?>

<?php if ($success_step_version_number === '1') : ?>
    <section class="<?php echo $block_class; ?>" style="background-color: <?php the_field('success-steps-block_color') ?>;">
        <div class="container">
            <div class="success-steps">
                <div class="success-steps__info">
                    <div class="label-large"        style="color: <?php the_field('success-steps-tagline_color') ?>;"><?php the_field('success-steps-tagline'); ?></div>
                    <h2  class="display-medium"     style="color: <?php the_field('success-steps-title_color') ?>;"><?php the_field('success-steps-title'); ?></h2>
                    <p   class="body-medium-medium" style="color: <?php the_field('success-steps-text_color') ?>;"><?php the_field('success-steps-text'); ?></p>
                </div>
                <div class="success-steps__block">
                    <?php if (have_rows('success-steps__card-info')) :
                        while (have_rows('success-steps__card-info')) : the_row(); ?>
                            <div class="card-info" style="background-color: <?php the_sub_field('success-steps__card-info_color') ?>;">
                                <div style="background-image: url('<?php the_sub_field('success-steps__card-info__pic'); ?>');"></div>
                                <h3  class="title-large"       style="color: <?php the_sub_field('success-steps__card-info__title_color') ?>;">
                                    <?php the_sub_field('success-steps__card-info__title'); ?>
                                </h3>
                                <p   class="body-small-medium" style="color: <?php the_sub_field('success-steps__card-info__text_color') ?>;">
                                    <?php the_sub_field('success-steps__card-info__text'); ?>
                                </p>
                            </div>
                        <?php endwhile; ?>
                    <?php endif;?>
                </div>
            </div>
<?php else : ?>
    <section class="<?php echo $block_class; ?>" <?php if (in_array($success_step_version_number, ['7'])) : ?><?php else : ?>style="background-color: <?php the_field('success-steps-block_color') ?>;"<?php endif; ?>>
        <div class="<?php if (in_array($success_step_version_number, ['7'])) : ?>container-medium<?php else : ?>container<?php endif; ?>">
            <div class="success-steps">
                <div class="success-steps__info <?php if (in_array($success_step_version_number, ['7'])) : ?>container-xxsmall<?php endif; ?>">
                    <div class="label-large"        style="color: <?php the_field('success-steps-tagline_color') ?>;"><?php the_field('success-steps-tagline'); ?></div>
                    <h2  class="display-medium"     style="color: <?php the_field('success-steps-title_color') ?>;"><?php the_field('success-steps-title'); ?></h2>
                    <p   class="body-medium-medium" style="color: <?php the_field('success-steps-text_color') ?>;"><?php the_field('success-steps-text'); ?></p>
                </div>
                <div class="success-steps__block">

                    <?php if (in_array($success_step_version_number, ['7'])) : ?>
                    <div class="success-steps__images">
                        <img src="<?php the_field('success-steps-block_decorative-element'); ?>" alt="<?php the_field('success-steps-title'); ?>">
                    </div>
                    <?php endif; ?>

                    <?php if (in_array($success_step_version_number, ['7', '8'])) : ?>
                    <div class="success-steps__cards">
                    <?php endif; ?>

                    <?php if (have_rows('success-steps__card-info')) :
                        $counter = 1;
                        while (have_rows('success-steps__card-info')) : the_row(); ?>
                            <div class="card-info" 
                                <?php if ($success_step_version_number === '4') : ?>
                                style="background-image: url('<?php the_sub_field('success-steps__card-info__pic'); ?>');"
                                <?php else : ?>
                                style="background-color: <?php the_sub_field('success-steps__card-info_color'); ?>"
                                <?php endif; ?>>

                                <div class="<?php if (in_array($success_step_version_number, ['5', '6', '7', '8'])) : ?>display-large card-number<?php else : ?>title-large<?php endif; ?>" 
                                    <?php if (in_array($success_step_version_number, ['1', '2', '3', '5'])) : ?>style="background-image: url('<?php the_sub_field('success-steps__card-info__pic'); ?>');
                                    <?php endif; ?>">

                                    <?php if (in_array($success_step_version_number, ['2', '5', '6', '7', '8'])) : ?>
                                        <?php if (in_array($success_step_version_number, ['2', '7', '8'])) : ?>
                                            <?php echo str_pad($counter, 2, '0', STR_PAD_LEFT); ?>
                                        <?php else : ?>
                                            <?php echo str_pad($counter, 2); ?>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                </div>

                                <?php if (in_array($success_step_version_number, ['7', '8'])) : ?>
                                <div>
                                <?php endif; ?>
                                    <?php if ($success_step_version_number === '6') : ?>
                                    <div class="card-title">
                                        <img src="<?php the_sub_field('success-steps__card-info__pic'); ?>" alt="<?php the_sub_field('success-steps__card-info__title'); ?>">
                                        <h3 class="title-larger" style="color: <?php the_sub_field('success-steps__card-info__title_color') ?>;">
                                            <?php the_sub_field('success-steps__card-info__title'); ?>
                                        </h3>
                                    </div>
                                    <?php else : ?>
                                    <h3 class="title-large" style="color: <?php the_sub_field('success-steps__card-info__title_color') ?>;">
                                        <?php the_sub_field('success-steps__card-info__title'); ?>
                                    </h3>
                                    <?php endif; ?>

                                    <p class="body-small-medium" style="color: <?php the_sub_field('success-steps__card-info__text_color') ?>;">
                                        <?php the_sub_field('success-steps__card-info__text'); ?>
                                    </p>
                                <?php if (in_array($success_step_version_number, ['7', '8'])) : ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php $counter++;?>
                        <?php endwhile; ?>
                    <?php endif; ?>
                    
                    <?php if (in_array($success_step_version_number, ['7','8'])) : ?>
                    </div>
                    <?php endif; ?>

                    <?php if (in_array($success_step_version_number, ['8'])) : ?>
                    <div class="success-steps__images">
                        <img src="<?php the_field('success-steps-block_decorative-element'); ?>" alt="<?php the_field('success-steps-title'); ?>">
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (in_array($success_step_version_number, ['6'])) : ?>
            <div class="background-element">
                <div class="ball-border">
                    <div class="ball"></div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>