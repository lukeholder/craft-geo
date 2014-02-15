<?php
namespace Craft;

class Geo_LocationService extends BaseApplicationComponent
{
    public function getInfo($ip)
    {
$default = <<<EOD
{"ip":"203.59.25.206","country_code":"","country_name":"","region_code":"","region_name":"","city":"","zipcode":"","latitude":"","longitude":"","metro_code":"","areacode":""}
EOD;

        $url = "/json/".$ip;
        $client = new \Guzzle\Http\Client("http://freegeoip.net");
        $response = $client->get($url)->send();

        if ($response->isSuccessful()) {
          return json_decode($response->getBody());
        } else {
          return json_decode($default);
        }
    }
}
