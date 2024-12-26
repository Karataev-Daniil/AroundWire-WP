<?php
// function get_available_versions($block_path) {
//     $pattern = get_template_directory() . '/' . $block_path . '-v*.php'; 
//     $files = glob($pattern); 

//     $versions = [];
//     if ($files) {
//         foreach ($files as $file) {
//             if (preg_match('/-v(\d+)\.php$/', basename($file), $matches)) {
//                 $version = 'v' . $matches[1];
//                 $versions[$version] = $version; 
//             }
//         }
//     }

//     if (!isset($versions['v1'])) {
//         $versions['v1'] = 'v1';
//     }

//     ksort($versions, SORT_NATURAL | SORT_FLAG_CASE);

//     return $versions;
// }

function add_versions_meta_box() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key'    => 'group_block_versions',
            'title'  => 'block versions',
            'label'  => 'block versions',
            'name'   => 'block_versions',
            'type'   => 'group',
            'fields' => array(
                array(
                    'key'           => 'field_template_presets',
                    'label'         => 'Template Presets',
                    'name'          => 'template_presets',
                    'type'          => 'select',
                    'choices'       => get_saved_templates(),
                    'allow_null'    => true,
                    'instructions'  => 'Select a saved template or create a new combination below.',
                ),
                array(
                    'key'           => 'field_delete_template_button',
                    'label'         => 'Delete Template',
                    'name'          => 'delete_template_button',
                    'type'          => 'message',
                    'message'       => '<button id="delete-template" type="button" class="button button-secondary">Delete Template</button>',
                    'instructions'  => 'Click to delete the selected template.',
                ),
                array(
                    'key'           => 'field_menu_version',
                    'label'         => 'version menu',
                    'name'          => 'menu_version',
                    'type'          => 'select',
                    'choices' => array(
                        'v1' => 'v1',
                        'v2' => 'v2',
                        'v3' => 'v3',
                        'v4' => 'v4',
                        'v5' => 'v5',
                        'v6' => 'v6',
                        'v7' => 'v7',
                        'v8' => 'v8',
                    ),
                    'default_value' => 'v1',
                ),
                array(
                    'key'           => 'field_hero_block_version',
                    'label'         => 'version hero block',
                    'name'          => 'hero_block_version',
                    'type'          => 'select',
                    'choices' => array(
                        'v1' => 'v1',
                        'v2' => 'v2',
                        'v3' => 'v3',
                        'v4' => 'v4',
                        'v5' => 'v5',
                        'v6' => 'v6',
                        'v7' => 'v7',
                    ),
                    'default_value' => 'v1', 
                ),
                array(
                    'key'           => 'field_benefits_version',
                    'label'         => 'version benefits',
                    'name'          => 'benefits_version',
                    'type'          => 'select',
                    'choices' => array(
                        'v1' => 'v1',
                        'v2' => 'v2',
                        'v3' => 'v3',
                        'v4' => 'v4',
                        'v5' => 'v5',
                        'v6' => 'v6',
                        'v7' => 'v7',
                        'v8' => 'v8',
                    ),
                    'default_value' => 'v1',
                ),
                array(
                    'key'           => 'field_success_steps_version',
                    'label'         => 'version success steps',
                    'name'          => 'success_steps_version',
                    'type'          => 'select',
                    'choices' => array(
                        'v1' => 'v1',
                        'v2' => 'v2',
                        'v3' => 'v3',
                        'v4' => 'v4',
                        'v5' => 'v5',
                        'v6' => 'v6',
                        'v7' => 'v7',
                        'v8' => 'v8',
                    ),
                    'default_value' => 'v1',
                ),
                array(
                    'key'           => 'field_expand_clienteles_version',
                    'label'         => 'version expand clienteles',
                    'name'          => 'expand_clienteles_version',
                    'type'          => 'select',
                    'choices' => array(
                        'v1' => 'v1',
                        'v2' => 'v2',
                        'v3' => 'v3',
                        'v4' => 'v4',
                        'v5' => 'v5',
                        'v6' => 'v6',
                    ),
                    'default_value' => 'v1', 
                ),
                array(
                    'key'           => 'field_more_views_version',
                    'label'         => 'version more views',
                    'name'          => 'more_views_version',
                    'type'          => 'select',
                    'choices' => array(
                        'v1' => 'v1',
                        'v2' => 'v2',
                        'v3' => 'v3',
                        'v4' => 'v4',
                        'v5' => 'v5',
                        'v6' => 'v6',
                        'v7' => 'v7',
                    ),
                    'default_value' => 'v1', 
                ),
                array(
                    'key'           => 'field_start_work_version',
                    'label'         => 'version start work',
                    'name'          => 'start_work_version',
                    'type'          => 'select',
                    'choices' => array(
                        'v1' => 'v1',
                        'v2' => 'v2',
                        'v3' => 'v3',
                        'v4' => 'v4',
                        'v5' => 'v5',
                        'v6' => 'v6',
                        'v7' => 'v7',
                    ),
                    'default_value' => 'v1', 
                ),
                array(
                    'key'           => 'field_faq_version',
                    'label'         => 'version faq',
                    'name'          => 'faq_version',
                    'type'          => 'select',
                    'choices' => array(
                        'v1' => 'v1',
                        'v2' => 'v2',
                        'v3' => 'v3',
                        'v4' => 'v4',
                        'v5' => 'v5',
                        'v6' => 'v6',
                    ),
                    'default_value' => 'v1', 
                ),
                array(
                    'key'           => 'field_reviews_version',
                    'label'         => 'version reviews',
                    'name'          => 'reviews_version',
                    'type'          => 'select',
                    'choices' => array(
                        'v1' => 'v1',
                    ),
                    'default_value' => 'v1', 
                ),
                array(
                    'key'           => 'field_fitback_button_version',
                    'label'         => 'version fitback button',
                    'name'          => 'fitback_button_version',
                    'type'          => 'select',
                    'choices' => array(
                        'v1' => 'v1',
                        'v2' => 'v2',
                        'v3' => 'v3',
                        'v4' => 'v4',
                    ),
                    'default_value' => 'v1', 
                ),
                array(
                    'key'           => 'field_footer_menu_version',
                    'label'         => 'version footer menu',
                    'name'          => 'footer_menu_version',
                    'type'          => 'select',
                    'choices' => array(
                        'v1' => 'v1',
                        'v2' => 'v2',
                        'v3' => 'v3',
                        'v4' => 'v4',
                        'v5' => 'v5',
                    ),
                    'default_value'  => 'v1',
                ),
                array(
                    'key'           => 'field_save_template_button',
                    'label'         => 'Save Template',
                    'name'          => 'save_template_button',
                    'type'          => 'message',
                    'message'       => '<button id="save-template" type="button" class="button button-primary">Save Template</button>',
                    'instructions'  => 'Click to save the current combination of block versions as a new template.',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param'    => 'page_template',
                        'operator' => '==',
                        'value'    => 'landing-layout.php',
                    ),
                ),
            ),
            'position' => 'side',
            'class'    => 'custom-grid',
        ));
    }
}
add_action('acf/init', 'add_versions_meta_box');

// Get saved templates
function get_saved_templates() {
    $templates = get_option('saved_block_templates', array());
    return array_combine(array_keys($templates), array_keys($templates)); // Convert to a format suitable for ACF
}

// Save template AJAX
function save_block_template_ajax() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied');
    }

    $template_name = sanitize_text_field($_POST['template_name'] ?? '');
    $block_versions = $_POST['block_versions'] ?? array();

    if (empty($block_versions) || !is_array($block_versions)) {
        wp_send_json_error('Invalid block versions');
    }

    if (empty($template_name)) {
        wp_send_json_error('Template name is required');
    }

    // Get current templates
    $templates = get_option('saved_block_templates', array());
    $templates[$template_name] = $block_versions; // Save the template with its block versions
    update_option('saved_block_templates', $templates);

    wp_send_json_success('Template saved successfully');
}
add_action('wp_ajax_save_block_template', 'save_block_template_ajax');

// Delete template AJAX
function delete_template_ajax() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied');
    }

    $template_name = sanitize_text_field($_POST['template_name'] ?? '');

    // Get saved templates
    $templates = get_option('saved_block_templates', array());

    if (!isset($templates[$template_name])) {
        wp_send_json_error('Template not found');
    }

    // Delete the template
    unset($templates[$template_name]);
    update_option('saved_block_templates', $templates);

    wp_send_json_success('Template deleted successfully');
}
add_action('wp_ajax_delete_template', 'delete_template_ajax');

function get_template_settings_ajax() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied');
    }

    $template_name = sanitize_text_field($_POST['template_name'] ?? '');
    $templates = get_option('saved_block_templates', array());

    if (!isset($templates[$template_name])) {
        wp_send_json_error('Template not found');
    }

    wp_send_json_success(array(
        'template_name' => $template_name,
        'block_versions' => $templates[$template_name], // Return block versions for the selected template
    ));
}
add_action('wp_ajax_get_template_settings', 'get_template_settings_ajax');

// Get saved templates for the dropdown
function get_saved_templates_ajax() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission denied');
    }

    $templates = get_option('saved_block_templates', array());
    wp_send_json_success(array_keys($templates)); // Send only the template names
}
add_action('wp_ajax_get_saved_templates', 'get_saved_templates_ajax');

function enqueue_admin_scripts($hook) {
    if ($hook === 'post.php' || $hook === 'post-new.php') {
        wp_enqueue_script(
            'admin-save-template',
            get_template_directory_uri() . '/js/admin-save-template.js',
            array('jquery'),
            null,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts');

// Add a button to copy all links to target pages
function add_copy_links_button_to_tablenav() {
    $screen = get_current_screen();
    
    // Check if this is the page editing screen
    if ($screen->post_type !== 'page') {
        return;
    }

    // Get all pages with the "Landing Page" template
    $pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'landing-layout.php'
    ));

    // Check if there are pages with the specified template
    if (empty($pages)) {
        return;
    }

    // Build the list of links
    $links = '';
    foreach ($pages as $page) {
        $links .= get_permalink($page->ID) . "\n"; // Get the link for each page
    }

    // Replace newline characters with JavaScript equivalents
    $links_js = str_replace(array("\r", "\n"), '\\n', esc_js($links));

    // Output the HTML for the button in the existing tablenav
    echo '
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            var buttonHTML = \'<div class="alignleft actions"><a id="copy-links-button" class="button" href="javascript:void(0);">Copy Landing Links</a></div>\';
            var nav = document.querySelector(".tablenav.top");
            if (nav) {
                // Find the element with the class "actions" and add the button at the beginning
                var actions = nav.querySelector(".actions");
                if (actions) {
                    actions.insertAdjacentHTML("beforebegin", buttonHTML);
                } else {
                    nav.insertAdjacentHTML("afterbegin", buttonHTML);
                }

                // Now add the event handler after creating the button
                document.getElementById("copy-links-button").addEventListener("click", function() {
                    var textarea = document.createElement("textarea");
                    textarea.value = `' . $links_js . '`; // Set the textarea value to the links
                    document.body.appendChild(textarea);
                    textarea.select();

                    // Use execCommand to copy the text
                    try {
                        var successful = document.execCommand("copy");
                        var msg = successful ? "Links copied to clipboard!" : "Failed to copy.";
                        alert(msg); // Notification of successful or failed copying
                    } catch (err) {
                        console.error("Error copying text: ", err);
                    }

                    document.body.removeChild(textarea);
                });
            }
        });
    </script>
    ';
}


function hide_button_on_landing_page() {
    if (is_page_template('landing-layout.php')) {
    echo '<style>
    .hide-on-landing {
        display: none !important;
        }
        </style>';
    }
}
add_action('wp_head', 'hide_button_on_landing_page');

function reorder_blog_menu() {
    global $submenu;
    
    // Check if 'blog' post type submenu exists
    if (isset($submenu['edit.php?post_type=blog'])) {
        // Get the archive link menu item
        $archive_item = array_pop($submenu['edit.php?post_type=blog']);
        
        // Prepend the archive link menu item to the submenu array
        array_unshift($submenu['edit.php?post_type=blog'], $archive_item);
    }
}

function add_description_metabox($post) {
    add_meta_box(
        'related_jobs_metabox',
        'Related Jobs Pages',
        'render_related_jobs_metabox',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_description_metabox');

function render_related_jobs_metabox($post) {
    $search_key = get_post_meta($post->ID, '_related_jobs_key', true);

    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'post__not_in'   => array($post->ID), 
        's'              => $search_key ? $search_key . ' jobs' : ''
    );

    $query = new WP_Query($args);

    wp_nonce_field('save_related_jobs_key_nonce', 'related_jobs_key_nonce');

    ob_start();
    ?>
    <label for="related_jobs_key"><?php _e('Search Key', 'textdomain'); ?></label>
    <input 
        type="text" 
        name="related_jobs_key" 
        id="related_jobs_key" 
        value="<?php echo esc_attr($search_key); ?>" 
        placeholder="<?php _e('Enter search key', 'textdomain'); ?>" 
        style="width: 100%;"
    />
    <p class="description"><?php _e('Specify a keyword to find related jobs by title.', 'textdomain'); ?></p>

    <table class="acf-table">
        <thead>
            <tr>
                <th><?php _e('Custom Title', 'textdomain'); ?></th>
                <th><?php _e('Link to Page', 'textdomain'); ?></th>
                <th><?php _e('Description', 'textdomain'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($query->have_posts()) : ?>
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <?php 
                    $description = get_post_meta(get_the_ID(), 'description', true);
                    $custom_title = get_post_meta(get_the_ID(), 'custom_related_job_title', true); 
                    ?>
                    <tr>
                        <td>
                            <input 
                                type="text" 
                                name="custom_related_job_title[<?php the_ID(); ?>]" 
                                value="<?php echo esc_attr($custom_title); ?>" 
                                style="width: 100%;"
                            />
                        </td>
                        <td>
                            <input 
                                type="text" 
                                name="related_job_title[<?php the_ID(); ?>]" 
                                value="<?php echo esc_attr(get_the_title()); ?>" 
                                style="width: 100%;"
                                readonly
                            />
                        </td>
                        <td>
                            <input 
                                type="text" 
                                name="related_job_description[<?php the_ID(); ?>]" 
                                value="<?php echo esc_attr($description); ?>" 
                                style="width: 100%;"
                            />
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr><td colspan="3"><?php _e('No related jobs found.', 'textdomain'); ?></td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php
    wp_reset_postdata();
    echo ob_get_clean();
}

function save_related_jobs_key($post_id) {
    if (isset($_POST['related_jobs_key_nonce']) && 
        wp_verify_nonce($_POST['related_jobs_key_nonce'], 'save_related_jobs_key_nonce')) {
        
        if (isset($_POST['related_jobs_key'])) {
            update_post_meta($post_id, '_related_jobs_key', sanitize_text_field($_POST['related_jobs_key']));
        }
    }
}
add_action('save_post', 'save_related_jobs_key');

function save_related_job_descriptions($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

    if (isset($_POST['related_jobs_key_nonce']) && 
        wp_verify_nonce($_POST['related_jobs_key_nonce'], 'save_related_jobs_key_nonce')) {

        if (isset($_POST['related_job_description'])) {
            foreach ($_POST['related_job_description'] as $page_id => $description) {
                update_post_meta($page_id, 'description', sanitize_text_field($description));
            }
        }

        if (isset($_POST['custom_related_job_title'])) {
            foreach ($_POST['custom_related_job_title'] as $page_id => $custom_title) {
                update_post_meta($page_id, 'custom_related_job_title', sanitize_text_field($custom_title));
            }
        }
    }

    return $post_id;
}
add_action('save_post', 'save_related_job_descriptions');
?>