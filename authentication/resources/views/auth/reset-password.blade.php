@extends('layouts.app')

@section('card', '1')

@section('title', 'Reset password')

@section('content')

    <h1>Reset your password</h1>

    <x-form-errors />

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="field">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $email) }}" required>
        </div>

        <div class="field">
            <label>New password</label>
            <input type="password" name="password" required>
        </div>

        <div class="field">
            <label>Confirm password</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button type="submit">Reset password</button>
    </form>

@endsection