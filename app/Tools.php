<?php

namespace Athletics\Make_Model;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Cookie\FileCookieJar;

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 * Tools
 */

class Tools {

	/**
	 * @var $guzzle GuzzleHttp\Client
	 * @access private
	 */
	private static $guzzle = null;

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * @var $jar GuzzleHttp\Cookie\FileCookieJar
	 * @access private
	 */
	private static $jar = null;

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * GET
	 *
	 * @param  string $url
	 * @param  array $params
	 * @return array
	 */
	public static function get( $url, $params = [] ) {

		return self::client( $url, $params, 'get' );

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * POST
	 *
	 * @param  string $url
	 * @param  array $params
	 * @return array
	 */
	public static function post( $url, $params = [] ) {

		return self::client( $url, $params, 'post' );

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Guzzle Client
	 *
	 * @param  string $url
	 * @param  array $params
	 * @param  string $type
	 * @return array
	 */
	private static function client( $url, $params, $type ) {

		if ( ! ini_get( 'date.timezone' ) ) {
			date_default_timezone_set( 'UTC' );
		}

		if ( is_null( self::$guzzle ) ) {
			self::$guzzle = new Guzzle();
		}

		if ( is_null( self::$jar ) ) {
			self::$jar = new FileCookieJar( COOKIES . '/cookies.json' );
		}

		$params = array_merge( $params, [
			'cookies' => self::$jar,
			'exceptions' => false,
		] );

		return self::$guzzle->$type( $url, $params );

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * curl wrapper
	 *
	 * @param string $url
	 * @param array $options
	 * @param string $method
	 * @return array $return
	 */
	public static function curl($url, $options, $method) {
		$return = array();

		if ( ! function_exists('curl_version') ) {
			die('The PHP CURL extension is not installed.');
		}

		$ch = curl_init();
		$useragent = self::useragent();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);

		foreach ($options as $option => $value) {
			$option = str_replace( 'CURLOPT_', '', strtoupper($option) );
			$value = ( is_array( $value ) ? http_build_query( $value, NULL, '&' ) : $value );

			curl_setopt($ch, constant("CURLOPT_{$option}"), $value);
		}

		$method = strtoupper($method);

		switch ($method) {
			case 'GET':
				curl_setopt($ch, CURLOPT_HTTPGET, true);
				break;
			case 'POST':
				curl_setopt($ch, CURLOPT_POST, true);
				break;
			case 'HEAD':
				curl_setopt($ch, CURLOPT_NOBODY, true);
				break;
			default:
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		}

		$response = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$return['status'] = $status;
		$return['response'] = $response;

		return $return;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * JSON Decode
	 *
	 * @param string $json
	 * @return array $result
	 */
	public static function json_decode( $json ) {
		$json_response_start = strpos( $json, '{' );
		$json_response_end = strrpos( $json, '}' );
		$json_response_string = substr( $json, $json_response_start, $json_response_end );
		$result = json_decode( $json_response_string, true );

		return $result;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * User-Agent
	 *
	 * @return string $useragent
	 */
	public static function useragent() {
		if ( isset($_SERVER['HTTP_USER_AGENT']) ) {
			return $_SERVER['HTTP_USER_AGENT'];
		}
		else if ( defined('PHP_VERSION_ID') ) {
			return 'PHP ' . PHP_VERSION_ID;
		}
		else {
			return 'PHP';
		}
	}

}