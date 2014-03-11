/**
 *		js module:
 *			mediator.js
 *
 *		desc:
 *			general utilities
 *
 *		credit:
 *			This module contains code based on HB Stone mediator pattern
 *			http://arguments.callee.info
 *          license: Creative Commons Attribution 3.0 license
 */

define([ "jquery", "app/util" ], function( $, util ) {

	var _name = 'Mediator',
		_debug_enable = true,
		debug = ( _debug_enable ) ? util.debug : function(){},
		_components = {}
	;

	debug( _name + ': initialized' );

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	function _broadcast( event, args, source ) {

        var e = event || false;
        var a = args || [];

        if ( !e ) {
            return;
        }

        //debug( [ "Mediator received", e, a ].join(' ') );
        for ( var c in _components ) {

            if ( typeof _components[ c ][ "on" + e ] == "function" ) {

                try {

                    //debug("Mediator calling " + e + " on " + c);
                    var s = source || _components[ c ];
                    _components[ c ][ "on" + e ].apply( s, a );

                } catch ( err ) {

                    debug( [ "Mediator error.", e, a, s, err ].join(' ') );

                }

            }

        }

    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    function _add_component( name, component, replace_duplicate ) {

        if ( name in _components ) {

            if ( replace_duplicate ) {

                _remove_component( name );

            } else {

                throw new Error( 'Mediator name conflict: ' + name );

            }

        }

        _components[ name ] = component;

		// debug( _components );

    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    function _remove_component( name ) {

        if ( name in _components ) {

            delete _components[ name ];

        }

    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    function _get_component( name ) {

        return _components[ name ] || false;

    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    function _contains_component( name ) {

        return ( name in _components );

    }

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/* return public-facing methods and/or vars */
	return {
		broadcast           : _broadcast,
        add                 : _add_component,
        rem                 : _remove_component,
        get                 : _get_component,
        has                 : _contains_component
	};

});
