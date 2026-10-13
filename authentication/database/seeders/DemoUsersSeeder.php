<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {

        User::factory()->withRole('moderator')->create([
            'name' => 'Moderator',
            'email' => 'moderator@example.com',
            'password' => Hash::make('Password123'),
        ]);

        User::factory()->withRole('user')->unverified()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('Password123'),
        ]);

        User::factory()->count(18)->withRole('user')->create();
        User::factory()->count(5)->withRole('user')->unverified()->create();
        User::factory()->count(4)->withRole('moderator')->create();
        User::factory()->count(1)->withRole('moderator')->unverified()->create();
        User::factory()->count(2)->withRole('admin')->create();
    }
}