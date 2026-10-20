<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    private UserRepository $users;
    private RoleRepository $roles;
    public function __construct(UserRepository $users,RoleRepository $roles)
    {
        $this->users = $users;
        $this->roles = $roles;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $roles = $this->roles->all();

        $search = $request->string('q')->trim()->toString();
        $roleFilter = $roles->contains('name', $request->query('role')) ? $request->query('role') : null;
        $verified = in_array($request->query('verified'), ['yes', 'no'], true) ? $request->query('verified') : null;

        $users = $this->users->getForAdmin($search, $roleFilter, $verified)->withQueryString();

        return view('admin.index', compact('users', 'roles', 'search', 'roleFilter', 'verified'));
    }

    public function updateRole(UpdateUserRoleRequest $request, User $user): RedirectResponse
    {
        $newRole = $this->roles->findOrFail($request->validated('role_id'));

        $oldRoles = $this->users->changeRole($user, $newRole);

        Log::info('User role changed', [
            'changed_by' => $request->user()->id,
            'target_user' => $user->id,
            'old_role' => $oldRoles,
            'new_role' => $newRole->name,
        ]);

        return back()->with('status', "{$user->name} is now {$newRole->label}.");
    }
}