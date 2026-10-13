<?php
/**
 * Blog posts export and import (ZIP with blog_posts.csv and an images/ folder).
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

const AW_BLOG_CSV_COLUMNS = array( 'Title', 'Content', 'Excerpt', 'Author', 'Tags', 'Categories', 'Images', 'Publish Date', 'SEO Title', 'SEO Description', 'Focus Keyword', 'FAQs' );

add_action( 'admin_menu', 'aw_blog_io_menu' );

/**
 * Blog → Export Posts / Import Posts.
 */
function aw_blog_io_menu(): void {
	add_submenu_page(
		'edit.php?post_type=blog',
		'Export Blog Posts',
		'Export Posts',
		'manage_options',
		'aw-export-blog',
		static fn() => aw_ie_render_page(
			array(
				'title'  => 'Export Blog Posts',
				'help'   => 'Downloads a ZIP with all blog posts (CSV) and their images.',
				'export' => 'aw_export_blog',
			)
		)
	);

	add_submenu_page(
		'edit.php?post_type=blog',
		'Import Blog Posts',
		'Import Posts',
		'manage_options',
		'aw-import-blog',
		static fn() => aw_ie_render_page(
			array(
				'title'  => 'Import Blog Posts',
				'help'   => 'Upload a ZIP made by Export Posts. Posts with the same title are updated.',
				'import' => 'aw_import_blog',
				'accept' => '.zip',
			)
		)
	);
}

add_action( 'admin_post_aw_export_blog', 'aw_export_blog' );

/**
 * Builds and downloads the export ZIP.
 */
function aw_export_blog(): void {
	aw_ie_guard( 'aw_export_blog' );

	$dir      = aw_ie_temp_dir();
	$csv_path = $dir . '/blog_posts.csv';
	$zip_path = $dir . '/export.zip';
	$csv      = fopen( $csv_path, 'w' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
	$zip      = new ZipArchive();

	if ( ! $csv || true !== $zip->open( $zip_path, ZipArchive::CREATE ) ) {
		wp_die( 'Could not create the export file.' );
	}

	fputcsv( $csv, AW_BLOG_CSV_COLUMNS );

	$posts = get_posts(
		array(
			'post_type'      => 'blog',
			'post_status'    => 'any',
			'posts_per_page' => -1,
		)
	);

	foreach ( $posts as $post ) {
		$images = array();
		$ids    = array_filter( array_merge( array( get_post_thumbnail_id( $post ) ), array_keys( get_attached_media( 'image', $post->ID ) ) ) );

		foreach ( array_unique( $ids ) as $attachment_id ) {
			$filename = aw_ie_zip_attachment( $zip, (int) $attachment_id, 'images' );

			if ( '' !== $filename && ! in_array( $filename, $images, true ) ) {
				$images[] = $filename;
			}
		}

		fputcsv(
			$csv,
			array(
				$post->post_title,
				$post->post_content,
				$post->post_excerpt,
				get_the_author_meta( 'display_name', (int) $post->post_author ),
				implode( ',', wp_get_post_terms( $post->ID, 'blog_tag', array( 'fields' => 'names' ) ) ),
				implode( ',', wp_get_post_terms( $post->ID, 'category', array( 'fields' => 'names' ) ) ),
				implode( ',', $images ),
				$post->post_date,
				get_post_meta( $post->ID, '_yoast_wpseo_title', true ),
				get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true ),
				get_post_meta( $post->ID, '_yoast_wpseo_focuskw', true ),
				wp_json_encode( aw_blog_faqs( $post->ID ) ),
			)
		);
	}

	fclose( $csv ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
	$zip->addFile( $csv_path, 'blog_posts.csv' );
	$zip->close();

	aw_ie_send_download( $zip_path, 'blog_export_' . gmdate( 'Y-m-d' ) . '.zip', 'application/zip' );
}

/**
 * FAQ repeater rows of a post.
 *
 * @return array<int, array{faq-title: string, faq-text: string}>
 */
function aw_blog_faqs( int $post_id ): array {
	$rows = aw_field( 'post-faqs', $post_id );
	$faqs = array();

	foreach ( is_array( $rows ) ? $rows : array() as $row ) {
		$title = aw_string( $row['faq-title'] ?? '' );
		$text  = aw_string( $row['faq-text'] ?? '' );

		if ( '' !== $title && '' !== $text ) {
			$faqs[] = array(
				'faq-title' => $title,
				'faq-text'  => $text,
			);
		}
	}

	return $faqs;
}

/**
 * Parses the FAQs column: JSON, or the legacy `Title: "…"; Text: "…" || …` format.
 *
 * @return array<int, array{faq-title: string, faq-text: string}>
 */
function aw_parse_blog_faqs( string $value ): array {
	$value = trim( $value );

	if ( '' === $value ) {
		return array();
	}

	$json = json_decode( $value, true );

	if ( is_array( $json ) ) {
		return array_values(
			array_filter(
				array_map(
					static fn( $row ): array => array(
						'faq-title' => sanitize_text_field( (string) ( $row['faq-title'] ?? '' ) ),
						'faq-text'  => wp_kses_post( (string) ( $row['faq-text'] ?? '' ) ),
					),
					$json
				),
				static fn( array $row ): bool => '' !== $row['faq-title'] && '' !== $row['faq-text']
			)
		);
	}

	$faqs = array();

	foreach ( explode( ' || ', $value ) as $item ) {
		if ( preg_match( '/Title: "(.*?)"; Text: "(.*)"/s', $item, $matches ) ) {
			$faqs[] = array(
				'faq-title' => sanitize_text_field( stripslashes( $matches[1] ) ),
				'faq-text'  => wp_kses_post( stripslashes( $matches[2] ) ),
			);
		}
	}

	return $faqs;
}

add_action( 'admin_post_aw_import_blog', 'aw_import_blog' );

/**
 * Imports posts from an export ZIP.
 */
function aw_import_blog(): void {
	aw_ie_guard( 'aw_import_blog' );

	$page = admin_url( 'edit.php?post_type=blog&page=aw-import-blog' );
	$file = aw_ie_uploaded_file( 'aw_file', 'zip' );

	if ( is_wp_error( $file ) ) {
		aw_ie_redirect( $page, $file->get_error_message(), false );
	}

	$dir       = aw_ie_temp_dir();
	$extracted = aw_ie_extract_zip( $file, $dir, array_merge( array( 'csv' ), AW_IE_IMAGE_TYPES ) );

	if ( is_wp_error( $extracted ) ) {
		aw_ie_redirect( $page, $extracted->get_error_message(), false );
	}

	$csv = fopen( $dir . '/blog_posts.csv', 'r' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen

	if ( ! $csv ) {
		aw_ie_redirect( $page, 'blog_posts.csv was not found in the archive.', false );
	}

	fgetcsv( $csv );
	$count = 0;

	while ( ( $row = fgetcsv( $csv ) ) !== false ) { // phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
		$row = array_pad( $row, count( AW_BLOG_CSV_COLUMNS ), '' );
		aw_import_blog_row( array_combine( AW_BLOG_CSV_COLUMNS, array_slice( $row, 0, count( AW_BLOG_CSV_COLUMNS ) ) ), $dir . '/images' );
		++$count;
	}

	fclose( $csv ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose

	aw_ie_redirect( $page, sprintf( '%d posts imported.', $count ), true );
}

/**
 * Creates or updates one post from a CSV row.
 *
 * @param array<string, string> $row        Column => value.
 * @param string                $images_dir Folder with the extracted images.
 */
function aw_import_blog_row( array $row, string $images_dir ): void {
	$title = sanitize_text_field( $row['Title'] );

	if ( '' === $title ) {
		return;
	}

	$data = array(
		'ID'           => aw_ie_find_post_by_title( $title, 'blog' ),
		'post_type'    => 'blog',
		'post_status'  => 'publish',
		'post_title'   => $title,
		'post_content' => wp_kses_post( $row['Content'] ),
		'post_excerpt' => sanitize_textarea_field( $row['Excerpt'] ),
		'post_author'  => get_current_user_id(),
	);

	if ( '' !== $row['Publish Date'] && strtotime( $row['Publish Date'] ) ) {
		$data['post_date'] = gmdate( 'Y-m-d H:i:s', (int) strtotime( $row['Publish Date'] ) );
	}

	$post_id = wp_insert_post( $data, true );

	if ( is_wp_error( $post_id ) ) {
		return;
	}

	// Images: the first one is the featured image; content links point to the imported files.
	$urls = array();

	foreach ( array_filter( array_map( 'trim', explode( ',', $row['Images'] ) ) ) as $index => $filename ) {
		$attachment_id = aw_ie_import_image( $images_dir . '/' . sanitize_file_name( $filename ), $post_id );

		if ( ! $attachment_id ) {
			continue;
		}

		if ( 0 === $index ) {
			set_post_thumbnail( $post_id, $attachment_id );
		}

		$urls[ sanitize_file_name( $filename ) ] = (string) wp_get_attachment_url( $attachment_id );
	}

	if ( $urls ) {
		wp_update_post(
			array(
				'ID'           => $post_id,
				'post_content' => aw_replace_image_urls( (string) get_post_field( 'post_content', $post_id ), $urls ),
			)
		);
	}

	$tags = array_filter( array_map( 'trim', explode( ',', $row['Tags'] ) ) );

	if ( $tags ) {
		wp_set_object_terms( $post_id, $tags, 'blog_tag' );
	}

	$categories = array_filter( array_map( 'trim', explode( ',', $row['Categories'] ) ) );
	wp_set_object_terms( $post_id, $categories, 'category' );

	$seo = array(
		'_yoast_wpseo_title'    => $row['SEO Title'],
		'_yoast_wpseo_metadesc' => $row['SEO Description'],
		'_yoast_wpseo_focuskw'  => $row['Focus Keyword'],
	);

	foreach ( $seo as $key => $value ) {
		if ( '' !== $value ) {
			update_post_meta( $post_id, $key, sanitize_text_field( $value ) );
		}
	}

	$faqs = aw_parse_blog_faqs( $row['FAQs'] );

	if ( $faqs && function_exists( 'update_field' ) ) {
		update_field( 'post-faqs', $faqs, $post_id );
	}
}

/**
 * Points <img src> at imported files, matched by filename.
 *
 * @param array<string, string> $urls Filename => new URL.
 */
function aw_replace_image_urls( string $content, array $urls ): string {
	return (string) preg_replace_callback(
		'/(<img\b[^>]*\bsrc=["\'])([^"\']+)(["\'])/i',
		static function ( array $matches ) use ( $urls ): string {
			$filename = sanitize_file_name( wp_basename( (string) wp_parse_url( $matches[2], PHP_URL_PATH ) ) );

			return isset( $urls[ $filename ] ) ? $matches[1] . esc_url( $urls[ $filename ] ) . $matches[3] : $matches[0];
		},
		$content
	);
}
