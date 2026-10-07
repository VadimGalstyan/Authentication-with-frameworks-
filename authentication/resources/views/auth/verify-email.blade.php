@extends('layouts.auth')

@section('title', 'Verify your email')

@section('content')

    <h1>Verify your email</h1>

    <p class="footer-link" style="margin-top:0; text-align:left;">
        We've sent a verification link to your email address.
        Click the link to activate your account.
    </p>

    @if (session('status'))
        <p class="footer-link" style="margin-top:0; text-align:left; color: var(--accent);">
            {{ session('status') }}
        </p>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">Resend verification email</button>
    </form>

    <div class="footer-link">
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" style="width:auto; background:none; color:var(--accent); text-decoration:underline; padding:0;">Log out</button>
        </form>
    </div>

@endsection