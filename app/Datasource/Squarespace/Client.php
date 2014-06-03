<?php

namespace Athletics\Make_Model\Squarespace;

use Athletics\Make_Model\Config as Config;
use Athletics\Make_Model\Tools as Tools;
use Athletics\Make_Model\Cache as Cache;

use Exception;

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 * Squarespace Client
 */

class Client {

	private $url;
	private $cookie;

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Init handles auth
	 */
	public function init() {
		if ( is_null(Config::get('squarespace.url')) ) {
			die('Please set the Squarespace url. Ex. <code>Config::set(\'squarespace.url\', \'blog.squarespace.com\');</code>');
		}

		$this->url = Config::get('squarespace.url');

		require_once(__DIR__ . '/Auth.php');

		$auth = new Auth();
		$auth->init();

		$this->cookie = COOKIES . '/squarespace.cookie.txt';
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Get Item
	 *
	 * @param string $item
	 * @param array $params 
	 * 		- featured bool
	 * 		- random bool
	 * 		- offset int
	 * 		- category mixed
	 * 		- tag mixed
	 * 
	 * @return array $item
	 */
	public function get_item( $item, $params = array() ) {
		// push limit on params
		$params['limit'] = 1;

		// use get_items()
		$items = $this->get_items( $item, $params );

		if ( count($items) > 1 ) {
			$item = $items;
		}
		else {
			// returns array of one if from collection
			$item = $items[0];
		}

		return $item;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Get Items
	 *
	 * @param string $collection
	 * @param array $params 
	 * 		- featured bool
	 * 		- random bool
	 * 		- offset int
	 * 		- limit int
	 * 		- category mixed
	 * 		- tag mixed
	 * 
	 * @return array $items
	 */
	public function get_items( $collection, $params = array() ) {
		// categories && tags
		$query = array();
		$taxonomies = array( 'category', 'tag' );

		foreach ( $taxonomies as $taxonomy ) {
			if ( isset( $params[$taxonomy] ) ) {
				// push onto array
				$query[$taxonomy] = $params[$taxonomy];
			}
		}
		
		$data = $this->_request( $collection, $query );

		// If is not a collection, sqs returns item instead of items
		if ( isset( $data['item'] ) ) {
			return $data['item'];
		}

		$items = $data['items'];

		// featured
		if ( isset( $params['featured'] ) ) {
			// sqs featured post key is starred
			$key = 'starred';

			// returns filtered array
			$items = $this->_multidimensional_array_filter( 
				$items,
				$key,
				$params['featured']
			);
		}

		// random
		if ( isset( $params['random'] ) && $params['random'] === true ) {
			shuffle( $items );
		}

		// offset
		if ( isset( $params['offset'] ) && ! empty( $params['offset'] ) ) {
			$items = array_slice( $items, $params['offset'] );
		}

		// limit 
		if ( isset( $params['limit'] ) && ! empty( $params['limit'] ) ) {
			$items = array_slice( $items, 0, $params['limit'] );
		}

		// filter data
		if ( ! isset( $params['raw'] ) || $params['raw'] !== true ) {
			$items = $this->_filter_items( $items );
		}

		return $items;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Make Request
	 *
	 * @param string $item
	 * @param array $query
	 * @return array $response
	 */
	private function _request( $item, $query = array() ) {
		$cache_key = $item;
		$url = "http://{$this->url}/{$item}?format=json";

		// if query var
		if ( ! empty( $query ) ) {
			$query_string = $this->_http_build_query($query);

			$cache_key = $cache_key . $query_string;
			$url = $url . $query_string;
		}

		// return from cache if 
		if ( $response = Cache::get( $cache_key ) ) {
			return $response;
		}

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

		// check for errors
		$this->_errors( $cache_key, $request );
		$response = Tools::json_decode($request['response']);

		// cache response 
		Cache::set( $cache_key, $response );

		return $response;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Helper: Filter Multidimensional Array by Key
	 *
	 * @param array $items
	 * @param string $key
	 * @param mixed $value
	 * @return array $items
	 */
	private function _multidimensional_array_filter( $items, $key, $value ) {
		$keymaster = array();

		foreach ( $items as $itemkey => $item ) {
			if ( $item[$key] !== $value ) {
				$keymaster[] = $itemkey;
			}
		}

		foreach ( $keymaster as $gatekeeper ) {
			unset( $items[$gatekeeper] );
		}

		return $items;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Check Request for Errors
	 * 
	 * @param string $item
	 * @param array $request
	 */
	private function _errors( $item, $request ) {

		if ( isset( $request['response']['error'] ) ) {
			$url = "http://{$this->url}/{$item}";

			die( "<h1>{$request['status']} : <a href='{$url}' target='_blank'>{$item}</a></h1> {$request['response']['error']}" );
		}

	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Helper: Build Query String from Multidimensional Array
	 *
	 * @param array $query
	 * @return string $query_string
	 */
	private function _http_build_query( $query ) {
		$query_string = '';

		foreach ( $query as $key => $value ) {
			$value = array_map( 'urlencode', $value );
			$csv = implode(',', $value);
			$query_string .= "&{$key}={$csv}";
		}

		return $query_string;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Helper: Filter Useful Data
	 *
	 * @param array $items
	 * @return array $filtered 
	 */
	private function _filter_items( $items ) {
		$unnecessary = array(
			'id',
			'collectionId',
			'cloned',
			'recordType',
			'version',
			'addedOn',
			'updatedOn',
			'displayIndex',
			'passthrough',
			'workflowState',
			'publishOn',
			'authorId',
			'systemDataId',
			'systemDataVariants',
			'systemDataSourceType',
			'systemDataOrigin',
			'filename',
			'mediaFocalPoint',
			'urlId',
			'sourceUrl',
			'customContent',
			'likeCount',
			'dislikeCount',
			'commentCount',
			'publicCommentCount',
			'commentState',
			'unsaved',
			'author' => array(
				'id',
				'lastLoginOn',
				'lastActiveOn',
				'deleted',
				'personalAccount',
				'isGlobalAdmin',
				'avatarId',
				'enabled',
				'confirmed',
				'emailVerified',
				'revalidateTimestamp',
				'invitesGiven',
				'systemGenerated',
			),
			'pushedServices',
			'originalSize',
			'recordTypeLabel',
		);

		foreach ( $items as &$item ) {
			foreach ( $unnecessary as $key => $unset ) {
				if ( is_array( $unset ) ) {

					foreach ( $unset as $nested ) {
						unset($item[$key][$nested]);
					}

					continue;
				}

				unset($item[$unset]);
			}
		}

		return $items;
	}

}