/**
 * "Block versions" box on the page edit screen: apply, save and delete presets
 * (named combinations of section versions).
 */
( function () {
	'use strict';

	const settings = window.awPresets;

	const fieldSelect = ( key ) => document.querySelector( `[data-key="${ key }"] select` );

	function request( action, data = {} ) {
		const body = new URLSearchParams( { action, nonce: settings.nonce } );

		Object.entries( data ).forEach( ( [ key, value ] ) => {
			if ( value && typeof value === 'object' ) {
				Object.entries( value ).forEach( ( [ field, version ] ) => body.append( `${ key }[${ field }]`, version ) );
			} else {
				body.append( key, value );
			}
		} );

		document.body.classList.add( 'loading' );

		return fetch( settings.ajaxUrl, { method: 'POST', credentials: 'same-origin', body } )
			.then( ( response ) => response.json() )
			.then( ( json ) => {
				if ( ! json.success ) {
					throw new Error( json.data || `Could not ${ action.replace( 'aw_', '' ).replace( '_', ' ' ) }.` );
				}

				return json.data;
			} )
			.catch( ( error ) => {
				window.alert( error.message );
				throw error;
			} )
			.finally( () => document.body.classList.remove( 'loading' ) );
	}

	function renderPresetOptions( select, names, selected = '' ) {
		select.replaceChildren( new Option( '- Select -', '' ), ...names.map( ( name ) => new Option( name, name, false, name === selected ) ) );
	}

	function init() {
		const presetSelect = fieldSelect( 'field_template_presets' );

		if ( ! presetSelect || ! settings ) {
			return;
		}

		presetSelect.addEventListener( 'change', () => {
			if ( ! presetSelect.value ) {
				return;
			}

			request( 'aw_get_preset', { name: presetSelect.value } ).then( ( versions ) => {
				Object.entries( versions ).forEach( ( [ key, version ] ) => {
					const select = fieldSelect( key );

					if ( select ) {
						select.value = version;
						select.dispatchEvent( new Event( 'change', { bubbles: true } ) );
					}
				} );
			} );
		} );

		document.getElementById( 'aw-save-preset' )?.addEventListener( 'click', () => {
			const name = window.prompt( 'Template name:' )?.trim();

			if ( ! name ) {
				return;
			}

			const versions = {};

			settings.fields.forEach( ( key ) => {
				const select = fieldSelect( key );

				if ( select ) {
					versions[ key ] = select.value;
				}
			} );

			request( 'aw_save_preset', { name, versions } ).then( ( names ) => {
				renderPresetOptions( presetSelect, names, name );
				window.alert( 'Template saved.' );
			} );
		} );

		document.getElementById( 'aw-delete-preset' )?.addEventListener( 'click', () => {
			const name = presetSelect.value;

			if ( ! name ) {
				window.alert( 'Select a template to delete.' );
				return;
			}

			if ( ! window.confirm( `Delete the template "${ name }"?` ) ) {
				return;
			}

			request( 'aw_delete_preset', { name } ).then( ( names ) => renderPresetOptions( presetSelect, names ) );
		} );
	}

	if ( window.acf?.addAction ) {
		window.acf.addAction( 'ready', init );
	} else {
		document.addEventListener( 'DOMContentLoaded', init );
	}
}() );
