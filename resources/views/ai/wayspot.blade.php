@extends('layouts.app')

@push("styles")
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

    <style>
        #map {
            height: 80vh;
        }
    </style>
@endpush

@section('content')
    <div class="container min-vh-100">
        <h1 class="app-title fw-bold text-center pt-xl-5 pt-md-4">Let's Plan Your Journey!</h1>

        <div class="row row-cols-md-2 g-4">
            <div class="form-group mt-5">
                <label for="select2-dropdown-city-from" class="mb-3">Where do you want to go from?</label>
                <br>
                <select id="select2-dropdown-city-from" class="w-100 py-4"></select>
            </div>
            <div class="form-group mt-5">
                <label for="select2-dropdown-city-to" class="mb-3">Where do you want to go to?</label>
                <br>
                <select id="select2-dropdown-city-to" class="w-100 py-4"></select>
            </div>
            <div>
                <label for="select2-dropdown-city-from" class="mb-3">Max locations (default: 200)</label>
                <br>
                <input id="limit-locations" type="number" class="form-control" placeholder="Default: 200" value="200">
            </div>
            <div>
                <label for="select2-dropdown-city-from" class="mb-3">Outbound (default: 10km)</label>
                <br>
                <input id="limit-radius" type="number" class="form-control"
                       placeholder="Outbound finds surrounding wayspot. The unit is kilometers (Km). default is 10"
                       value="10">
            </div>
            <div class="w-100">
                <label for="select2-dropdown-city-from" class="mb-3">Category (Default: All)</label>
                <br>
                <div class="d-inline-flex align-items-center">
                    <button data-value="0" type="button" class="btn btn-group-item bg-app-active active m-2 select-all">All category</button>
                    <span style="border: 1.5px solid #bab8b8; height: 40px;width: 0px"></span>
                </div>
                @foreach($subCategories as $subCategory)
                    <button data-value="{{$subCategory["id"]}}" type="button" class="btn btn-group-item bg-app-active m-2">{{$subCategory["name"]}}</button>
                @endforeach
            </div>
        </div>

        <div class="my-4 d-flex justify-content-end">
            <button type="button" onclick="generateMap()" class="btn-create-map btn bg-app btn-app text-white">Generate
                Map
            </button>
        </div>

        <div id="map"></div>
        <div id="viewOnGGMap" style="display: none">
            <div class="d-flex justify-content-end fw-bold cursor-pointer mt-4">
                <a style="color: #0d6efd;text-decoration: underline" href="https://www.google.com/maps/dir/33.93729,-106.85761/33.98729,-106.85861" target="_blank">View on google map</a>
            </div>
        </div>
    </div>

    @push("scripts")
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" defer></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"
                defer></script>

        <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

        <script>
            var locations = [];
            var cityFrom = {};
            var cityTo = {};

            function filterByCategory() {
                const listCategories =  [...$(".btn-group-item.active").map((index,i)=>$(i).data("value"))];
                let locationsFilter = [...locations];
                if (listCategories?.length === 1 && listCategories[0] == 0){

                } else {
                    locationsFilter = [...locations].filter(function (location) {
                        return location?.sub_categories?.find(i=>listCategories.includes(i.id))
                    });
                }
                initMap(locationsFilter, cityFrom, cityTo)
            }

            function categorySelect() {
                $(".btn-group-item").click(function () {
                    if($(this).hasClass("active")){
                        $(this).removeClass("active")
                    } else $(this).addClass("active");

                    if ($(this).hasClass("select-all")){
                        $(".btn-group-item").removeClass("active")
                        $(this).addClass("active");
                    } else {
                        $(".btn-group-item.select-all").removeClass("active")
                    }

                    if (locations && locations?.length) filterByCategory();
                })
            }
            $(function () {
                categorySelect();
                $('#select2-dropdown-city-from,#select2-dropdown-city-to').select2({
                    ajax: {
                        url: BASE_API + '/ai/trip-plan/cities?&ios3=USA',
                        data: function (params) {
                            var query = {
                                search: params.term
                            }
                            // Query parameters will be ?search=[term]&type=public
                            return query;
                        },
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            // Trả về dữ liệu theo định dạng của Select2
                            return {
                                results: data.map(i => ({...i, text: `${i.city_ascii} - ${i.country},${i.admin_name}`}))
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 1, // Số ký tự tối thiểu trước khi bắt đầu tìm kiếm
                    placeholder: 'Select a city (Only cities in USA)',
                    // templateResult: formatResult, // Tạo mẫu hiển thị kết quả
                    // templateSelection: formatSelection
                });

                $('#select2-dropdown-city-from,#select2-dropdown-city-to').on('select2:select', async function (e) {
                    const city = e.params.data;
                    await onSelectCity(city)
                });
            })

            var map;

            function mapFindMaxPosition(instructions,coordinates) {
                let latRight = cityFrom.lat,latLeft = cityFrom.lat,lngTop = cityFrom.lng,lngBottom = cityFrom.lng;

                $.each(instructions,function (i,instruction) {
                    const index = instruction.index;
                    const position = coordinates[index];
                    const lat = position.lat;
                    const lng = position.lng;

                    // Update latLeft and latRight
                    // if(latLeft > lat){
                    //     console.log("change latLeft: ",instruction,position)
                    // }
                    latLeft = Math.min(latLeft, lat);
                    // if(latRight < lat){
                    //     console.log("change latright: ",instruction,position)
                    // }
                    latRight = Math.max(latRight, lat);

                    // Update lngTop and lngBottom
                    // if(lngTop < lat){
                    //     console.log("change lngTop: ",instruction,position)
                    // }
                    lngTop = Math.max(lngTop, lng);
                    // if(lngBottom > lat){
                    //     console.log("change lngBottom: ",instruction,position)
                    // }
                    lngBottom = Math.min(lngBottom, lng);
                })
                return {latLeft,latRight,lngTop,lngBottom}
            }

            function initMap(locations, cityFrom, cityTo) {
                map?.remove();
                map = null;
                $("#map").html("");
                $("#viewOnGGMap").show();

                $("#viewOnGGMap a").attr("href",`https://www.google.com/maps/dir/${cityFrom.lat},${cityFrom.lng}/${cityTo.lat},${cityTo.lng}`)

                const avgLat = (parseFloat(cityFrom.lat) + parseFloat(cityTo.lat)) / 2;
                const avgLng = (parseFloat(cityFrom.lng) + parseFloat(cityTo.lng)) / 2;


                map = L.map('map').setView([avgLng, avgLat], 15);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);


                $.each(locations, function (index, item) {
                    const position = [item.latitude, item.longitude];
                    // item.photo = JSON.parse(item.photo)
                    var marker = L.marker(position).addTo(map);
                    const imgUrl = item?.photo?.images?.large?.url;
                    const name = item.name;
                    const rating = item.num_reviews;
                    const subCategoryHtml = item.sub_categories.map(category=>(`
                        <span class="badge bg-secondary fw-bold me-1">${category?.name}</span>
                    `)).join(" ");

                    const popup = `<div>
                        <img src="${imgUrl}" class="w-100 rounded-3">
                        <div class="fw-bold mt-2">${name} - ${item?.location_string}</div>
                        <span class="badge bg-success fw-bold me-1">Rating: ${rating}</span>
                        ${subCategoryHtml}
                        <div>${item?.description}</div>
                    </div>`

                    marker.bindPopup(popup).openPopup();

                    var circle = L.circle(position, {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.5,
                        radius: rating * 3
                    }).addTo(map);

                    circle.bindPopup(popup);

                    document.getElementById("map").scrollIntoView({ behavior: "smooth" });
                })

                // const cityIcon = L.icon({
                //     iconUrl: 'https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.vecteezy.com%2Fpng%2F14301029-red-pin-for-pointing-the-destination-on-the-map-3d-illustration&psig=AOvVaw1X_u7WHWF0_rl1rOoTxWwn&ust=1711025409433000&source=images&cd=vfe&opi=89978449&ved=0CBIQjRxqFwoTCPi407_wgoUDFQAAAAAdAAAAABBM',
                //     iconSize: [38, 95],
                //     iconAnchor: [22, 94],
                //     popupAnchor: [-3, -76],
                //     shadowUrl: 'my-icon-shadow.png',
                //     shadowSize: [68, 95],
                //     shadowAnchor: [22, 94]
                // });

                const routing = L.Routing.control({
                    waypoints: [
                        L.latLng(cityFrom.lat,cityFrom.lng),
                        L.latLng(cityTo.lat,cityTo.lng)
                    ],
                    showAlternatives: true,
                    collapsible: true
                }).addTo(map);

                // marker cityFrom
                var marker = L.marker([cityFrom.lat, cityFrom.lng]).addTo(map);
                marker.bindPopup(`<b>Start point: ${cityFrom.name}</b>`).openPopup();

                // marker cityTo
                var marker = L.marker([cityTo.lat, cityTo.lng]).addTo(map);
                marker.bindPopup(`<b>End point: ${cityTo.name}</b>`).openPopup();

                var polygon = L.polygon([
                    [cityTo.lat, cityTo.lng],
                    [cityTo.lat, cityFrom.lng],
                    [cityFrom.lat, cityFrom.lng],
                    [cityFrom.lat, cityTo.lng],
                ]).addTo(map);

                routing.on('routeselected', function(e) {
                    console.log(e)
                    const route = e.route
                    const route2 = e.alternatives[0];
                    const coordinates = route.coordinates;
                    const instructions = route.instructions;
                    const mapMaxPositon = mapFindMaxPosition(instructions,coordinates);
                    const mapMaxPositon2 = mapFindMaxPosition(route2.instructions,route2.coordinates);

                    L.polygon([
                        [mapMaxPositon.latLeft, mapMaxPositon.lngTop],
                        [mapMaxPositon.latRight, mapMaxPositon.lngTop],
                        [mapMaxPositon.latRight, mapMaxPositon.lngBottom],
                        [mapMaxPositon.latLeft, mapMaxPositon.lngBottom],
                    ],{
                        color: 'yellow',
                        fillOpacity: 0.2,
                    }).addTo(map);

                    L.polygon([
                        [mapMaxPositon2.latLeft, mapMaxPositon2.lngTop],
                        [mapMaxPositon2.latRight, mapMaxPositon2.lngTop],
                        [mapMaxPositon2.latRight, mapMaxPositon2.lngBottom],
                        [mapMaxPositon2.latLeft, mapMaxPositon2.lngBottom],
                    ],{
                        color: 'yellow',
                        fillOpacity: 0.2,
                    }).addTo(map);
                    // Your action goes here
                })

                // Determine bounding box
                var bounds = locations.map(item => ([item.latitude, item.longitude])).reduce(function (bounds, loc) {
                    return bounds.extend(loc);
                }, L.latLngBounds(locations[0], locations[0]));

                // bounds.extend([cityTo.lng, cityTo.lat])
                // bounds.extend([cityTo.lng, cityTo.lat])

                // Set map view to the bounding box and adjust zoom level
                map.fitBounds(bounds);
            }

            function generateMap() {
                const city_from = $("#select2-dropdown-city-from").val();
                const city_to = $("#select2-dropdown-city-to").val();
                const limit = $("#limit-locations").val();
                const limitRadius = $("#limit-radius").val();
                $(".btn-create-map").attr("disabled", true);

                axios.get(BASE_API + `/ai/trip-plan/wayspot?&from_city_id=${city_from}&to_city_id=${city_to}&limit=${limit}&radius=${limitRadius}`)
                    .then(r => {
                        function compare(a, b) {
                            if (a.numb_review < b.numb_review) {
                                return -1;
                            }
                            if (a.numb_review > b.numb_review) {
                                return 1;
                            }
                            return 0;
                        }

                        locations = (r.data.locations).sort(compare);
                        locations = locations.map(location=>({...location,photo: JSON.parse(location.photo)}));
                        cityFrom = r.data.city_from;
                        cityTo = r.data.city_to;
                        initMap(locations, cityFrom, cityTo)
                    }).finally(() => {
                    $(".btn-create-map").attr("disabled", false);
                })
            }
        </script>


    @endpush
@endsection
