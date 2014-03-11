/**
 *		js module:
 *			template.js
 *
 *		desc:
 *			template example that renders json data
 *
 */

define([ 'jquery', 'app/util', 'underscore' ], function( $, util, _ ) {

	var _name = 'Template',
		_debug_enable = true,
		debug = ( _debug_enable ) ? util.debug : function(){};
	;	

	 _find_data();

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/*
	 * Look for data to render
	 *
	 * Example:
	 *
	 * 		<div class="example_js_render"
	 *			data-data-url="assets/data/example.json"
	 *			data-template-id="#example_js_template"
	 *			data-target-selector="body" >
	 *		</div><!-- .example_js_render -->
	 *
	 */

	function _find_data() {

		var $data = $( '.example_js_render' ) // Find an HTML node with this class
		;

		if ( $data.length > 0 ) {

			$.each( $data, function( index, node ) {

				/* Example only */
				_render({
					'template_id': $( node ).attr( 'data-template-id' ),
					'$target': $( $( node ).attr( 'data-target-selector' ) ),
					'data_url': $( node ).attr( 'data-data-url' )
				});

			});

		}	

	}		

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/*
	 * Pass a template id, render destination, and JSON datasource
	 * and this function will render the data in HTML
	 */

	function _render( options ) {

		var template_id = options.template_id,
			$target = options.$target,
			data_url = options.data_url
		;

		$.ajax({
			'url': data_url,
			'dataType': 'json',
			'success': function( data ) {

				var html = $( template_id ).html(),
					template = _.template( html )
				;

				_.find( data.items, function( index ) {

					var html = template({
						'title': index.title,
						'body': index.body,
						'slug': index.urlId
					});

					$target.append( html );

				});
			}
		});

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	return {
		render : _render
	};

});	