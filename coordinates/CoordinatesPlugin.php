<?php
namespace Craft;

/**
 * Class CoordinatesPlugin
 *
 * Thank you for using Craft Coordinates!
 * @see https://github.com/benjamminf/craft-coordinates
 * @package Craft
 */
class CoordinatesPlugin extends BasePlugin
{
	public function getName()
	{
		return Craft::t("Coordinates");
	}

	public function getDescription()
	{
		return Craft::t("Twig filters for Craft CMS that finds the latitude and longitude from an address");
	}

	public function getVersion()
	{
		return '1.3.0';
	}

	public function getDeveloper()
	{
		return 'Benjamin Fleming';
	}

	public function getDeveloperUrl()
	{
		return 'http://benjamminf.github.io';
	}

	public function getDocumentationUrl()
	{
		return 'https://github.com/benjamminf/craft-coordinates';
	}

	public function getReleaseFeedUrl()
	{
		return 'https://raw.githubusercontent.com/benjamminf/craft-coordinates/master/releases.json';
	}

	/**
	 * Register the Twig extension
	 *
	 * @return CoordinatesTwigExtension
	 */
	public function addTwigExtension()
	{
		Craft::import('plugins.coordinates.twigextensions.CoordinatesTwigExtension');

		return new CoordinatesTwigExtension();
	}
}
