<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view_dashboard' => 'View dashboard',
            'access_moderator_page' => 'Access moderator page',
            'access_admin_page' => 'Access admin page',
            'view_users' => 'View users',
            'manage_users' => 'Manage users',
        ];

        foreach ($permissions as $name => $label) 
        {
            Permission::updateOrCreate(['name' => $name], ['label' => $label]);
        }
    }
}