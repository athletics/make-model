<?php

namespace Athletics\Make_Model\Squarespace;

use Athletics\Make_Model\Config as Config;
use Athletics\Make_Model\Tools as Tools;
use Athletics\Make_Model\Cache as Cache;

use Exception;

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 * Squarespace Auth
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Auth {

	private $url;
	private $cookie;

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	public function init() {
		// set up cookie
		$this->_cookie();

		// get and sanitize url
		$this->url = Config::get('squarespace.url');
		$this->url = str_replace(array('http://', 'https://', '/'), array('', '', ''), $this->url);

		$url = "http://{$this->url}/?format=json";
		$options = array(
			'CURLOPT_MAXREDIRS' => 4,
			'CURLOPT_RETURNTRANSFER' => true,
			'CURLOPT_FOLLOWLOCATION' => true,
			'CURLOPT_COOKIEJAR' => $this->cookie,
			'CURLOPT_COOKIEFILE' => $this->cookie,
			'CURLOPT_HEADER' => true
		);
		$method = 'GET';

		$request = Tools::curl($url, $options, $method);

		if ($request['status'] !== 401) return;

		// authorize
		$this->_authorize();
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	private function _authorize() {
		if ( is_null(Config::get('squarespace.password')) ) {
			die('Please set the Squarespace password. Ex. <code>Config::set(\'squarespace.password\', \'p4ssw0rd!\');</code>');
		}

		// clear cookies
		file_put_contents($this->cookie, '');

		$crumb = $this->_crumb();

		$url = "http://{$this->url}/api/auth/AuthenticateWithSite";
		$options = array(
			'CURLOPT_MAXREDIRS' => 4,
			'CURLOPT_RETURNTRANSFER' => true,
			'CURLOPT_FOLLOWLOCATION' => true,
			'CURLOPT_COOKIEJAR' => $this->cookie,
			'CURLOPT_COOKIEFILE' => $this->cookie,
			'CURLOPT_POSTFIELDS' => array(
				'password' => Config::get('squarespace.password'),
				'crumb' => $crumb
			)
		);
		$method = 'POST';

		$request = Tools::curl($url, $options, $method);
		$response = Tools::json_decode($request['response']);

		if ( isset( $response['error'] ) ) {
			$error = 'Squarespace Config Error: ' . ( isset( $response['errors']['password'] ) ? $response['errors']['password'] : 'Unable to connect.' );
			die( $error );
		}
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Get crumb (nonce) for auth request
	 *
	 * @return string $crumb
	 */
	private function _crumb() {
		$url = "http://{$this->url}/";
		$options = array(
			'CURLOPT_MAXREDIRS' => 4,
			'CURLOPT_RETURNTRANSFER' => true,
			'CURLOPT_FOLLOWLOCATION' => true,
			'CURLOPT_COOKIEJAR' => $this->cookie,
			'CURLOPT_COOKIEFILE' => $this->cookie,
			'CURLOPT_HEADER' => true
		);
		$method = 'GET';

		$request = Tools::curl($url, $options, $method);
		
		$response = $request['response'];

		preg_match('/crumb=(.*);/', $response, $matches);

		$crumb = $matches[1];

		return $crumb;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Set up cookie
	 */
	private function _cookie() {
		$this->cookie = COOKIES . '/squarespace.cookie.txt';

		if ( ! file_exists( $this->cookie ) ) {
			file_put_contents($this->cookie, '');
		}
	}

}