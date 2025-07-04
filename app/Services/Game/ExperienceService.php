<?php

namespace App\Services\Game;

use App\Contracts\Game\ExperienceServiceInterface;
use App\Models\Games\Mining\UserOre;
use Illuminate\Support\Facades\Redis;

class ExperienceService implements ExperienceServiceInterface
{
 
    public function xpGained(int $oreID, int $number): int {
        
        $xpPerOre = config("mining-game.experience.{$oreID}", []);
        $totalXp = $xpPerOre * $number;
        
        return $totalXp;
    }

    public function cacheXp(int $totalXp): void {
        
        $userId = auth()->id();

        // Redis key — unique per user
        $key = "userId{$userId}:cached_xp";    

        //Increment to update cached xp with new xp
        Redis::incrby($key, $totalXp);
        // dd(Redis::get("userId{$userId}:cached_xp"));

    }
}