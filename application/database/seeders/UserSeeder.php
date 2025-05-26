<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Marie Dupont',
                'email' => 'marie@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Ahmed Ben Ali',
                'email' => 'ahmed@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Sofia Garcia',
                'email' => 'sofia@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Lucas Martin',
                'email' => 'lucas@example.com',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        // Créer des relations de follow aléatoires
        $allUsers = User::all();
        foreach ($allUsers as $user) {
            $usersToFollow = $allUsers->except($user->id)->random(rand(1, 3));
            foreach ($usersToFollow as $userToFollow) {
                $user->following()->attach($userToFollow->id);
            }
        }
    }
}
