<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function queryWithRoles(): Builder
    {
        return User::with('roles');
    }

    public function whereSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    public function whereHasRole(Builder $query, string $role): Builder
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }

    public function whereVerified(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function whereUnverified(Builder $query): Builder
    {
        return $query->whereNull('email_verified_at');
    }

    public function orderById(Builder $query): Builder
    {
        return $query->orderBy('id');
    }

    public function paginate(Builder $query, int $perPage): LengthAwarePaginator
    {
        return $query->paginate($perPage);
    }

    public function roleNames(User $user): array
    {
        return $user->roles()->pluck('name')->all();
    }

    public function replaceRoles(User $user, int $roleId): void
    {
        $user->roles()->sync([$roleId]);
    }

    public function addRole(User $user, int $roleId): void
    {
        $user->roles()->syncWithoutDetaching([$roleId]);
    }
}