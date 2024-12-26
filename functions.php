<?php
// Enqueue support for selecting a featured image for posts
function my_theme_setup(){
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'my_theme_setup');

// Enqueue support for adding SVG files
function rmn_custom_mime_types( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'rmn_custom_mime_types' );

// Disable all feed links and show 404 error on feed pages
remove_action( 'do_feed_rdf',  'do_feed_rdf',  10, 1 );
remove_action( 'do_feed_rss',  'do_feed_rss',  10, 1 );
remove_action( 'do_feed_rss2', 'do_feed_rss2', 10, 1 );
remove_action( 'do_feed_atom', 'do_feed_atom', 10, 1 );

function disable_all_feeds() {
    if (is_feed()) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        nocache_headers();
    }
}
add_action('template_redirect', 'disable_all_feeds');

// Remove feed links from wp_head
add_action( 'wp', function() {
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'rsd_link' );
} );

// Include the main theme setup file
require_once get_template_directory() . '/includes/theme-setup/theme-setup.php';

// Include script enqueue functions for styles and scripts
require_once get_template_directory() . '/includes/scripts/enqueue-scripts.php';

// Include custom post types registration functions
require_once get_template_directory() . '/includes/custom-post-types/custom-post-types.php';

// Include AJAX functions for handling front-end requests
require_once get_template_directory() . '/includes/ajax-functions.php';

// Include functions related to search functionality
require_once get_template_directory() . '/includes/search/search-functions.php';

// Include functions for modifying and displaying menus
require_once get_template_directory() . '/includes/admin/menu-functions.php';

// Include redirection rules for specific URLs
// require_once get_template_directory() . '/includes/redirects/redirects.php';

// Include security-related functions (e.g., headers and content security)
require_once get_template_directory() . '/includes/security/security-functions.php';

// Include settings for the theme's admin panel (e.g., options and configurations)
require_once get_template_directory() . '/includes/admin/admin-settings.php';

// Include functions for exporting and importing blog posts
require_once get_template_directory() . '/includes/export-import/blog-posts-export-import.php';

// Include functions for exporting and importing faqs posts
require_once get_template_directory() . '/includes/export-import/faqs-export-import.php';

// Uncomment the next line if pages export/import functionality is needed
require_once get_template_directory() . '/includes/export-import/pages-export-import.php';
?>