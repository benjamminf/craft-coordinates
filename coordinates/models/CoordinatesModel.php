<?php
namespace Craft;

class CoordinatesModel extends BaseModel
{
	private $_addressData;

	public function getCoordinates($separator = ',')
	{
		$lat = $this->latitude;
		$lng = $this->longitude;

		return is_numeric($lat) && is_numeric($lng) ? $lat . $separator . $lng : false;
	}

	public function getFormattedAddress()
	{
		$data = $this->_getAddressData();

		return $data ? $data['address'] : false;
	}

	public function getLat()
	{
		return $this->latitude;
	}

	public function getLng()
	{
		return $this->longitude;
	}

	public function getCoords($separator = ',')
	{
		return $this->getCoordinates($separator);
	}

	public function __toString()
	{
		return $this->getCoordinates();
	}

	protected function defineAttributes()
	{
		return array(
			'address' => AttributeType::String,
			'latitude' => AttributeType::Number,
			'longitude' => AttributeType::Number,
		);
	}

	private function _getAddressData()
	{
		if(!$this->_addressData)
		{
			$addr = $this->address;
			$this->_addressData = $addr ? craft()->coordinates->getAddressData($addr) : false;
		}

		return $this->_addressData;
	}
}
