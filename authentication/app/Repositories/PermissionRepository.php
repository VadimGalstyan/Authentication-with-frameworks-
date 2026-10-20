<?php

namespace App\Repositories;

use App\Models\Permission;
use Illuminate\Support\Collection;

class PermissionRepository
{
    public function allNames(): Collection
    {
        return Permission::pluck('name');
    }
}