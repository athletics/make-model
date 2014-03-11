<?php

/**
 * Example Config File
 *
 * Make a copy of this file named config.php
 */

$config = array(
	'vendor.path' => dirname(dirname(__DIR__)),

	// example of a more complex vendor path:
	// 'vendor.path' => dirname(dirname(dirname(dirname(__DIR__)))) . '/composer',

	'datasource' => array( 
		'name' => 'squarespace',
		'url' => 'blog.squarespace.com',
		'password' => 'p4ssw0rd!',
	),

	'cache.duration' => 300,
);

return $config;