<?php

namespace App\Contracts\Game;

interface ExperienceServiceInterface
{

    public function xpGained(int $oreID, int $amount): int;
    public function cacheXp(int $totalXp): void;
}