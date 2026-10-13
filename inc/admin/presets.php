<?php
/**
 * Landing presets: named combinations of section versions, stored in one option.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

const AW_PRESETS_OPTION = 'saved_block_templates';

/**
 * Saved presets: name => [field key => version].
 *
 * @return array<string, array<string, string>>
 */
function aw_presets(): array {
	$presets = get_option( AW_PRESETS_OPTION, array() );

	return is_array( $presets ) ? $presets : array();
}

/**
 * Preset names as ACF select choices.
 *
 * @return array<string, string>
 */
function aw_preset_choices(): array {
	$names = array_map( 'strval', array_keys( aw_presets() ) );

	return array_combine( $names, array_map( 'esc_html', $names ) ) ?: array();
}

/**
 * Keeps only known version field keys with values like "v3".
 *
 * @param mixed $versions Raw versions from the request.
 * @return array<string, string>
 */
function aw_sanitize_preset_versions( $versions ): array {
	$allowed = array_map( static fn( string $name ): string => 'field_' . $name, aw_version_field_names() );
	$clean   = array();

	if ( ! is_array( $versions ) ) {
		return $clean;
	}

	foreach ( $versions as $key => $value ) {
		if ( in_array( $key, $allowed, true ) && is_string( $value ) && preg_match( '/^v\d{1,2}$/', $value ) ) {
			$clean[ $key ] = $value;
		}
	}

	return $clean;
}

/**
 * Common checks for the preset endpoints.
 */
function aw_presets_guard(): void {
	check_ajax_referer( 'aw_presets', 'nonce' );

	if ( ! current_user_can( 'edit_pages' ) ) {
		wp_send_json_error( 'Permission denied.', 403 );
	}
}

add_action( 'wp_ajax_aw_save_preset', 'aw_ajax_save_preset' );

/**
 * Saves the current versions under a name.
 */
function aw_ajax_save_preset(): void {
	aw_presets_guard();

	$name     = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$versions = aw_sanitize_preset_versions( wp_unslash( $_POST['versions'] ?? array() ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised by aw_sanitize_preset_versions().

	if ( '' === $name ) {
		wp_send_json_error( 'Template name is required.' );
	}

	if ( ! $versions ) {
		wp_send_json_error( 'No block versions to save.' );
	}

	$presets          = aw_presets();
	$presets[ $name ] = $versions;
	update_option( AW_PRESETS_OPTION, $presets, false );

	wp_send_json_success( array_keys( $presets ) );
}

add_action( 'wp_ajax_aw_delete_preset', 'aw_ajax_delete_preset' );

/**
 * Deletes a preset.
 */
function aw_ajax_delete_preset(): void {
	aw_presets_guard();

	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$presets = aw_presets();

	if ( ! isset( $presets[ $name ] ) ) {
		wp_send_json_error( 'Template not found.' );
	}

	unset( $presets[ $name ] );
	update_option( AW_PRESETS_OPTION, $presets, false );

	wp_send_json_success( array_keys( $presets ) );
}

add_action( 'wp_ajax_aw_get_preset', 'aw_ajax_get_preset' );

/**
 * Versions of one preset.
 */
function aw_ajax_get_preset(): void {
	aw_presets_guard();

	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$presets = aw_presets();

	if ( ! isset( $presets[ $name ] ) ) {
		wp_send_json_error( 'Template not found.' );
	}

	wp_send_json_success( aw_sanitize_preset_versions( $presets[ $name ] ) );
}

add_action( 'admin_enqueue_scripts', 'aw_enqueue_presets_script' );

/**
 * Preset controls on the page editor.
 */
function aw_enqueue_presets_script( string $hook ): void {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) || 'page' !== get_current_screen()?->post_type ) {
		return;
	}

	$relative = 'assets/js/admin/presets.js';

	wp_enqueue_script( 'aw-presets', aw_asset_uri( $relative ), array( 'acf-input' ), aw_asset_version( $relative ), true );
	wp_localize_script(
		'aw-presets',
		'awPresets',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'aw_presets' ),
			'fields'  => array_map( static fn( string $name ): string => 'field_' . $name, aw_version_field_names() ),
		)
	);
}
