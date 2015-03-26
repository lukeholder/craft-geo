<?php
namespace Craft;

class Geo_LocationService extends BaseApplicationComponent
{
    public function getInfo($doCache)
    {
        $data = array(
            "ip"=>"",
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
        
        $devMode = craft()->config->get('devMode');
        $ip = craft()->request->getIpAddress();

        $localIps = array("127.0.0.1","::1");

        if(in_array($ip,$localIps) or $devMode)
        {
             $ip = craft()->config->get('defaultIp', 'geo');
        }

        $cachedData = craft()->cache->get("craft.geo.".$ip);

        if ($cachedData){
            $cached = json_decode($cachedData,true);
            $cached['cached']= true;
            return $cached;
        }
        
        $apiOne = $this->getNekudoData($ip);

        if (!empty($apiOne)){
            $data = array_merge($data,$apiOne);
        }else{

            $apiTwo = $this->getTelizeData($ip);

            if (!empty($apiTwo)){
                $data = array_merge($data,$apiTwo);
            }

        }

        
        if($doCache){
            $seconds = craft()->config->get('cacheTime', 'geo');
            craft()->cache->add("craft.geo.".$ip,json_encode($data),$seconds);    
        }
        

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

        if(isset($data->subdivisions[0])){
            $regionName = $data->subdivisions[0]->names->en;
        }else{
            $regionName = "";
        }

        if(isset($data->city)){
            $city = $data->city->names->en;
        }else{
            $city = "";
        }

        $data = array(
            "ip"=>$data->traits->ip_address,
            "country_code"=>$data->country->iso_code,
            "country_name"=>$data->country->names->en,
            "region_name"=>$regionName,
            // Yes i know, i am not getting postcode etc yet.
            "city"=>$city,
            "latitude"=>$data->location->latitude,
            "longitude"=>$data->location->longitude,
            "cached"=>false
        );

        return $data;
    }

    private function getTelizeData($ip){

        $url = "/geip/".$ip;
        $telizeClient = new \Guzzle\Http\Client("http://www.telize.com");
        $response = $telizeClient->get($url)->send();

        if (!$response->isSuccessful()) {
            return array();
        }

        $data = json_decode($response->getBody(),true);

        $data = array(
            "ip"=>$data['ip'],
            "country_code"=>$data['country_code'],
            "country_name"=>$data['country'],
            "latitude"=>$data['latitude'],
            "longitude"=>$data['longitude'],
            "cached"=>false
        );

        return $data;
    }
}
