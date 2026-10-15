@extends('layouts.app')

@section('card', '1')

@section('title', 'Forgot password')

@section('content')

    <h1>Forgot your password?</h1>

    @if (session('status'))
        <p class="footer-link" style="margin-top:0; text-align:left; color: var(--accent);">
            {{ session('status') }}
        </p>
    @endif

    <x-form-errors />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="field">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <button type="submit">Send reset link</button>
    </form>

    <div class="footer-link">
        <a href="{{ route('login') }}">Back to login</a>
    </div>

@endsection