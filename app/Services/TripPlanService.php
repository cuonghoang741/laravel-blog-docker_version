<?php

namespace App\Services;

use App\Models\Location;
use App\Models\LocationCategory;
use App\Models\LocationLocationSubcategory;
use App\Models\LocationSubcategory;
use App\Models\Post;
use Maatwebsite\Excel\Excel;

class TripPlanService
{
    public function __construct()
    {
    }

    public function generate_map_html($locations) {
        // Create a base map centered on the first location
        $map = "<div id='map' style='width: 100%; height: 500px;'></div>";
        $map .= "<script>";
        $map .= "var map = L.map('map').setView([" . floatval($locations[0]['latitude']) . ", " . floatval($locations[0]['longitude']) . "], 12);";

        // Add tile layer
        $map .= "L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {";
        $map .= "'attribution': '© <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'";
        $map .= "}).addTo(map);";

        // Create a feature group to store markers
        $map .= "var featureGroup = L.featureGroup().addTo(map);";

        // Add custom markers to the feature group
        foreach ($locations as $location) {
            $lat = floatval($location['latitude']);
            $lng = floatval($location['longitude']);
            $location->photo = json_decode($location->photo);
            $img_url = !empty($location->photo->images->thumbnail->url) ? $location->photo->images->thumbnail->url : '';
            $name = $location['name'];
            $color_code = isset($location['color_code']) ? $location['color_code'] : 'white';
            $cluster_id = isset($location['cluster_id']) ? intval($location['cluster_id']) + 1 : 999 + 1;
            $html_content = "<div style=\'position: relative;\'><img src='$img_url' alt='$name' style='width:50px;height:50px;border: solid $color_code 4px;'><p style='padding:5px 2px;background:$color_code;color:white'>$cluster_id</p><div style='position: absolute;bottom: -22px;color: white;background: #00000057;text-wrap: nowrap;padding: 2px 5px;left: 50%;transform: translateX(-50%);'>$name</div></div>";
            $map .= "var icon = L.divIcon({html: \"$html_content\"});";
            $map .= "L.marker([$lat, $lng], {icon: icon}).addTo(featureGroup);";
        }

        $map .= "</script>";

        return $map;
    }

    public function get_location_id($query) {
        $url = "https://tourist-attraction.p.rapidapi.com/typeahead";

        $payload = http_build_query(array(
            "q" => $query,
            "language" => "en_US"
        ));

        $headers = array(
            "content-type: application/x-www-form-urlencoded",
            "X-RapidAPI-Key: 0cae1ba2demshd638e040e259026p1198ecjsnfd261048c8db",
            "X-RapidAPI-Host: tourist-attraction.p.rapidapi.com"
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

//        echo "_____Searching city " . $query . " - Result: <br/>";
//        print_r($data);

        if (isset($data['results']) && count($data['results']) > 0) {
            $first_location_id = $data['results']['data'][0]['result_object']['location_id'];
            $first_location_name = $data['results']['data'][0]['result_object']['name'];
//            echo "First location_id: " . $first_location_id . "<br/>";
//            echo "First location_name: " . $first_location_name . "<br/>";
            return $first_location_id;
        } else {
            echo "No results found.<br/>";
            return "No results found.";
        }
    }

    function get_location_attractions($location_id, $city,$limit = 20) {
        $url = "https://tourist-attraction.p.rapidapi.com/search";

        $headers = [
            "content-type: application/x-www-form-urlencoded",
            "X-RapidAPI-Key: 0cae1ba2demshd638e040e259026p1198ecjsnfd261048c8db",
            "X-RapidAPI-Host: tourist-attraction.p.rapidapi.com"
        ];

        $hasMore = 1;
        $allLocations = [];
        $offset = 0;
        $total_results = 0;
        $total_results_count = 100000;

        while ($total_results < $limit && $total_results_count > $offset) {
            $payload = http_build_query([
                "location_id" => $location_id,
                "language" => "en_US",
                "currency" => "USD",
                "offset" => $offset
            ]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            if (isset($data['results']) && isset($data['results']['data'])) {
                $allLocations = array_merge($allLocations, $data['results']['data']);
                $total_results += (int)$data['results']['paging']['results'];
                $offset += (int)$data['results']['paging']['results'];
                $total_results_count = (int)$data['results']['paging']['total_results'];
//                echo "total_results_count $total_results_count";
//                echo "total_results $total_results";
                $locations = $data['results']['data'];
                $this->sort_locations(array_slice($locations, 0, $limit), 'num_reviews');
                $this->createLocations($locations,$city);
            } else {
                echo "breack";
                break;
            }
        }

        return $allLocations;
    }

    private function createLocations($locations,$city){
        foreach ($locations as &$location){
            try {
                $location['city_id'] = $city->id;
                $location['photo'] = !empty($location['photo']) ? json_encode($location['photo']) : "[]";
                $location['ancestors'] = !empty($location['ancestors']) ? json_encode($location['ancestors']) : "[]";
//                $location['subcategory'] = json_encode($location['subcategory']);
                $location['address_obj'] = !empty($location['address_obj']) ? json_encode($location['address_obj']) : "[]";
                $location['hours'] = !empty($location['hours']) ? json_encode($location['hours']) : "[]";
                $location['subtype'] = !empty($location['subtype']) ? json_encode($location['subtype']) : "[]";
                $location['booking'] = !empty($location['booking']) ? json_encode($location['booking']) : "[]";
                $location['offer_group'] = !empty($location['offer_group']) ? json_encode($location['offer_group']) : "[]";
                $location['tags'] = !empty($location['tags']) ? json_encode($location['tags']) : "[]";
                $location['awards'] = !empty($location['awards']) ? json_encode($location['awards']) : "[]";
                $location['neighborhood_info'] = !empty($location['neighborhood_info']) ? json_encode($location['neighborhood_info']) : "[]";
                $location['animal_welfare_tag'] = !empty($location['animal_welfare_tag']) ? json_encode($location['animal_welfare_tag']) : "[]";
                $location['nearest_metro_station'] = !empty($location['nearest_metro_station']) ? json_encode($location['nearest_metro_station']) : "[]";

                // update or create category
                $category = $location['category'];
                $category = LocationCategory::query()->updateOrCreate(["key"=>$category["key"]],$category);

                // update or create subcategory
                $subcategories = $location['subcategory'];
                foreach ($subcategories as $key=>$subcategory){
                    $subcategories[$key] = LocationSubcategory::query()->updateOrCreate(["key"=>$subcategory["key"]],$subcategory);
                }

                $location["category_id"] = $category->id;

                unset($location["category"]);
                unset($location["subcategory"]);
                $location = Location::updateOrCreate(['location_id' => $location['location_id']], $location);

                foreach ($subcategories as $subcategory){
                    $data = [
                        "location_id"=>$location->id,
                        "location_subcategory_id"=>$subcategory->id,
                    ];
                    LocationLocationSubcategory::query()->updateOrCreate($data,$data);
                }
            } catch (\Exception $exception){
//                dd($location);
            }
        }

        return $locations;
    }

    private function sort_locations($locations, $key = 'num_reviews') {
        usort($locations, function($a, $b) use ($key) {
            return $b[$key] - $a[$key];
        });
        return $locations;
    }

    function get_thumb($city) {
        $size = "full";
        $url = "https://unsplash.com/napi/search/photos";
        $params = array(
            "orientation" => "landscape",
            "per_page" => "20",
            "query" => $city,
            "plus" => "none"
        );

        try {
            $queryString = http_build_query($params);
            $fullUrl = $url . "?" . $queryString;

            // Set up headers
            $options = [
                "http" => [
                    "header" => "User-Agent: My-App",
                ],
            ];
            $context = stream_context_create($options);

            // Make the request
            $response = file_get_contents($fullUrl, false, $context);

            if ($response !== false) {
                $data = json_decode($response, true);
                if (!empty($data["results"])) {
                    $image_url = $data["results"][0]["urls"][$size];
                    if ($image_url) {
                        return $image_url;
                    } else {
                        return "Size '{$size}' not found.";
                    }
                } else {
                    return "No images found for the query.";
                }
            } else {
                return "Failed to get response from API.";
            }
        } catch (Exception $e) {
            return "An error occurred: " . $e->getMessage();
        }
    }


}
