<?php

namespace Athletics\Make_Model;
use Exception;

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 * Make/Model Client
 */

class Client {

	private $datasource;

	public function __construct() {
		// define constants
		$this->define_constants();

		// check if writable
		$this->_writable();

		// require lib
		$this->_lib();

		// instantiate datasource
		$this->_datasource();
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Get Item
	 *
	 * @param string $item
	 * @param array $params
	 *
	 * @return array $data
	 */
	public function get_item( $item, $params = array() ) {
		$data = $this->datasource->get_item( $item, $params );

		return $data;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Get Items
	 *
	 * @param string $collection
	 * @param array $params
	 *
	 * @return array $data
	 */
	public function get_items( $collection, $params = array() ) {
		$data = $this->datasource->get_items( $collection, $params );

		return $data;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Define constants
	 */
	public function define_constants( $new_constants=array() ) {

		$constants = array(
			'ROOT' => dirname(dirname(dirname(__DIR__))),
			'APP_ROOT' => __DIR__,
			'CACHE' => __DIR__ . '/cache',
			'COOKIES' => __DIR__ . '/cookies',
		);

		// merge in any new constants
		if (isset($new_constants) && count($new_constants) > 0) {
			$constants = array_merge($constants, $new_constants);
		}

		foreach ( $constants as $constant => $value ) {
			if ( ! defined($constant) ) {
				define($constant, $value);
			}
		}
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Check if writable
	 */
	private function _writable() {
		$directories = array(
			APP_ROOT,
			CACHE,
			COOKIES,
		);

		foreach ( $directories as $directory ) {
			if ( ! file_exists( $directory ) ) {
				die( "directory {$directory} does not exist." );
			}
			elseif ( ! is_writable( $directory ) ) {
				die( "directory {$directory} is not writable." );
			}
		}
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Require library
	 */
	private function _lib() {
		$files = array(
			'Debug.php',
			'Config.php',
			'Tools.php',
			'Cache.php',
		);

		foreach ( $files as $file ) {
			require_once(APP_ROOT . "/{$file}");
		}
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Instantiate Datasource
	 */
	private function _datasource() {
		$datasource = Config::get('datasource');

		if ( is_null($datasource) ) {
			die('Please define a datasource. Ex: <code>Config::set(\'datasource\', \'squarespace\');</code>');
		}

		$datasource = ucfirst( strtolower($datasource) );

		if ( ! file_exists(APP_ROOT . "/Datasource/{$datasource}/Client.php") ) {
			die("{$datasource} does not exist.");
		}		

		require_once(APP_ROOT . "/Datasource/{$datasource}/Client.php");

		// namespaced class name for datasource
		$this->class = __NAMESPACE__ . '\\' . $datasource . '\\Client';

		// instantiate
		$this->datasource = new $this->class();

		// init
		$this->datasource->init();
	}

}