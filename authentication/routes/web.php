<?php

use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\VerifyEmailController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisterController::class, 'show'])->name('register');

Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class,'show'])->name('login');

Route::post('/login', [LoginController::class,'store']);

Route::post('/logout', [LoginController::class,'logout']);

Route::get('/dashboard', [DashboardController::class,'show'])->middleware(['auth','verified']);


Route::middleware('auth')->group(function () 
{
    Route::get('/email/verify', function () {
                            return view('auth.verify-email');
                            })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');

    Route::post('/email/resend', [VerifyEmailController::class, 'resend'])
        ->middleware('throttle:1,1')
        ->name('verification.send');
});