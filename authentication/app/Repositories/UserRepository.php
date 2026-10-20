<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

Enum AdminPage : int
{
    case UsersPerPage = 15;
}

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function getForAdmin(string $search, ?string $role, ?string $verified)
    {
        $query = User::with('roles');

        if ($search !== '') 
        {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role !== null) {
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        if ($verified === 'yes') {
            $query->whereNotNull('email_verified_at');
        }

        if ($verified === 'no') {
            $query->whereNull('email_verified_at');
        }

        return $query->orderBy('id')->paginate(AdminPage::UsersPerPage->value);
    }

    public function changeRole(User $user, Role $role): string
    {
        $oldRoles = $user->roles()->pluck('name')->implode(', ');

        DB::transaction(function () use ($user, $role) {
            $user->roles()->sync([$role->id]);
        });

        $user->refreshAccessCache();

        return $oldRoles;
    }
}