/**
 *		js module:
 *			header.js
 *
 *		desc:
 *			general utilities
 *
 */

define([ "jquery", "jqueryui", "app/util", "app/mediator" ], function( $, jqueryui, util, mediator ) { 

	var _name = 'Header',
		_debug_enable = true,
		debug = ( _debug_enable ) ? util.debug : function(){}
	;

	_init();

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	function _init() {

		debug( _name + ': initialized' );

		// Example of mediator subscription
		_subscribe();

		_bind_hover_event();

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/*
	 * This is an example of a event that broadcasts through the mediator
	 * 
	 * See the _subscribe() example below for receiving broadcasts
	 *
	 */

	function _bind_hover_event() {

		$( '#header' )
			.on( 'mouseenter', function() {

				mediator.broadcast( 'HeaderHover' );

			} );

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/* Subscribe to mediator
	 *
	 */

	function _subscribe() {

		mediator.add( _name, function() {

			return {

				name: _name,

				onHeaderHover: function() {

					debug( this.name + ": hover" );

				}

			};

		}());

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	return {
		init : _init
	};

});
