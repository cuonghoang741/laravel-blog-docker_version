<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @stack('head')
    <title>{{ config('app.name', 'Huuk - Travel platform powered by AI') }}</title>
    <meta name="og:title" content="{{ config('app.name', 'Huuk - Travel platform powered by AI') }}"/>

    <link rel="shortcut icon" href="https://d1j8r0kxyu9tj8.cloudfront.net/files/qAwg1QhQXzmTunGiqhg1L6Gb7bwMdOy6V4kivU62.png">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link href="/css/select2.min.css" rel="stylesheet"/>
    <link href="/css/toastr.min.css" rel="stylesheet"/>
    <link href="/font-awesome/css/all.min.css" rel="stylesheet"
          type="text/css"/>

    @vite([
        'resources/sass/app.scss',
        'resources/js/app.js'
    ])

    @stack('styles')

</head>

<body class="d-flex flex-column vh-100">
    @include('shared/navbar')

    <div>
        @include('shared/alerts')

        <main class="my-4">
            @yield('content')
        </main>
    </div>

    @include('shared/footer')

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Z2VBY9M42Z"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/js/select2.min.js" defer></script>
    <script src="/js/axios.js" defer></script>
    <script src="/js/app.js" defer></script>
    <script src="/js/sweetalert2.js" defer></script>
    <script src="/js/toastr.min.js" defer></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-Z2VBY9M42Z');
    </script>

    @stack('scripts')

</body>
</html>
