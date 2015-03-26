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
        return 'Luke Holder (Make with Morph)';
    }

    public function getDeveloperUrl()
    {
        return 'http://makewithmorph.com';
    }
}
