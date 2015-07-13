<?php
namespace Craft;

use Twig_Extension;
use Twig_Filter_Method; // TODO Deprecated class - if anyone can be bothered, fix this please

class CoordinatesTwigExtension extends \Twig_Extension
{
	private static $_cache;

	/**
	 * Returns all information about an address
	 *
	 * @param $address
	 * @return array|bool
	 */
	protected function getAddressData($address)
	{
		if(!is_array(self::$_cache))
		{
			self::$_cache = array();
		}

		if(!array_key_exists($address, self::$_cache))
		{
			$data = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address));
			$data = json_decode($data);
			$addressData = false;

			if($data->status == 'OK' && !empty($data->results))
			{
				$data = $data->results[0];
				$loc = $data->geometry->location;

				$addressData = array(
					'address' => $data->formatted_address,
					'latitude' => $loc->lat,
					'longitude' => $loc->lng
				);
			}

			self::$_cache[$address] = $addressData;
		}

		return self::$_cache[$address];
	}

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
	 * Register the Twig filters
	 *
	 * @return array
	 */
	public function getFilters()
	{
		return array(
			'latitude' => new Twig_Filter_Method($this, 'getLatitude'),
			'longitude' => new Twig_Filter_Method($this, 'getLongitude'),
			'coordinates' => new Twig_Filter_Method($this, 'getCoordinates'),
			'formatAddress' => new Twig_Filter_Method($this, 'formatAddress')
		);
	}

	/**
	 * Finds the latitude from an address
	 *
	 * @param $address
	 * @return string
	 */
	public function getLatitude($address)
	{
		$data = $this->getAddressData($address);

		return $data ? $data['latitude'] : '';
	}

	/**
	 * Finds the longitude from an address
	 *
	 * @param $address
	 * @return string
	 */
	public function getLongitude($address)
	{
		$data = $this->getAddressData($address);

		return $data ? $data['longitude'] : '';
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
		$data = $this->getAddressData($address);

		return $data ? $data['latitude'] . $separator . $data['longitude'] : '';
	}

	/**
	 * Standardises the format of an address
	 *
	 * @param $address
	 * @return mixed
	 */
	public function formatAddress($address)
	{
		$data = $this->getAddressData($address);

		return $data ? $data['address'] : $address;
	}
}
