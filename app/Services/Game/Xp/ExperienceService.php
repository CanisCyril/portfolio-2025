<?php

namespace App\Services\Game\Xp;

use App\Contracts\Game\Xp\ExperienceServiceInterface;

class ExperienceService implements ExperienceServiceInterface
{
    public function xpGained(int $oreID, int $number): int
    {

        $xpPerOre = config("mining-game.experience.{$oreID}", []);
        $totalXp = $xpPerOre * $number;

        // dd(['1' => $xpPerOre, '2' => $number, '3' => $totalXp]);

        return $totalXp;
    }

    public function xpRequiredForLevel(int $level): int
    {
        return (int) (100 * pow($level, 1.5));
    }

}
