<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
{{--    <script src="{{ asset('js/app.js') }}" defer></script>--}}
    <script>
        window.App = {!! json_encode([
                'csrfToken' => csrf_token(),
                'user' => Auth::user(),
                'signedIn' => Auth::check()
        ]) !!};
    </script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vendor/tribute.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.0/trix.min.css" rel="stylesheet">

<!-- deerhiig daraah baidlaar mix tei bichij bolno -->
{{--    <link rel="stylesheet" href="{{ mix('css/app.css') }}" />--}}
    <script defer src="{{ mix('js/app.js') }}"></script>

{{--    Algolia Start --}}
{{--    <script src="https://cdn.jsdelivr.net/npm/algoliasearch@4.0.0/dist/algoliasearch-lite.umd.js" integrity="sha256-MfeKq2Aw9VAkaE9Caes2NOxQf6vUa8Av0JqcUXUGkd0=" crossorigin="anonymous"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/vue-instantsearch@3.0.3/dist/vue-instantsearch.js" integrity="sha256-8RxtmDcLI0MppUbZmtPAbljybngiUe3fWODdCvjTdFo=" crossorigin="anonymous"></script>--}}

    {{--    Algolia End --}}

    <style>
        [v-cloak] > * { display:none; }
        [v-cloak]::before { content: "loading..."; }
    </style>

    @yield('head')
</head>
<body style="padding-bottom: 100px;">
<div id="app">

    @include('layouts.nav')

    <main class="py-4">
        @yield('content')
    </main>

    <flash message="{{ session('flash') }}"></flash>
</div>
</body>
</html>
