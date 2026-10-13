@extends('layouts.app')

@section('title', 'Access denied')

@section('content')

    <h1>Access denied</h1>

    <div class="info-panel">
        <div class="info-row" style="display:block;">
            <p style="margin:0 0 0.5rem;">
                {{ $exception->getMessage() ?: 'You are not allowed to view this page.' }}
            </p>
            <a href="{{ url('/dashboard') }}">Back to dashboard</a>
        </div>
    </div>

@endsection