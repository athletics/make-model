/**
 *		RequireJS compiled config:
 *			build.js
 *
 *		desc:
 *			
 *
 */

({
    baseUrl:                  './assets/js',
    paths: {
		'requirelib':         './require/require',
		'jquery':             './lib/jquery-1.11.0.min',
		'jqueryui':           './lib/jquery-ui-1.10.4.custom.min',
		'throttledebounce':   './lib/jquery.ba-throttle-debounce.min',
		'app/util':           './app/util',
		'app/mediator':       './app/mediator',
		'app/keycontrol':     './app/keycontrol'
    },
	shim: {
		'jqueryui': {
			'deps':           [ 'jquery' ]
		},
		'throttledebounce': {
			'deps':           [ 'jquery' ]
		}
	},
    name:                     'app',
    out:                      'compiled/app-compiled.js',
    include:                  [ 'requirelib' ]
})
