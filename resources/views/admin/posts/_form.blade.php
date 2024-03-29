@php
    $posted_at = old('posted_at', (isset($post) ? $post->posted_at->format('Y-m-d\TH:i') : null));
@endphp

<div class="form-group mb-3">
    <label class="form-label" for="title">
        @lang('posts.attributes.title')
    </label>

    <input
        type="text"
        id="title"
        name="title"
        @class(['form-control', 'is-invalid' => $errors->has('title')])
        required
        value="{{ old('title', $post ?? null) }}"
    >

    @error('title')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="row row-cols-2">
    <div class="form-group mb-3">
        <label class="form-label" for="city">
            City
        </label>

        <input
            type="text"
            id="city"
            name="city"
            @class(['form-control', 'is-invalid' => $errors->has('city')])
            required
            value="{{ old('city', $post ?? null) }}"
        >

        @error('city')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label class="form-label" for="country">
            Country
        </label>

        <input
            type="text"
            id="country"
            name="country"
            @class(['form-control', 'is-invalid' => $errors->has('country')])
            required
            value="{{ old('country', $post ?? null) }}"
        >

        @error('country')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group mb-3">
        <label class="form-label" for="type">
            Type
        </label>

        <input
            type="text"
            id="type"
            name="type"
            @class(['form-control', 'is-invalid' => $errors->has('type')])
            required
            value="{{ old('type', $post ?? null) }}"
        >

        @error('type')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label class="form-label" for="kind">
            Kind
        </label>

        <input
            type="text"
            id="kind"
            name="kind"
            @class(['form-control', 'is-invalid' => $errors->has('kind')])
            required
            value="{{ old('type', $post ?? null) }}"
        >

        @error('kind')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label class="form-label" for="status">
            Status
        </label>

        <input
            type="text"
            id="status"
            name="status"
            @class(['form-control', 'is-invalid' => $errors->has('status')])
            required
            value="{{ old('type', $post ?? null) }}"
        >

        @error('status')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>




<div class="row">
    <div class="form-group mb-3 col-md-6">
        <label class="form-label" for="author_id">
            @lang('posts.attributes.author')
        </label>

        <select name="author_id" id="author_id" @class(['form-control', 'is-invalid' => $errors->has('author_id')]) required>
            @foreach ($users as $id => $name)
                <option value="{{ $id }}" @selected(old('author_id', $post ?? null) == $id)>
                    {{ $name }}
                </option>
            @endforeach
        </select>

        @error('author_id')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group mb-3 col-md-6">
        <label class="form-label" for="posted_at">
            @lang('posts.attributes.posted_at')
        </label>

        <input
            type="datetime-local"
            name="posted_at"
            id="posted_at"
            @class(['form-control', 'is-invalid' => $errors->has('posted_at')])
            required
            value="{{ $posted_at }}"
        >

        @error('posted_at')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="form-group mb-3">
    <label class="form-label" for="thumbnail_id">
        @lang('posts.attributes.thumbnail')
    </label>

{{--    <select name="thumbnail_id" id="thumbnail_id" @class(['form-control', 'is-invalid' => $errors->has('thumbnail_id')])>--}}
{{--        <option value="">--}}
{{--            @lang('posts.placeholder.thumbnail')--}}
{{--        </option>--}}

{{--        @foreach ($media as $id => $name)--}}
{{--            <option value="{{ $id }}" @selected(old('thumbnail_id', $post ?? null) == $id)>--}}
{{--                {{ $name }}--}}
{{--            </option>--}}
{{--        @endforeach--}}
{{--    </select>--}}

    <input
        type="text"
        name="thumb_url"
        id="thumb_url"
        @class(['form-control', 'is-invalid' => $errors->has('thumb_url')])
        required
        value="{{ old('thumb_url', $post ?? null) }}"
    >

    @error('thumbnail_id')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

@if(!empty($categories))
    <div class="form-group mb-3">
        <label class="form-label" for="thumbnail_id">
            Category
        </label>

        <select name="category_id" id="thumbnail_id" @class(['form-control', 'is-invalid' => $errors->has('category_id')])>
            <option value="">
                Null
            </option>

            @foreach ($categories as $id => $name)
                <option value="{{ $id }}" @selected(old('category_id', $post ?? null) == $id)>
                    {{ $name }}
                </option>
            @endforeach
        </select>


        @error('category')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
@endif


<div class="form-group mb-3">
    <label class="form-label" for="content">
        @lang('posts.attributes.content')
    </label>

    <textarea
        name="content"
        id="content"
        cols="50"
        rows="10"
        required
        @class(['form-control trumbowyg-form', 'is-invalid' => $errors->has('content')])
    >{{ old('content', $post ?? null) }}</textarea>

    @error('content')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>
