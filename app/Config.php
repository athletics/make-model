<?php

namespace Athletics\Make_Model;

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 * Config
 */

class Config {

	/**
	 * Config Array
	 *
	 * @var array
	 */
	private static $config = array();

	/**
	 * Set Config
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	static function set( $key, $value ) {
		self::$config[$key] = $value;
	}

	/**
	 * Get Config
	 *
	 * @param string $key
	 * @return mixed
	 */
	static function get( $key ) {
		return isset( self::$config[$key] ) ? self::$config[$key] : null;
	}

}