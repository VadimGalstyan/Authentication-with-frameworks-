<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'user' => [
                'label' => 'User',
                'permissions' => ['view_dashboard'],
            ],

            'moderator' => [
                'label' => 'Moderator',
                'permissions' => ['view_dashboard', 'access_moderator_page', 'view_users'],
            ],
            
            'admin' => [
                'label' => 'Administrator',
                'permissions' => ['view_dashboard', 'access_moderator_page', 'access_admin_page', 'view_users', 'manage_users'],
            ],
        ];

        foreach ($roles as $name => $data) 
        {
            $role = Role::updateOrCreate(['name' => $name], ['label' => $data['label']]);

            $role->permissions()->sync(
                Permission::whereIn('name', $data['permissions'])->pluck('id')
            );
        }
    }
}