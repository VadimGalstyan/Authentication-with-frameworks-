<?php

use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisterController::class, 'show'])->name('register');

Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class,'show'])->name('login');

Route::post('/login', [LoginController::class,'store']);

Route::post('/logout', [LoginController::class,'logout']);

Route::get('/dashboard', [DashboardController::class,'show'])->middleware('auth');