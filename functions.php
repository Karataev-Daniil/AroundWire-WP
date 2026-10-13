<?php
/**
 * AroundWire theme bootstrap.
 *
 * Every feature lives in its own file under inc/ and uses the aw_ prefix.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

define( 'AW_VERSION', '2.0.0' );
define( 'AW_DIR', get_template_directory() );
define( 'AW_URI', get_template_directory_uri() );

$aw_modules = array(
	'helpers',
	'settings',
	'setup',
	'sections',
	'assets',
	'post-types',
	'content',
	'blog',
	'reviews',
	'search',
	'seo',
	'ajax',
	'admin/presets',
	'admin/related-jobs',
	'import-export/common',
	'import-export/blog',
	'import-export/faqs',
	'import-export/pages',
);

foreach ( $aw_modules as $aw_module ) {
	require_once AW_DIR . '/inc/' . $aw_module . '.php';
}

unset( $aw_modules, $aw_module );
