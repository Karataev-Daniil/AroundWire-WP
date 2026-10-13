/**
 * Google Tag Manager events of the contact forms: the first input (form start) and a sent form.
 */
( function () {
	'use strict';

	const push = ( event, eventModel ) => {
		window.dataLayer?.push( { event, eventModel } );
	};

	const fieldsOf = ( form ) => Array.from( form.querySelectorAll( '.input-block input' ) );
	const started = new WeakSet();

	document.addEventListener( 'input', ( event ) => {
		const form = event.target.closest?.( '.wpcf7-form' );

		if ( ! form || started.has( form ) || ! event.target.matches( '.input-block input' ) ) {
			return;
		}

		started.add( form );

		const fields = fieldsOf( form );

		push( 'form_start_trigger', {
			form_id: form.querySelector( 'input[name="_wpcf7"]' )?.value,
			form_name: 'Contact Form',
			form_destination: window.location.href,
			form_length: fields.length,
			first_field_id: event.target.id,
			first_field_name: event.target.name,
			first_field_type: event.target.type,
			first_field_position: fields.indexOf( event.target ) + 1,
		} );
	} );

	document.addEventListener( 'wpcf7mailsent', ( event ) => {
		push( 'form_submit_trigger', {
			form_id: event.detail?.contactFormId,
			form_name: 'Contact Form',
			form_destination: window.location.href,
			form_length: fieldsOf( event.target ).length,
		} );
	} );
}() );
