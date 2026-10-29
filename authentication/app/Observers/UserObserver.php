<?php

namespace App\Observers;

use App\Models\User;
use App\Services\UserRoleService;

class UserObserver
{
    public function __construct(private UserRoleService $roles) {}

    public function created(User $user): void
    {
        $this->roles->assignDefaultRole($user);
    }
}