<?php
/**
 * FAQs export and import (CSV: Title, Content, Category).
 *
 * Categories are comma separated, an FAQ can belong to several.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', 'aw_faqs_io_menu' );

/**
 * FAQs → Export / Import.
 */
function aw_faqs_io_menu(): void {
	add_submenu_page(
		'edit.php?post_type=faqs',
		'Export FAQs',
		'Export',
		'manage_options',
		'aw-export-faqs',
		static fn() => aw_ie_render_page(
			array(
				'title'  => 'Export FAQs',
				'help'   => 'Downloads all published FAQs as a CSV file.',
				'export' => 'aw_export_faqs',
			)
		)
	);

	add_submenu_page(
		'edit.php?post_type=faqs',
		'Import FAQs',
		'Import',
		'manage_options',
		'aw-import-faqs',
		static fn() => aw_ie_render_page(
			array(
				'title'  => 'Import FAQs',
				'help'   => 'Upload a CSV made by Export. FAQs with the same title are updated.',
				'import' => 'aw_import_faqs',
				'accept' => '.csv',
			)
		)
	);
}

add_action( 'admin_post_aw_export_faqs', 'aw_export_faqs' );

/**
 * Downloads the CSV.
 */
function aw_export_faqs(): void {
	aw_ie_guard( 'aw_export_faqs' );

	$path = aw_ie_temp_dir() . '/faqs.csv';
	$csv  = fopen( $path, 'w' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen

	if ( ! $csv ) {
		wp_die( 'Could not create the export file.' );
	}

	fputcsv( $csv, array( 'Title', 'Content', 'Category' ) );

	$faqs = get_posts(
		array(
			'post_type'      => 'faqs',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		)
	);

	foreach ( $faqs as $faq ) {
		$terms = wp_get_post_terms( $faq->ID, 'faq_category', array( 'fields' => 'names' ) );
		fputcsv( $csv, array( $faq->post_title, $faq->post_content, implode( ',', $terms ) ) );
	}

	fclose( $csv ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose

	aw_ie_send_download( $path, 'faqs_export_' . gmdate( 'Y-m-d' ) . '.csv', 'text/csv; charset=utf-8' );
}

add_action( 'admin_post_aw_import_faqs', 'aw_import_faqs' );

/**
 * Creates or updates FAQs from the uploaded CSV.
 */
function aw_import_faqs(): void {
	aw_ie_guard( 'aw_import_faqs' );

	$page = admin_url( 'edit.php?post_type=faqs&page=aw-import-faqs' );
	$file = aw_ie_uploaded_file( 'aw_file', 'csv' );

	if ( is_wp_error( $file ) ) {
		aw_ie_redirect( $page, $file->get_error_message(), false );
	}

	$csv = fopen( $file, 'r' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen

	if ( ! $csv ) {
		aw_ie_redirect( $page, 'Could not read the file.', false );
	}

	fgetcsv( $csv );
	$count = 0;

	while ( ( $row = fgetcsv( $csv ) ) !== false ) { // phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
		[ $title, $content, $categories ] = array_pad( $row, 3, '' );
		$title                            = sanitize_text_field( (string) $title );

		if ( '' === $title ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'ID'           => aw_ie_find_post_by_title( $title, 'faqs' ),
				'post_type'    => 'faqs',
				'post_status'  => 'publish',
				'post_title'   => $title,
				'post_content' => wp_kses_post( (string) $content ),
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			continue;
		}

		$names = array_filter( array_map( 'trim', explode( ',', (string) $categories ) ) );

		if ( $names ) {
			wp_set_object_terms( $post_id, $names, 'faq_category' );
		}

		++$count;
	}

	fclose( $csv ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose

	aw_ie_redirect( $page, sprintf( '%d FAQs imported.', $count ), true );
}
