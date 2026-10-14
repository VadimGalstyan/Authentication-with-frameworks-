<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with('roles');

        if ($search = $request->input('search')) 
        {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleId = $request->input('role_id')) 
        {
            $query->whereHas('roles', function ($q) use ($roleId) {
                $q->where('roles.id', $roleId);
            });
        }

        if ($request->filled('verified')) 
        {
            if ($request->boolean('verified')) 
            {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('admin.index', compact('users', 'roles'));
    }

    public function updateRole(UpdateUserRoleRequest $request, User $user)
    {
        $this->authorize('updateRole', $user);

        $oldRoles = $user->roles->pluck('name')->join(', ');

        $newRoleId = $request->validated('role_id');
        $newRole = Role::findOrFail($newRoleId);

        $user->roles()->sync([$newRoleId]);

        Log::info('User role changed', [
            'changed_by'  => auth()->id(),
            'target_user' => $user->id,
            'old_roles'   => $oldRoles ?: 'none',
            'new_role'    => $newRole->name,
            'timestamp'   => now()->toDateTimeString(),
        ]);

        return back()->with('success', "Role for {$user->name} updated to {$newRole->label}.");
    }
}