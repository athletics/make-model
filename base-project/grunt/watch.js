var paths = require( './paths' );

module.exports = {

	styles: {
		// Which files to watch (all .less files recursively in the less directory)
		files: paths.styles,
		tasks: [ 'less' ],
		options: {
			nospawn: true
		}
	}

};