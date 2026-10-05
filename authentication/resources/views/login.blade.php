@extends('layouts.auth')

@section('title', 'Log in')

@section('content')

    <h1>Log in</h1>

    @if ($errors->any())
        <ul class="errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div class="field">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}">
        </div>

        <div class="field">
            <label>Password</label>
            <input type="password" name="password">
        </div>

        <button type="submit">Log in</button>
    </form>

    <div class="footer-link">
        Don't have an account? <a href="/register">Register</a>
    </div>

@endsection