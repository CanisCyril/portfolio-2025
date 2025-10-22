<?php

namespace App\Services\Game\Level;

use App\Contracts\Game\Level\MiningLevelCacheServiceInterface;
use App\Models\Games\Mining\UserMiningLevel;
use Illuminate\Support\Facades\Redis;

class MiningLevelCacheService implements MiningLevelCacheServiceInterface
{
    public function getMiningLevel(int $userId): int
    {
        $miningLevelKey = "user:{$userId}:mining_level";

        if (!Redis::exists($miningLevelKey)) {

            return $this->setMiningLevel($userId, $miningLevelKey);
        }

        return (int) Redis::get($miningLevelKey);
    }


    public function setMiningLevel(int $userId, string $miningLevelKey): int
    {
        $level = UserMiningLevel::where('user_id', $userId)->value('level');

        return (int) Redis::set($miningLevelKey, $level, 'EX', 3600);
    }
}
