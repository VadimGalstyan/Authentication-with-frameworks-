<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\RegistrationService;

class RegisterController extends Controller
{
    public function __construct(private RegistrationService $registration) {}

    public function show()
    {
        return view('register');
    }

    public function store(RegisterRequest $request)
    {
        $this->registration->register($request->validated());

        return redirect()->route('dashboard');
    }
}