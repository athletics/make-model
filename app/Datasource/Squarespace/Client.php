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

	/**
	 * @var string $url
	 * @access private
	 */
	private $url;

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Init handles auth
	 */
	public function init() {

		if ( is_null( Config::get( 'squarespace.url' ) ) ) {
			die( 'Please set the Squarespace url. Ex. <code>Config::set(\'squarespace.url\', \'blog.squarespace.com\');</code>' );
		}

		require_once(__DIR__ . '/Auth.php');

		$auth = new Auth();
		$auth->init();

		$this->url = $auth->squarespace_url();

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

		// If this is a page
		if ( isset( $data['mainContent'] ) ) {
			$data['collection']['body'] = $data['mainContent'];
			return $data['collection'];
		}

		if ( isset($data['pagination']['nextPage']) ) {
			$data = $this->_get_paginated_data( $collection, $query, $data, $params['limit'] );
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
	 * Request Paginated Data (if needed)
	 *
	 * @param string $collection
	 * @param array $query
	 * @param array $data
	 * @param int $limit
	 *
	 * @return array $data
	 */
	private function _get_paginated_data( $collection, $query, &$data, $limit ) {

		if ( (int) $limit < (int) $data['pagination']['pageSize'] ) {
			return $data;
		}

		$total = $data['pagination']['pageSize'];
		$offset = $data['pagination']['nextPageOffset'];
		$next_page = true;

		while ( $total < $limit && $next_page ) {
			$query['offset'] = $offset;

			$request = $this->_request( $collection, $query );

			$data['items'] = array_merge($data['items'], $request['items']);

			if ( isset($request['pagination']['nextPageOffset']) ) {
				$offset = $request['pagination']['nextPageOffset'];
			}

			if ( ! isset($request['pagination']['nextPage']) ) {
				$next_page = false;
			}
		}

		return $data;
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * Make Request
	 *
	 * @param string $item
	 * @param array $query
	 * @return array $data
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
		if ( $data = Cache::get( $cache_key ) ) {
			return $data;
		}

		$response = Tools::get( $url );

		// check for errors
		$this->_errors( $cache_key, $response );
		$data = json_decode( (string) $response->getBody(), true );

		// cache response
		Cache::set( $cache_key, $data );

		return $data;
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
	 * @param GuzzleHttp\Message\ResponseInterface $response
	 */
	private function _errors( $item, $response ) {

		$status = $response->getStatusCode();

		if ( $status !== 200 ) {
			$url = "http://{$this->url}/{$item}";
			$data = json_decode( (string) $response->getBody(), true );
			die( "<h1>{$status} : <a href='{$url}' target='_blank'>{$item}</a></h1> {$data['error']}" );
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
			$value = array_map( 'urlencode', ( is_array($value) ? $value : array($value) ) );
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