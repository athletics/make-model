# Make / Model

[Athletics](http://athleticsnyc.com) created Make/Model as an environment for building prototypes.

As the web has changed, our process for designing websites has changed. Necessitated by the responsive nature of today’s web, we strive to get out of PSDs and into the browser as early in the process as possible. This allows us to build for the constraints of the multi-device web early in our design process.

Make/Model is written in PHP using the [Silex](http://silex.sensiolabs.org/) microframework and the [Twig](http://twig.sensiolabs.org/) templating engine.

## Installation

### Composer

Install Make/Model using [Composer](https://getcomposer.org/).

An example `composer.json` would look like:

```json
{
	"repositories": [
		{
			"type": "vcs",
			"url": "https://github.com/athletics/make-model"
		}
	],
	"require": {
		"athletics/make-model": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Then you can run `composer install`.

## Content Sources

Make/Model integrates with third-party APIs to use as sources for content. This removes the work of setting up representative content, passing it off to a CMS, and allowing for the reuse of content across projects.

Currently Squarespace is the only supported datasource, but several others are in the works. Pull requests for new datasources are welcome.

### Supported

- Squarespace - Squarespace features simple authentication and [a JSON endpoint for every URL](http://blog.squarespace.com/blog/your-data-everywhere).

### Upcoming

- [WordPress.com REST API](http://developer.wordpress.com/docs/api/) - Access data from WordPress.com blogs, uses OAuth 2.0 for authentication, also available in the [Jetpack for WordPress plugin](http://jetpack.me/).
- [WP-API](https://github.com/WP-API/WP-API) - Native WordPress JSON API, on the WordPress 4.1 roadmap to be integrated into WordPress core.

For more information on how the WordPress.com REST API and WP-API differ, [see the WP-API documentation](http://wp-api.org/misc/comparison.html).

## Setup

### Base Project

Copy the base-project directory into your project.

```bash
cp -r /path/to/vendor/athletics/make-model/base-project/ /path/to/my-cool-project/
```

### Configuration

Copy the example config file `config.example.php` as `config.php` and fill in the values.

## Credits

An Atheltics Project.

### Contributors

[@jamesellisnyc](https://github.com/jamesellisnyc), [@redred](https://github.com/redred), and [@matthewspencer](https://github.com/matthewspencer). Honorable mention [@cpl593h](http://www.youtube.com/watch?v=CMBeqNfYEYY).
