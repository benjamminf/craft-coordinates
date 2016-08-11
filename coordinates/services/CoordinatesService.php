<?php
namespace Craft;

/**
 * Class CoordinatesService
 *
 * @package Craft
 */
class CoordinatesService extends BaseApplicationComponent
{
	/**
	 * Returns all information about an address
	 *
	 * @param $address
	 * @return array|null
	 */
	public function getAddressData($address)
	{
		$cacheKey = 'coordinates:' . md5(trim($address));
		$addressData = craft()->cache->get($cacheKey);

		if(!$addressData)
		{
			$data = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address));
			$data = json_decode($data);

			if($data->status == 'OK' && !empty($data->results))
			{
				$data = $data->results[0];
				$loc = $data->geometry->location;

				$addressData = array(
					'address' => $data->formatted_address,
					'latitude' => $loc->lat,
					'longitude' => $loc->lng
				);

				craft()->cache->set($cacheKey, $addressData);
			}
		}

		return $addressData;
	}
}
