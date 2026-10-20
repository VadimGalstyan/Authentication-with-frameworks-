<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        
        $password = config('roles.admin.password');

        if (!$password) 
        {
            $this->command->error('Set ADMIN_PASSWORD in your .env file before seeding.');
            return;
        }

        $admin = User::firstOrNew(['email' => config('roles.admin.email')]);
        $admin->name = config('roles.admin.name');
        $admin->password = config('roles.admin.password');
        $admin->email_verified_at = now();
        $admin->save();

        $admin->roles()->sync([Role::where('name', 'admin')->firstOrFail()->id]);
    }
}