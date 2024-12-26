<?php
// Get the version passed from the $args array (e.g., v1, v2, etc.)
$hero_version = isset($args['version']) ? $args['version'] : 'v1'; // Default to 'v1' if not passed

// Remove the 'v' prefix for comparison purposes (if needed)
$hero_version_number = substr($hero_version, 1); // This will extract the number part (e.g., 'v1' becomes '1')

// Example of applying version to classes and styles
$block_class = "hero-block__wrapper {$hero_version}"; // 'v1', 'v2', etc.
?>


<?php if ($hero_version_number === '7') : ?>
    <section class="<?php echo $block_class; ?>">
        <div class="hero-block">
            <div class="hero-block__preview">
                <img class="preview-pic-one" src="<?php the_field('hero-block-pic'); ?>" alt="<?php the_field('hero-block-title'); ?> desktop">
            </div>
            <div class="container">
                <div class="hero-block__info">
                    <div class="uppercase-small" style="color: <?php the_field('hero-block-tagline_color'); ?>;">
                        <?php the_field('hero-block-tagline'); ?>
                    </div>
                    <h1 class="display-large" style="color: <?php the_field('hero-block-text_color'); ?>;">
                        <?php the_field('hero-block-title'); ?>
                    </h1>
                    <p class="body-large-medium" style="color: <?php the_field('hero-block-text_color'); ?>;">
                        <?php the_field('hero-block-text'); ?>
                    </p>
                    <button class="noLink link-button nofollow primary-button-large button-large hero-block__button form-popup-button">
                        <?php the_field('hero-block-button'); ?>
                    </button>
                </div>
            </div>
        </div>
    </section>
<?php else : ?>
    <section class="<?php echo $block_class; ?>" 
        <?php if (in_array($hero_version_number, ['1', '2', '3', '4', '5'])) : ?>
            style="background-color: <?php the_field('hero-block_color'); ?>;"
        <?php endif; ?>>
        <div class="container">
            <div class="hero-block">
                <div class="hero-block__info" style="<?php echo $hero_version_number === '3' ? 'background-color: '.get_field('hero-block_color').';' : ''; ?>">
                    <div class="<?php echo in_array($hero_version_number, ['6']) ? 'uppercase-small' : 'label-large'; ?>"
                         style="color: <?php the_field('hero-block-tagline_color'); ?>;">
                        <?php the_field('hero-block-tagline'); ?>
                    </div>
                    <h1 class="display-large" style="color: <?php the_field('hero-block-text_color'); ?>;">
                        <?php the_field('hero-block-title'); ?>
                    </h1>
                    <?php if ($hero_version_number === '1') : ?>
                        <div class="preview-block">
                            <img class="preview-statistics" src="<?php the_field('hero-block-pic'); ?>" alt="<?php the_field('hero-block-title'); ?> desktop">
                            <img class="preview-statistics-mobile" src="<?php the_field('hero-block-pic-mobile'); ?>" alt="<?php the_field('hero-block-title'); ?> mobile">
                        </div>
                    <?php endif; ?>
                    <p class="body-large-medium" style="color: <?php the_field('hero-block-text_color'); ?>;">
                        <?php the_field('hero-block-text'); ?>
                    </p>
                    <button class="noLink link-button nofollow primary-button-large button-large hero-block__button form-popup-button">
                        <?php the_field('hero-block-button'); ?>
                    </button>
                </div>
                <div class="hero-block__preview">
                    <?php if (in_array($hero_version_number, ['1', '2', '3', '4', '5', '6'])) : ?>
                        <img class="pic-desktop preview-statistics" src="<?php the_field('hero-block-pic'); ?>" alt="<?php the_field('hero-block-title'); ?> desktop">
                    <?php endif; ?>
                    <?php if (in_array($hero_version_number, ['2', '3', '4', '5', '6'])) : ?>
                        <img class="pic-tablet preview-statistics-mobile" src="<?php the_field('hero-block-pic-mobile'); ?>" alt="<?php the_field('hero-block-title'); ?> mobile">
                    <?php endif; ?>
                    <?php if (in_array($hero_version_number, ['2', '3', '4', '6'])) : ?>
                        <img class="pic-mobile preview-pic-more" src="<?php the_field('hero-block-pic-more'); ?>" alt="<?php the_field('hero-block-title'); ?> more">
                    <?php endif; ?>
                    <?php if ($hero_version_number === '6') : ?>
                        <img class="preview-pic-fore" src="<?php the_field('hero-block-pic-fore'); ?>" alt="<?php the_field('hero-block-title'); ?> fore">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>