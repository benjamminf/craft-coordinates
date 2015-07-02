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

### Always use within cache blocks
Since this plugin fetches data from Google's servers, it's highly recommended that any use is wrapped in a [cache block](http://buildwithcraft.com/docs/templating/cache) to prevent it from making requests to Google on every page load.
```twig
{% cache %}
    {{ 'Flinders St, Melbourne VIC 3000' | coordinates }}
{% endcache %}
```

## API

Filter                       | Description
-----------------------------|------------------------------------------------------------------
coordinates(separator = ',') | Returns the latitude and longitude, separated by a custom string.
latitude                     | Returns the latitude as a number.
longitude                    | Returns the longitude as a number.
formatAddress                | Returns a standardised address format.
