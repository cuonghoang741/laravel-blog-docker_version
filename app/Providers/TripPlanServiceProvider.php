<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TripPlanServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    function generate_map_html($locations) {
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
            $img_url = $location['photo']['images']['thumbnail']['url'];
            $name = $location['name'];
            $color_code = isset($location['color_code']) ? $location['color_code'] : 'white';
            $cluster_id = isset($location['cluster_id']) ? intval($location['cluster_id']) + 1 : 999 + 1;
            $html_content = "<div style='position: relative;'><img src='$img_url' alt='$name' style='width:50px;height:50px;border: solid $color_code 4px;'><p style='padding:5px 2px;background:$color_code;color:white'>$cluster_id</p><div style='position: absolute;bottom: -22px;color: white;background: #00000057;text-wrap: nowrap;padding: 2px 5px;left: 50%;transform: translateX(-50%);'>$name</div></div>";
            $map .= "var icon = L.divIcon({html: '$html_content'});";
            $map .= "L.marker([$lat, $lng], {icon: icon}).addTo(featureGroup);";
        }

        $map .= "</script>";

        return $map;
    }

    public function test(){
        $locations = array(
            array(
                'latitude' => '40.7128',
                'longitude' => '-74.0060',
                'photo' => array(
                    'images' => array(
                        'thumbnail' => array(
                            'url' => 'https://example.com/image.jpg'
                        )
                    )
                ),
                'name' => 'New York',
                'color_code' => 'blue',
                'cluster_id' => 1
            ),
            // Add more locations as needed
        );

        echo generate_map_html($locations);
    }
}
