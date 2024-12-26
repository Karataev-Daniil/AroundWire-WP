<?php
// Get the version passed from the $args array (e.g., v1, v2, etc.)
$more_view_version = isset($args['version']) ? $args['version'] : 'v1'; // Default to 'v1' if not passed

// Remove the 'v' prefix for comparison purposes (if needed)
$more_view_version_number = substr($more_view_version, 1); // This will extract the number part (e.g., 'v1' becomes '1')

// Example of applying version to classes and styles
$block_class = "more-views__wrapper {$more_view_version}"; // 'v1', 'v2', etc.
?>

<section class="<?php echo $block_class; ?>" 
    <?php if (!in_array($more_view_version_number, ['6'])) : ?>
        style="background-color: <?php the_field('more-views-block_color') ?>;"
    <?php endif; ?>>
    <?php if (in_array($more_view_version_number, ['4'])) : ?>
    <div class="more-views__video">
        <video playsinline="true" autoplay="true" loop="true" preload="auto" muted="true">
            <source src="<?php the_field('more-views__video'); ?>" type="video/mp4">
            <source src="/wp-content/uploads/2024/08/aw_video.webm" type="video/webm">
            <source src="/wp-content/uploads/2024/08/aw_video.mp4" type="video/mp4">
        </video>
    </div>
    <?php endif; ?>

    <div class="<?php if (in_array($more_view_version_number, ['6'])) : ?>container-xxsmall<?php else : ?>container<?php endif; ?>">
        <div class="more-views">
            <?php if (in_array($more_view_version_number, ['3', '4', '7'])) : ?>
            <div class="more-views__video">
                <?php if (!in_array($more_view_version_number, ['4'])) : ?>
                <video playsinline="true" autoplay="true" loop="true" preload="auto" muted="true">
                    <source src="<?php the_field('more-views__video'); ?>" type="video/mp4">
                    <source src="/wp-content/uploads/2024/08/aw_video.webm" type="video/webm">
                    <source src="/wp-content/uploads/2024/08/aw_video.mp4" type="video/mp4">
                </video>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="more-views__info">
                <h2 class="display-medium"    style="color: <?php the_field('more-views__title_color') ?>;"><?php the_field('more-views__title'); ?></h2>

                <?php if (in_array($more_view_version_number, ['1'])) : ?>
                    <video playsinline="true" autoplay="true" loop="true" muted="true" preload="auto">
                        <source src="<?php the_field('more-views__video') ?>;" type="video/mp4">
                        <source src="/wp-content/uploads/2024/08/aw_video.webm" type="video/webm">
                        <source src="/wp-content/uploads/2024/08/aw_video.mp4" type="video/mp4">
                    </video>
                <?php endif; ?>

                <div class="title-large"      style="color: <?php the_field('more-views__tagline_color') ?>;"><?php the_field('more-views__tagline'); ?></div>
                <p class="body-medium-medium" style="color: <?php the_field('more-views__text_color') ?>;"><?php the_field('more-views__text'); ?></p>
            </div>

            <?php if (in_array($more_view_version_number, ['1', '2', '5', '6'])) : ?>
            <div class="more-views__video">
                <?php if (in_array($more_view_version_number, ['5'])) : ?>
                <div class="video-wrapper">
                <?php endif; ?>
                    <video playsinline="true" autoplay="true" loop="true" preload="auto" muted="true">
                        <source src="<?php the_field('more-views__video'); ?>" type="video/mp4">
                        <source src="/wp-content/uploads/2024/08/aw_video.webm" type="video/webm">
                        <source src="/wp-content/uploads/2024/08/aw_video.mp4" type="video/mp4">
                    </video>
                <?php if (in_array($more_view_version_number, ['5'])) : ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>