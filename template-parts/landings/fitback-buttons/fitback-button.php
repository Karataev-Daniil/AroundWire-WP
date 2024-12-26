<?php
// Get the version passed from the $args array (e.g., v1, v2, etc.)
$fitback_version = isset($args['version']) ? $args['version'] : 'v1'; // Default to 'v1' if not passed

// Remove the 'v' prefix for comparison purposes (if needed)
$fitback_version_number = substr($fitback_version, 1); // This will extract the number part (e.g., 'v1' becomes '1')

// Example of applying version to classes and styles
$block_class = "fitback-button__wrapper {$fitback_version}"; // 'v1', 'v2', etc.
?>

<?php if ($fitback_version_number === '1') : ?>
<section class="fitback-button__wrapper v1">
    <a class="fitback-button primary-button-medium button-large" href="" onclick="BrevoBookingPage.initStaticButton({ url: 'https://meet.brevo.com/around/borderless' });return false;">Let’s Meet</a>
</section>
<?php else : ?>   
<section class="<?php echo $block_class; ?>">
    <div class="container">
        <div class="fitback">
            <h2 class="display-small"><?php the_field('fitback-text'); ?></h2>
            <a class="fitback-button primary-button-medium button-large" href="" onclick="BrevoBookingPage.initStaticButton({ url: 'https://meet.brevo.com/around/borderless' });return false;"><?php the_field('fitback-button-title'); ?></a>
        </div>
    </div>
</section>
<?php endif; ?>