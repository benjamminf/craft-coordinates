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
			new Twig_SimpleFunction('addressData', array($this, 'getAddressData')),
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
	public function getAddressData($address)
	{
		return craft()->coordinates->getAddressData($address);
	}

	/**
	 * Finds the latitude from an address
	 *
	 * @param $address
	 * @return string
	 */
	public function getLatitude($address)
	{
		$data = craft()->coordinates->getAddressData($address);

		return $data ? $data['latitude'] : false;
	}

	/**
	 * Finds the longitude from an address
	 *
	 * @param $address
	 * @return string
	 */
	public function getLongitude($address)
	{
		$data = craft()->coordinates->getAddressData($address);

		return $data ? $data['longitude'] : false;
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
		$data = craft()->coordinates->getAddressData($address);

		return $data ? $data['latitude'] . $separator . $data['longitude'] : false;
	}

	/**
	 * Standardises the format of an address
	 *
	 * @param $address
	 * @return mixed
	 */
	public function formatAddress($address)
	{
		$data = craft()->coordinates->getAddressData($address);

		return $data ? $data['address'] : false;
	}
}
