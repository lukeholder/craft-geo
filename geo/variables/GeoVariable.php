<?php
namespace Craft;

class GeoVariable
{
    public function location()
    {
        return craft()->geo_location->getInfo($_SERVER['REMOTE_ADDR']);
    }
}
