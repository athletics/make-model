<?php

namespace Athletics\Make_Model\WordPress;

use Athletics\Make_Model\Config as Config;
use Athletics\Make_Model\Tools as Tools;
use Athletics\Make_Model\Cache as Cache;

use Exception;

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 * WordPress Client
 *
 * @see http://developer.wordpress.com/docs/api/
 */

class Client {

	private $url;

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	public function init() {

		if ( is_null(Config::get('wordpress.url')) ) {
			die('Please set the WordPress url. Ex. <code>Config::set(\'wordpress.url\', \'blog.wordpress.com\');</code>');
		}

		$this->url = $this->_format_url( Config::get('wordpress.url') );

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Format URL for use with REST API
	 *
	 * @param string $url
	 * @return string $url
	 */
	private function _format_url( $url ) {

		// Remove the protocol
		$url = str_replace( array('http://', 'https://'), '', $url );

		// Remove trailing slash
		$url = rtrim( $url, '/' );

		// URL encode the URL
		// Provides the ability to use a Jetpack-connected blog running in a subdirectory
		$url = urlencode( $url );

		return $url;

	}

}