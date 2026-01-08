<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'JohnDoe@test.com',
                'password' => Hash::make('password'),
                'role_id' => 1,

            ],
             [
                'name' => 'Admin',
                'email' => 'Admin@test.com',
                'password' => Hash::make('password'),
                'role_id' => 2,

            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['name' => $user['name']], 
                $user
            );
        }
    }
}
