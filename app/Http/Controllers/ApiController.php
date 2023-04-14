<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Api  Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling all the API requests.
    |
    */

    /**
     * Geolocate the user and get the lists of stores and recycling centers.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Facades\View
    */
    function geoLocate(Request $request) {
        // geolocate the user
        $latitude = floatval($request->input('latitude'));
        $longitude = floatval($request->input('longitude'));
        $locationDetails = $this->getLocationDetails($latitude, $longitude);

        // get the list of the recycling centers
        $recyclingCenters = $this->getRecyclingCenters($locationDetails['latitude'], $locationDetails['longitude']);

        // get the list of the stores
        $storeResults = $this->getStores($latitude, $longitude);

        // return the results in one view
        return View::make('results', [
            'currentLocation' => $locationDetails['formatted'], 
            'recyclingCenters' => $recyclingCenters,
            'storeResults' => $storeResults,
            'itinerary' => null,
        ]);
    }

    /**
     * Get the geolocation of the user, using Open Cage Data API.
     * @param float $latitude
     * @param float $longitude
     * @return array<float, string>
    */
    private function getLocationDetails(float $latitude, float $longitude) {
        $result = [];
        // prepare the query with its parameters
        $queryString = http_build_query([
            'key' => env('OPEN_CAGE_DATA_API_KEY'),
            'q' => $latitude . '+' . $longitude,
            'limit' => 1,
        ]);

        // run the query and get the results
        $ch = curl_init(sprintf('%s?%s', 'https://api.opencagedata.com/geocode/v1/json', $queryString));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($ch);
        curl_close($ch);
        $apiResult = json_decode($json, true);

        // store the results in an array
        $data = $apiResult['results'][0];
        $result['latitude'] = $data['geometry']['lat'];
        $result['longitude'] = $data['geometry']['lng'];
        $result['formatted'] = $data['formatted'];
        return $result;
    }

    /**
     * Get the list of nearby recyling centers, using Opendatasoft API.
     * @param float $latitude
     * @param float $longitude
     * @return array<float, string>
    */
    private function getRecyclingCenters(float $latitude, float $longitude) {
        $results = [];

        // prepare the query with the parameters
        $distance = '5km'; // the maximum radius of search
        $queryString = http_build_query([
            'limit' => 10, 
            'offset' => 0,
            'refine' => 'dechets:Equipements électriques et électroniques hors d\'usage',
            'lang' => 'fr',
            'timezone' => 'UTC',
            'where' => 'distance(coordonnees, geom\'{"type": "Point", "coordinates": [' . $longitude . ',' . $latitude . ']}\',' . $distance . ')',
        ]);

        // run query and get results
        $ch = curl_init(sprintf('%s?%s', 'https://public.opendatasoft.com/api/v2/catalog/datasets/sinoe-annuaire-dma-annexe/records', $queryString));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($ch);
        curl_close($ch);
        $apiResults = json_decode($json, true);

        // populate the results array
        $i = 0;
        foreach($apiResults['records'] as $result) {
            $i++;
            $data = $result['record']['fields'];
            $result['name'] = $data['nom_decheterie'];
            $result['address'] = $data['adresse_decheterie_suite'];
            $result['postal_code'] = $data['code_postal_decheterie'];
            $result['city'] = $data['commune_decheterie'];
            $result['state'] = $data['nom_de_la_region'];
            $result['phone'] = $data['telephone'];
            $result['longitude'] = $data['coordonnees']['lon'];
            $result['latitude'] = $data['coordonnees']['lat'];
            $result['anchor'] = preg_replace('/[^A-Za-z0-9\-]/', '-', strtolower($result['name'])) . '-' . $i;
            $results[] = $result;
        }
        return $results;
    }

    /**
     * Get the list of nearby electronics store, using Geoapify Places API.
     * @param float $latitude
     * @param float $longitude
     * @return array<float, string>
    */
    private function getStores(float $latitude, float $longitude) {
        // prepare the query
        $radiusMeters = '2000';
        $limit = '20'; 
        $queryString = http_build_query([
            'categories' => 'commercial.elektronics',
            'filter' => 'circle:' . $longitude . ',' . $latitude . ',' . $radiusMeters,
            'bias' => 'proximity:' . $longitude . ',' . $latitude,
            'limit' => $limit,
            'apiKey' => env('GEOAPIFY_API_KEY'),
        ]);

        // run the query and get the results
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => sprintf('%s?%s', 'https://api.geoapify.com/v2/places', $queryString),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $jsonData = json_decode($response, true);

        // preparing the results array
        $storeResults = [];
        $features = $jsonData['features'];
        
        // select the keys that we want to retrieve
        $propertiesKeys = ['name', 'address_line2'];
        $rawDatasourceKeys = ['housenumber', 'phone', 'contact:phone', 'email', 'website', 'contact:website', 'opening_hours', 'contact:facebook'];

        // array for formatting opening hours
        $list = [
            'Mo' => 'Lun',
            'Tu' => 'Mar',
            'We' => 'Mer',
            'Th' => 'Jeu',
            'Fr' => 'Ven',
            'Sa' => 'Sam',
            'Su' => 'Dim',
            ':' => 'h',
            '00' => '',
            ',' => ' / ',
            ';' => '<br>'
        ];

        // populate the results array
        $i = 0;
        foreach($features as $feature) {
            $i++;
            $location = []; // one feature = one location 
            foreach($propertiesKeys as $propertyKey) {
                $location[str_replace(':', '_', $propertyKey)] = $feature['properties'][$propertyKey] ?? '';
            }

            foreach($rawDatasourceKeys as $rawDatasourceKey) {
                $location[str_replace(':', '_', $rawDatasourceKey)] = $feature['properties']['datasource']['raw'][$rawDatasourceKey] ?? '';
            }

            // formatting the opening hours using the $list array
            $find = array_keys($list);
            $replace = array_values($list);
            $location['opening_hours'] = str_ireplace($find, $replace, $location['opening_hours']);

            $location['longitude'] = $feature['geometry']['coordinates'][0];
            $location['latitude'] = $feature['geometry']['coordinates'][1];
            $location['anchor'] = preg_replace('/[^A-Za-z0-9\-]/', '-', strtolower($location['name'])) . '-' . $i;

            $storeResults[] = $location;
        }

        return $storeResults;
    }
    
    /**
     * Get an itinerary from one point to another, using Geoapify Routing API.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Facades\View
    */
    function getItinerary(Request $request){
        // get the info from the $request: coordinates (departure and destination), destination name and selected mode
        $mylatitude = $request->input('latitude');
        $mylongitude = $request->input('longitude');
        $dlatitude = $request->input('destLatitude');
        $dlongitude = $request->input('destLongitude');
        $destinationName = $request->input('name');

        // get the right mode value according to the selected mode
        switch ($request->input('mode')) {
            case 'walk':
                $mode = 'À pied';
                break;
            case 'drive':
                $mode = 'En voiture';
                break;
            case 'bicycle':
                $mode = 'À vélo';
                break;
            default:
                $mode = 'À pied';
        };

        // get the departure and destination details
        $myLocation = $this->getLocationDetails($mylatitude, $mylongitude);
        $destinationAddress = $this->getLocationDetails($dlatitude,$dlongitude);
        
        // prepare the quesy
        $queryString = http_build_query([
            'waypoints' => $mylatitude.','.$mylongitude.'|'.$dlatitude.','.$dlongitude,
            'mode' => $request->input('mode'),
            'lang' => 'fr',
            'details' => 'instruction_details',
            'apiKey' => env('GEOAPIFY_API_KEY'),
        ]);

        // run the query and get the response
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL =>sprintf('%s?%s','https://api.geoapify.com/v1/routing', $queryString),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $jsonData = json_decode($response, true);

        // prepare the results array
        $result = [];

        // format the time
        $seconds = round($jsonData['features'][0]['properties']['time']);
        $timeArray = explode(':',sprintf('%02d:%02d:%02d', ($seconds/ 3600),($seconds/ 60 % 60), $seconds% 60));
        $time = ($timeArray[0] >= 1 ? $timeArray[0] . ' h ' : '') . ($timeArray[1] >= 1 ? $timeArray[1] . ' min' : '');

        $result['response'] = $jsonData;
        $result['destination_name'] = $destinationName;
        $result['destination_address'] = $destinationAddress['formatted'];
        $result['mode'] = $mode;
        $result['distance'] = $jsonData['features'][0]['properties']['distance'];
        $result['location_latitude'] = $mylatitude;
        $result['location_longitude'] = $mylongitude;
        $result['destination_latitude'] = $dlatitude;
        $result['destination_longitude'] = $dlongitude;
        $result['time'] = $time;

        return View::make('results', [
            'currentLocation' => $myLocation['formatted'], 
            'recyclingCenters' => null,
            'storeResults' => null,
            'itinerary' => $result,
        ]);
    }
}