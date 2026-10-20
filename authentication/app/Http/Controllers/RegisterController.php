<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    private UserRepository $users;
    public function __construct(UserRepository $users) 
    {
        $this->users = $users;
    }

    public function show()
    {
        return view('register');
    }

    public function store(RegisterRequest $request)
    {
        $user = $this->users->create($request->validated());

        event(new Registered($user));

        Auth::login($user);

        return redirect('/dashboard');
    }
}