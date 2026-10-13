<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(18)->withRole('user')->create();
        User::factory()->count(5)->withRole('user')->unverified()->create();
        User::factory()->count(4)->withRole('moderator')->create();
        User::factory()->count(1)->withRole('moderator')->unverified()->create();
        User::factory()->count(2)->withRole('admin')->create();
    }
}