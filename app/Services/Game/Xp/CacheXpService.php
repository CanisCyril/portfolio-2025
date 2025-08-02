<?php

namespace App\Services\Game\Xp;

use App\Contracts\Game\Xp\CacheXpServiceInterface;
use App\Contracts\Game\Level\MiningLevelCacheServiceInterface;
use App\Contracts\Game\Level\LevelUpServiceInterface;
use App\Models\Games\Mining\UserMiningLevel;
use Illuminate\Support\Facades\Redis;

class CacheXpService implements CacheXpServiceInterface
{
    protected $miningLevelCacheService;
    protected $levelUpService;

    public function __construct(
        MiningLevelCacheServiceInterface $miningLevelCacheService,
        LevelUpServiceInterface $levelUpService
    ) {

        $this->miningLevelCacheService = $miningLevelCacheService;
        $this->levelUpService = $levelUpService;
    }

    public function cacheXp(int $totalXp): array
    {

        // need to set cooldown here to prevent spam clicking
        // should be done dynamically based on pickaxe, preferably fetch cooldowns from config
        $userId = auth()->id();
        $key = "user:{$userId}:mining_xp";
        $ttl = 900; // seconds (fixed cooldown)

        // Atomically increment XP and reset TTL using Lua script
        Redis::eval(
            <<<LUA
            local key = KEYS[1]
            local increment = tonumber(ARGV[1])
            local ttl = tonumber(ARGV[2])
            local newVal = redis.call('incrby', key, increment)
            redis.call('expire', key, ttl)
            return newVal
            LUA,
            1,
            $key,
            $totalXp,
            $ttl
        );

        // Track the user in a sorted set
        Redis::zadd('xp_pending_users', time(), $userId);

        $cachedUserXp = Redis::get($key);
        $baseXp = $this->getBaseXp($userId);
        $totalCurrentXp = $cachedUserXp + $baseXp;


        // Update the user's mining level if necessary
        $miningLevel = $this->miningLevelCacheService->getMiningLevel($userId);
        $xpRequiredForNextLevel = $this->levelUpService->xpRequiredForNextLevel($miningLevel);
        // dd($totalCurrentXp, $xpRequiredForNextLevel);
        if ($totalCurrentXp >= $xpRequiredForNextLevel) {

            $this->levelUpService->levelUp($userId);
        }

        return ['gainedXp' => $totalXp, 'totalCurrentXp' => $totalCurrentXp];

    }

    public function getBaseXp($userId): int
    {

        $baseXpKey = "user:{$userId}:base_mining_xp";

        if (!Redis::exists($baseXpKey)) {
            $baseXp = UserMiningLevel::where('user_id', $userId)->value('total_xp');

            return (int) Redis::getset($baseXpKey, $baseXp);
        }

        return (int) Redis::get($baseXpKey);
    }
}
