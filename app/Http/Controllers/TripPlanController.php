<?php

namespace App\Http\Controllers;

use App\Exports\PostsExport;
use App\Models\City;
use App\Models\Location;
use App\Models\LocationCategory;
use App\Models\LocationLocationSubcategory;
use App\Models\LocationSubcategory;
use App\Models\Plan;
use App\Models\Post;
use App\Services\TripPlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Excel;
use Illuminate\Http\Response;

/**
 * @OA\Info(
 *     title="Tên API",
 *     version="1.0.0",
 *     description="Mô tả API của bạn",
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */
class TripPlanController extends Controller
{
    public function __construct(TripPlanService $tripPlanService)
    {
        $this->tripPlanService = $tripPlanService;
        $this->rapidKey = config("services.rapid.key");
        $this->colors = ["#FF7BF9", "#A269EB", "#EB6969", "#D1EB69", "#61E19C"];
    }

    public function fillCityAdvisorId(City $city)
    {
        if (empty($city->trip_advisor_id)) {
            try {
                $id = $this->tripPlanService->get_location_id($city->city_ascii);
                $city->trip_advisor_id = $id;
                return $city->save();
            } catch (\Exception $exception) {
                $city->trip_advisor_id = -1;
                return $city->save();
            }
        }
    }

    /**
     * @OA\Get(
     *     path="/web-api/v1/ai/trip-plan/cities",
     *     tags={"city"},
     *     summary="Get city by keyword",
     *     operationId="searchCities",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="ID of the city",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function searchCities(Request $request)
    {
        $search = $request->input("search");

        $cities = City::query()->where(function ($whereBuilder) use ($search) {
            return $whereBuilder->where("name", "like", "%$search%")
                ->orWhere("country", "like", "%$search%")
                ->orWhere("city_ascii", "like", "%$search%")
                ->orWhere("iso2", "like", "%$search%")
                ->orWhere("iso3", "like", "%$search%")
                ->orWhere("admin_name", "like", "%$search%");
        })->limit(5)->get();

        return $cities;
    }

    private function createLocations($locations, $city)
    {
        foreach ($locations as &$location) {
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
                $category = LocationCategory::query()->updateOrCreate(["key" => $category["key"]], $category);

                // update or create subcategory
                $subcategories = $location['subcategory'];
                foreach ($subcategories as $key => $subcategory) {
                    $subcategories[$key] = LocationSubcategory::query()->updateOrCreate(["key" => $subcategory["key"]], $subcategory);
                }

                $location["category_id"] = $category->id;

                unset($location["category"]);
                unset($location["subcategory"]);
//                dd($location);
                $location = Location::updateOrCreate(['location_id' => $location['location_id']], $location);

                foreach ($subcategories as $subcategory) {
                    $data = [
                        "location_id" => $location->id,
                        "location_subcategory_id" => $subcategory->id,
                    ];
                    LocationLocationSubcategory::query()->updateOrCreate($data, $data);
                }
            } catch (\Exception $exception) {
            }
        }

        return $locations;
    }

    /**
     * @OA\Get(
     *     path="/web-api/v1/ai/trip-plan/cities/{cityId}/locations",
     *     tags={"city"},
     *     summary="Get city locations",
     *     operationId="cityLocations",
     *     @OA\Parameter(
     *         name="cityId",
     *         in="path",
     *         description="ID of the city",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function cityLocations(City $city, $limit = 200)
    {
        $locations = Location::where("city_id", $city->id)->get();
        if (!count($locations) || isUpdatedWithinOneMonth($city->updated_at)) {
            $trip_advisor_id = $city->trip_advisor_id;
            if (!$trip_advisor_id) {
                $this->fillCityAdvisorId($city);
                $city = $city->refresh();
            }
            $locations = $this->tripPlanService->get_location_attractions($city->trip_advisor_id, $city, $limit);
            $locations = $this->createLocations($locations, $city);
        }

        return $locations;
    }

    /**
     * @OA\Get(
     *     path="/web-api/v1/ai/trip-plan/cities/{cityId}/locations/force",
     *     tags={"city"},
     *     summary="Force regain all city locations",
     *     operationId="cityLocationsForce",
     *     @OA\Parameter(
     *         name="cityId",
     *         in="path",
     *         description="Regain all city locations (will take a long time)",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function cityLocationsForce(City $city)
    {
        $locations = Location::query()->where("city_id", $city->id)->delete();
        $this->tripPlanService->get_location_attractions($city->trip_advisor_id, $city, 100000);

        return $locations;
    }

    public function test()
    {
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


    public function index(Request $request)
    {
        return view("ai.trip_planner");
    }


    /**
     * @OA\Post(
     *     path="/web-api/v1/ai/trip-plan",
     *     tags={"trip plan"},
     *     summary="Create a new plan",
     *     operationId="createPlan",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Plan information",
     *         @OA\JsonContent(
     *             @OA\Property(property="city_id", type="integer"),
     *             @OA\Property(property="daterange", type="string",description="EX: 03/11/2024 12:00 AM - 03/16/2024 11:59 PM"),
     *             @OA\Property(property="budget", type="number"),
     *             @OA\Property(property="people", type="integer"),
     *             @OA\Property(property="location", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plan created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="image_url", type="string"),
     *             @OA\Property(property="json_data", type="string"),
     *             @OA\Property(property="author_id", type="integer")
     *         )
     *     )
     * )
     */
    public function createPlan(Request $request)
    {
        $data = [
            "city_id" => $request->input("city_id"),
            "budget" => $request->input("budget"),
            "daterange" => $request->input("daterange"),
            "people" => $request->input("people"),
            "location" => $request->input("location"),
            "author_id" => Auth::id() ?? 2
        ];
        $city = City::query()->find($request->city_id);
        if (!$city) {
            return response()->json(['error' => 'City not found'], Response::HTTP_NOT_FOUND);
        }
        $name = $city->name;
        $data["json_data"] = json_encode($data);

        $name = $name . " · " . day_diff($data["daterange"]) . " days";
        $data["name"] = $name;

        $data["image_url"] = $this->tripPlanService->get_thumb($name);

        return Plan::create($data);;
    }


    public function show(Plan $plan)
    {
        if (true) {
//        if (!$plan->json_data_result){
            $data = json_decode($plan["json_data"]);
            $dateRange = $data->daterange;
            $dateRangeDay = getDateRange($dateRange);
            $locationPerDay = !empty($data->location) ? (int)$data->location : 3;
            if ($locationPerDay === 4) {
                $locationPerDay = mt_rand(4, 6);
            }
            $locations = Location::with("category")->where("city_id", $data->city_id)->get();

            if (!count($locations)) {
                $city = City::query()->find($data->city_id);
                $this->tripPlanService->get_location_attractions($city->trip_advisor_id, $city, 200);
            }
            $results = [];
            $indexLocation = 0;
            foreach ($dateRangeDay as $day) {
                $results[$day] = [];
                if (empty($locations[$indexLocation])) {
                    break;
                }
                $timeRange = generateTimeSlots($locationPerDay + 1);
                for ($i = 0; $i < $locationPerDay; $i++) {
                    if (!empty($locations[$indexLocation])) {
//                        $locations[$indexLocation]->category = $locations[$indexLocation]->category()->get();
                        $locations[$indexLocation]->photo = json_decode($locations[$indexLocation]->photo);
                        $locations[$indexLocation]->time = $timeRange[$i];
                        array_push($results[$day], $locations[$indexLocation]->toArray());
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
        if (count($colors) < count((array)$plan->json_data_result)) {
            $colors = array_merge($this->colors, randomHexColors(count((array)$plan->json_data_result)));
        }
        $plan->city = $plan->city()->first()->toArray();
        return view("ai.show", ["plan" => $plan->toArray(), "colors" => $colors]);
    }

    public function getUSALocations(Request $request)
    {
        $iso3 = "USA";
        $cities = City::query()->where("iso3", $iso3)->get();
        foreach ($cities as $city) {
            $this->fillCityAdvisorId($city);
            $trip_advisor_id = $city->trip_advisor_id;
            if ($trip_advisor_id) {
                $this->tripPlanService->get_location_attractions($trip_advisor_id, $city, 200);
            }
        }
    }


    public function exportPosts(Excel $excel)
    {
        return $excel->download(new PostsExport, 'posts.xlsx');
    }

    /**
     * @OA\Get(
     *     path="/web-api/v1/ai/trip-plan/wayspot",
     *     tags={"trip plan"},
     *     summary="wayspot from 2 cities with a radius of 10km",
     *     operationId="locationsBetween",
     *     @OA\Parameter(
     *         name="from_city_id",
     *         in="query",
     *         description="id of the city from which you started moving",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="to_city_id",
     *         in="query",
     *         description="id of the city where you end up moving",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function locationsBetween(Request $request)
    {
        $cityFrom = City::find($request->from_city_id);
        $cityTo = City::find($request->to_city_id);
        $radius = 0.0897; //10km
        $count = Location::query()->whereIn("city_id", [$cityFrom->id, $cityTo->id])->count();
        if ($count) {
            $cityFromLat = $cityFrom->lat;
            $cityFromLng = $cityFrom->lng;
            $cityFromLngTop = (float)$cityFrom->lng + $radius;
            $cityFromLngBot = (float)$cityFrom->lng - $radius;

            $cityToLat = $cityTo->lat;
            $cityToLng = $cityTo->lng;
            $cityToLngTop= (float)$cityTo->lng + $radius;
            $cityToLngBot= (float)$cityTo->lng - $radius;

            $latLeft = max([$cityToLat,$cityFromLat]);
            $latRight = min([$cityToLat,$cityFromLat]);
            $lngTop = max([$cityFromLngTop,$cityToLngTop]);
            $lngBot = max([$cityToLngBot,$cityFromLngBot]);

            $locations = Location::query()
                ->whereIn("city_id", [$request->from_city_id, $request->to_city_id])
                ->where(function ($whereBuilder) use ($latRight,$lngTop,$latLeft,$lngBot) {
                    $whereBuilder
                        ->where("latitude",">",$latRight)
                        ->where("latitude","<",$latLeft)
                        ->where("longitude","<",$lngTop)
                        ->where("longitude",">",$lngBot)
                    ;
                })
                ->limit(10)->get();
            return $locations;
        } else {
            return response()->json(['error' => 'Cannot find locations between 2 cities'], Response::HTTP_NOT_FOUND);
        }
    }
}
