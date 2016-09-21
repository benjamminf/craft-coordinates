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
		$plugin = craft()->plugins->getPlugin('coordinates');

		$cacheKey = 'coordinates-' . $plugin->getVersion() . ':' . md5(trim($address));
		$addressData = craft()->cache->get($cacheKey);

		if(!$addressData)
		{
			$data = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address));
			$data = json_decode($data);

			if($data->status == 'OK' && !empty($data->results))
			{
				$data = $data->results[0];
				$loc = $data->geometry->location;

				$parts = [];

				foreach($data->address_components as $part)
				{
					$type = $part->types[0];
					$parts[$type] = $part->long_name;

					if(in_array('postal_code', $part->types))
					{
						$parts['postal_code'] = $part->long_name;
					}

					if(in_array('country', $part->types))
					{
						$parts['country'] = $part->long_name;
					}

					if(in_array('administrative_area_level_1', $part->types))
					{
						$parts['state'] = $part->long_name;
					}

					if(in_array('administrative_area_level_2', $part->types))
					{
						$parts['council'] = $part->long_name;
					}

					if(in_array('locality', $part->types))
					{
						$parts['suburb'] = $part->long_name;
					}

					if(in_array('route', $part->types))
					{
						$parts['street'] = $part->long_name;
					}

					if(in_array('street_number', $part->types))
					{
						$parts['street_number'] = $part->long_name;
					}
				}

				$addressData = array(
					'address' => $data->formatted_address,
					'parts' => $parts,
					'latitude' => $loc->lat,
					'longitude' => $loc->lng,
				);

				craft()->cache->set($cacheKey, $addressData);
			}
		}

		return $addressData;
	}

	/**
	 * @param $address
	 * @return CoordinatesModel
	 */
	public function getModelFromAddress($address)
	{
		$model = new CoordinatesModel;
		$model->address = $address;

		$data = $this->getAddressData($address);

		if($data)
		{
			$model->latitude = floatval($data['latitude']);
			$model->longitude = floatval($data['longitude']);
		}

		return $model;
	}

	/**
	 * @param int $lat
	 * @param int $lng
	 * @return CoordinatesModel
	 */
	public function getModelFromCoordinates($lat = 0, $lng = 0)
	{
		$model = new CoordinatesModel;
		$model->latitude = floatval($lat);
		$model->longitude = floatval($lng);

		return $model;
	}

	/**
	 * @param array $coords
	 * @return CoordinatesModel
	 */
	public function getModelFromArray(array $coords)
	{
		$lat = isset($coords[0]) ? $coords[0] : 0;
		$lng = isset($coords[1]) ? $coords[1] : 0;

		return $this->getModelFromCoordinates($lat, $lng);
	}

	/**
	 * @param $value
	 * @return CoordinatesModel|null
	 */
	public function getModel($value)
	{
		if($value instanceof CoordinatesModel)
		{
			return $value;
		}

		if(is_string($value))
		{
			return $this->getModelFromAddress($value);
		}

		if(is_array($value))
		{
			return $this->getModelFromArray($value);
		}

		return null;
	}

	/**
	 * @param array $coords
	 * @return array
	 */
	public function getCenterCoordinates(array $coords)
	{
		$minLat = INF;
		$minLng = INF;
		$maxLat = -INF;
		$maxLng = -INF;

		foreach($coords as $coord)
		{
			$coord = $this->getModel($coord);

			if($coord instanceof CoordinatesModel)
			{
				$minLat = min($minLat, $coord->latitude);
				$minLng = min($minLng, $coord->longitude);
				$maxLat = max($maxLat, $coord->latitude);
				$maxLng = max($maxLng, $coord->longitude);
			}
		}

		$lat = ($minLat + $maxLat) / 2;
		$lng = ($minLng + $maxLng) / 2;

		return $this->getModelFromCoordinates($lat, $lng);
	}

	/**
	 *
	 * @see http://stackoverflow.com/a/18623672/556609
	 * @param array $coords
	 * @return array
	 */
	public function getAverageCoordinates(array $coords)
	{
		$numCoords = count($coords);

		$x = 0.0;
		$y = 0.0;
		$z = 0.0;

		foreach($coords as $coord)
		{
			$coord = $this->getModel($coord);

			if($coord instanceof CoordinatesModel)
			{
				$lat = floatval($coord->latitude) * pi() / 180;
				$lon = floatval($coord->longitude) * pi() / 180;

				$a = cos($lat) * cos($lon);
				$b = cos($lat) * sin($lon);
				$c = sin($lat);

				$x += $a;
				$y += $b;
				$z += $c;
			}
		}

		$x /= $numCoords;
		$y /= $numCoords;
		$z /= $numCoords;

		$lng = atan2($y, $x) * 180 / pi();
		$hyp = sqrt($x * $x + $y * $y);
		$lat = atan2($z, $hyp) * 180 / pi();

		return $this->getModelFromCoordinates($lat, $lng);
	}

	/**
	 * @param $lat
	 * @param $lng
	 * @return string
	 */
	public function getUrl($lat, $lng)
	{
		return "http://www.google.com/maps/place/{$lat},{$lng}";
	}

	/**
	 * @param $address
	 * @return string
	 */
	public function getAddressUrl($address)
	{
		return 'http://www.google.com/maps/place/' . urlencode($address);
	}
}
