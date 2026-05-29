<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $adminRole = Role::create(['name' => 'admin',]);
        $userRole = Role::create(['name' => 'user',]);

        $admins = User::factory(3)->create();

        foreach ($admins as $admin) {
            $admin->roles()->attach($adminRole);
            Post::factory()
                ->count(rand(2, 5))
                ->create([
                    'user_id' => $admin->id,
                ]);
        }

        $users = User::factory(10)->create();

        foreach ($users as $user) {
            $user->roles()->attach($userRole);
            Post::factory()
                ->count(rand(2, 5))
                ->create([
                    'user_id' => $user->id,
                ]);
        }
    }
}
