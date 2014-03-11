/**
 *		RequireJS dynamic load config:
 *			main.js
 *
 *		desc:
 *			config for dynamic, uncompiled loading
 *
 */

requirejs.config({
	'baseUrl':                'assets/js',
    'paths': {
		'requirelib':         'require/require',
		'jquery':             'lib/jquery-1.11.0.min',
		/* Optional CDN delivered jQuery
		'jquery':             [
								'//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min',
								'./lib/jquery-1.11.0.min'
							  ],
		*/
		'jqueryui':           'lib/jquery-ui-1.10.4.custom.min',
		'underscore':         'lib/underscore-min',
		'app/util':           'app/util',
		'app/mediator':       'app/mediator',
		'app/keycontrol':     'app/keycontrol',
		'app/header':         'app/header',
		'app/template':       'app/template',
		'app/inputplaceholder':       'app/inputplaceholder'
    },
    'shim': {
		'jqueryui': {
			'deps':           [ 'jquery' ]
		}
	}
});

requirejs([
	'jquery',
	'underscore',
	'app/util',
	'app/mediator',
	'app/template',
	'app/header',
	'app/inputplaceholder'
]);
