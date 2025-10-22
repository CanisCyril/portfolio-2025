<?php

namespace App\Services\Game\Level;

use App\Contracts\Game\Level\LevelUpServiceInterface;
use App\Contracts\Game\Level\MiningLevelCacheServiceInterface;
use App\Models\Games\Mining\UserMiningLevel;

class LevelUpService implements LevelUpServiceInterface
{
    protected $miningLevelCacheService;

    public function __construct(MiningLevelCacheServiceInterface $miningLevelCacheService)
    {
        $this->miningLevelCacheService = $miningLevelCacheService;
    }

    public function xpRequiredForNextLevel(int $level): int
    {
        return (int) (100 * pow($level, 1.5));
    }

    public function levelUp(int $userId): void
    {
        // Increment the user's mining level
        UserMiningLevel::where('user_id', $userId)->increment('level');
        $this->miningLevelCacheService->setMiningLevel($userId, "user:{$userId}:mining_level");
    }
}
