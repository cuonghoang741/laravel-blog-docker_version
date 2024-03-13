<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Location;
use App\Models\LocationCategory;
use App\Models\LocationLocationSubcategory;
use App\Models\LocationSubcategory;
use App\Models\Plan;
use App\Services\TripPlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripPlanController extends Controller
{
    public function __construct(TripPlanService $tripPlanService)
    {
        $this->tripPlanService = $tripPlanService;
        $this->rapidKey = config("services.rapid.key");
        $this->colors = ["#FF7BF9","#A269EB","#EB6969","#D1EB69","#61E19C"];
    }

    public function fillCityAdvisorId(City $city){
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

    public function searchCities(Request $request){
        $search = $request->input("search");

        $cities = City::query()->where(function ($whereBuilder) use ($search){
            return $whereBuilder->where("name","like","%$search%")
                ->orWhere("country","like","%$search%")
                ->orWhere("city_ascii","like","%$search%")
                ->orWhere("iso2","like","%$search%")
                ->orWhere("iso3","like","%$search%")
                ->orWhere("admin_name","like","%$search%");
        })->limit(5)->get();

//        $this->fillCityIds($cities);
        return $cities;
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
//                dd($location);
                $location = Location::updateOrCreate(['location_id' => $location['location_id']], $location);

                foreach ($subcategories as $subcategory){
                    $data = [
                        "location_id"=>$location->id,
                        "location_subcategory_id"=>$subcategory->id,
                    ];
                    LocationLocationSubcategory::query()->updateOrCreate($data,$data);
                }
            } catch (\Exception $exception){
            }
        }

        return $locations;
    }

    public function cityLocations(City $city){
        $locations = Location::where("city_id",$city->id)->get();
        if (!count($locations) || isUpdatedWithinOneMonth($city->updated_at)){
            $locations = $this->tripPlanService->get_location_attractions($city->trip_advisor_id,$city,200);
            $locations = $this->createLocations($locations,$city);
        }

        return $locations;
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
        return $this->tripPlanService->get_location_id("hanoi");
    }

    public function index(Request $request){
        return view("ai.trip_planner");
    }


    public function createPlan(Request $request){
        $data = [
            "city" => $request->input("city"),
            "budget" => $request->input("budget"),
            "daterange" => $request->input("daterange"),
            "people" => $request->input("people"),
            "location" => $request->input("location"),
            "author_id"=>Auth::id()
        ];
        $name = removeSubstringAfterLastDash($data["city"]["name"]);
        $data["json_data"] = json_encode($data);

        $name = $name." · ".day_diff($data["daterange"]). " days";
        $data["name"] = $name;

        $data["image_url"] = $this->tripPlanService->get_thumb($name);
        return Plan::query()->create($data);
    }


    public function show(Plan $plan){
        if (!$plan->data_result_json){
            $data = json_decode($plan["json_data"]);
            $dateRange = $data->daterange;
            $dateRangeDay = getDateRange($dateRange);
            $locationPerDay = !empty($data->location) ? (int) $data->location : 3;
            $locations = Location::query()->where("city_id",$data->city->id)->get();

            $results = [];
            $indexLocation = 0;
            foreach ($dateRangeDay as $day){
                $results[$day] = [];
                if (empty($locations[$indexLocation])){
                    break;
                }
                for ($i = 0;$i < $locationPerDay;$i++){
                    if (!empty($locations[$indexLocation])){
                        $locations[$indexLocation]->photo = json_decode($locations[$indexLocation]->photo);
                        array_push($results[$day],$locations[$indexLocation]->toArray());
                        $indexLocation += 1;
                    } else break;
                }
            }
            $plan->json_data_result = json_encode($results);
            $plan->save();
        }
        $plan->json_data_result = json_decode($plan->json_data_result);
        $plan->json_data = json_decode($plan->json_data);

        $colors = $this->colors;
        if (count($colors) < count((array)$plan->json_data_result)){
            $colors = array_merge($this->colors,randomHexColors(count((array)$plan->json_data_result)));
        }
        return view("ai.show",["plan"=>$plan->toArray(),"colors"=>$colors]);
    }
}
