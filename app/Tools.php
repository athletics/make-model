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

}