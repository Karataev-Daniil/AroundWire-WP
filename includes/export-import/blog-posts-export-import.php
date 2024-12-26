<?php
// Add a page to the admin menu for exporting
function add_export_blog_page() {
    add_submenu_page(
        'edit.php?post_type=blog',
        'Export Blog Posts',
        'Export Posts',
        'manage_options',
        'export_blog_posts',
        'render_export_blog_page'
    );
}
add_action('admin_menu', 'add_export_blog_page');

// Render the export page
function render_export_blog_page() {
    ?>
    <div class="wrap">
        <h1>Export Blog Posts</h1>
        <p>Click the button below to download a ZIP file of all blog posts.</p>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="export_blog_posts">
            <?php wp_nonce_field('export_blog_posts_nonce'); // Add CSRF protection ?>
            <button type="submit" class="button button-primary">Export to ZIP</button>
        </form>
    </div>
    <?php
}

function handle_export_blog_posts() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Create a temporary directory for export
    $upload_dir = wp_upload_dir();
    $export_dir = $upload_dir['basedir'] . '/blog_export_' . time();
    if (!file_exists($export_dir)) {
        mkdir($export_dir, 0755, true);
    }

    // Path to the CSV file
    $csv_file = $export_dir . '/blog_posts.csv';

    // Open stream for writing CSV
    $output = fopen($csv_file, 'w');
    fputcsv($output, [
        'Title', 'Content', 'Excerpt', 'Author', 'Tags', 
        'Categories', 'Images', 'Publish Date', 
        'SEO Title', 'SEO Description', 'Focus Keyword', 'FAQs'
    ]);

    // Create a folder for images
    $images_dir = $export_dir . '/images';
    mkdir($images_dir, 0755, true);

    // Query all posts
    $args = ['post_type' => 'blog', 'posts_per_page' => -1];
    $posts = get_posts($args);

    foreach ($posts as $post) {
        // Fill in post data
        $post_data = [
            $post->post_title,
            $post->post_content,
            $post->post_excerpt,
            get_the_author_meta('display_name', $post->post_author),
            implode(',', wp_get_post_terms($post->ID, 'blog_tag', ['fields' => 'names'])),
            implode(',', wp_get_post_terms($post->ID, 'category', ['fields' => 'names'])),
            '', // Field for images, fill later
            $post->post_date,
            get_post_meta($post->ID, '_yoast_wpseo_title', true),
            get_post_meta($post->ID, '_yoast_wpseo_metadesc', true),
            get_post_meta($post->ID, '_yoast_wpseo_focuskw', true),
            '' // Field for FAQs, fill later
        ];

        $images_list = []; // List to store image names

        // Get thumbnail
        $thumbnail_id = get_post_thumbnail_id($post->ID);
        if ($thumbnail_id) {
            $thumbnail_path = get_attached_file($thumbnail_id);
            $thumbnail_filename = basename($thumbnail_path);
            // Avoid duplication
            if (!in_array($thumbnail_filename, $images_list)) {
                copy($thumbnail_path, $images_dir . '/' . $thumbnail_filename);
                $images_list[] = $thumbnail_filename; // Add thumbnail name
            }
        }

        // Get post images
        $images = get_attached_media('image', $post->ID);
        foreach ($images as $image) {
            $image_path = get_attached_file($image->ID);
            $image_filename = basename($image_path);
            // Avoid duplication
            if (!in_array($image_filename, $images_list)) {
                copy($image_path, $images_dir . '/' . $image_filename);
                $images_list[] = $image_filename; // Add image name to the list
            }
        }

        // Write all image names to the CSV string
        $post_data[6] = implode(',', $images_list);

        // Get FAQs (ACF Repeater)
        if (have_rows('post-faqs', $post->ID)) {
            $faqs = [];
            while (have_rows('post-faqs', $post->ID)) {
                the_row();
                $faq_title = get_sub_field('faq-title');
                $faq_text = get_sub_field('faq-text');

                if (!empty($faq_title) && !empty($faq_text)) {
                    $faq_title = addslashes($faq_title);
                    $faq_text = addslashes($faq_text);
                    $faqs[] = "Title: \"$faq_title\"; Text: \"$faq_text\"";
                }
            }
        
            if (!empty($faqs)) {
                $post_data[11] = implode(" || ", $faqs);
            }
        }

        // Write row to CSV
        fputcsv($output, $post_data);
    }

    fclose($output);

    // Create ZIP archive
    $zip_file = $export_dir . '.zip';
    $zip = new ZipArchive();

    if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE) {
        // Add CSV file to ZIP
        $zip->addFile($csv_file, 'blog_posts.csv');

        // Add images to ZIP
        $files = scandir($images_dir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $zip->addFile($images_dir . '/' . $file, 'images/' . $file);
            }
        }

        // Close the archive
        $zip->close();
    } else {
        // Handle error opening ZIP
        die('Could not create ZIP archive.');
    }

    // Check headers
    if (headers_sent($file, $line)) {
        die("Error: headers already sent in $file on line $line");
    }

    // Download ZIP
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="blog_export_' . date('Y-m-d') . '.zip"');
    header('Content-Length: ' . filesize($zip_file)); // Specify file size
    readfile($zip_file);
    unlink($zip_file); // Delete ZIP file after download
    exit; // End script execution
}
add_action('admin_post_export_blog_posts', 'handle_export_blog_posts');

// Add a page to the admin menu for importing
function add_import_blog_page() {
    add_submenu_page(
        'edit.php?post_type=blog',
        'Import Blog Posts',
        'Import Posts',
        'manage_options',
        'import_blog_posts',
        'render_import_blog_page'
    );
}
add_action('admin_menu', 'add_import_blog_page');

function check_upload_size() {
    $upload_size = wp_max_upload_size();
    echo 'Максимальный размер загрузки: ' . size_format($upload_size);
}


// Render the import page
function render_import_blog_page() {
    ?>
    <div class="wrap">
        <h1>Import Blog Posts</h1>
        <p>Upload a ZIP file to import blog posts and images.</p>
        <div class="import-form">
            <form id="import-blog-form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="import_blog_posts">
                <?php wp_nonce_field('import_blog_posts_nonce'); // Add CSRF protection ?>
                <input type="file" name="blog_zip" accept=".zip" required> <!-- Изменено на .zip -->
                <button type="submit" class="button button-primary">Import from ZIP</button>
            </form>
            <div id="import-message" style="margin-top: 10px;"></div> <!-- Container for messages -->
            <?php check_upload_size() ?>
        </div>
    </div>
    <script type="text/javascript">
        (function($) {
            $('#import-blog-form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form behavior

                var formData = new FormData(this);
                formData.append('action', 'import_blog_posts'); // Add action to form data

                $.ajax({
                    url: ajaxurl, // AJAX URL provided by WordPress
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var message = response.success ? response.data : response.data;
                        $('#import-message').html('<div class="notice ' + (response.success ? 'notice-success' : 'notice-error') + '"><p>' + message + '</p></div>');
                    },
                    error: function() {
                        $('#import-message').html('<div class="notice notice-error"><p>An error occurred while processing the request.</p></div>');
                    }
                });
            });
        })(jQuery);
    </script>
    <?php
}

function handle_import_blog_posts() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('You do not have sufficient permissions.');
        return;
    }

    if (isset($_FILES['blog_zip']) && $_FILES['blog_zip']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['blog_zip']['tmp_name'];

        // Check and create a temporary directory
        $upload_dir = wp_upload_dir();
        $import_dir = $upload_dir['basedir'] . '/blog_import_' . time();
        mkdir($import_dir, 0755, true);

        // Unzip the ZIP file
        $zip = new ZipArchive();
        if ($zip->open($file) === TRUE) {
            $zip->extractTo($import_dir);
            $zip->close();

            // Read the CSV file
            $csv_file = $import_dir . '/blog_posts.csv';
            if (file_exists($csv_file)) {
                $handle = fopen($csv_file, 'r');
                fgetcsv($handle); // Skip the header

                // Directory for images
                $image_dir = $upload_dir['basedir'] . '/imported_images/';
                if (!file_exists($image_dir)) { 
                    mkdir($image_dir, 0755, true);
                }

                while (($data = fgetcsv($handle)) !== FALSE) {
                    $post_title = $data[0];
                    $post_content = $data[1];
                    $post_excerpt = $data[2];
                    $post_tags = !empty($data[4]) ? explode(',', $data[4]) : [];
                    $categories = !empty($data[5]) ? explode(',', $data[5]) : [];
                    $post_images = !empty($data[6]) ? explode(',', $data[6]) : []; // Check images
                    $post_date = $data[7];
                    $seo_title = $data[8]; // SEO Title
                    $seo_description = $data[9]; // SEO Description
                    $focus_keyword = $data[10]; // Focus Keyword
                    $faq_json = $data[11];

                    // Get current user's ID as author
                    $post_author = get_current_user_id();

                    $post_content = update_image_urls($post_content);

                    // Check if a post with the same title exists
                    $existing_post = get_page_by_title($post_title, OBJECT, 'blog');

                    if ($existing_post) {
                        // Update the post
                        $post_id = $existing_post->ID;
                        $new_post = [
                            'ID'            => $post_id, // Specify ID for update
                            'post_title'    => $post_title,
                            'post_content'  => $post_content,
                            'post_excerpt'  => $post_excerpt,
                            'post_status'   => 'publish',
                            'post_author'   => $post_author,
                            'post_date'     => $post_date,
                            'post_type'     => 'blog',
                        ];
                        wp_update_post($new_post);
                    } else {
                        // Create a new post
                        $new_post = [
                            'post_title'    => $post_title,
                            'post_content'  => $post_content,
                            'post_excerpt'  => $post_excerpt,
                            'post_status'   => 'publish',
                            'post_author'   => $post_author,
                            'post_date'     => $post_date,
                            'post_type'     => 'blog',
                        ];

                        // Insert post and get its ID
                        $post_id = wp_insert_post($new_post);
                    }

                    // Set taxonomies (tags and categories)
                    if (!empty($post_tags)) {
                        wp_set_object_terms($post_id, $post_tags, 'blog_tag');
                    }
                    
                    if (!empty($categories)) {
                        foreach ($categories as $category) {
                            $cat_id = term_exists(trim($category), 'category');
                            if ($cat_id === 0 || $cat_id === null) {
                                $cat_id = wp_create_category(trim($category));
                            } else {
                                $cat_id = $cat_id['term_id']; // Get ID of existing category
                            }
                            wp_set_post_terms($post_id, [$cat_id], 'category', true); // Attach category to post
                        }
                    } else {
                        wp_set_post_terms($post_id, [], 'category');
                    }

                    // Set the thumbnail and upload images
                    if (!empty($post_images) && isset($post_images[0])) {
                        $image = $post_images[0]; // Get only the first image
                        $image_path = $import_dir . '/images/' . $image;

                        // Path to the image in the common folder
                        $new_image_path = $image_dir . $image;

                        if (file_exists($image_path)) {
                            // Check if the image already exists in the media library
                            $attachment_id = attachment_url_to_postid(wp_upload_dir()['url'] . '/imported_images/' . $image);
                            
                            if (!$attachment_id) {
                                // If the image does not exist, check if a file with the same name exists
                                if (!file_exists($new_image_path)) {
                                    // Move the image to the common folder
                                    rename($image_path, $new_image_path);

                                    $filetype = wp_check_filetype(basename($new_image_path), null);
                                    $attachment = [
                                        'post_mime_type' => $filetype['type'],
                                        'post_title'     => sanitize_file_name($image),
                                        'post_content'   => '',
                                        'post_status'    => 'inherit',
                                    ];

                                    // Insert the image and get the ID
                                    $attach_id = wp_insert_attachment($attachment, $new_image_path, $post_id);

                                    // Set the thumbnail
                                    set_post_thumbnail($post_id, $attach_id);
                                } else {
                                    // If the image already exists in the common folder, just set it as the thumbnail
                                    $attachment_id = attachment_url_to_postid(wp_upload_dir()['url'] . '/imported_images/' . $image);
                                    set_post_thumbnail($post_id, $attachment_id);
                                }
                            } else {
                                // If the image already exists, just set it as the thumbnail
                                set_post_thumbnail($post_id, $attachment_id);
                            }
                        }
                    }

                    if (!empty($post_images)) {
                        foreach ($post_images as $index => $image) {
                            // Skip the first image, as it has already been processed
                            if ($index === 0) {
                                continue;
                            }
                    
                            $image_path = $import_dir . '/images/' . $image;
                            $new_image_path = $image_dir . $image;
                    
                            if (file_exists($image_path)) {
                                // Check if a file with the same name exists in the common folder
                                if (!file_exists($new_image_path)) {
                                    // Move the image to the common folder
                                    rename($image_path, $new_image_path);
                    
                                    $filetype = wp_check_filetype(basename($new_image_path), null);
                                    $attachment = [
                                        'post_mime_type' => $filetype['type'],
                                        'post_title'     => sanitize_file_name($image),
                                        'post_content'   => '',
                                        'post_status'    => 'inherit',
                                    ];
                    
                                    // Insert the image and get the ID
                                    wp_insert_attachment($attachment, $new_image_path, $post_id);
                                }
                            }
                        }
                    }

                    // Set Yoast SEO metadata
                    if ($seo_title) {
                        update_post_meta($post_id, '_yoast_wpseo_title', $seo_title);
                    }
                    if ($seo_description) {
                        update_post_meta($post_id, '_yoast_wpseo_metadesc', $seo_description);
                    }
                    if ($focus_keyword) {
                        update_post_meta($post_id, '_yoast_wpseo_focuskw', $focus_keyword);
                    }

                    // Import FAQ data (if available)
                    if (!empty($faq_json)) {
                        // Decode the FAQ JSON (or process it if it's a delimited string)
                        $faqs = explode(" || ", $faq_json);
                        $faq_data = [];
                        foreach ($faqs as $faq) {
                            // Parse each FAQ into title and text
                            preg_match('/Title: "(.*?)"; Text: "(.*)"/', $faq, $matches); // Removed outer quotes from the regex

                            if (!empty($matches[1]) && !empty($matches[2])) {
                                // Trim any extra spaces or quotes from title and text
                                $faq_data[] = [
                                    'faq-title' => trim($matches[1], '"'), // Remove extra quotes
                                    'faq-text'  => trim($matches[2], '"')  // Remove extra quotes
                                ];
                            }
                        }
                        // Update the FAQ repeater field
                        if (!empty($faq_data)) {
                            update_field('post-faqs', $faq_data, $post_id);
                        }
                    }
                }

                fclose($handle);
                // Remove the temporary directory and its contents
                rrmdir($import_dir); 

                wp_send_json_success('Posts imported successfully.');
            } else {
                wp_send_json_error('CSV file not found in the ZIP.');
            }
        } else {
            wp_send_json_error('Failed to open ZIP file.');
        }
    } else {
        wp_send_json_error('File upload error.');
    }
}

add_action('wp_ajax_import_blog_posts', 'handle_import_blog_posts');


function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . "/" . $object)) {
                    rrmdir($dir . "/" . $object);
                } else {
                    unlink($dir . "/" . $object);
                }
            }
        }
        rmdir($dir);
    }
}

// Function to update image URLs
function update_image_urls($content) {
    $domain = get_site_url(); // Get the site domain
    $new_base_url = $domain . '/wp-content/uploads/imported_images/';

    // Replace all image URLs in the content
    $content = preg_replace_callback(
        '/<img[^>]+src=["\']([^"\']+)["\']/', // Find <img> tags
        function ($matches) use ($new_base_url) {
            $image_name = basename($matches[1]); // Get the file name
            return str_replace($matches[1], $new_base_url . $image_name, $matches[0]); // Replace URL
        },
        $content
    );

    return $content;
}
?>