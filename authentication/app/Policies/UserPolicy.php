<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_users');
    }

    public function view(User $user, User $target): bool
    {
        return $user->hasPermission('view_users');
    }

    public function updateRole(User $user, User $target): bool
    {
        if (!$user->hasPermission('manage_users')) 
        {
            return false;
        }

        
        return $user->id !== $target->id;
    }
}