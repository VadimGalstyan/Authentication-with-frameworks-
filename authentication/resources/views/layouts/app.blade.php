<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'My Website')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="dashboard">

    <div class="topbar">
        <div class="brand">My Website</div>
        <div class="nav-links">
            @auth
                <a href="/dashboard">Dashboard</a>
                <form method="POST" action="/logout" style="display:inline;">
                    @csrf
                    <button type="submit" style="width:auto; background:none; padding:0; color:white; opacity:0.85; font-size:0.9rem; cursor:pointer;">Log out</button>
                </form>
            @else
                <a href="/login">Log in</a>
                <a href="/register">Register</a>
            @endauth
        </div>
    </div>

    <div class="page-content">
        @yield('content')
    </div>

</body>
</html>