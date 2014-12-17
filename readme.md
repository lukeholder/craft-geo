# Geo Plugin for Craft CMS

A simple plugin to get information about your users location.

Put the geo folder in your craft plugins folder.

Variables available in craft twig templates:

```
location: {{ craft.geo.country_name }}
ip: {{ craft.geo.ip }}
country_code: {{ craft.geo.country_code }}
country_name: {{ craft.geo.country_name }}
region_code: {{ craft.geo.region_code }}
region_name: {{ craft.geo.region_name }}
city: {{ craft.geo.city }}
zipcode: {{ craft.geo.zipcode }}
latitude: {{ craft.geo.latitude }}
longitude: {{ craft.geo.longitude }}
metro_code: {{ craft.geo.metro_code }}
areacode: {{ craft.geo.areacode }}
```

You are limited to 10,000 requests an hour for this plugin. It caches a single IP
address for 12 hours by default - config setting for this will come soon.

# TODO

Add additional API endpoints for API redundancy.

# Licence
MIT.
Pull requests welcome.