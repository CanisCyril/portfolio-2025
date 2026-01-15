<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Games\Mining\UserPickaxe;

class UserPickaxesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userPickaxes = [
            [
                'user_id' => 1,
                'pickaxe_id' => 1,
                'equipped' => 1,
            ],
            [
                'user_id' => 2,
                'pickaxe_id' => 1,
                'equipped' => 1,
            ],
        ];

        foreach ($userPickaxes as $userPickaxe) {
            UserPickaxe::updateOrCreate(
                [
                    'user_id' => $userPickaxe['user_id'],
                    'pickaxe_id' => $userPickaxe['pickaxe_id']
                ],
                $userPickaxe
            );
        }
    }
}
