<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

enum AdminPage : int
{
    case UsersPerPage = 15;
}

class UserService
{
    public function __construct(private UserRepository $users,private RoleRepository $roles,)
    {
    }

    public function register(array $data): User
    {
        $user = $this->users->create($data);

        event(new Registered($user));

        Auth::login($user);

        return $user;
    }

    public function assignDefaultRole(User $user): void
    {
        $role = $this->roles->findByName(config('roles.default'));

        if ($role) {
            $this->users->addRole($user, $role->id);
        }
    }

    public function listForAdmin(array $input): array
    {
        $roles = $this->roles->all();

        $q = $input['q'] ?? '';
        $search = is_string($q) ? trim($q) : '';

        $role = $roles->contains('name', $input['role'] ?? null) ? $input['role'] : null;

        $verified = in_array($input['verified'] ?? null, ['yes', 'no'], true) ? $input['verified'] : null;

        $query = $this->users->queryWithRoles();

        if ($search !== '') 
        {
            $query = $this->users->whereSearch($query, $search);
        }

        if ($role !== null) 
        {
            $query = $this->users->whereHasRole($query, $role);
        }

        if ($verified === 'yes') 
        {
            $query = $this->users->whereVerified($query);
        }

        if ($verified === 'no') 
        {
            $query = $this->users->whereUnverified($query);
        }

        $query = $this->users->orderById($query);

        $users = $this->users->paginate($query, AdminPage::UsersPerPage->value)->withQueryString();

        return [
            'users' => $users,
            'roles' => $roles,
            'search' => $search,
            'roleFilter' => $role,
            'verified' => $verified,
        ];
    }
    public function changeRole(User $actor, User $target, int $roleId): Role
    {
        $newRole = $this->roles->findOrFail($roleId);

        $oldRoles = DB::transaction(function () use ($target, $newRole) {
            $old = $this->users->roleNames($target);
            $this->users->replaceRoles($target, $newRole->id);

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