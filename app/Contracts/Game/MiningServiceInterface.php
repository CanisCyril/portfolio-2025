<?php

namespace App\Contracts\Game;

use App\Models\Games\Mining\UserOre;
interface MiningServiceInterface
{

    public function mineOre(int $oreID): array;
}