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

    <form method="POST" action="/register">
        @csrf
        <div class="field">
            <label>Name</label>
            <input type="text" name="name" value="{{old('name')}}">
        </div>

        <div class="field">
            <label>Email</label>
            <input type="email" name="email" value="{{old('email')}}">
        </div>

        <div class="field">
            <label>Password</label>
            <input type="password" name="password">
        </div>

        <div class="field">
            <label>Password confirmation</label>
            <input type="password" name="password_confirmation">
        </div>

        <button type="submit">Create account</button>
    </form>

@endsection