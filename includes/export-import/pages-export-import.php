<?php
// Function to add export page
function add_export_pages_menu() {
    add_submenu_page(
        'edit.php?post_type=page',
        'Export Pages',
        'Export Pages',
        'manage_options',
        'export_pages',
        'render_export_pages_to_zip'
    );
}
add_action('admin_menu', 'add_export_pages_menu');

// Function to render the export page
function render_export_pages_to_zip() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Export Pages', 'text-domain'); ?></h1>
        <p><?php esc_html_e('Click the button below to download a ZIP file of all pages.', 'text-domain'); ?></p>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="export_pages">
            <?php wp_nonce_field('export_pages_nonce'); ?>
            <button type="submit" class="button button-primary"><?php esc_html_e('Export to ZIP', 'text-domain'); ?></button>
        </form>
    </div>
    <?php
}

// Recursive function to handle ACF fields, including repeaters, colors, and images
function export_acf_fields( $post_id ) {
    $fields = get_fields( $post_id );
    $acf_data = array();

    if ( ! empty( $fields ) ) {
        foreach ( $fields as $key => $value ) {
            // Get the field object to retrieve key, label, name, type, and subfields for repeaters
            $field = get_field_object( $key, $post_id );

            // Add key, label, name, and type to the field data
            $field_data = array(
                'key'   => isset( $field['key'] ) ? $field['key'] : '',
                'label' => isset( $field['label'] ) ? $field['label'] : '',
                'name'  => isset( $field['name'] ) ? $field['name'] : '',
                'type'  => isset( $field['type'] ) ? $field['type'] : '',  // Save the field type
            );

            // Handle color fields
            if ( isset( $field['type'] ) && $field['type'] === 'color_picker' ) {
                $field_data['value'] = $value;  // Save the color value
            }

            // Add sub_fields to the field data (for repeaters and flexible content)
            if ( isset( $field['sub_fields'] ) && is_array( $field['sub_fields'] ) ) {
                $field_data['sub_fields'] = array();
                foreach ( $field['sub_fields'] as $sub_field ) {
                    $sub_field_data = array(
                        'key'   => $sub_field['key'],
                        'label' => $sub_field['label'],
                        'name'  => $sub_field['name'],
                        'type'  => $sub_field['type'],
                    );

                    // If subfield is color_picker, save the color value
                    if ( $sub_field['type'] === 'color_picker' ) {
                        $sub_field_data['value'] = isset( $value[ $sub_field['name'] ] ) ? $value[ $sub_field['name'] ] : '';
                    }

                    $field_data['sub_fields'][] = $sub_field_data;
                }
            }

            // If the field is a repeater, loop through and process each row
            if ( isset( $field['type'] ) && $field['type'] === 'repeater' && is_array( $value ) ) {
                $repeater_data = array();
                foreach ( $value as $row ) {
                    $row_data = array();
                    foreach ( $row as $sub_key => $sub_value ) {
                        // If the sub field is an image, we need to process it
                        if ( is_array( $sub_value ) && isset( $sub_value['ID'] ) ) {
                            $image_path = get_attached_file( $sub_value['ID'] );
                            $row_data[$sub_key] = array(
                                'attachment_id' => $sub_value['ID'],
                                'url' => $sub_value['url'],
                                'path' => $image_path,
                                'parent_repeater' => $field['key'], // Add parent repeater key for relation
                            );
                        }
                        // Handle color picker fields inside repeater rows
                        elseif ( is_string( $sub_value ) && isset( $field['sub_fields'] ) ) {
                            // Check if subfield is a color picker and save the value
                            $sub_field = array_filter( $field['sub_fields'], function( $sub ) use ( $sub_key ) {
                                return $sub['name'] === $sub_key && $sub['type'] === 'color_picker';
                            });

                            if ( ! empty( $sub_field ) ) {
                                $row_data[$sub_key] = $sub_value; // Save color value
                            } else {
                                $row_data[$sub_key] = $sub_value;
                            }
                        } else {
                            $row_data[$sub_key] = $sub_value;
                        }
                    }
                    $repeater_data[] = $row_data;  // Add the row data to the repeater
                }
                $field_data['value'] = $repeater_data;
            } else {
                // For non-repeater fields, just save their value
                $field_data['value'] = $value;
            }

            // Add this field data to the overall ACF data array
            $acf_data[$key] = $field_data;
        }
    }

    return $acf_data;
}

function export_pages_to_zip() {
    if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'export_pages_nonce' ) ) {
        die('Permission denied: Nonce verification failed');
    }

    $pages = get_posts( array( 'post_type' => 'page', 'numberposts' => -1 ) );
    $zip = new ZipArchive();

    $upload_dir = wp_upload_dir();
    $zip_file = $upload_dir['path'] . '/exported_pages.zip';

    if ( $zip->open( $zip_file, ZIPARCHIVE::CREATE ) === TRUE ) {
        foreach ( $pages as $page ) {
            $page_data = array(
                'title' => $page->post_title,
                'acf' => export_acf_fields( $page->ID ),
                'url' => wp_make_link_relative( get_permalink( $page->ID ) ),
                'content' => apply_filters( 'the_content', $page->post_content ),
            );

            $yoast_data = array(
                'yoast_title'       => get_post_meta( $page->ID, '_yoast_wpseo_title', true ),
                'yoast_description' => get_post_meta( $page->ID, '_yoast_wpseo_metadesc', true ),
                'yoast_keywords'    => get_post_meta( $page->ID, '_yoast_wpseo_focuskw', true ),
            );

            if ( ! empty( $yoast_data['yoast_title'] ) || ! empty( $yoast_data['yoast_description'] ) || ! empty( $yoast_data['yoast_keywords'] ) ) {
                $page_data['yoast'] = $yoast_data;
            }

            $acf_file = tempnam( sys_get_temp_dir(), 'acf_' . $page->ID );
            file_put_contents( $acf_file, json_encode( $page_data['acf'] ) );
            $zip->addFile( $acf_file, 'pages/' . $page->post_name . '/acf.json' );

            $title_file = tempnam( sys_get_temp_dir(), 'title_' . $page->ID );
            file_put_contents( $title_file, json_encode( array( 'title' => $page->post_title ) ) );
            $zip->addFile( $title_file, 'pages/' . $page->post_name . '/title.json' );

            $url_file = tempnam( sys_get_temp_dir(), 'url_' . $page->ID );
            file_put_contents( $url_file, json_encode( array( 'url' => $page_data['url'] ) ) );
            $zip->addFile( $url_file, 'pages/' . $page->post_name . '/url.json' );

            $content_file = tempnam( sys_get_temp_dir(), 'content_' . $page->ID );
            file_put_contents( $content_file, json_encode( array( 'content' => $page_data['content'] ) ) );
            $zip->addFile( $content_file, 'pages/' . $page->post_name . '/content.json' );

            $yoast_file = tempnam( sys_get_temp_dir(), 'yoast_' . $page->ID );
            file_put_contents( $yoast_file, json_encode( $yoast_data ) );
            $zip->addFile( $yoast_file, 'pages/' . $page->post_name . '/yoast.json' );

            $attachments = get_attached_media( 'image', $page->ID );
            foreach ( $attachments as $attachment ) {
                $image_path = get_attached_file( $attachment->ID );
                $image_filename = basename( $image_path );
                $image_new_path = 'pages/' . $page->post_name . '/images/' . $image_filename;
                $zip->addFile( $image_path, $image_new_path );
            }
        }

        $zip->close();

        header( 'Content-Type: application/zip' );
        header( 'Content-Disposition: attachment; filename="exported_pages.zip"' );
        header( 'Content-Length: ' . filesize( $zip_file ) );
        readfile( $zip_file );

        // Очистка
        unlink( $zip_file );
        exit;
    } else {
        die('Failed to create ZIP');
    }
}

add_action( 'admin_post_export_pages', 'export_pages_to_zip' );

// Function to add import page
function add_import_pages_menu() {
    add_submenu_page(
        'edit.php?post_type=page',
        'Import Pages',
        'Import Pages',
        'manage_options',
        'import_pages',
        'render_import_pages_from_zip'
    );
}
add_action('admin_menu', 'add_import_pages_menu');

// Function to render the import page
function render_import_pages_from_zip() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Import Pages', 'text-domain'); ?></h1>
        <p><?php esc_html_e('Upload a ZIP file to import pages, ACF fields, and images.', 'text-domain'); ?></p>
        <form method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="import_pages">
            <?php wp_nonce_field( 'import_pages_nonce' ); ?>
            <input type="file" name="import_file" required>
            <button type="submit">Import Pages</button>
        </form>
    </div>
    <?php
}

// Recursive function to import ACF fields, including repeaters, colors, and images
function import_acf_fields( $post_id, $acf_data ) {
    if ( ! empty( $acf_data ) ) {
        foreach ( $acf_data as $key => $field_data ) {
            // Handle the color picker field
            if ( isset( $field_data['type'] ) && $field_data['type'] === 'color_picker' ) {
                update_field( $key, $field_data['value'], $post_id );
            }

            // Handle repeaters
            if ( isset( $field_data['type'] ) && $field_data['type'] === 'repeater' && isset( $field_data['value'] ) ) {
                $repeater_rows = array();

                // Check if 'value' is an array before iterating
                if ( is_array( $field_data['value'] ) ) {
                    foreach ( $field_data['value'] as $row ) {
                        $row_data = array();
                        foreach ( $row as $sub_key => $sub_value ) {
                            // Handle color pickers inside repeater rows
                            if ( isset( $sub_value ) && is_string( $sub_value ) && ! empty( $sub_value ) ) {
                                $row_data[$sub_key] = $sub_value;
                            }

                            // Handle images inside repeater fields
                            if ( isset( $sub_value['url'] ) && isset( $sub_value['path'] ) ) {
                                // Extract image filename from the URL
                                $image_filename = basename( $sub_value['url'] );

                                // Check if image exists in the media library by file name
                                $existing_image_url = get_image_url_by_filename( $image_filename );

                                if ( $existing_image_url ) {
                                    // If image exists, update the field with the existing image URL
                                    $row_data[$sub_key] = $existing_image_url;
                                } else {
                                    // Otherwise, upload the image
                                    $upload_dir = wp_upload_dir();
                                    $imported_images_dir = $upload_dir['basedir'] . '/imported_images_pages/';

                                    if ( ! file_exists( $imported_images_dir ) ) {
                                        wp_mkdir_p( $imported_images_dir );
                                    }

                                    // Image import logic
                                    $new_image_path = $imported_images_dir . $image_filename;

                                    // Ensure unique filename
                                    $counter = 1;
                                    while ( file_exists( $new_image_path ) ) {
                                        $image_filename = pathinfo( $image_filename, PATHINFO_FILENAME ) . '-' . $counter . '.' . pathinfo( $image_filename, PATHINFO_EXTENSION );
                                        $new_image_path = $imported_images_dir . $image_filename;
                                        $counter++;
                                    }

                                    // Upload image
                                    $image_data = file_get_contents( $sub_value['path'] );
                                    $upload = wp_upload_bits( $image_filename, null, $image_data );
                                    if ( $upload['error'] ) {
                                        continue;
                                    }

                                    // Add image as attachment
                                    $attachment = array(
                                        'guid'           => $upload_dir['url'] . '/imported_images_pages/' . $image_filename,
                                        'post_mime_type' => mime_content_type( $upload['file'] ),
                                        'post_title'     => $image_filename,
                                        'post_content'   => '',
                                        'post_status'    => 'inherit'
                                    );

                                    $attachment_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );
                                    require_once( ABSPATH . 'wp-admin/includes/image.php' );
                                    $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
                                    wp_update_attachment_metadata( $attachment_id, $attachment_data );

                                    // Debugging: log the URL of the uploaded image
                                    error_log( 'Uploaded image URL: ' . $upload_dir['url'] . '/imported_images_pages/' . $image_filename );

                                    // Save image URL to the row data
                                    $row_data[$sub_key] = $upload_dir['url'] . '/imported_images_pages/' . $image_filename;
                                }
                            }
                        }
                        $repeater_rows[] = $row_data;
                    }

                    // Update the repeater field
                    update_field( $key, $repeater_rows, $post_id );
                }
            } else {
                // For non-repeater fields, just update the value
                update_field( $key, $field_data['value'], $post_id );
            }
        }
    }
}

// Helper function to get image URL by file name
function get_image_url_by_filename( $filename ) {
    global $wpdb;
    
    // Look for the image in the media library based on the filename
    $attachment_url = $wpdb->get_var( $wpdb->prepare( "
        SELECT guid FROM $wpdb->posts 
        WHERE post_title = %s
        AND post_type = 'attachment'
    ", $filename ) );

    // Debugging: log the result of the image search
    error_log( 'Image search result for ' . $filename . ': ' . $attachment_url );

    return $attachment_url ? $attachment_url : false;
}

// Main function to import pages from ZIP
function import_pages_from_zip() {
    // Nonce verification for security
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'import_pages_nonce')) {
        wp_die('Permission denied: Nonce verification failed');
    }

    // Checking if the ZIP file is uploaded
    if (isset($_FILES['import_file']) && $_FILES['import_file']['error'] == 0) {
        $zip_file = $_FILES['import_file']['tmp_name'];

        $zip = new ZipArchive();
        if ($zip->open($zip_file) === TRUE) {
            // Extracting the ZIP file
            $extract_dir = wp_upload_dir()['path'] . '/imported_pages/';
            $zip->extractTo($extract_dir);
            $zip->close();

            // Reading page data from ZIP
            $pages_dir = $extract_dir . 'pages/';
            $titles = glob($pages_dir . '*/title.json');

            foreach ($titles as $title_file) {
                // Reading page title
                $title_data = json_decode(file_get_contents($title_file), true);
                $page_title = $title_data['title'];

                // Searching for an existing page with the same title
                $query = new WP_Query(array(
                    'post_type' => 'page',
                    'title' => $page_title,  // Search by title only
                    'post_status' => 'any',  // Search across all statuses (drafts, published, etc.)
                    'posts_per_page' => 1,   // Limit the search to 1 result
                ));

                if ($query->have_posts()) {
                    // Page found, updating its content
                    $page_id = $query->posts[0]->ID;

                    // Update page content
                    $content_file = dirname($title_file) . '/content.json';
                    if (file_exists($content_file)) {
                        $content_data = json_decode(file_get_contents($content_file), true);
                        wp_update_post(array(
                            'ID' => $page_id,
                            'post_content' => $content_data['content'],
                        ));
                    }

                    // Importing ACF fields
                    $acf_file = dirname($title_file) . '/acf.json';
                    if (file_exists($acf_file)) {
                        $acf_data = json_decode(file_get_contents($acf_file), true);
                        import_acf_fields($page_id, $acf_data);
                    }

                    // Updating SEO data
                    $seo_file = dirname($title_file) . '/yoast.json'; // Note: changed to yoast.json for consistency
                    if (file_exists($seo_file)) {
                        $seo_data = json_decode(file_get_contents($seo_file), true);

                        if (!empty($seo_data)) {
                            if (isset($seo_data['yoast_title'])) {
                                update_post_meta($page_id, '_yoast_wpseo_title', $seo_data['yoast_title']);
                            }
                            if (isset($seo_data['yoast_description'])) {
                                update_post_meta($page_id, '_yoast_wpseo_metadesc', $seo_data['yoast_description']);
                            }
                            if (isset($seo_data['yoast_keywords'])) {
                                update_post_meta($page_id, '_yoast_wpseo_focuskw', $seo_data['yoast_keywords']);
                            }
                        }
                    }

                } else {
                    // Page not found, creating a new one
                    $page_id = wp_insert_post(array(
                        'post_title' => $page_title,
                        'post_type' => 'page',
                        'post_status' => 'publish',
                    ));

                    // Import content and ACF fields for the new page
                    $content_file = dirname($title_file) . '/content.json';
                    if (file_exists($content_file)) {
                        $content_data = json_decode(file_get_contents($content_file), true);
                        wp_update_post(array(
                            'ID' => $page_id,
                            'post_content' => $content_data['content'],
                        ));
                    }

                    // Importing ACF fields
                    $acf_file = dirname($title_file) . '/acf.json';
                    if (file_exists($acf_file)) {
                        $acf_data = json_decode(file_get_contents($acf_file), true);
                        import_acf_fields($page_id, $acf_data);
                    }

                    // Updating SEO data
                    $seo_file = dirname($title_file) . '/yoast.json'; // Note: changed to yoast.json for consistency
                    if (file_exists($seo_file)) {
                        $seo_data = json_decode(file_get_contents($seo_file), true);

                        if (!empty($seo_data)) {
                            if (isset($seo_data['yoast_title'])) {
                                update_post_meta($page_id, '_yoast_wpseo_title', $seo_data['yoast_title']);
                            }
                            if (isset($seo_data['yoast_description'])) {
                                update_post_meta($page_id, '_yoast_wpseo_metadesc', $seo_data['yoast_description']);
                            }
                            if (isset($seo_data['yoast_keywords'])) {
                                update_post_meta($page_id, '_yoast_wpseo_focuskw', $seo_data['yoast_keywords']);
                            }
                        }
                    }
                }
            }

            // Uploading images to the default media folder
            $image_dir = $extract_dir . 'images/';
            $images = glob($image_dir . '*');
            foreach ($images as $image_path) {
                $image_filename = basename($image_path);
                $upload_dir = wp_upload_dir()['path'];

                // Check if the file already exists
                if (!file_exists($upload_dir . '/' . $image_filename)) {
                    $upload = wp_upload_bits($image_filename, null, file_get_contents($image_path));
                    if ($upload['error']) {
                        error_log('Error uploading image: ' . $upload['error']);
                        continue;
                    }

                    // Add image as attachment
                    $image_data = getimagesize($upload['file']);
                    if ($image_data !== false) {
                        $attachment = array(
                            'guid' => $upload['url'],
                            'post_mime_type' => $image_data['mime'],
                            'post_title' => $image_filename,
                            'post_content' => '',
                            'post_status' => 'inherit',
                        );
                        $attachment_id = wp_insert_attachment($attachment, $upload['file']);
                        require_once(ABSPATH . 'wp-admin/includes/image.php');
                        $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
                        wp_update_attachment_metadata($attachment_id, $attachment_data);
                    } else {
                        error_log('Invalid image type: ' . $image_filename);
                    }
                }
            }
        } else {
            wp_die('Failed to open ZIP file.');
        }
    } else {
        wp_die('File not uploaded.');
    }
}

add_action('admin_post_import_pages', 'import_pages_from_zip');

if (!function_exists('rrmdir')) {
    function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object))
                        rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            rmdir($dir);
        }
    }
}

?>