<?php

namespace Athletics\Make_Model\Squarespace;

use Athletics\Make_Model\Config;
use Athletics\Make_Model\Tools;
use Athletics\Make_Model\Cache;

use Exception;

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 * Squarespace Auth
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Auth {

	/**
	 * @var string $url
	 * @access private
	 */
	private $url;

	/**
	 * @var string $crumb
	 * @access private
	 */
	private $crumb;

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Init
	 * 	- Setup Squarespace URL
	 *  - Check if logged in
	 */
	public function init() {

		$this->url = $this->squarespace_url();
		$response = Tools::get( "http://{$this->url}/?format=json" );

		if ( $response->getStatusCode() !== 401 ) return;

		$this->crumb = $this->get_crumb( $response );
		$this->authorize();

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	private function authorize() {

		if ( is_null( Config::get( 'squarespace.password' ) ) ) {
			die( 'Please set the Squarespace password. Ex. <code>Config::set(\'squarespace.password\', \'p4ssw0rd!\');</code>' );
		}

		$crumb = $this->crumb;
		$password = Config::get( 'squarespace.password' );

		$url = "http://{$this->url}/api/auth/AuthenticateWithSite";
		$data = [
			'crumb' => $crumb,
			'password' => $password,
		];

		$response = Tools::post( $url, [ 'body' => $data ] );
		$data = $response->json();

		if ( isset( $data['error'] ) ) {
			die( $data['error'] );
		}

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Get crumb (nonce) for auth request
	 *
	 * @param  GuzzleHttp\Message\ResponseInterface $response
	 * @return mixed $crumb
	 */
	private function get_crumb( $response ) {

		$string = (string) $response->getHeader( 'set-cookie' );

		preg_match( '/crumb=(.*);/', $string, $matches );

		$crumb = ! empty( $matches[1] ) ? $matches[1] : false;

		return $crumb;

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Sanitized URL from Config
	 *
	 * @return string
	 */
	public function squarespace_url() {

		$url = Config::get( 'squarespace.url' );

		$search = [
			'http://',
			'https://',
			'/',
		];

		return str_replace( $search, '', $url );

	}

}