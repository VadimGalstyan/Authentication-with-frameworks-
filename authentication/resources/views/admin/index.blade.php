@extends('layouts.app')

@section('title', 'User Management')

@section('content')

<div style="width: 100%; max-width: none; margin: 0; padding: 0; box-sizing: border-box;">
    <h1>Admin Users Management</h1>

    @if(session('status'))
        <div style="padding: 10px; background-color: #d1e7dd; color: #0f5132; margin-bottom: 15px; border-radius: 4px;">
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div style="padding: 10px; background-color: #f8d7da; color: #842029; margin-bottom: 15px; border-radius: 4px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET" action="{{ route('admin.index') }}" style="display: flex; gap: 10px; margin-bottom: 20px; align-items: center; flex-wrap: wrap;">
        <input 
            type="text" 
            name="q" 
            placeholder="Search name or email..." 
            value="{{ $search }}"
            style="padding: 8px; width: 220px;"
        >

        <select name="role" style="padding: 8px;">
            <option value="">All Roles</option>
            @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ $roleFilter === $role->name ? 'selected' : '' }}>
                    {{ $role->label }}
                </option>
            @endforeach
        </select>

        <select name="verified" style="padding: 8px;">
            <option value="">All Verification States</option>
            <option value="yes" {{ $verified === 'yes' ? 'selected' : '' }}>Verified</option>
            <option value="no" {{ $verified === 'no' ? 'selected' : '' }}>Unverified</option>
        </select>

        <button type="submit" style="padding: 8px 15px; cursor: pointer;">Filter</button>
        <a href="{{ route('admin.index') }}" style="padding: 8px 15px; text-decoration: none; background: #e0e0e0; color: #333; border-radius: 3px;">Reset</a>
    </form>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background-color: #f4f4f4;">
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Verified</th>
                <th>Created Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roles->pluck('label')->join(', ') }}</td>
                    <td>
                        @if($user->hasVerifiedEmail())
                            <span style="color: green; font-weight: bold;">Yes</span>
                        @else
                            <span style="color: red; font-weight: bold;">No</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        @can('updateRole', $user)
                            <form method="POST" action="{{ route('admin.update-role', $user) }}" style="display: flex; gap: 5px;">
                                @csrf
                                @method('PATCH')
                                <select name="role_id" style="padding: 4px;">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'selected' : '' }}>
                                            {{ $role->label }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" style="padding: 4px 8px; cursor: pointer;">Save</button>
                            </form>
                        @else
                            <span style="color: #888; font-size: 0.85rem;">Cannot change own role</span>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #666;">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
        </div>

        <div style="display: flex; gap: 8px;">
            @if ($users->onFirstPage())
                <span style="padding: 6px 12px; background: #e5e7eb; color: #9ca3af; border-radius: 4px;">&laquo; Previous</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" style="padding: 6px 12px; background: #2563eb; color: white; text-decoration: none; border-radius: 4px;">&laquo; Previous</a>
            @endif

            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" style="padding: 6px 12px; background: #2563eb; color: white; text-decoration: none; border-radius: 4px;">Next &raquo;</a>
            @else
                <span style="padding: 6px 12px; background: #e5e7eb; color: #9ca3af; border-radius: 4px;">Next &raquo;</span>
            @endif
        </div>
    </div>

</div>
@endsection