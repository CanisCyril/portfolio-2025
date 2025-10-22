<?php

namespace App\Contracts\Game\Xp;

interface ExperienceServiceInterface
{
    public function xpGained(int $oreID, int $amount): int;

    public function xpRequiredForLevel(int $level): int;
}
