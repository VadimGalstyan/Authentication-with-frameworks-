<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

    @hasSection('card')
        <body>
            <div class="card">
                <x-flash />
                @yield('content')
            </div>
        </body>
    @else
        <body class="dashboard">
            @include('partials.nav')
            <div class="page-content @yield('page-class')">
                <x-flash />
                @yield('content')
            </div>
        </body>
    @endif
</html>