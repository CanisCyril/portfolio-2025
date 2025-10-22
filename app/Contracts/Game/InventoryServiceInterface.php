<?php

namespace App\Contracts\Game;

use App\Models\Games\Mining\UserOre;

interface InventoryServiceInterface
{
    public function addItems(int $oreID, int $amount): UserOre;
}
