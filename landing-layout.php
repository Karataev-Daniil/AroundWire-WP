<?php
/**
 * Template Name: Landing Page
 *
 * Renders the sections of aw_sections() in order, each in the version chosen
 * in the "Block versions" box. Their assets are enqueued by aw_enqueue_landing_assets().
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

get_header();

$aw_layout = aw_landing_layout();
$aw_paired = aw_sections_are_paired( $aw_layout );

foreach ( $aw_layout as $aw_key => $aw_version ) {
	if ( $aw_paired && 'expand-clientele' === $aw_key ) {
		continue; // Rendered inside success steps v1.
	}

	aw_render_section( $aw_key, $aw_version, array( 'nested_expand' => $aw_paired && 'success-step' === $aw_key ) );
}

get_footer();
