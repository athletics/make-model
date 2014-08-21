<?php

namespace Athletics\Make_Model;

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 * Tools
 */

class Tools {

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