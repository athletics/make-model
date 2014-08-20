/**
 *		RequireJS dynamic load config:
 *			main.js
 *
 *		desc:
 *			config for dynamic, uncompiled loading
 *
 */

requirejs.config({
	'baseUrl':                  'assets/',
    'paths': {
		'requirelib':           'bower_components/require/build/require.min',
		'jquery':               'bower_components/jquery/dist/jquery.min',
		'jqueryui/events':      'bower_components/jquery-ui/ui/minified/effect.min',
		'underscore':           'bower_components/underscore/underscore-min',
		'app/util':             'js/app/util',
		'app/mediator':         'js/app/mediator',
		'app/keycontrol':       'js/app/keycontrol',
		'app/header':           'js/app/header',
		'app/template':         'js/app/template',
		'app/inputplaceholder': 'js/app/inputplaceholder'
    },
    'shim': {
		'jqueryui/events': {
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
