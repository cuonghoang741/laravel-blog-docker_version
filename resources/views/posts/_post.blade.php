    <div class="col mb-4">
        <div class="border-0">
            {{--    @if ($post->hasThumbnail())--}}
            <a href="{{ route('posts.show', $post)}}" data-turbo-frame="_top">
                {{--          <img src="{{ $post->thumbnail->getUrl('thumb') }}" alt="{{ $post->thumbnail->name }}" class="card-img-top rounded-4">--}}
                <div class="card-img rounded-20 overflow-hidden position-relative">
                    <div class="card-img position-absolute top-0 start-0 zoom"
                         style="background-image: url('{{$post['thumb_url']}}')"></div>
                </div>
            </a>
            {{--    @endif--}}

            @if(!empty($post['category']))
                <div class="badge fw-light fs-6 gray-600 px-2 mt-4" style="background-color: {{$post['category']['background_color']}}">
                    <small class="mx-1">
                        {{$post['category']['name']}}
                    </small>
                </div>
            @endif

            <h4 class="card-title fw-bold h3 fw-bold my-3">
                <a href="{{ route('posts.show', $post) }}" class="link-dark limit-3-line" data-turbo-frame="_top">
                    {{ $post->title }}
                </a>
            </h4>

            {{--            <p class="card-text text-body-secondary">--}}
            {{--                {{ Str::words(strip_tags($post->content), 10) }}--}}
            {{--            </p>--}}

            {{--            <p class="card-text">--}}
            {{--                <small>--}}
            {{--                    <a href="{{ route('users.show', $post->author) }}" class="link-secondary" data-turbo-frame="_top">--}}
            {{--                        {{ $post->author->fullname }}--}}
            {{--                    </a>--}}
            {{--                </small>--}}
            {{--            </p>--}}

            <span class="text-body-secondary fs-6 opacity-5">
                <small>
                @humanize_date($post->posted_at)
                </small>
                ·
                <small>{{$post->view}} views</small>
            </span>

            {{--            <div class="card-text">--}}
            {{--                <small class="text-body-secondary">--}}
            {{--                    <x-icon name="comments" prefix="fa-regular"/> {{ $post->comments_count }}--}}

            {{--                    @include('likes/_likes')--}}
            {{--                </small>--}}
            {{--            </div>--}}
        </div>
    </div>
