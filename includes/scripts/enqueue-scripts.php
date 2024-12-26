<?php
// Function to get file version
function get_file_version($file_path) {
    $file = get_template_directory() . $file_path;
    return file_exists($file) ? filemtime($file) : '1.0.0';
}

// Include jQuery built into WordPress
function custom_enqueue_assets() {
    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Connect common styles
    $common_styles = array(
        'reset-style'          => '/css/reset.css',
        'fonts-style'          => '/css/fonts.css',
        'main-style'           => '/style.css',
        'form-tracking-script' => '/js/form-tracking.js',
    );

    foreach ($common_styles as $handle => $path) {
        wp_enqueue_style($handle, get_template_directory_uri() . $path, array(), get_file_version($path));
    }

    // Enqueue UI Kit styles
    $ui_kit_styles = array(
        'button-kit-style'          => '/css/ui-kit/buttons.css',
        'typography-kit-style'      => '/css/ui-kit/typography.css',
        'pallete-collors-kit-style' => '/css/ui-kit/pallete-collors.css',
    );

    foreach ($ui_kit_styles as $handle => $path) {
        wp_enqueue_style($handle, get_template_directory_uri() . $path, array(), get_file_version($path));
    }

    // Enqueue home page assets
    if (is_page_template('page-templates/template-home.php')) {
        wp_enqueue_style('home-page-style',       get_template_directory_uri() . '/css/template/home-page.css',    array(), get_file_version('/css/template/home-page.css'));
        wp_enqueue_script('home-page-script',     get_template_directory_uri() . '/js/template/home-page.js',      array(), get_file_version('/js/template/home-page.js'), true);

        wp_enqueue_style('template-post-style',   get_template_directory_uri() . '/css/modules/template-post.css', array(), get_file_version('/css/modules/template-post.css'));
        wp_enqueue_script('archive-blog-script',  get_template_directory_uri() . '/js/load-more.js',               array(), get_file_version('/js/load-more.js'), true);
        wp_enqueue_script('form-tracking-script', get_template_directory_uri() . '/js/form-tracking.js',           array(), get_file_version('/js/form-tracking.js'), true);
    }

    if (is_page_template('page-templates/template-near-me.php')) {
        wp_enqueue_style('near-me-page-stayles',  get_template_directory_uri() . '/css/template/near-me-page.css', array(), get_file_version('/css/template/near-me-page.css'));
        wp_enqueue_script('near-me-page-script',  get_template_directory_uri() . '/js/template/near-me-page.js',   array(), get_file_version('/js/template/near-me-page.js'), true);

        wp_enqueue_script('form-tracking-script', get_template_directory_uri() . '/js/form-tracking.js',           array(), get_file_version('/js/form-tracking.js'), true);
    }

    // Enqueue landing styles and scripts
    if (is_page_template('landing-layout.php')) {
        enqueue_landing_page_assets();

        wp_enqueue_script('form-tracking-script', get_template_directory_uri() . '/js/form-tracking.js',           array(), get_file_version('/js/form-tracking.js'), true);
    }

    // Enqueue styles and scripts for 404 error, search, blog
    if (is_404()) {
        wp_enqueue_style('error-404-style',          get_template_directory_uri() . '/css/template/error-404.css',       array(), get_file_version('/css/template/error-404.css'));
    }

    if (is_page_template('page-templates/template-sitemap.php') || is_page_template('page-templates/template-sitemap-near-me.php')) {
        // Enqueue styles for the sitemap page
        wp_enqueue_style('sitemap-page-style',       get_template_directory_uri() . '/css/template/sitemap-page.css',    array(), get_file_version('/css/template/sitemap-page.css'));
    }

    if (is_search() || is_post_type_archive('blog') || is_singular('blog') || is_tax('blog_tag') || is_page_template('page-templates/template-sitemap.php')) {
        // Enqueue styles for the blog
        wp_enqueue_style('blog-archive-style',       get_template_directory_uri() . '/css/template/blog.css',            array(), get_file_version('/css/template/blog.css'));
        wp_enqueue_style('template-post-style',       get_template_directory_uri() . '/css/modules/template-post.css',   array(), get_file_version('/css/modules/template-post.css'));

        // Enqueue scripts for the blog
        wp_enqueue_script('archive-blog-script',     get_template_directory_uri() . '/js/template/archive-blog.js',      array('jquery'), get_file_version('/js/template/archive-blog.js'), true);
        wp_enqueue_style('search-style',             get_template_directory_uri() . '/css/template/search.css',          array(), get_file_version('/css/template/search.css'));

        // Enqueue the custom script for loading more posts
        wp_enqueue_script('custom-load-more-script', get_template_directory_uri() . '/js/load-more.js',                  array(), get_file_version('/js/load-more.js'), true);

        // Enqueue form style
        wp_enqueue_style('form-style',               get_template_directory_uri() . '/css/ui-elements/form-messeng.css', array(), get_file_version('/css/ui-elements/form-messeng.css'), true);

        // Enqueue form scripts
        wp_enqueue_script('form-script',             get_template_directory_uri() . '/js/ui-elements/form-messeng.js',   array(), get_file_version('/js/ui-elements/form-messeng.js'), true);
        wp_enqueue_script('form-tracking-script',    get_template_directory_uri() . '/js/form-tracking.js',              array(), get_file_version('/js/form-tracking.js'), true);

        // Localize script to pass PHP variables to JavaScript
        wp_localize_script('custom-load-more-script', 'load_more_params', array(
            'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
            'posts_per_page' => 6,
        ));

        // Localize script for AJAX
        wp_localize_script('archive-blog-script', 'myData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));
    }

    // Enqueue Slick Slider
    custom_enqueue_slick_scripts();
}
add_action('wp_enqueue_scripts', 'custom_enqueue_assets');

// Function to enqueue assets for landing parts and create missing CSS files
function enqueue_landing_assets($template_part, $version = null) {
    // Get the component name (e.g., 'hero-block')
    $component_name = basename($template_part);

    // Check if the path contains 'template-parts/landings/'
    if (strpos($template_part, 'template-parts/landings/') !== false) {
        // Define the CSS file path
        $css_file_path = get_template_directory() . "/css/landings/{$component_name}s/{$component_name}-{$version}.css";
        $css_file_url = get_template_directory_uri() . "/css/landings/{$component_name}s/{$component_name}-{$version}.css";

        // Check if the CSS file exists
        if (!file_exists($css_file_path)) {
            // Attempt to create the missing CSS file
            if (!is_dir(dirname($css_file_path))) {
                mkdir(dirname($css_file_path), 0755, true); // Create directory if it doesn't exist
            }

            // Add default content to the CSS file
            file_put_contents($css_file_path, "/* Styles for {$component_name} - Version {$version} */\n");
        }

        // Enqueue the CSS file
        wp_enqueue_style(
            "{$component_name}-style-{$version}", // Unique name for styles
            $css_file_url,
            array(),
            get_file_version("/css/landings/{$component_name}s/{$component_name}-{$version}.css")
        );

        // Define the JS file path
        $js_file_path = get_template_directory() . "/js/landings/{$component_name}s/{$component_name}-{$version}.js";
        $js_file_url = get_template_directory_uri() . "/js/landings/{$component_name}s/{$component_name}-{$version}.js";

        // Check if the JS file exists and enqueue if it does
        if (file_exists($js_file_path)) {
            wp_enqueue_script(
                "{$component_name}-script-{$version}", // Unique name for script
                $js_file_url,
                array(), // Dependencies (if needed)
                get_file_version("/js/landings/{$component_name}s/{$component_name}-{$version}.js"),
                true // Enqueue at the bottom of the page
            );
        }
    }
}

// Function to enqueue assets for components (modules and UI elements)
function enqueue_component_assets($template_part, $version = null) {
    // Get the component name
    $component_name = basename($template_part);

    // Check for components
    if (strpos($template_part, 'template-parts/components/') !== false) {
        // Modules
        if (strpos($template_part, 'template-parts/components/modules/') !== false) {
            $css_file = "/css/modules/{$component_name}.css";
            if (file_exists(get_template_directory() . $css_file)) {
                wp_enqueue_style(
                    "{$component_name}-style", 
                    get_template_directory_uri() . $css_file,
                    array(),
                    get_file_version($css_file)
                );
            }
        
            $js_file = "/js/modules/{$component_name}.js";
            if (file_exists(get_template_directory() . $js_file)) {
                wp_enqueue_script(
                    "{$component_name}-script", 
                    get_template_directory_uri() . $js_file, 
                    array(), 
                    get_file_version($js_file),
                    true
                );
            }
        }
        // UI Elements
        elseif (strpos($template_part, 'template-parts/components/ui-elements/') !== false) {
            $css_file = "/css/ui-elements/{$component_name}.css";
            if (file_exists(get_template_directory() . $css_file)) {
                wp_enqueue_style(
                    "{$component_name}-style", 
                    get_template_directory_uri() . $css_file,
                    array(),
                    get_file_version($css_file)
                );
            }
        
            $js_file = "/js/ui-elements/{$component_name}.js";
            if (file_exists(get_template_directory() . $js_file)) {
                wp_enqueue_script(
                    "{$component_name}-script", 
                    get_template_directory_uri() . $js_file, 
                    array(),
                    get_file_version($js_file),
                    true
                );
            }
        }
    }
}

function load_component_with_assets($template_part, $version = 'v1') {
    // Check if the path is in 'template-parts/components/'
    if (strpos($template_part, 'template-parts/components/') !== false) {
        // Version is not needed for modules
        $component_path = $template_part; // Use only $template_part
    } else {
        // Get the component name, adding version for other cases
        $component_path = "{$template_part}-{$version}";
    }

    get_template_part($template_part, $version);

    // Call the function to enqueue styles and scripts
    enqueue_component_assets($template_part, $version);
}

// Function to load a template with arguments and enqueue its assets
function load_template_with_args($template, $args = [], $version = 'v1') {
    // Call to enqueue styles and scripts
    enqueue_component_assets($template, $version); // Enqueue assets for the template

    // Extract variables from the arguments array
    if (!empty($args)) {
        extract($args);
    }

    // Load the template file with the extracted arguments
    include locate_template($template . '.php'); 
}

// Enqueue assets for landing pages
function enqueue_landing_page_assets() {
    // Check if the used template matches the specified one
    if (is_page_template('landing-layout.php')) { 
        $landing_styles = array(
            'main-menu-style'        => '/css/landings/main-menus.css',
            // 'hero-blocks-style'      => '/css/landings/hero-blocks.css',
            'benefits-style'         => '/css/landings/benefits.css',
            // 'success-steps-style'    => '/css/landings/success-steps.css',
            // 'expand-clientele-style' => '/css/landings/expand-clientele.css',
            // 'more-views-style'       => '/css/landings/more-views.css',
            'start-work-style'       => '/css/landings/start-work.css',
            // 'faqs-style'             => '/css/landings/faqs.css',
            'fitback-button-style'   => '/css/landings/fitback-button.css',
            'footer-menu-style'      => '/css/landings/footer-menus.css',
        );

        foreach ($landing_styles as $handle => $path) {
            wp_enqueue_style($handle, get_template_directory_uri() . $path, array(), get_file_version($path));
        }

        $landing_scripts = array(
            'benefits-script'   => '/js/landings/benefits.js',
            'more-views-script' => '/js/landings/more-views.js',
            'faqs-script'       => '/js/landings/faqs.js',
        );

        foreach ($landing_scripts as $handle => $path) {
            wp_enqueue_script($handle, get_template_directory_uri() . $path, array(), get_file_version($path), true);
        }
    }
}

add_action('wp_enqueue_scripts', 'enqueue_landing_page_assets');

// Enqueue scripts for utils
function enqueue_utils_scripts_assets() {
    wp_enqueue_script('utils-script', get_template_directory_uri() . '/utils.js', array(), get_file_version('/utils.js'), true);
}
add_action('wp_enqueue_scripts', 'enqueue_utils_scripts_assets');

function custom_enqueue_slick_scripts() {
    wp_enqueue_style('slick-css', get_template_directory_uri() . '/includes/slick/slick.css',    array(), get_file_version('/includes/slick/slick.css'));
    wp_enqueue_script('slick-js', get_template_directory_uri() . '/includes/slick/slick.min.js', array(), get_file_version('/includes/slick/slick.min.js'), true);
}
?>