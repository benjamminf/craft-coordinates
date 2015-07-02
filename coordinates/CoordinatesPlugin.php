<?php
namespace Craft;

class CoordinatesPlugin extends BasePlugin
{
	/**
	 * The name of the plugin
	 *
	 * @return mixed
	 */
	function getName()
	{
		return Craft::t('Coordinates');
	}

	/**
	 * Current version
	 *
	 * @return string
	 */
	function getVersion()
	{
		return '1.0';
	}

	/**
	 * The knucklehead who wrote this plugin
	 *
	 * @return string
	 */
	function getDeveloper()
	{
		return 'Benjamin Fleming';
	}

	/**
	 * His fancy pants website which probably doesn't exist
	 *
	 * @return string
	 */
	function getDeveloperUrl()
	{
		return 'http://benf.co';
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
