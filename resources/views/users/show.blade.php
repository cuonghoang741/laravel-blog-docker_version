@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <x-card class="text-center mb-2">
                    <h2 class="card-title mb-0">{{ $user->name }}</h2>
                    <small class="card-subtitle mb-2 text-body-secondary">{{ $user->email }}</small>

                    <div class="card-text row mt-3">
                        <div class="col-md-4">
                            <span class="text-body-secondary d-block">@lang('comments.comments')</span>
                            {{ $comments_count }}
                        </div>

                        <div class="col-md-4">
                            <span class="text-body-secondary d-block">@lang('posts.posts')</span>
                            {{ $posts_count }}
                        </div>

                        <div class="col-md-4">
                            <span class="text-body-secondary d-block">@lang('likes.likes')</span>
                            {{ $likes_count }}
                        </div>
                    </div>

                    @profile($user)
                    <a href="{{ route('users.edit') }}" class="btn btn-primary btn-sm float-end">
                        @lang('users.edit')
                    </a>
                    @endprofile
                </x-card>
            </div>
        </div>

        <div class="row gy-5">
{{--            <div class="col-md-6">--}}
{{--                <h2>@lang('comments.last_comments')</h2>--}}

{{--                <div class="space-y-3">--}}
{{--                    @each('users/_comment', $comments, 'comment')--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="col-12">
                <h2>Plans</h2>

                <div class="row row-cols-4">
                    @foreach($plans as $plan)
                        <a href="/ai/trip-planner/{{$plan['id']}}" class="d-block col cursor-pointer my-2">
                            <div class="w-100 card-img rounded-10" style="background-image: url('{{$plan["image_url"]}}')"></div>
                            <div class="fw-bold h5 my-2">{{$plan['name']}}</div>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-12">
                <h2>@lang('posts.last_posts')</h2>

                <div class="row row-cols-4">
                    @each('posts/_post', $posts, 'post')
                </div>
            </div>
        </div>
    </div>
@endsection
