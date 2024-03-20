@extends('layouts.app')

@push("styles")
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
    <style>
        #map { height: 80vh; }
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
        </div>

        <div class="my-4 d-flex justify-content-end">
            <button type="button" onclick="generateMap()" class="btn-create-map btn bg-app btn-app text-white">Generate
                Map
            </button>
        </div>

        <div id="map"></div>

    </div>

    @push("scripts")
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" defer></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"
                defer></script>

        <script>
            $(function () {
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
                                results: data.map(i => ({...i, text: `${i.city_ascii} - ${i.country}`}))
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
        </script>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
                integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
                crossorigin=""></script>
        <script>
            function initMap(locations,cityFrom,cityTo){
                const avgLat = (parseFloat(cityFrom.lat) + parseFloat(cityTo.lat)) / 2;
                const avgLng = (parseFloat(cityFrom.lng) + parseFloat(cityTo.lng)) / 2;


                var map = L.map('map').setView([avgLng, avgLat], 15);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);


                $.each(locations,function (index,item) {
                    const position = [item.latitude,item.longitude];
                    console.log(item)
                    item.photo = JSON.parse(item.photo)
                    var marker = L.marker(position).addTo(map);
                    const imgUrl = item?.photo?.images?.large?.url;
                    const name = item.name;
                    const rating = item.num_reviews;

                    const popup = `<div>
                        <img src="${imgUrl}" class="w-100 rounded-3">
                        <div class="fw-bold mt-2">${name}</div>
                        <span class="badge bg-success fw-bold">Rating: ${rating}</span>
                        <div>${item?.description}</div>
                    </div>`

                    marker.bindPopup(popup).openPopup();

                    var circle = L.circle(position, {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.5,
                        radius: rating
                    }).addTo(map);

                    circle.bindPopup(popup);
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

                // marker cityFrom
                var marker = L.marker([cityFrom.lat,cityFrom.lng]).addTo(map);
                marker.bindPopup(`<b>Start point: ${cityFrom.name}</b>`).openPopup();

                // marker cityTo
                var marker = L.marker([cityTo.lat,cityTo.lng]).addTo(map);
                marker.bindPopup(`<b>End point: ${cityTo.name}</b>`).openPopup();


                // Determine bounding box
                var bounds = locations.map(item=>([item.latitude,item.longitude])).reduce(function(bounds, loc) {
                    return bounds.extend(loc);
                }, L.latLngBounds(locations[0], locations[0]));

                bounds.extend([cityTo.lng,cityTo.lat])
                bounds.extend([cityTo.lng,cityTo.lat])

                // Set map view to the bounding box and adjust zoom level
                map.fitBounds(bounds);
            }

            function generateMap(){
                const city_from = $("#select2-dropdown-city-from").val();
                const city_to = $("#select2-dropdown-city-to").val();
                const limit = $("#limit-locations").val();
                $(".btn-create-map").attr("disabled",true);

                axios.get(BASE_API + `/ai/trip-plan/wayspot?&from_city_id=${city_from}&to_city_id=${city_to}&limit=${limit}`)
                    .then(r=>{
                        let locations = r.data.locations;
                        function compare(a, b) {
                            if (a.numb_review < b.numb_review) {
                                return -1;
                            }
                            if (a.numb_review > b.numb_review) {
                                return 1;
                            }
                            return 0;
                        }
                        locations = locations.sort(compare);
                        const cityFrom = r.data.city_from;
                        const cityTo = r.data.city_to;
                        initMap(locations,cityFrom,cityTo)
                    }).finally(()=>{
                    $(".btn-create-map").attr("disabled",false);
                })
            }
        </script>
    @endpush
@endsection
