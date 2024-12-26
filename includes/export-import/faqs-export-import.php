<?php
// Add export page to admin menu
function add_export_faqs_page() {
    add_submenu_page(
        'edit.php?post_type=faqs',
        'Export FAQs',
        'Export',
        'manage_options',
        'export_faqs',
        'render_export_faq_page'
    );
}
add_action('admin_menu', 'add_export_faqs_page');

// Render the export page
function render_export_faq_page() {
    ?>
    <div class="wrap">
        <h1>Export FAQs</h1>
        <p>Click the button below to download a CSV file of all FAQs.</p>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="export_faqs">
            <?php wp_nonce_field('export_faqs_nonce'); // Add CSRF protection ?>
            <button type="submit" class="button button-primary">Export to CSV</button>
        </form>
    </div>
    <?php
}

// Hook into the admin_post action for the export
add_action('admin_post_export_faqs', 'handle_export_faqs');

function handle_export_faqs() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Query all FAQs
    $args = [
        'post_type'      => 'faqs',
        'posts_per_page' => -1, // Get all FAQs
        'post_status'    => 'publish',
    ];
    $faqs = get_posts($args);

    if (empty($faqs)) {
        die('No FAQs found to export.');
    }

    // Set headers to download CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="faqs_export_' . date('Y-m-d') . '.csv"');
    
    // Open the PHP output stream
    $output = fopen('php://output', 'w');
    
    // Add the CSV headers
    fputcsv($output, ['Title', 'Content', 'Category']);

    foreach ($faqs as $faq) {
        // Get categories
        $categories = get_the_terms($faq->ID, 'faq_category');
        $category_names = $categories ? wp_list_pluck($categories, 'name') : [];

        // Prepare FAQ data for export
        $faq_data = [
            $faq->post_title,
            $faq->post_content,
            implode(',', $category_names), // Categories will be shown as a comma-separated string
        ];

        // Write row to CSV
        fputcsv($output, $faq_data);
    }

    fclose($output); // Close the output stream

    exit; // End script execution
}

// Add import page to admin menu
function add_import_faqs_page() {
    add_submenu_page(
        'edit.php?post_type=faqs',
        'Import FAQs',
        'Import',
        'manage_options',
        'import_faqs',
        'render_import_page'
    );
}
add_action('admin_menu', 'add_import_faqs_page');

// Render import page
function render_import_page() {
    if (isset($_POST['import_faqs'])) {
        check_admin_referer('import_faqs_action', 'import_faqs_nonce');
        if (!empty($_FILES['import_file']['tmp_name'])) {
            import_faqs_from_csv($_FILES['import_file']['tmp_name']);
        }
    }
    ?>
    <div class="wrap">
        <h1>Import FAQs</h1>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('import_faqs_action', 'import_faqs_nonce'); ?>
            <input type="file" name="import_file" accept=".csv" required>
            <button type="submit" name="import_faqs" class="button button-primary">Import</button>
        </form>
    </div>
    <?php
}

// Import FAQs from CSV
function import_faqs_from_csv($file) {
    if (($handle = fopen($file, 'r')) !== FALSE) {
        $headers = fgetcsv($handle); // Skip headers

        while (($data = fgetcsv($handle)) !== FALSE) {
            // Ensure proper column mapping
            $title = $data[0];       // Title column
            $content = $data[1];     // Content column
            $category = $data[2];    // Category column

            // Custom query to check if the post exists by title (including trash)
            global $wpdb;
            $post_id = $wpdb->get_var(
                $wpdb->prepare("
                    SELECT ID FROM {$wpdb->posts} 
                    WHERE post_title = %s 
                    AND post_type = 'faqs'
                    AND post_status != 'trash'
                    LIMIT 1
                ", $title)
            );

            if ($post_id) {
                // If the post exists, update it
                wp_update_post([
                    'ID'           => $post_id,
                    'post_content' => $content,
                ]);

                // Update or assign the category
                if (!empty($category)) {
                    // Check if the category exists by name
                    $term = get_term_by('name', $category, 'faq_category');
                    if ($term) {
                        // If the category exists, use its term ID
                        $term_id = $term->term_id;
                    } else {
                        // If the category doesn't exist, create it and get its ID
                        $term = wp_insert_term($category, 'faq_category');
                        if (is_wp_error($term)) {
                            error_log('Error creating term: ' . $term->get_error_message());
                            continue; // Skip this post if category creation fails
                        }
                        $term_id = $term['term_id'];
                    }

                    // Assign the category to the post
                    wp_set_object_terms($post_id, $term_id, 'faq_category');
                }
            } else {
                // If the post does not exist, create a new FAQ post
                $post_id = wp_insert_post([
                    'post_title'   => $title,
                    'post_content' => $content,
                    'post_type'    => 'faqs',
                    'post_status'  => 'publish',
                ]);

                // Assign category if it's provided
                if ($post_id && !empty($category)) {
                    // Check if the category exists by name
                    $term = get_term_by('name', $category, 'faq_category');
                    if ($term) {
                        // If the category exists, use its term ID
                        $term_id = $term->term_id;
                    } else {
                        // If the category doesn't exist, create it and get its ID
                        $term = wp_insert_term($category, 'faq_category');
                        if (is_wp_error($term)) {
                            error_log('Error creating term: ' . $term->get_error_message());
                            continue; // Skip this post if category creation fails
                        }
                        $term_id = $term['term_id'];
                    }

                    // Assign the category to the post
                    wp_set_object_terms($post_id, $term_id, 'faq_category');
                }
            }

            // Reset the query
            wp_reset_postdata();
        }

        fclose($handle);
    }
}
?>
