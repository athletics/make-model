<?php

namespace Athletics\Make_Model;
use Exception;

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 * Disk Cache
 */

class Cache {

	/**
	 * Set Cache Item
	 *
	 * @param string $key
	 * @param array $data
	 *
	 * @return bool $cache_set
	 */
	public static function set( $key, $data ) {
		$datasource = Config::get('datasource');
		$key = self::_sanitize_key( $key );
		$filename = "{$datasource}.{$key}.txt";
		$timestamp = time();

		// serialize data with timestamp
		$serialized_data = serialize( array('timestamp' => $timestamp, 'data' => $data) );

		// write cache
		$cache_set = file_put_contents( CACHE . "/{$filename}", $serialized_data );

		// file_put_contents() returns false on failure, number of bytes written on success
		$cache_set = ( $cache_set === false ? $cache_set : true );

		return $cache_set;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Get Cache Item
	 *
	 * @param string $key
	 * 
	 * @return bool|array
	 */
	public static function get( $key ) {
		$datasource = Config::get('datasource');
		$key = self::_sanitize_key( $key );
		$filename = "{$datasource}.{$key}.txt";

		// get cache
		$serialized_data = @file_get_contents( CACHE . "/{$filename}" );

		// file_get_contents() returns false on failure
		if ( $serialized_data === false ) return false;

		$cache = unserialize( $serialized_data );
		extract( $cache );

		// return false if cache is expired
		$cache_duration = ( ! is_null(Config::get('cache.duration')) ? (int) Config::get('cache.duration') : 300 );
		if ( time() > ($timestamp + $cache_duration) ) return false;

		return $data;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Helper Function: Sanitize Key
	 * 
	 * Clean up key name
	 * Ex. users/john-doe => users.john-doe
	 *
	 * @param string $key
	 * @return string $key
	 */
	private static function _sanitize_key( $key ) {
		$search = array(
			'/',
			'&',
			'=',
			',',
			'+'
		);
		$replace = array(
			'.',
			'.',
			'_',
			'-',
			'-',
		);

		$key = strtolower( str_replace($search, $replace, $key) );

		return $key;
	}

}