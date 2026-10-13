<?php
/**
 * Post types: FAQs and the blog.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'aw_register_post_types' );

/**
 * Registers the faqs and blog post types and their taxonomies.
 */
function aw_register_post_types(): void {
	register_post_type(
		'faqs',
		array(
			'labels'       => array(
				'name'               => 'FAQs',
				'singular_name'      => 'FAQ',
				'add_new'            => 'Add FAQ',
				'add_new_item'       => 'Add FAQ',
				'edit_item'          => 'Edit FAQ',
				'new_item'           => 'New FAQ',
				'view_item'          => 'View FAQ',
				'search_items'       => 'Search FAQs',
				'not_found'          => 'Not found',
				'not_found_in_trash' => 'Not found in trash',
				'menu_name'          => 'FAQs',
			),
			'public'       => true,
			'menu_icon'    => 'dashicons-info',
			'supports'     => array( 'title', 'editor' ),
			'has_archive'  => false,
			'rewrite'      => true,
			'show_in_rest' => true,
		)
	);

	register_taxonomy(
		'faq_category',
		'faqs',
		array(
			'labels'            => array(
				'name'          => 'FAQ Categories',
				'singular_name' => 'FAQ Category',
				'search_items'  => 'Search FAQ Categories',
				'all_items'     => 'All FAQ Categories',
				'parent_item'   => 'Parent FAQ Category',
				'edit_item'     => 'Edit FAQ Category',
				'update_item'   => 'Update FAQ Category',
				'add_new_item'  => 'Add New FAQ Category',
				'new_item_name' => 'New FAQ Category Name',
				'menu_name'     => 'FAQ Categories',
			),
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'faq-category' ),
		)
	);

	register_post_type(
		'blog',
		array(
			'labels'       => array(
				'name'               => 'Blog',
				'singular_name'      => 'Blog Post',
				'add_new'            => 'Add New Blog Post',
				'add_new_item'       => 'Add New Blog Post',
				'edit_item'          => 'Edit Blog Post',
				'new_item'           => 'New Blog Post',
				'view_item'          => 'View Blog Post',
				'search_items'       => 'Search Blog Posts',
				'not_found'          => 'No Blog Posts found',
				'not_found_in_trash' => 'No Blog Posts found in Trash',
				'menu_name'          => 'Posts for Blog',
			),
			'description'  => 'Blog posts',
			'public'       => true,
			'show_in_rest' => true,
			'menu_position' => 5,
			'menu_icon'    => 'dashicons-format-aside',
			'supports'     => array( 'thumbnail', 'title', 'editor', 'author', 'excerpt', 'comments' ),
			'has_archive'  => 'blog',
			'rewrite'      => array(
				'slug'       => 'blog',
				'with_front' => false,
			),
			'taxonomies'   => array( 'category', 'blog_tag' ),
		)
	);

	register_taxonomy(
		'blog_tag',
		'blog',
		array(
			'labels'            => array(
				'name'          => 'Tags',
				'singular_name' => 'Tag',
				'search_items'  => 'Search Tags',
				'all_items'     => 'All Tags',
				'edit_item'     => 'Edit Tag',
				'update_item'   => 'Update Tag',
				'add_new_item'  => 'Add New Tag',
				'new_item_name' => 'New Tag Name',
				'menu_name'     => 'Tags',
			),
			'public'            => true,
			'hierarchical'      => false,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'tag',
				'with_front' => false,
			),
		)
	);
}

add_action( 'admin_menu', 'aw_blog_archive_menu_link' );

/**
 * "View Blog" link under the blog menu.
 */
function aw_blog_archive_menu_link(): void {
	global $submenu;

	$link = get_post_type_archive_link( 'blog' );

	if ( $link && current_user_can( 'edit_posts' ) ) {
		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- adding an external link is the documented way.
		$submenu['edit.php?post_type=blog'][] = array( 'View Blog', 'edit_posts', $link );
	}
}
