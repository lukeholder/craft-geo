# Geo Plugin for Craft CMS

A simple plugin to get information about your users location.

Put the geo folder in your craft plugins folder.

Variables available in craft twig templates:

```
location: {{ craft.geo.info.country_name }}
ip: {{ craft.geo.info.ip }}
country_code: {{ craft.geo.info.country_code }}
country_name: {{ craft.geo.info.country_name }}
region_code: {{ craft.geo.info.region_code }}
region_name: {{ craft.geo.info.region_name }}
city: {{ craft.geo.info.city }}
zipcode: {{ craft.geo.info.zipcode }}
latitude: {{ craft.geo.info.latitude }}
longitude: {{ craft.geo.info.longitude }}
metro_code: {{ craft.geo.info.metro_code }}
areacode: {{ craft.geo.info.areacode }}
cached: {{ craft.geo.info.cached }}
```

You are limited to 10,000 requests an hour for this plugin. It caches a single IP
address for 12 hours by default - config setting for this will come soon.

If you are in Crafts devMode then a default ip adress of 8.8.8.8 will be used.
Config for this also to come.

# TODO

Add additional API endpoints for API redundancy.

# Licence
MIT.
Pull requests welcome.