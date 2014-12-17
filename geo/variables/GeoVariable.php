<?php
namespace Craft;

class GeoVariable
{

    public function info()
    {

        return craft()->geo_location->getInfo();
    }

}
