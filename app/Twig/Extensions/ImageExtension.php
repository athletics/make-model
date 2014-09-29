<?php

namespace Athletics\Make_Model\Twig\Extensions;

use Athletics\Make_Model\Config as Config;

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 * Twig Image Function
 *
 * To format requests for image sizes
 */
class ImageExtension extends \Twig_Extension {

	public function getName() {
		return 'image_extension';
	}

	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('image', array($this, 'image')),
		);
	}

	/**
	 * Format URL for Image sizes
	 *
	 * @param string $image
	 * @param array $size
	 * @return string $image
	 */
	public function image($image, $size = array()) {
		// no size, no need to add params
		if (empty($size)) {
			return $image;
		}

		// sanitize array keys
		if ( ! isset($size['width']) ) {
			$size['width'] = $size[0];
			unset($size[0]);
		}
		if ( count($size) === 2 && ! isset($size['height']) ) {
			$size['height'] = $size[1];
			unset($size[1]);
		}

		$datasource = Config::get('datasource');
		$datasource = ucfirst( strtolower($datasource) );

		switch ($datasource) {
			case 'Squarespace':
				$image = $this->_squarespace($image, $size);
				break;
		}

		return $image;
	}

	/**
	 * Squarespace
	 *
	 * @see http://developers.squarespace.com/using-the-imageloader/
	 * @param string $image
	 * @param array $size
	 * @return string $image
	 */
	private function _squarespace($image, $size) {
		if (count($size) === 2) {
			$width = $size['width'];
			$height = $size['height'];

			$image .= "?format={$width}x{$height}";
		}
		else {
			$image .= "?format={$size['width']}w";
		}

		return $image;
	}

}