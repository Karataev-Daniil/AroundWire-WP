<?php
/*
Template Name: Landing Page
*/

// List of all blocks and their versions
$blocks = [
    'hero-blocks/hero-block'             => get_field('hero_block_version'),
    'benefits/benefit'                   => get_field('benefits_version'),
    'success-steps/success-step'         => get_field('success_steps_version'),
    'expand-clienteles/expand-clientele' => get_field('expand_clienteles_version'),
    'more-views/more-view'               => get_field('more_views_version'),
    'start-work/start-work'              => get_field('start_work_version'),
    'faqs/faq'                           => get_field('faq_version'),
    'reviews/review'                     => get_field('reviews_version'),
    'fitback-buttons/fitback-button'     => get_field('fitback_button_version'),
];

// Connecting assets and templates
foreach ($blocks as $block => $version) {
    enqueue_landing_assets("template-parts/landings/{$block}", $version);
}

// Load header
get_header();

// Render each block with its version
foreach ($blocks as $block => $version) {
    // Passing the version of a specific block to the template
    get_template_part("template-parts/landings/{$block}", null, ['version' => $version]);
}

// Load footer
get_footer();
?>