<?php
add_action('template_redirect', function() {
    // Array of redirects: 'old_path' => 'new_url'
    $redirects = [
        // Individual Redirects
        '/hvac-leads-in-las-vegas'                                                   => 'https://pro.aroundwire.com/hvac-leads-las-vegas/',
        '/blog/the-roadmap-to-starting-an-hvac-business-2'                           => 'https://pro.aroundwire.com/blog/the-roadmap-to-starting-an-hvac-business/',
        // '/blog/hvac-lead-generation-tips-for-las-vegas-2'                           => 'https://pro.aroundwire.com/blog/hvac-lead-generation-tips-for-las-vegas/',
        '/blog/align-your-locksmith-business-with-niche-specializations-for-success' => 'https://pro.aroundwire.com/blog/aligning-your-locksmith-business-with-niche-specializations/',
        '/blog/land-more-gigs-with-ai-job-search-tips-for-service-providers'         => 'https://pro.aroundwire.com/blog/land-more-gigs-with-the-power-of-the-ai/',
        '/blog/solar-panel-installation-mistakes-and-how-to-avoid-them-2'            => 'https://pro.aroundwire.com/blog/solar-panel-installation-mistakes-and-how-to-avoid-them/',

        // Grouped Redirects (all redirect to the same target)
        '/blog/proven-hvac-lead-generation-strategies-for-las-vegas'      => 'https://pro.aroundwire.com/blog/how-to-get-hvac-leads/',
        '/blog/hvac-lead-generation-tips-for-las-vegas'                   => 'https://pro.aroundwire.com/blog/how-to-get-hvac-leads/',
        '/blog/hvac-lead-generation-in-las-vegas-2024'                    => 'https://pro.aroundwire.com/blog/how-to-get-hvac-leads/',
        '/blog/guide-to-hvac-lead-generation-in-vegas-2024'               => 'https://pro.aroundwire.com/blog/how-to-get-hvac-leads/',
        '/blog/diy-hvac-lead-generation-tips-for-vegas'                   => 'https://pro.aroundwire.com/blog/how-to-get-hvac-leads/',
        '/blog/hvac-lead-generation-strategies-for-las-vegas'             => 'https://pro.aroundwire.com/blog/how-to-get-hvac-leads/',
        '/blog/hvac-lead-generation-with-government-programs'             => 'https://pro.aroundwire.com/blog/how-to-get-hvac-leads/',
        '/blog/unique-hvac-lead-generation-strategies-for-las-vegas-2024' => 'https://pro.aroundwire.com/blog/how-to-get-hvac-leads/',
        '/blog/hvac-lead-generation-tips-for-vegas-you-need-to-know'      => 'https://pro.aroundwire.com/blog/how-to-get-hvac-leads/',
        '/blog/hvac-marketing-strategies-for-las-vegas'                   => 'https://pro.aroundwire.com/blog/how-to-get-hvac-leads/',
        '/blog/attract-hvac-customers-in-las-vegas'                       => 'https://pro.aroundwire.com/blog/how-to-get-hvac-leads/',
		// NEW
		'/blog/hvac-lead-generation-tips-for-las-vegas-2'                 => 'https://pro.aroundwire.com/blog/how-to-get-hvac-leads/',
		'/blog/hvac-lead-generation-tips-for-las-vegas'                   => 'https://pro.aroundwire.com/blog/how-to-get-hvac-leads/',
		
    ];

    $current_path = strtolower(untrailingslashit(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));

    if (array_key_exists($current_path, $redirects)) {
        error_log("Redirecting to: {$redirects[$current_path]}");
        wp_redirect($redirects[$current_path], 301);
        exit;
    }
});

function redirect_to_https() {
    if ( !is_local_environment() && $_SERVER['HTTPS'] != 'on' ) {

        $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header("Location: $url", true, 301);  
        exit(); 
    }
}

function is_local_environment() {
    return strpos($_SERVER['HTTP_HOST'], 'local') !== false || strpos($_SERVER['HTTP_HOST'], 'localhost') !== false;
}

add_action('template_redirect', 'redirect_to_https');

?>