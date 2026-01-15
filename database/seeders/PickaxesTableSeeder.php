<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Games\Mining\Pickaxe;

class PickaxesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pickaxes = [
            [
                'name' => 'bronze_pickaxe',
                'display_name' => 'Bronze Pickaxe',
                'level_requirement' => 0,
                'durability' => 100,
            ],
            [
                'name' => 'iron_pickaxe',
                'display_name' => 'Iron Pickaxe',
                'level_requirement' => 5,
                'durability' => 150,
            ],
            [
                'name' => 'steel_pickaxe',
                'display_name' => 'Steel Pickaxe',
                'level_requirement' => 10,
                'durability' => 200,
            ],
        ];

        foreach ($pickaxes as $pickaxe) {
            Pickaxe::updateOrCreate(
                ['name' => $pickaxe['name']], 
                $pickaxe
            );
        }
    }
}
