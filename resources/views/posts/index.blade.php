@extends('layouts.app')

@section('content')

  <!-- Headline Section -->
  <div class="container">
      <header class="container text-center my-5">
          <div class="d-flex align-items-center justify-content-center mb-5">
              <div class="col-xl-8">
                  <h1 class="fw-bold h1 app-title-lg lh-sm">HUUK - TRAVEL PLATFORM POWERED BY AI</h1>
                  <p class="my-4 app-title-ssm">We focus on providing valuable information, sharing new knowledge, and offering unique perspectives, sharing new knowledge on daily life.</p>
                  <form class="d-flex justify-content-center align-items-center w-100 mt-5">
                      <div class="form-group mx-sm-3 mb-2 flex-fill">
                          @include ('posts/_search_form')
                      </div>
                      <button type="submit" class="btn btn-dark mb-2 rounded-pill app-btn-lg fs-5">Search</button>
                  </form>
              </div>
          </div>
          @if(!request('q'))
              <div class="my-4">
                  <img class="w-100" src="https://d1j8r0kxyu9tj8.cloudfront.net/files/m8TNhM9Rnu8M2vit0Cdm1TLjI0bL0oUaZhNd80pf.jpg" alt="">
              </div>
          @endif
      </header>



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

      <section class="container my-5">
          <div class="">
              <div class="col-12 d-flex justify-content-between align-items-center mb-4">
                  <h2>Trending</h2>
                  <a href="#" class="btn-link see-more rounded-0">See More →</a>
              </div>
              <div class="row row-cols-md-3 row-cols-1">
                  @each('posts/_post', $trending, 'post', 'posts/_empty')
              </div>
          </div>
      </section>

      <section class="container my-5">
          <div class="">
              <div class="col-12 d-flex justify-content-between align-items-center mb-4">
                  <h2>For you</h2>
                  <a href="#" class="btn-link see-more rounded-0">See More →</a>
              </div>
              <!-- Card 1 -->
              <div class="row row-cols-md-4 row-cols-1">
                  @each('posts/_post', $posts, 'post', 'posts/_empty')
              </div>
          </div>
      </section>
  </div>

{{--  <x-turbo-frame id="posts">--}}
{{--    <div class="d-flex justify-content-between gap-3 mt-3">--}}
{{--      <div class="p-2">--}}
{{--        <h2>--}}
{{--          @if (filled(request('q')))--}}
{{--            {{ trans_choice('posts.search_results', $posts->count()) }}--}}
{{--          @else--}}
{{--            @lang('posts.last_posts')--}}
{{--          @endif--}}
{{--        </h2>--}}
{{--      </div>--}}

{{--      <div class="p-2">--}}
{{--        <a href="{{ route('posts.feed') }}" data-turbo="false">--}}
{{--          <x-icon name="rss" />--}}
{{--        </a>--}}
{{--      </div>--}}
{{--    </div>--}}

{{--    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">--}}
{{--      @each('posts/_post', $posts, 'post', 'posts/_empty')--}}
{{--    </div>--}}

{{--    <div class="d-flex justify-content-center">--}}
{{--      {{ $posts->links() }}--}}
{{--    </div>--}}
{{--  </x-turbo-frame>--}}
@endsection
