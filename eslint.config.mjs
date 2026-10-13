import js from '@eslint/js';
import globals from 'globals';

export default [
	{
		ignores: [ 'assets/vendor/**', 'vendor/**', 'node_modules/**' ],
	},
	js.configs.recommended,
	{
		files: [ 'assets/js/**/*.js' ],
		languageOptions: {
			ecmaVersion: 2022,
			sourceType: 'script',
			globals: {
				...globals.browser,
			},
		},
		rules: {
			'no-var': 'error',
			'prefer-const': 'error',
			eqeqeq: 'error',
		},
	},
];
