<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'user_tester',
                'display_name' => 'Test Account',
                'description' => null,
            ],
            [
                'name' => 'admin_tester',
                'display_name' => 'Admin',
                'description' => null,
            ],
            [
                'name' => 'agent',
                'display_name' => 'Agent',
                'description' => null,
            ],
            [
                'name' => 'it_support',
                'display_name' => 'IT Support',
                'description' => null,
            ],
            [
                'name' => 'web_developer',
                'display_name' => 'Web Developer',
                'description' => null,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']], 
                $role
            );
        }
    }
}
