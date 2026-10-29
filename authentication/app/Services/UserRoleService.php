<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserRoleService
{
    public function __construct(
        private UserRepository $users,
        private RoleRepository $roles,
    ) {}
    public function assignDefaultRole(User $user): void
    {
        $role = $this->roles->findByName(config('roles.default'));

        if (! $role) 
        {
            throw new \RuntimeException(
                "Default role '" . config('roles.default') . "' does not exist. Did you run the seeders?"
            );
        }

        $this->users->addRole($user, $role->id);
    }

    public function changeRole(User $actor,User $target,int $roleId): Role 
    {
        $newRole = $this->roles->findOrFail($roleId);

        $oldRoles = DB::transaction(function () use ($target,$newRole) 
        {
            $old = $this->users->roleNames($target);

            $this->users->replaceRoles(
                $target,
                $newRole->id
            );

            return $old;
        });

        $target->refreshAccessCache();

        Log::info('User role changed', [
            'changed_by' => $actor->id,
            'target_user' => $target->id,
            'old_role' => implode(', ', $oldRoles),
            'new_role' => $newRole->name,
        ]);

        return $newRole;
    }
}