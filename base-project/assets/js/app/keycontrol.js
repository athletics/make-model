/**
 *		js module:
 *			keycontrol.js
 *
 *		desc:
 *			general utilities
 *
 */

define([ "jquery", "app/util", "app/mediator" ], function( $, util, mediator ) {

	var _name = 'Keycontrol',
		_debug_enable = true,
		debug = ( _debug_enable ) ? util.debug : function(){},
		_key_codes = [
			38,  // arrow up
			40   // arrow down
		]
	;	

	_init();

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	function _init() {

		_bind();
		_subscribe();
	
		debug( _name + ': initialized' );

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	function _bind() {

		$( window ).on( 'keydown', function( e ) {

			// debug( e.which );

			for ( var i in _key_codes ) {

				if ( event.which === _key_codes[ i ] ) {

					// Broadcast this key
					mediator.broadcast( 'KeyUp', [ _key_codes[ i ] ] );

					e.preventDefault();

				}

			}

		});

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/* Subscribe to mediator
	 *
	 */

	function _subscribe() {

		mediator.add( _name, function() {

			return {

				name: _name,

				onKeyUp: function( key_code ) {

					debug( this.name + ": key up." );
					debug( key_code );

				}

			};

		}());

	}

	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	return {};

});
