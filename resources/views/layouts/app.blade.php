<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script src="https://use.fontawesome.com/59a10b2a62.js"></script>
    {{--<script src="https://kit.fontawesome.com/4ac4bd2132.js" crossorigin="anonymous"></script>--}}
</head>
<body>
    <div id="app">

        @include('layouts.partials.nav')
        <div class="clearfix"></div>
        @section('search')
            @include('layouts.partials.search', ['category' => null, 'action' => route('adverts.index', request()->all())])
        @show
        <div class="clearfix"></div>

        <main class="py-4">
            <div class="container">

                {{ Breadcrumbs::view('breadcrumbs::bootstrap4') }}
                @include('layouts.partials.flash')
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
