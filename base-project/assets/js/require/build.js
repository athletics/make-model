/**
 *		RequireJS compiled config:
 *			build.js
 *
 *		desc:
 *			
 *
 */

({
    baseUrl:                  'assets/',
    paths: {
		'requirelib':           'bower_components/require/build/require.min',
		'jquery':               'bower_components/jquery/dist/jquery.min',
		'jqueryui/events':      'bower_components/jquery-ui/ui/minified/effect.min',
		'jquery/throttledebounce':     'js/lib/jquery.ba-throttle-debounce.min',
		'app/util':             'js/app/util',
		'app/mediator':         'js/app/mediator',
		'app/keycontrol':       'js/app/keycontrol'
    },
	shim: {
		'jqueryui/events': {
			'deps':           [ 'jquery' ]
		},
		'jquery/throttledebounce': {
			'deps':           [ 'jquery' ]
		}
	},
    name:                     'app',
    out:                      'compiled/app-compiled.js',
    include:                  [ 'requirelib' ]
});
