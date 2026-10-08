<?php

use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisterController::class, 'show'])->name('register');

Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class,'show'])->name('login');

Route::post('/login', [LoginController::class,'store']);

Route::post('/logout', [LoginController::class,'logout']);

Route::get('/dashboard', [DashboardController::class,'show'])->middleware(['auth','verified']);


Route::middleware('auth')->group(function () {

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

Route::middleware('guest')->group(function () {
    
    Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'show'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});