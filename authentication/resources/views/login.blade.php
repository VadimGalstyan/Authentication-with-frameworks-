@extends('layouts.app')

@section('card', '1')

@section('title', 'Log in')

@section('content')

    <h1>Log in</h1>

    @if (session('status'))
        <p class="footer-link" style="margin-top:0; text-align:left; color: var(--accent);">
            {{ session('status') }}
        </p>
    @endif

    <x-form-errors />

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
        Don't have an account? <a href="{{ route('register') }}">Register</a>
    </div>

    <div class="footer-link">
        <a href="{{ route('password.request') }}">Forgot password?</a>
    </div>

@endsection