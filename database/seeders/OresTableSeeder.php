<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Games\Mining\Ore;

class OresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $ores = [
            [
                'name' => 'copper',
                'display_name' => 'Copper',
                'value' => 1,
                'level_requirement' => 0,
            ],
            [
                'name' => 'tin',
                'display_name' => 'Tin',
                'value' => 1.5,
                'level_requirement' => 2,
            ],
            [
                'name' => 'iron',
                'display_name' => 'Iron',
                'value' => 2,
                'level_requirement' => 10,
            ],
            [
                'name' => 'silver',
                'display_name' => 'Silver',
                'value' => 3,
                'level_requirement' => 15,
            ],
            [
                'name' => 'gold',
                'display_name' => 'Gold',
                'value' => 5,
                'level_requirement' => 20,
            ],
        ];

        foreach ($ores as $ore) {
            Ore::updateOrCreate(
                ['name' => $ore['name']], 
                $ore
            );
        }
    }
}
