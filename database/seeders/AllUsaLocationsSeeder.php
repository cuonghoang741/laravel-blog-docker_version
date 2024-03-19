<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Location;
use App\Services\TripPlanService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AllUsaLocationsSeeder extends Seeder
{

    private $tripPlanService;

    public function __construct()
    {
        $this->tripPlanService = new TripPlanService();
    }
    /**
     * Run the database seeds.
     */

    private function fillCityAdvisorId(City $city){
        if (empty($city->trip_advisor_id)){
            try {
                $id = $this->tripPlanService->get_location_id($city->city_ascii);
                $city->trip_advisor_id = $id;
                $city->save();
            } catch (\Exception $exception){
                $city->trip_advisor_id = -1;
                $city->save();
            }
        }
    }

    public function run(): void
    {
        $iso3 = "USA";
        $cities = City::query()->where("iso3",$iso3)->get();
        foreach ($cities as $key=>$city){
            echo $key."-".$city->name."\n";
            Location::query()->where("city_id",$city->id)->delete();
            $this->fillCityAdvisorId($city);
            $trip_advisor_id = $city->trip_advisor_id;
            if ($trip_advisor_id){
                $this->tripPlanService->get_location_attractions($trip_advisor_id,$city,1000000);
            }
        }
    }
}
