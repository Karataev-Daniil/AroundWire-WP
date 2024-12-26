<?php
/*
Template Name: deploy
*/
get_header();

wp_enqueue_style('deploy-page-style', get_template_directory_uri() . '/deploy-page.css');
?>

<div class="deploy-page">
    <div class="deploy-page-info">
        <h1 class="display-small">Under Maintenance</h1>
        <p class="body-medium-medium">The page you're looking for is currently under maintenance and will be back soon.</p>
    </div>
    <img src="/wp-content/uploads/2024/11/Frame-15.svg">
</div>

<?php
get_footer();
?>