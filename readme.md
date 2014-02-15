# Geo Plugin for Craft CMS

A simple plugin to get information about your users location.

Put the geo folder in your craft plugins folder.

Variables available in craft twig templates:

```
location: {{ craft.geo.location.country_name }}
ip: {{ craft.geo.location.ip }}
country_code: {{ craft.geo.location.country_code }}
country_name: {{ craft.geo.location.country_name }}
region_code: {{ craft.geo.location.region_code }}
region_name: {{ craft.geo.location.region_name }}
city: {{ craft.geo.location.city }}
zipcode: {{ craft.geo.location.zipcode }}
latitude: {{ craft.geo.location.latitude }}
longitude: {{ craft.geo.location.longitude }}
metro_code: {{ craft.geo.location.metro_code }}
areacode: {{ craft.geo.location.areacode }}
```

You are limited to 10,000 requests an hour for this plugin.

# Licence
MIT.
Pull requests welcome.