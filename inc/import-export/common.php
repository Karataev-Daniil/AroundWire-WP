<?php
/**
 * Shared helpers for the import and export tools.
 *
 * - Every handler checks the capability and a nonce first.
 * - Work happens in a private temp folder that is always removed.
 * - ZIP archives are extracted file by file: no paths outside the folder,
 *   only expected file types, size limits.
 * - Images reach the media library through media_handle_sideload(), which validates them.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

const AW_IE_MAX_FILES      = 2000;
const AW_IE_MAX_FILE_BYTES = 20 * MB_IN_BYTES;
const AW_IE_IMAGE_TYPES    = array( 'jpg', 'jpeg', 'png', 'gif', 'webp', 'avif' );

/**
 * Stops unless the current user may manage options and the nonce is valid.
 */
function aw_ie_guard( string $action ): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to do this.', 'aroundwire' ), 403 );
	}

	check_admin_referer( $action );
}

/**
 * Creates a private temp folder that is deleted when the request ends.
 */
function aw_ie_temp_dir(): string {
	$dir = trailingslashit( get_temp_dir() ) . 'aw-ie-' . wp_generate_password( 12, false );

	if ( ! wp_mkdir_p( $dir ) ) {
		wp_die( esc_html__( 'Could not create a temporary folder.', 'aroundwire' ) );
	}

	register_shutdown_function( 'aw_rmdir', $dir );

	return $dir;
}

/**
 * Removes a folder recursively.
 */
function aw_rmdir( string $dir ): void {
	if ( ! is_dir( $dir ) ) {
		return;
	}

	$items = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ),
		RecursiveIteratorIterator::CHILD_FIRST
	);

	foreach ( $items as $item ) {
		$item->isDir() ? rmdir( $item->getPathname() ) : wp_delete_file( $item->getPathname() ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir
	}

	rmdir( $dir ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir
}

/**
 * Validated path of an uploaded file, or an error message.
 *
 * @return string|WP_Error
 */
function aw_ie_uploaded_file( string $field, string $extension ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- the nonce is checked by aw_ie_guard() before this runs.
	$file = $_FILES[ $field ] ?? null;

	if ( ! is_array( $file ) || UPLOAD_ERR_OK !== ( $file['error'] ?? UPLOAD_ERR_NO_FILE ) || ! is_uploaded_file( $file['tmp_name'] ) ) {
		return new WP_Error( 'aw_upload', 'The file was not uploaded.' );
	}

	if ( strtolower( pathinfo( (string) $file['name'], PATHINFO_EXTENSION ) ) !== $extension ) {
		return new WP_Error( 'aw_upload', sprintf( 'Please upload a .%s file.', $extension ) );
	}

	return (string) $file['tmp_name'];
}

/**
 * Extracts a ZIP archive into $dest, skipping unsafe paths and unexpected file types.
 *
 * @param string[] $extensions Allowed lowercase extensions.
 * @return true|WP_Error
 */
function aw_ie_extract_zip( string $zip_path, string $dest, array $extensions ) {
	$zip = new ZipArchive();

	if ( true !== $zip->open( $zip_path ) ) {
		return new WP_Error( 'aw_zip', 'The file is not a valid ZIP archive.' );
	}

	if ( $zip->numFiles > AW_IE_MAX_FILES ) {
		$zip->close();
		return new WP_Error( 'aw_zip', 'The archive contains too many files.' );
	}

	for ( $i = 0; $i < $zip->numFiles; $i++ ) {
		$stat = $zip->statIndex( $i );
		$name = str_replace( '\\', '/', (string) ( $stat['name'] ?? '' ) );

		if ( '' === $name || str_ends_with( $name, '/' ) ) {
			continue;
		}

		$unsafe = str_starts_with( $name, '/' ) || preg_match( '#(^|/)\.\.(/|$)#', $name ) || str_contains( $name, ':' );
		$type   = strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );

		if ( $unsafe || ! in_array( $type, $extensions, true ) || ( $stat['size'] ?? 0 ) > AW_IE_MAX_FILE_BYTES ) {
			continue;
		}

		$target = $dest . '/' . $name;
		wp_mkdir_p( dirname( $target ) );
		file_put_contents( $target, (string) $zip->getFromIndex( $i ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	}

	$zip->close();

	return true;
}

/**
 * Adds a sanitised image filename's file from the media library to a ZIP archive.
 *
 * @return string Archive filename used, or an empty string when the attachment has no file.
 */
function aw_ie_zip_attachment( ZipArchive $zip, int $attachment_id, string $folder ): string {
	$path = get_attached_file( $attachment_id );

	if ( ! $path || ! is_readable( $path ) ) {
		return '';
	}

	$filename = sanitize_file_name( wp_basename( $path ) );
	$zip->addFile( $path, trailingslashit( $folder ) . $filename );

	return $filename;
}

/**
 * Imports an image file into the media library once.
 *
 * Images imported earlier are recognised by their original filename, so running
 * the same import twice does not duplicate media.
 *
 * @return int Attachment ID, or 0 on failure.
 */
function aw_ie_import_image( string $path, int $post_id = 0 ): int {
	$filename = sanitize_file_name( wp_basename( $path ) );

	if ( ! is_readable( $path ) || ! in_array( strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) ), AW_IE_IMAGE_TYPES, true ) ) {
		return 0;
	}

	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_aw_import_source', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => $filename, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);

	if ( $existing ) {
		return (int) $existing[0];
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	// media_handle_sideload() moves the file, so give it a copy.
	$tmp = wp_tempnam( $filename );
	copy( $path, $tmp );

	$attachment_id = media_handle_sideload(
		array(
			'name'     => $filename,
			'tmp_name' => $tmp,
		),
		$post_id
	);

	if ( is_wp_error( $attachment_id ) ) {
		wp_delete_file( $tmp );
		return 0;
	}

	update_post_meta( $attachment_id, '_aw_import_source', $filename );

	return (int) $attachment_id;
}

/**
 * Finds a post of a type by its exact title.
 */
function aw_ie_find_post_by_title( string $title, string $post_type ): int {
	$ids = get_posts(
		array(
			'post_type'      => $post_type,
			'post_status'    => 'any',
			'title'          => $title,
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	return $ids ? (int) $ids[0] : 0;
}

/**
 * Streams a file as a download, deletes it and ends the request.
 */
function aw_ie_send_download( string $path, string $filename, string $type ): void {
	nocache_headers();
	header( 'Content-Type: ' . $type );
	header( 'Content-Disposition: attachment; filename="' . sanitize_file_name( $filename ) . '"' );
	header( 'Content-Length: ' . filesize( $path ) );
	readfile( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
	wp_delete_file( $path );
	exit;
}

/**
 * Redirects back to a tool page with a result notice.
 */
function aw_ie_redirect( string $page_url, string $message, bool $success ): void {
	wp_safe_redirect(
		add_query_arg(
			array(
				'aw_notice' => rawurlencode( $message ),
				'aw_status' => $success ? 'success' : 'error',
			),
			$page_url
		)
	);
	exit;
}

/**
 * Prints the notice passed by aw_ie_redirect().
 */
function aw_ie_notice(): void {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- display only.
	if ( empty( $_GET['aw_notice'] ) ) {
		return;
	}

	$status  = 'success' === ( $_GET['aw_status'] ?? '' ) ? 'success' : 'error';
	$message = sanitize_text_field( rawurldecode( wp_unslash( (string) $_GET['aw_notice'] ) ) );
	// phpcs:enable

	printf( '<div class="notice notice-%1$s"><p>%2$s</p></div>', esc_attr( $status ), esc_html( $message ) );
}

/**
 * Shared admin page: one export button and/or one upload form.
 *
 * @param array{title: string, export?: string, import?: string, accept?: string, help?: string} $page Page config.
 */
function aw_ie_render_page( array $page ): void {
	?>
	<div class="wrap">
		<h1><?php echo esc_html( $page['title'] ); ?></h1>
		<?php aw_ie_notice(); ?>

		<?php if ( ! empty( $page['help'] ) ) : ?>
			<p><?php echo esc_html( $page['help'] ); ?></p>
		<?php endif; ?>

		<?php if ( ! empty( $page['export'] ) ) : ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="<?php echo esc_attr( $page['export'] ); ?>">
				<?php wp_nonce_field( $page['export'] ); ?>
				<?php submit_button( 'Download', 'primary', 'submit', false ); ?>
			</form>
		<?php endif; ?>

		<?php if ( ! empty( $page['import'] ) ) : ?>
			<form method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="<?php echo esc_attr( $page['import'] ); ?>">
				<?php wp_nonce_field( $page['import'] ); ?>
				<input type="file" name="aw_file" accept="<?php echo esc_attr( $page['accept'] ?? '' ); ?>" required>
				<?php submit_button( 'Import', 'primary', 'submit', false ); ?>
				<p class="description">Maximum upload size: <?php echo esc_html( size_format( wp_max_upload_size() ) ); ?></p>
			</form>
		<?php endif; ?>
	</div>
	<?php
}
