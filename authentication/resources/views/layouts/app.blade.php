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
                @can('view_dashboard')
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                @endcan
                @can('access_moderator_page')
                    <a href="{{ route('moderator.index') }}">Moderator</a>
                @endcan
                @can('access_admin_page')
                    <a href="{{ route('admin.index') }}">Admin</a>
                @endcan
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" style="width:auto; background:none; padding:0; color:white; opacity:0.85; font-size:0.9rem; cursor:pointer;">Log out</button>
                </form>
            @else
                <a href="{{ route('login') }}">Log in</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    </div>

    <div class="page-content">
        @yield('content')
    </div>

</body>
</html>