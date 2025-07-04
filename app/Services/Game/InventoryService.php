<?php

namespace App\Services\Game;

use App\Contracts\Game\InventoryServiceInterface;
use App\Models\Games\Mining\UserOre;

class InventoryService implements InventoryServiceInterface
{
    public function addItems(int $oreID, int $amount): UserOre {

        $userOre = UserOre::firstOrNew([
            'user_id' => auth()->id(),
            'ore_id' => $oreID
        ]);

        // Add to existing amount (defaulting to 0 if new)
        $userOre->amount = $userOre->amount + $amount;
        $userOre->save();

        return $updatedOreCount = $userOre->load('ore');
    }
}