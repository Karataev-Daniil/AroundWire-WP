<?php
// Registering a custom post type for FAQ
add_action( 'init', 'register_post_faqs' );
function register_post_faqs(){
	register_post_type( 'faqs', [
		'label'  => null,
		'labels' => [
			'name'               => 'FAQs', 
			'singular_name'      => 'FAQ', 
			'add_new'            => 'Add FAQ', 
			'add_new_item'       => 'Adding FAQ', 
			'edit_item'          => 'Edit FAQ', 
			'new_item'           => 'New FAQ', 
			'view_item'          => 'View FAQ', 
			'search_items'       => 'Search FAQs', 
			'not_found'          => 'Not found', 
			'not_found_in_trash' => 'Not found in trash', 
			'parent_item_colon'  => '', 
			'menu_name'          => 'FAQs', 
		],
		'description'            => '',
		'public'                 => true,
		'show_in_menu'           => true, 
		'show_in_rest'           => null, 
		'rest_base'              => null, 
		'menu_position'          => null,
		'menu_icon'              => 'dashicons-info',
		'hierarchical'           => false,
		'supports'               => [ 'title', 'editor' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'             => [],
		'has_archive'            => false,
		'rewrite'                => true,
		'query_var'              => true,
	] );

    register_taxonomy(
        'faq_category',
        'faqs',
        array(
            'labels' => array(
                'name'              => 'FAQ Categories',
                'singular_name'     => 'FAQ Category',
                'search_items'      => 'Search FAQ Categories',
                'all_items'         => 'All FAQ Categories',
                'parent_item'       => 'Parent FAQ Category',
                'parent_item_colon' => 'Parent FAQ Category:',
                'edit_item'         => 'Edit FAQ Category',
                'update_item'       => 'Update FAQ Category',
                'add_new_item'      => 'Add New FAQ Category',
                'new_item_name'     => 'New FAQ Category Name',
                'menu_name'         => 'FAQ Categories',
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'faq-category' ),
        )
    );
}

function register_post_blog() {
    // Register custom post type 'blog'
    register_post_type('blog', [
        'label' => null,
        'labels' => [
            'name' => 'Blog',
            'singular_name' => 'Blog',
            'add_new' => 'Add New Blog Post',
            'add_new_item' => 'Add New Blog Post',
            'edit_item' => 'Edit Blog Post',
            'new_item' => 'New Blog Post',
            'view_item' => 'View Blog Post',
            'search_items' => 'Search Blog Posts',
            'not_found' => 'No Blog Posts found',
            'not_found_in_trash' => 'No Blog Posts found in Trash',
            'parent_item_colon' => '',
            'menu_name' => 'Posts for Blog',
        ],
        'description' => 'A custom post type for blog posts',
        'public' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-format-aside',
        'hierarchical' => false,
        'supports' => ['thumbnail', 'title', 'editor', 'author', 'excerpt', 'comments'],
        'has_archive' => 'blog',
        'rewrite' => [
            'slug' => 'blog',
            'with_front' => false,
        ],
        'query_var' => true,
        'taxonomies' => ['category', 'blog_tag'],
    ]);

    // Register custom taxonomy 'blog_tag' for 'blog' post type
    register_taxonomy('blog_tag', 'blog', [
        'label' => 'Tags',
        'labels' => [
            'name' => 'Tags',
            'singular_name' => 'Tag',
            'search_items' => 'Search Tags',
            'all_items' => 'All Tags',
            'edit_item' => 'Edit Tag',
            'update_item' => 'Update Tag',
            'add_new_item' => 'Add New Tag',
            'new_item_name' => 'New Tag Name',
            'menu_name' => 'Tags',
        ],
        'public' => true,
        'hierarchical' => false,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => [
            'slug' => 'tag',
            'with_front' => false,
        ],
    ]);
}
add_action('init', 'register_post_blog');

function register_post_blog_menu() {
    // Register custom post type and taxonomy
    register_post_blog();
    
    // Add submenu page for the archive link
    add_submenu_page(
        'edit.php?post_type=blog',  // Parent menu slug
        'Blog Archive',             // Page title
        'View Blog',                // Menu item label
        'manage_options',           // Capability
        esc_url(get_post_type_archive_link('blog')) // Archive page URL
    );
}
add_action('admin_menu', 'register_post_blog_menu');

// Function to include blog posts in search results
function include_blog_in_search($query) {
    if ($query->is_search() && $query->is_main_query()) {
        $query->set('post_type', ['post', 'blog']);
    }
}
add_action('pre_get_posts', 'include_blog_in_search');

// Blog Post View Counter
function set_post_views($post_id) {
    $count_key = 'post_views_count';
    
    // Get current view count, if not found, return 0
    $count = get_post_meta($post_id, $count_key, true);
    if (!$count) {
        $count = 0;
    }

    // Increment the count by 1
    $count++;

    // Update the post meta with the new view count
    update_post_meta($post_id, $count_key, $count);
}

function get_post_views($post_id) {
    $count_key = 'post_views_count';

    // Get the current view count, return 0 if not set
    $count = get_post_meta($post_id, $count_key, true);
    
    // If no count found, return 0
    return ($count) ? $count : 0;
}

function set_og_image_from_first_post_for_blog_tag() {
    if (is_tax('blog_tag')) {
        $term = get_queried_object();
        $args = array(
            'tax_query' => array(
                array(
                    'taxonomy' => 'blog_tag',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ),
            ),
            'posts_per_page' => 1,
        );
        
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $query->the_post();
            $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'full');
            if ($thumbnail) {
                echo '<meta property="og:image" content="' . esc_url($thumbnail) . '">';
            }
            wp_reset_postdata();
        }
    }
}
add_action('wp_head', 'set_og_image_from_first_post_for_blog_tag');


?>