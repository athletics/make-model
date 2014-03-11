# Make / Model

Athletics created Make/Model as an environment for building prototypes. 

As the web has changed, our process for designing websites has changed. Necessitated by the responsive nature of today’s web, we strive to get out of PSDs and into the browser as early in the process as possible. This allows us to build for the constraints of the multi-device web early in our design process.

Make/Model is written in PHP using the [Silex](http://silex.sensiolabs.org/) microframework and the [Twig](http://twig.sensiolabs.org/) templating engine. It uses [Squarespace](http://blog.squarespace.com/blog/your-data-everywhere) as a datasource, which features a JSON endpoint for every URL.

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