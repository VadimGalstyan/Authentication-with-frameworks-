<?php

namespace App\Observers;

use App\Models\User;
use App\Repositories\RoleRepository;

class UserObserver
{
    private RoleRepository $roles;
    public function __construct(RoleRepository $roles) 
    {
        $this->roles=$roles;
    }

    public function created(User $user): void
    {
        $role = $this->roles->findByName(config('roles.default'));

        if ($role) 
        {
            $user->roles()->syncWithoutDetaching([$role->id]);
        }
    }
}