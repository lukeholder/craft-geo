<?php
namespace Craft;

class Geo_LocationService extends BaseApplicationComponent
{
    public function getInfo()
    {
        $data = array(
            'ip'=>"",
            "country_code"=>"",
            "country_name"=>"",
            "region_code"=>"",
            "region_name"=>"",
            "city"=>"",
            "zipcode"=>"",
            "latitude"=>"",
            "longitude"=>"",
            "metro_code"=>"",
            "areacode"=>"",
            "timezone"=>"",
            "cached"=>false
        );
    
        $ip = craft()->config->get('devMode') ? '8.8.8.8' : craft()->request->getIpAddress();

        $cachedData = craft()->cache->get("craft.geo.".$ip);

        if ($cachedData){
            $cached = json_decode($cachedData,true);
            $cached['cached']= true;
            return $cached;
        }
        
        $data = array_merge($data,$this->getNekudoData($ip));
        
        craft()->cache->add("craft.geo.".$ip,json_encode($data),43200);

        return $data;
    }


    private function getNekudoData($ip){

        $url = "/api/".$ip."/full";
        $nekudoClient = new \Guzzle\Http\Client("http://geoip.nekudo.com");
        $response = $nekudoClient->get($url)->send();

        if (!$response->isSuccessful()) {
            return array();
        }

        $data = json_decode($response->getBody());


        $data = array(
            'ip'=>$data->traits->ip_address,
            "country_code"=>$data->country->iso_code,
            "country_name"=>$data->country->names->en,
            "region_name"=>$data->subdivisions[0]->names->en,
            "city"=>$data->city->names->en,
            "latitude"=>$data->location->latitude,
            "longitude"=>$data->location->longitude,
            "cached"=>false
        );

        return $data;
    }
}
