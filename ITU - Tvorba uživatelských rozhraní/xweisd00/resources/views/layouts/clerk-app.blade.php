<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EVVP') }}</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app"></div>
    <div class="__height-100 d-flex flex-column justify-content-between">
        <div id="content-wrap">
            @include('inc.clerk-navbar')
            <div class="container">
                @include('inc.messages')
            </div>
    
            @yield('content')
        </div>

        @include('inc.footer')
        
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
