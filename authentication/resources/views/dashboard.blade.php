@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h1>Dashboard</h1>

    <div class="info-panel">
        <div class="info-row">
            <span class="label">Name</span>
            <span class="value">{{ $user->name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Email</span>
            <span class="value">{{ $user->email }}</span>
        </div>
    </div>

@endsection