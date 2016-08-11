# Coordinates
Twig filters for Craft CMS that finds the latitude and longitude from an address.

The plugin fetches the coordinates through the Google Maps API *without* the need for an API key. Simply install the plugin and being using it.

## How to use

```twig
{{ 'Flinders St, Melbourne VIC 3000' | coordinates }}
{# Outputs '-37.8182609,144.9648863' #}

{{ 'flinders STREET melbourne, Victoria, 3000' | formatAddress }}
{# Outputs 'Flinders Street, Melbourne VIC 3000, Australia' #}
```

## API

Filter                                           | Description
-------------------------------------------------|------------------------------------------------------------------
`coordinates(separator=',')` or `coords(sep...)` | Returns the latitude and longitude, separated by a custom string.
`latitude` or `lat`                              | Returns the latitude as a number.
`longitude` or `lng`                             | Returns the longitude as a number.
`formatAddress`                                  | Returns a standardised address format.

Function               | Description
-----------------------|--------------------------------------------------------------------
`addressData(address)` | Returns the latitude, longitude, and formatted address as an object


## Changelog

#### v1.1.0
- `Added` Added shorthand filters `lat`, `lng`, and `coords`, as well as a `addressData()` function
- `Added` Added release feed to the plugin
- `Improved` Improved internal caching so there is no need for `{% cache %}` tags
- `Improved` Refactored code

#### v1.0.1

- `Improved` Added minor caching so that address data is only requested once per template render.

#### v1.0.0

Initial release