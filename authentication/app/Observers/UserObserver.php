<?php

namespace App\Observers;

use App\Models\User;
use App\Services\UserService;

class UserObserver
{
    public function __construct(private UserService $users) 
    {
    }

    public function created(User $user): void
    {
        $this->users->assignDefaultRole($user);
    }
}