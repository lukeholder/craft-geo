<?php
namespace Craft;

class GeoPlugin extends BasePlugin
{
    public function getName()
    {
         return Craft::t('Geo');
    }

    public function getVersion()
    {
        return '1.3';
    }

    public function getDeveloper()
    {
        return 'Luke Holder';
    }

    public function getDeveloperUrl()
    {
        return 'http://lukeholder.com';
    }
}
