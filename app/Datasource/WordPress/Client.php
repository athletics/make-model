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

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Get Item
	 *
	 * @param string $item
	 * @param array $params
	 * @param array $item
	 */
	public function get_item( $item, $params = array() ) {
		// restrict to one result
		// will be ignored if requesting a specific post
		$params['number'] = 1;

		$data = $this->get_items( $item, $params );

		return $data;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Get Items
	 *
	 * @param string $collection
	 * @param array $params
	 * @return array $items
	 */
	public function get_items( $collection, $params = array() ) {
		$data = $this->_request( $collection, $params );

		return $data;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Make Request
	 *
	 * @param string $item
	 * @param array $params
	 * @return array $response
	 */
	private function _request( $item, $params ) {

		$this->_restricted_post_actions_check( $item );

		$cache_key = $item;
		$url = $this->_build_url($item, $params);

		if ( $response = Cache::get( $cache_key ) ) {
			return $response;
		}

		$request = Tools::curl($url, array(
			'CURLOPT_MAXREDIRS' => 4,
			'CURLOPT_RETURNTRANSFER' => true,
			'CURLOPT_FOLLOWLOCATION' => true,
			'CURLOPT_HEADER' => true
		), 'GET');

		$this->_errors( $item, $params, $request );

		extract($request);

		Cache::set( $cache_key, $response );

		return $response;

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Build URL for API Request
	 *
	 * @param string $item
	 * @param array $params
	 * @return string $url
	 */
	private function _build_url( $item, $params ) {
		$url = "https://public-api.wordpress.com/rest/v1/sites/{$this->url}/{$item}";

		if ( ! empty( $query ) ) {
			$query_string = http_build_query($query);

			$cache_key .= $query_string;
			$url .= "?{$query_string}";
		}

		return $url;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Check Request for Errors
	 * 
	 * @param string $item
	 * @param array $params
	 * @param array $request
	 */
	private function _errors( $item, $params, $request ) {

		if ( $request['status'] !== 200 ) {
			$url = $this->_build_url($item, $params);

			die( "<h1>{$request['status']} : <a href='{$url}' target='_blank'>{$item}</a></h1>" );
		}

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Check for Post Method Actions
	 *
	 * @param string $item_to_check
	 */
	private function _restricted_post_actions_check( $item_to_check ) {

		$requires_post_method_and_oauth = array(
			'/new',
			'/delete',
			'/related',
		);

		array_filter($requires_post_method_and_oauth, function($restricted) use($item_to_check) {
			if ( strpos($item_to_check, $restricted) !== false ) {
				die("{$item_to_check} requires oauth2 authentication with wordpress.com which is not currently supported.");
			}
		});

	}

}