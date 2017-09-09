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

        // Anonymized IP
        $ip = $this->anonymizeIp($ip);

        $cachedData = craft()->cache->get("craft.geo.".$ip);

        if ($cachedData){
            $cached = json_decode($cachedData,true);
            $cached['cached']= true;
            return $cached;
        }

        $apiOne = $this->getNekudoData($ip);

        if (!empty($apiOne)){
            $data = array_merge($data, $apiOne);
        // } else {
        //     $apiTwo = $this->getTelizeData($ip);
        //     if (!empty($apiTwo)){
        //         $data = array_merge($data, $apiTwo);
        //     }
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
        $response = $nekudoClient->get($url, array(), array("exceptions" => false))->send();

        if (!$response->isSuccessful()) {
            return array();
        }

        $data = json_decode($response->getBody());
        if (property_exists($data, "type") && $data->type === "error") {
            return array();
        }

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

    // private function getTelizeData($ip){

    //     $url = "/geip/".$ip;
    //     $telizeClient = new \Guzzle\Http\Client("http://www.telize.com");
    //     $response = $telizeClient->get($url, array(), array("exceptions" => false))->send();

    //     if (!$response->isSuccessful()) {
    //         return array();
    //     }

    //     $data = json_decode($response->getBody(),true);

    //     $data = array(
    //         "ip"=>$data['ip'],
    //         "country_code"=>$data['country_code'],
    //         "country_name"=>$data['country'],
    //         "latitude"=>$data['latitude'],
    //         "longitude"=>$data['longitude'],
    //         "cached"=>false
    //     );

    //     return $data;
    // }

    private function anonymizeIp($ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
            $segments = explode('.', $ip);

            switch (strlen($segments[3])) {
                case 1:
                case 2:
                    // Set last segment to zero
                    $segments[3] = 0;
                    break;

                case 3:
                    // Keep the first digit and set the last two to zero
                    $ending = substr($segments[3], 0, 1);
                    if (substr($segments[3], 1) == '00') {
                        // Set two random digits if IP already ended with two zeros
                        $ending .= rand(0, 9);
                        $ending .= rand(0, 9);
                    } else {
                        $ending .= '00';
                    }
                    $segments[3] = $ending;
                    break;
            }

            $anonymizedIp = implode('.', $segments);
            return $anonymizedIp;
        } else {
            // Not a valid IP
            return $ip;
        }
    }

}
