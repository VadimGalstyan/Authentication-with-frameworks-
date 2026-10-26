<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;

class RegisterController extends Controller
{
    public function __construct(private UserService $users) {}

    public function show()
    {
        return view('register');
    }

    public function store(RegisterRequest $request)
    {
        $this->users->register($request->validated());

        return redirect()->route('dashboard');
    }
}