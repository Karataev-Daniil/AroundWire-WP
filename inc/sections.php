<?php
/**
 * Landing page sections: one registry drives the version fields, the asset loader and the layout.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

const AW_LANDING_TEMPLATE = 'landing-layout.php';

/**
 * Sections in render order.
 *
 * - field:    ACF field that stores the chosen version (keys are unchanged from v1 of the theme).
 * - versions: number of design variants.
 * - wrapper:  base class of the section element.
 * - script:   optional section script in assets/js/sections.
 *
 * @return array<string, array{label: string, field: string, versions: int, wrapper: string, script?: string}>
 */
function aw_sections(): array {
	return array(
		'hero-block'       => array(
			'label'    => 'hero block',
			'field'    => 'hero_block_version',
			'versions' => 7,
			'wrapper'  => 'hero-block__wrapper',
		),
		'benefit'          => array(
			'label'    => 'benefits',
			'field'    => 'benefits_version',
			'versions' => 8,
			'wrapper'  => 'benefits__wrapper',
			'script'   => 'benefits',
		),
		'success-step'     => array(
			'label'    => 'success steps',
			'field'    => 'success_steps_version',
			'versions' => 8,
			'wrapper'  => 'success-steps__wrapper',
		),
		'expand-clientele' => array(
			'label'    => 'expand clienteles',
			'field'    => 'expand_clienteles_version',
			'versions' => 6,
			'wrapper'  => 'expand-clientele__wrapper',
		),
		'more-view'        => array(
			'label'    => 'more views',
			'field'    => 'more_views_version',
			'versions' => 7,
			'wrapper'  => 'more-views__wrapper',
		),
		'start-work'       => array(
			'label'    => 'start work',
			'field'    => 'start_work_version',
			'versions' => 7,
			'wrapper'  => 'start-work__wrapper',
		),
		'faq'              => array(
			'label'    => 'faq',
			'field'    => 'faq_version',
			'versions' => 6,
			'wrapper'  => 'faqs__wrapper',
		),
		'review'           => array(
			'label'    => 'reviews',
			'field'    => 'reviews_version',
			'versions' => 1,
			'wrapper'  => 'buyer-stories__wrapper',
			'script'   => 'reviews',
		),
		'fitback-button'   => array(
			'label'    => 'fitback button',
			'field'    => 'fitback_button_version',
			'versions' => 4,
			'wrapper'  => 'fitback-button__wrapper',
		),
	);
}

/**
 * Layout-level version fields that are not sections: [field, label, versions].
 *
 * @return array<int, array{0: string, 1: string, 2: int}>
 */
function aw_layout_version_fields(): array {
	return array(
		array( 'menu_version', 'version menu', 8 ),
		array( 'footer_menu_version', 'version footer menu', 5 ),
	);
}

/**
 * Whether the current request renders the landing template.
 */
function aw_is_landing(): bool {
	return is_page_template( AW_LANDING_TEMPLATE );
}

/**
 * Validated version key of a field: 'v1' … 'vN', 'v1' when empty or out of range.
 */
function aw_version_from_field( string $field, int $versions, ?int $post_id = null ): string {
	$value  = aw_string( aw_field( $field, $post_id ?? false ) );
	$number = preg_match( '/^v(\d+)$/', $value, $matches ) ? (int) $matches[1] : 1;

	return 'v' . ( $number >= 1 && $number <= $versions ? $number : 1 );
}

/**
 * Chosen version of every section on a landing page.
 *
 * @return array<string, string> Section key => version.
 */
function aw_landing_layout( ?int $post_id = null ): array {
	$layout = array();

	foreach ( aw_sections() as $key => $section ) {
		$layout[ $key ] = aw_version_from_field( $section['field'], $section['versions'], $post_id );
	}

	return $layout;
}

/**
 * Success steps v1 and expand clientele v1 are designed as one block: the second
 * is rendered inside the first one's container. Any other combination renders them separately.
 *
 * @param array<string, string> $layout Section key => version.
 */
function aw_sections_are_paired( array $layout ): bool {
	return 'v1' === ( $layout['success-step'] ?? '' ) && 'v1' === ( $layout['expand-clientele'] ?? '' );
}

/**
 * Renders a landing section.
 *
 * @param array<string, mixed> $args Extra template arguments.
 */
function aw_render_section( string $key, string $version, array $args = array() ): void {
	$sections = aw_sections();

	if ( ! isset( $sections[ $key ] ) ) {
		return;
	}

	get_template_part(
		'template-parts/sections/' . $key,
		null,
		array_merge(
			$args,
			array(
				'version' => $version,
				'number'  => aw_version_number( $version ),
				'classes' => $sections[ $key ]['wrapper'] . ' ' . $version,
			)
		)
	);
}

add_action( 'acf/init', 'aw_register_version_fields' );

/**
 * Sidebar box "block versions" on landing pages, with saved presets.
 */
function aw_register_version_fields(): void {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$select = static function ( string $name, string $label, int $versions ): array {
		$choices = array();

		for ( $i = 1; $i <= $versions; $i++ ) {
			$choices[ 'v' . $i ] = 'v' . $i;
		}

		return array(
			'key'           => 'field_' . $name,
			'label'         => $label,
			'name'          => $name,
			'type'          => 'select',
			'choices'       => $choices,
			'default_value' => 'v1',
			'wrapper'       => array( 'class' => 'aw-version-field' ),
		);
	};

	$fields = array(
		array(
			'key'          => 'field_template_presets',
			'label'        => 'Template Presets',
			'name'         => 'template_presets',
			'type'         => 'select',
			'choices'      => aw_preset_choices(),
			'allow_null'   => true,
			'instructions' => 'Select a saved template or create a new combination below.',
		),
		array(
			'key'     => 'field_delete_template_button',
			'label'   => 'Delete Template',
			'name'    => 'delete_template_button',
			'type'    => 'message',
			'message' => '<button id="aw-delete-preset" type="button" class="button button-secondary">Delete Template</button>',
		),
	);

	$layout_fields = aw_layout_version_fields();
	$fields[]      = $select( ...$layout_fields[0] );

	foreach ( aw_sections() as $section ) {
		$fields[] = $select( $section['field'], 'version ' . $section['label'], $section['versions'] );
	}

	$fields[] = $select( ...$layout_fields[1] );
	$fields[] = array(
		'key'          => 'field_save_template_button',
		'label'        => 'Save Template',
		'name'         => 'save_template_button',
		'type'         => 'message',
		'message'      => '<button id="aw-save-preset" type="button" class="button button-primary">Save Template</button>',
		'instructions' => 'Saves the current combination of block versions as a new template.',
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_block_versions',
			'title'    => 'Block versions',
			'fields'   => $fields,
			'location' => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => AW_LANDING_TEMPLATE,
					),
				),
			),
			'position' => 'side',
		)
	);
}

/**
 * Names of all version fields (sections and layout).
 *
 * @return string[]
 */
function aw_version_field_names(): array {
	$names = array_column( aw_sections(), 'field' );

	foreach ( aw_layout_version_fields() as $field ) {
		$names[] = $field[0];
	}

	return $names;
}
