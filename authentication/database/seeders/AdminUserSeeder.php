<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrNew(['email' => config('roles.admin.email')]);

        $admin->name = config('roles.admin.name');
        $admin->password = config('roles.admin.password');
        $admin->email_verified_at = now();
        $admin->save();

        $admin->roles()->sync([Role::where('name', 'admin')->firstOrFail()->id]);
    }
}