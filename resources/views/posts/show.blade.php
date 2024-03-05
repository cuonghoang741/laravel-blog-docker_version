@extends('layouts.app')

@section('content')
    <div class="editor-view">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    @if(!empty($post['category']))
                        <div class="badge fw-light fs-6 gray-600 px-2 mt-4"
                             style="background-color: {{$post['category']['background_color']}}">
                            <small class="mx-1">
                                {{$post['category']['name']}}
                            </small>
                        </div>
                    @endif
                    <h1 class="app-title-md fw-bold my-2">{{ $post->title }}</h1>
                    <span class="text-body-secondary fs-6 opacity-5">
                        <small>
                        @humanize_date($post->posted_at)
                        </small>
                        ·
                        <small>{{$post->view}} views</small>
                    </span>
                </div>
            </div>
        </div>

        <div class="card-img my-md-5 my-4"
             style="background-image: url('{{$post->thumb_url}}');padding-bottom: 50%"></div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="mb-3">
                        <small class="text-body-secondary">
                            <a href="{{ route('users.show', $post->author) }}">
                                {{ $post->author->fullname }}
                            </a>
                        </small>,

                        <span class="text-body-secondary fs-6 opacity-5">
                        <small>
                        @humanize_date($post->posted_at)
                        </small>
                        ·
                        <small>{{$post->view}} views</small>
                    </span>
                    </div>

                    <div class="post-content">
                        {!! $post->content !!}
                    </div>

                    {{--                <div class="mt-3">--}}
                    {{--                    @include('likes/_likes')--}}
                    {{--                </div>--}}

                    <section class="container my-5">
                        <div class="">
                            <div class="col-12 d-flex justify-content-between align-items-center mb-4">
                                <h2>Latest</h2>
                                <a href="#" class="btn-link see-more rounded-0">See More →</a>
                            </div>
                            <div class="row row-cols-md-2">
                                @each('posts/_post', $latest, 'post', 'posts/_empty')
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    {{--  @include ('comments/_list')--}}
@endsection
