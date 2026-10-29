<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegistrationService
{
    public function __construct(private UserRepository $users) {}

    public function register(array $data): User
    {
        $user = $this->users->create($data);

        event(new Registered($user));

        Auth::login($user);

        return $user;
    }
}