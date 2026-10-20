<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository
{
    public function all()
    {
        return Role::orderBy('id')->get();
    }

    public function findOrFail(int $id): Role
    {
        return Role::findOrFail($id);
    }

    public function findByName(string $name): ?Role
    {
        return Role::where('name', $name)->first();
    }
}