<?php

namespace App\Repositories;

use App\DataTransferObjects\UserFilter;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function paginateByFilter(UserFilter $filter, int $perPage): LengthAwarePaginator
    {
        return User::query()
            ->with('roles')
            ->when($filter->search !== '', function ($query) use ($filter) {
                $term = '%' . $this->escapeLike($filter->search) . '%';

                $query->where(fn ($q) => $q->where('name', 'like', $term)
                                           ->orWhere('email', 'like', $term));
            })
            ->when($filter->role !== null, fn ($query) => $query->whereHas(
                'roles',
                fn ($q) => $q->where('name', $filter->role)
            ))
            ->when($filter->verified === true, fn ($query) => $query->whereNotNull('email_verified_at'))
            ->when($filter->verified === false, fn ($query) => $query->whereNull('email_verified_at'))
            ->orderBy('id')
            ->paginate($perPage);
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

    private function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }
}