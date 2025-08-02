<?php

namespace App\Contracts\Game;

interface MiningServiceInterface
{
    public function mineOre(int $oreID): array;
}
