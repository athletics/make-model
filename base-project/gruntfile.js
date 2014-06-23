module.exports = function(grunt) {

	// 1. All configuration goes here 
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		less: {
			development: {
				options: {
					compress: false,
					yuicompress: false,
					optimization: 2
				},
				files: {
					// target.css file: source.less file
					'assets/css/css-bootstrap.css': 'assets/css/css-bootstrap.less'
				}
			}
		},
		
		watch: {
			options : {
				livereload: true,
			},
			styles: {
				// Which files to watch (all .less files recursively in the less directory)
				files: ['assets/css/**/*.less'],
				tasks: ['less'],
				options: {
					nospawn: true
				}
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-watch');

	// 4. Where we tell Grunt what to do when we type "grunt" into the terminal.
	grunt.registerTask('default',
		[
			'less',
			'watch'
		]
	);

};