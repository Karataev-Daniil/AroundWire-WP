<?php
/**
 * Theme settings page (Settings → AroundWire).
 *
 * Integration IDs and contact details used to be hard-coded in templates.
 * Defaults keep the values the site shipped with.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

/**
 * Settings fields: key => [label, default, description].
 *
 * @return array<string, array{0: string, 1: string, 2: string}>
 */
function aw_settings_fields(): array {
	return array(
		'gtm_id'            => array( 'Google Tag Manager ID', 'GTM-KZJ3N5T3', 'Set to "off" to disable GTM.' ),
		'jivo_id'           => array( 'JivoSite widget ID', 'CmI9Acp1H1', 'Set to "off" to disable the chat widget.' ),
		'site_verification' => array( 'Google site verification', 'ZV2GpAnN3tqLZCjqD0Pn64TBAb_8YhcacN0fx2FNbuQ', 'Content of the google-site-verification meta tag.' ),
		'phone'             => array( 'Phone number', '+17029793555', 'Digits with country code, used for the tel: link.' ),
		'phone_label'       => array( 'Phone label', '1-702-979-3555', 'How the phone number is shown in the menu.' ),
		'cf7_home_form'     => array( 'Contact Form 7 ID, home page', '160e85a', '' ),
		'cf7_landing_form'  => array( 'Contact Form 7 ID, landings and popup', '6033b00', '' ),
		'mailpoet_form'     => array( 'MailPoet form ID, blog newsletter', '1', '' ),
		'brevo_meeting_url' => array( 'Brevo meeting URL', 'https://meet.brevo.com/around/borderless', 'Opened by the "Let’s meet" button on landings.' ),
	);
}

/**
 * A single theme setting with its default as a fallback.
 */
function aw_setting( string $key ): string {
	$fields = aw_settings_fields();
	$stored = get_option( 'aw_settings', array() );

	if ( is_array( $stored ) && isset( $stored[ $key ] ) && '' !== $stored[ $key ] ) {
		return 'off' === $stored[ $key ] ? '' : (string) $stored[ $key ];
	}

	return $fields[ $key ][1] ?? '';
}

add_action( 'admin_menu', 'aw_settings_menu' );

/**
 * Adds Settings → AroundWire.
 */
function aw_settings_menu(): void {
	add_options_page( 'AroundWire', 'AroundWire', 'manage_options', 'aw-settings', 'aw_render_settings_page' );
}

add_action( 'admin_init', 'aw_register_settings' );

/**
 * Registers the option and its fields.
 */
function aw_register_settings(): void {
	register_setting(
		'aw_settings',
		'aw_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'aw_sanitize_settings',
			'default'           => array(),
		)
	);

	add_settings_section( 'aw_main', '', '__return_false', 'aw-settings' );

	foreach ( aw_settings_fields() as $key => $field ) {
		add_settings_field(
			$key,
			esc_html( $field[0] ),
			'aw_render_settings_field',
			'aw-settings',
			'aw_main',
			array(
				'key'         => $key,
				'default'     => $field[1],
				'description' => $field[2],
				'label_for'   => 'aw-' . $key,
			)
		);
	}
}

/**
 * Keeps only known keys, as plain text (URLs as URLs).
 *
 * @param mixed $input Submitted values.
 * @return array<string, string>
 */
function aw_sanitize_settings( $input ): array {
	$clean = array();

	if ( ! is_array( $input ) ) {
		return $clean;
	}

	foreach ( array_keys( aw_settings_fields() ) as $key ) {
		if ( ! isset( $input[ $key ] ) ) {
			continue;
		}

		$clean[ $key ] = str_ends_with( $key, '_url' )
			? esc_url_raw( (string) $input[ $key ] )
			: sanitize_text_field( (string) $input[ $key ] );
	}

	return $clean;
}

/**
 * One text input.
 *
 * @param array{key: string, default: string, description: string} $args Field arguments.
 */
function aw_render_settings_field( array $args ): void {
	$stored = get_option( 'aw_settings', array() );
	$value  = is_array( $stored ) ? ( $stored[ $args['key'] ] ?? '' ) : '';

	printf(
		'<input type="text" class="regular-text" id="aw-%1$s" name="aw_settings[%1$s]" value="%2$s" placeholder="%3$s">',
		esc_attr( $args['key'] ),
		esc_attr( $value ),
		esc_attr( $args['default'] )
	);

	if ( '' !== $args['description'] ) {
		printf( '<p class="description">%s</p>', esc_html( $args['description'] ) );
	}
}

/**
 * The settings page.
 */
function aw_render_settings_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1>AroundWire</h1>
		<p>Empty fields fall back to the value shown as a placeholder.</p>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'aw_settings' );
			do_settings_sections( 'aw-settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}
