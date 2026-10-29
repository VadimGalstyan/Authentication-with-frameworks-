<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Models\User;
use App\Services\UserListingService;
use App\Services\UserRoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(
        private UserListingService $userListing,
        private UserRoleService $userRoles,
    ) 
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        return view('admin.index', $this->userListing->listForAdmin(
            $request->only(['q', 'role', 'verified'])
        ));
    }

    public function updateRole(UpdateUserRoleRequest $request, User $user): RedirectResponse
    {
        $role = $this->userRoles->changeRole(
            $request->user(),
            $user,
            (int) $request->validated('role_id')
        );

        return back()->with('status', "{$user->name} is now {$role->label}.");
    }
}