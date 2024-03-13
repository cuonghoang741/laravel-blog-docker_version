@extends('layouts.app')

@push("styles")
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <style>
        .btn-group-item {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 12px 20px;
            width: 140px;
            height: 48px;
            border: 1px solid #E6E6E6;
            border-radius: 12px;
        }

    </style>
@endpush

@section('content')
    <div class="container min-vh-100">
        <div class="row justify-content-center mt-xl-5 mt-md-4">
            <div class="col-xl-8 col-md-10">
                <h1 class="app-title fw-bold text-center">Let's Plan Your Journey!</h1>

                <form action="">
                    <div class="form-group mt-5">
                        <label for="" class="mb-3">Where do you want to go?</label>
                        <select id="select2-dropdown-city" class="w-100 py-4"></select>
                    </div>
                    <div class="form-group mt-5 position-relative">
                        <label for="" class="mb-3">Choose Your Preferred Time Frame for Travel</label>
                        <div class="position-relative">
                            <input class="w-100 app-input" type="text" name="daterange" placeholder="Select date"/>
                            <span class="position-absolute end-0 px-3 pointer-event-none-all" style="top: 15px"><i
                                    class="fad fa-calendar-week"></i></span>
                        </div>
                    </div>
                    <div class="form-group mt-5 position-relative number-people">
                        <label for="" class="mb-3">How many people are going?</label>
                        <div class="d-flex align-items-center w-100 flex-wrap position-relative" style="left: -5px">
                            <button type="button" class="btn btn-group-item bg-app-active m-2">1-2 people</button>
                            <button type="button" class="btn btn-group-item bg-app-active m-2 active">3-5 people
                            </button>
                            <button type="button" class="btn btn-group-item bg-app-active m-2">6-8 people</button>
                            <button type="button" class="btn btn-group-item bg-app-active m-2">8-12 people</button>
                            <button type="button" class="btn btn-group-item bg-app-active m-2">
                                <span class="fs-5">></span> 15 people
                            </button>
                        </div>
                    </div>
                    <div class="form-group mt-5 position-relative number-location">
                        <label for="" class="mb-3">How many places do you want to go per day?</label>
                        <div class="d-flex align-items-center w-100 flex-wrap position-relative" style="left: -5px">
                            <button type="button" class="btn btn-group-item bg-app-active m-2">1 location</button>
                            <button type="button" class="btn btn-group-item bg-app-active m-2">2 location</button>
                            <button type="button" class="btn btn-group-item bg-app-active m-2 active">3 location</button>
                            <button type="button" class="btn btn-group-item bg-app-active m-2"><span class="fs-5">></span>4 locations</button>
                        </div>
                    </div>
                    <div class="form-group mt-5 position-relative select-budget">
                        <label for="" class="mb-3">What's your estimated budget for this trip? <span class="gray-500">(Optional)</span></label>
                        <div class="dropdown dropdown-select">
                            <button
                                class="btn app-input w-100 dropdown-toggle d-flex align-items-center justify-content-between"
                                type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Select your budget
                            </button>
                            <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="#">Less than 500 USD</a></li>
                                <li><a class="dropdown-item" href="#">Between 500 and 1000 USD</a></li>
                                <li><a class="dropdown-item" href="#">Between 1000 and 2000 USD</a></li>
                                <li><a class="dropdown-item" href="#">Between 2000 and 5000 USD</a></li>
                                <li><a class="dropdown-item" href="#">Between 5000 and 10000 USD</a></li>
                                <li><a class="dropdown-item" href="#">More than 10000 USD</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-md-5 mt-4">
                        @if(\Illuminate\Support\Facades\Auth::user())
                            <button type="button" onclick="planForm.create()" class="btn-create-plan btn bg-app btn-app text-white">Generate
                                Itinerary
                            </button>
                        @else
                            <button type="button" class="btn-create-plan btn bg-app btn-app text-white">Generate
                                Itinerary
                            </button>
                        @endif
                    </div>
                    <quote class="text-center d-flex justify-content-center mt-5 gray-500">
                        <small>HuukAI - Powered by Huuk.Social - Your Travel Assistant</small>
                    </quote>
                </form>
            </div>
        </div>
    </div>

    @push("scripts")
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" defer></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"
                defer></script>

        <script>

            const planForm = {
                getData: function () {
                    return {
                        city: {
                            name: $(".select2-selection__rendered").attr("title"),
                            id: $("#select2-dropdown-city").val(),
                        },
                        daterange: $("input[name='daterange']").val(),
                        people: $(".number-people .btn-group-item.active").html(),
                        location: $(".number-location .btn-group-item.active").html()?.replace("location",""),
                        budget: $(".select-budget .dropdown-item.active").html()
                    }
                },
                post: function (data) {
                    return axios.post("/ai/trip-planner", data);
                },
                create: function () {
                    const data = planForm.getData();
                    const valid = planForm.validate(data);
                    if (!valid) return ;
                    $(".btn-create-plan").attr("disabled",true);
                    planForm.post(data)
                        .then(r => {
                            const id = r.data.id;
                            location.href = "/ai/trip-planner/" + id
                        }).catch(e => {
                        quickToastMixin("error", "Cannot create your trip plan. Please try again")
                    }).finally(()=>{
                        $(".btn-create-plan").attr("disabled",false);
                    })
                },
                validate: function (data) {
                    const box = $("#select2-dropdown-city").parent();
                    if(!data.city?.id){
                        $(box).find(".select2-selection").addClass("error");
                        $(box).focus();
                        return false;
                    } else {
                        $(box).find(".select2-selection").removeClass("error");
                    }
                    if (!data.daterange){
                        $("input[name='daterange']").addClass("error")
                        $("input[name='daterange']").focus();
                        return false;
                    } else {
                        $("input[name='daterange']").removeClass("error")
                    }
                    return true;
                }
            }


            $(function () {
                $(".btn-group-item").click(function () {
                    const box = $(this).closest(".d-flex");
                    $(box).find(".btn-group-item").removeClass("active")
                    $(this).addClass("active")
                })
            })
            $(function () {
                $('input[name="daterange"]').daterangepicker({
                    autoUpdateInput: false,
                    timePicker: true,
                    locale: {
                        format: TIME_STRING.date_time
                    }
                });
                $('input[name="daterange"]').on('apply.daterangepicker', function (ev, picker) {
                    $(this).val(picker.startDate.format(TIME_STRING.date_time) + ' - ' + picker.endDate.format(TIME_STRING.date_time));
                });
            });
            $(function () {
                $('#select2-dropdown-city').select2({
                    ajax: {
                        url: BASE_API + '/ai/trip-plan/cities',
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
                    placeholder: 'Select a city',
                    // templateResult: formatResult, // Tạo mẫu hiển thị kết quả
                    // templateSelection: formatSelection
                });

                $('#select2-dropdown-city').on('select2:select', async function (e) {
                    const city = e.params.data;
                    await onSelectCity(city)
                });

                //
                //
                // // Hàm để tạo mẫu hiển thị kết quả
                // function formatResult(result) {
                //     if (!result.id) {
                //         return result.text;
                //     }
                //     var $result = $(
                //         '<div class="select2-result">' +
                //         `<span> ${result.city_ascii} - ${result.country} </span>` +
                //         '</div>'
                //     );
                //     return $result;
                // }
                //
                // // Hàm để tạo mẫu cho kết quả được chọn
                // function formatSelection(result) {
                //     if(result){
                //         return `${result?.city_ascii ?? ""} ${result?.country ? ` - ${result?.country}` : ""} `;
                //     } else {
                //         return "Select a city"
                //     }
                // }
            })

            async function fillId(city) {
                if (!city.trip_advisor_id) {
                    return await axios.get(BASE_API + `/ai/trip-plan/cities/${city.id}/fill-id`)
                }
            }

            function getLocations(city) {
                axios.get(BASE_API + `/ai/trip-plan/cities/${city.id}/locations`);
            }

            async function onSelectCity(city) {
                console.log(city)
                await fillId(city);
                getLocations(city);
            }
        </script>
    @endpush
@endsection
