var paths = require( './paths' );

module.exports = {

	site: {
		dir: paths.php
	},
	options: {
		bin: 'vendor/bin/phpcs',
		standard: 'WordPress'
	}

};