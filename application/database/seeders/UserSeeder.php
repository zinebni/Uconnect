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
            // 10 utilisateurs ajoutés
            [
                'name' => 'Emma Wilson',
                'email' => 'emma@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Youssef El Amrani',
                'email' => 'youssef@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Chloe Nguyen',
                'email' => 'chloe@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Mohamed Zaki',
                'email' => 'mohamed@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Lina Haddad',
                'email' => 'lina@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Ali Mansour',
                'email' => 'ali@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Sara Bouchra',
                'email' => 'sara@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'David Brown',
                'email' => 'david@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Fatima Zahra',
                'email' => 'fatima@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Fatima Zahra',
                'email' => 'fatima1010@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Omar Khalil',
                'email' => 'omar@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Omar Khalil',
                'email' => 'omar123@example.com',
                'password' => Hash::make('password'),
            ]
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
