<?php
namespace Craft;

use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleFilter;

/**
 * Class CoordinatesTwigExtension
 *
 * @package Craft
 */
class CoordinatesTwigExtension extends \Twig_Extension
{
	/**
	 * The name of the Twig extension
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'Coordinates';
	}

	/**
	 * Register the Twig functions
	 *
	 * @return array
	 */
	public function getFunctions()
	{
		return array(
			new Twig_SimpleFunction('addressData', array($this, 'getCoordinatesModel')),
			new Twig_SimpleFunction('centerCoords', array($this, 'getCenter')),
			new Twig_SimpleFunction('averageCoords', array($this, 'getAverage')),
		);
	}

	/**
	 * Register the Twig filters
	 *
	 * @return array
	 */
	public function getFilters()
	{
		return array(
			new Twig_SimpleFilter('latitude', array($this, 'getLatitude')),
			new Twig_SimpleFilter('lat', array($this, 'getLatitude')),
			new Twig_SimpleFilter('longitude', array($this, 'getLongitude')),
			new Twig_SimpleFilter('lng', array($this, 'getLongitude')),
			new Twig_SimpleFilter('coordinates', array($this, 'getCoordinates')),
			new Twig_SimpleFilter('coords', array($this, 'getCoordinates')),
			new Twig_SimpleFilter('formatAddress', array($this, 'formatAddress')),
		);
	}

	/**
	 * Returns the latitude, longitude and formatted address from an address
	 *
	 * @param $address
	 * @return mixed
	 */
	public function getCoordinatesModel($address)
	{
		return craft()->coordinates->getModel($address);
	}

	/**
	 * Finds the latitude from an address
	 *
	 * @param $address
	 * @return string
	 */
	public function getLatitude($address)
	{
		$model = craft()->coordinates->getModel($address);

		return $model ? $model->latitude : false;
	}

	/**
	 * Finds the longitude from an address
	 *
	 * @param $address
	 * @return string
	 */
	public function getLongitude($address)
	{
		$model = craft()->coordinates->getModel($address);

		return $model ? $model->longitude : false;
	}

	/**
	 * Finds the latitude and longitude from an address
	 *
	 * @param $address
	 * @param string $separator
	 * @return string
	 */
	public function getCoordinates($address, $separator = ',')
	{
		$model = craft()->coordinates->getModel($address);

		return $model->getCoordinates($separator);
	}

	/**
	 * Standardises the format of an address
	 *
	 * @param $address
	 * @return mixed
	 */
	public function formatAddress($address)
	{
		$model = craft()->coordinates->getModel($address);

		return $model ? $model->getFormattedAddress() : false;
	}

	/**
	 * @param array $coords
	 * @return mixed
	 */
	public function getCenter(array $coords)
	{
		return craft()->coordinates->getCenterCoordinates($coords);
	}

	/**
	 * @param array $coords
	 * @return mixed
	 */
	public function getAverage(array $coords)
	{
		return craft()->coordinates->getAverageCoordinates($coords);
	}
}
