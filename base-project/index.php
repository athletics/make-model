<?php

/**
 * Include Config
 */
$config_file = __DIR__ . '/config/config.php';

// check if config.php exists
if ( ! file_exists($config_file) ) {
	die("Please create {$config_file}.");
}

// get config array
$config = require_once($config_file);

// check if config returns array
if ( ! is_array($config) ) {
	die('Config file is incorrectly formatted. Please refer to config.example.php');
}

// check if vendor.path is set
if ( ! isset($config['vendor.path']) ) {
	die('Please set vendor.path in the config file.');
}

// check if autoload exists
if ( ! file_exists( "{$config['vendor.path']}/vendor/autoload.php" ) ) {
	die("{$config['vendor.path']}/vendor/autoload.php does not exist. Please run <code>composer install</code>.");
}

// require classes
require_once("{$config['vendor.path']}/vendor/autoload.php");


/**
 * Alias Classes
 */
use Athletics\Make_Model\Config as Config;
use Athletics\Make_Model\Client as Client;
use Athletics\Make_Model\Twig\Extensions\ImageExtension as ImageExtension;


/**
 * Config 
 */
if ( ! isset($config['datasource']) || empty($config['datasource']) ) {
	die('Please set the datasource in the config file.');
}

Config::set('datasource', $config['datasource']['name']);

foreach ($config['datasource'] as $key => $value) {
	if ( $key === 'name' ) continue;

	Config::set("{$config['datasource']['name']}.{$key}", $value);
}


/**
 * Start Silex
 */
$app = new Silex\Application();

// turn on debug
$app['debug'] = true;

// instantiate the client service
$app['client'] = function () {
	return new Client();
};

// register twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__ . '/templates/',
));

// add our twig extension
$app['twig']->addExtension( new ImageExtension($app) );


/**
 * Router
 */
$app
	->match('{url}', function($url) use($app) {
		// sanitize url
		$server_path = $_SERVER['SCRIPT_NAME'];
		$server_path = str_replace('index.php', '', $server_path);

		// clean url
		$url = str_replace($server_path, '', $url);

		$files = array( 
			'template' => "{$url}/template.twig",
			'data' => "{$url}/data.php",
		);

		$templates = __DIR__ . '/templates/';

		// throw 404 if template or data is not found
		foreach ($files as $file) {
			if ( ! file_exists( $templates . $file ) ) {
				die("404 - {$file} does not exist");
			}
		}

		// get data
		$data = include($templates . $files['data']);

		return $app['twig']->render($files['template'], $data);
	})
	// default value is index for root
	->value('url', 'index')

	// allow trailing slash to match
	->assert('url', '.+')
;

$app->run();