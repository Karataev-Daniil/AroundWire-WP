<?php
/**
 * Pages export and import with ACF fields and Yoast meta.
 *
 * Archive layout: pages/<slug>/page.json and pages/<slug>/images/<file>.
 * Image and file fields are exported as files inside the archive and replaced by
 * {"aw_file": "<name>"} in the JSON; the import reads them only from the archive.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', 'aw_pages_io_menu' );

/**
 * Pages → Export Pages / Import Pages.
 */
function aw_pages_io_menu(): void {
	add_submenu_page(
		'edit.php?post_type=page',
		'Export Pages',
		'Export Pages',
		'manage_options',
		'aw-export-pages',
		static fn() => aw_ie_render_page(
			array(
				'title'  => 'Export Pages',
				'help'   => 'Downloads a ZIP with every page, its ACF fields, Yoast meta and images.',
				'export' => 'aw_export_pages',
			)
		)
	);

	add_submenu_page(
		'edit.php?post_type=page',
		'Import Pages',
		'Import Pages',
		'manage_options',
		'aw-import-pages',
		static fn() => aw_ie_render_page(
			array(
				'title'  => 'Import Pages',
				'help'   => 'Upload a ZIP made by Export Pages. Pages with the same title are updated.',
				'import' => 'aw_import_pages',
				'accept' => '.zip',
			)
		)
	);
}

add_action( 'admin_post_aw_export_pages', 'aw_export_pages' );

/**
 * Builds and downloads the export ZIP.
 */
function aw_export_pages(): void {
	aw_ie_guard( 'aw_export_pages' );

	$zip_path = aw_ie_temp_dir() . '/pages.zip';
	$zip      = new ZipArchive();

	if ( true !== $zip->open( $zip_path, ZipArchive::CREATE ) ) {
		wp_die( 'Could not create the export file.' );
	}

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'any',
			'posts_per_page' => -1,
		)
	);

	foreach ( $pages as $page ) {
		$folder = 'pages/' . ( $page->post_name ?: 'page-' . $page->ID );
		$acf    = array();

		if ( function_exists( 'get_field_objects' ) ) {
			foreach ( (array) get_field_objects( $page->ID, false ) as $name => $field ) {
				$acf[ $name ] = aw_export_acf_value( $field, $field['value'] ?? null, $zip, $folder . '/images' );
			}
		}

		$data = array(
			'title'    => $page->post_title,
			'slug'     => $page->post_name,
			'template' => get_page_template_slug( $page ),
			'content'  => $page->post_content,
			'acf'      => $acf,
			'yoast'    => array(
				'title'       => get_post_meta( $page->ID, '_yoast_wpseo_title', true ),
				'description' => get_post_meta( $page->ID, '_yoast_wpseo_metadesc', true ),
				'keyword'     => get_post_meta( $page->ID, '_yoast_wpseo_focuskw', true ),
			),
		);

		$zip->addFromString( $folder . '/page.json', (string) wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
	}

	$zip->close();

	aw_ie_send_download( $zip_path, 'pages_export_' . gmdate( 'Y-m-d' ) . '.zip', 'application/zip' );
}

/**
 * Raw ACF value prepared for export. Image and file IDs become {"aw_file": name}
 * and the files are added to the archive. Repeaters and groups are walked recursively.
 *
 * @param array<string, mixed> $field  ACF field object.
 * @param mixed                $value  Raw value.
 * @return mixed
 */
function aw_export_acf_value( array $field, $value, ZipArchive $zip, string $images_folder ) {
	$type = $field['type'] ?? '';

	if ( in_array( $type, array( 'image', 'file' ), true ) && is_numeric( $value ) ) {
		$name = aw_ie_zip_attachment( $zip, (int) $value, $images_folder );
		return '' === $name ? null : array( 'aw_file' => $name );
	}

	if ( 'gallery' === $type && is_array( $value ) ) {
		return array_values( array_filter( array_map( static fn( $id ) => aw_export_acf_value( array( 'type' => 'image' ), $id, $zip, $images_folder ), $value ) ) );
	}

	$sub_fields = array_column( (array) ( $field['sub_fields'] ?? array() ), null, 'key' );

	if ( 'repeater' === $type && is_array( $value ) ) {
		return array_map(
			static function ( $row ) use ( $sub_fields, $zip, $images_folder ) {
				$out = array();
				foreach ( (array) $row as $key => $sub_value ) {
					$out[ $key ] = isset( $sub_fields[ $key ] ) ? aw_export_acf_value( $sub_fields[ $key ], $sub_value, $zip, $images_folder ) : $sub_value;
				}
				return $out;
			},
			array_values( $value )
		);
	}

	if ( 'group' === $type && is_array( $value ) ) {
		$out = array();
		foreach ( $value as $key => $sub_value ) {
			$out[ $key ] = isset( $sub_fields[ $key ] ) ? aw_export_acf_value( $sub_fields[ $key ], $sub_value, $zip, $images_folder ) : $sub_value;
		}
		return $out;
	}

	return $value;
}

add_action( 'admin_post_aw_import_pages', 'aw_import_pages' );

/**
 * Imports pages from an export ZIP.
 */
function aw_import_pages(): void {
	aw_ie_guard( 'aw_import_pages' );

	$page = admin_url( 'edit.php?post_type=page&page=aw-import-pages' );
	$file = aw_ie_uploaded_file( 'aw_file', 'zip' );

	if ( is_wp_error( $file ) ) {
		aw_ie_redirect( $page, $file->get_error_message(), false );
	}

	$dir       = aw_ie_temp_dir();
	$extracted = aw_ie_extract_zip( $file, $dir, array_merge( array( 'json' ), AW_IE_IMAGE_TYPES, array( 'pdf' ) ) );

	if ( is_wp_error( $extracted ) ) {
		aw_ie_redirect( $page, $extracted->get_error_message(), false );
	}

	$files = glob( $dir . '/pages/*/page.json' ) ?: array();

	foreach ( $files as $json_file ) {
		$data = json_decode( (string) file_get_contents( $json_file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		if ( is_array( $data ) ) {
			aw_import_page( $data, dirname( $json_file ) . '/images' );
		}
	}

	if ( ! $files ) {
		aw_ie_redirect( $page, 'No pages were found in the archive. Use a ZIP made by Export Pages.', false );
	}

	aw_ie_redirect( $page, sprintf( '%d pages imported.', count( $files ) ), true );
}

/**
 * Creates or updates one page.
 *
 * @param array<string, mixed> $data       Decoded page.json.
 * @param string               $images_dir Folder with this page's files.
 */
function aw_import_page( array $data, string $images_dir ): void {
	$title = sanitize_text_field( (string) ( $data['title'] ?? '' ) );

	if ( '' === $title ) {
		return;
	}

	$post_id = wp_insert_post(
		array(
			'ID'           => aw_ie_find_post_by_title( $title, 'page' ),
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_name'    => sanitize_title( (string) ( $data['slug'] ?? '' ) ),
			'post_content' => wp_kses_post( (string) ( $data['content'] ?? '' ) ),
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		return;
	}

	$template = (string) ( $data['template'] ?? '' );

	if ( '' !== $template && array_key_exists( $template, wp_get_theme()->get_page_templates( null, 'page' ) ) ) {
		update_post_meta( $post_id, '_wp_page_template', $template );
	}

	if ( function_exists( 'update_field' ) ) {
		foreach ( (array) ( $data['acf'] ?? array() ) as $name => $value ) {
			update_field( sanitize_key( (string) $name ), aw_import_acf_value( $value, $images_dir, $post_id ), $post_id );
		}
	}

	$yoast = array(
		'_yoast_wpseo_title'    => $data['yoast']['title'] ?? '',
		'_yoast_wpseo_metadesc' => $data['yoast']['description'] ?? '',
		'_yoast_wpseo_focuskw'  => $data['yoast']['keyword'] ?? '',
	);

	foreach ( $yoast as $key => $value ) {
		if ( '' !== $value ) {
			update_post_meta( $post_id, $key, sanitize_text_field( (string) $value ) );
		}
	}
}

/**
 * Replaces {"aw_file": name} markers with imported attachment IDs, recursively.
 * Text values keep simple markup only.
 *
 * @param mixed $value Exported value.
 * @return mixed
 */
function aw_import_acf_value( $value, string $images_dir, int $post_id ) {
	if ( is_array( $value ) && isset( $value['aw_file'] ) && 1 === count( $value ) ) {
		$id = aw_ie_import_image( $images_dir . '/' . sanitize_file_name( (string) $value['aw_file'] ), $post_id );
		return $id ?: null;
	}

	if ( is_array( $value ) ) {
		$out = array();
		foreach ( $value as $key => $item ) {
			$out[ is_int( $key ) ? $key : sanitize_key( $key ) ] = aw_import_acf_value( $item, $images_dir, $post_id );
		}
		return $out;
	}

	return is_string( $value ) ? wp_kses_post( $value ) : $value;
}
