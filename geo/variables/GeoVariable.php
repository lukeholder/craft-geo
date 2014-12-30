<?php
namespace Craft;

class GeoVariable
{

    public function info($cache=true)
    {
        return craft()->geo_location->getInfo($cache);
    }

}
