/**
 *		js module:
 *			inputplaceholder.js
 *
 *		desc:
 *			general utilities
 *
 */

define([ "jquery", "app/util" ], function( $, util ) { 

	"use strict";

	var _name = 'InputPlaceholder',
		_debug_enable = true,
		debug = ( _debug_enable ) ? util.debug : function(){}
	;

	_init();

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	function _init() {

		debug( _name + ': initialized' );

		_detect();

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/* 
	 * Only apply if the browser doesn't support input placeholder 
	 *
	 */

	function _detect() {

		var is_ie7 = false,
			is_ie8 = false,
			is_ie9 = false,
			modernizr_detects_false = false
		;

		if ( $( 'html' ).hasClass( 'ie7' ) ) {
			is_ie7 = true;
		} else if ( $( 'html' ).hasClass( 'ie8' ) ) {
			is_ie8 = true;
		} else if ( $( 'html' ).hasClass( 'ie9' ) ) {
			is_ie9 = true;
		} else if (( typeof Modernizr !== 'undefined' ) && !Modernizr.input.placeholder) {
			modernizr_detects_false = true;
		}

		if ( is_ie7 || is_ie8 || is_ie9 || modernizr_detects_false ) {
			_setup();
		}

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	function _setup() {

		var $nodes = $( 'input[placeholder]' );

		$.each( $nodes, function( index, node ) {

			_set_text( $( node ) );

			_bind_handlers( $( node ) );

		});

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	function _set_text( $node ) {

		$node.val( $node.attr( 'placeholder' ) );

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	function _bind_handlers( $node ) {

		$node
			.on( 'focus', function() {
				if ( $node.val() === $node.attr( 'placeholder' ) ) {
					$node.val( '' );
				}
			})
			.on( 'blur', function() {
				if ( $node.val() === '' ) {
					_set_text( $node );
				}
			});	

	};

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

});
