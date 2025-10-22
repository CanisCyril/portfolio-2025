<?php

namespace App\Services\Game\Xp;

use App\Contracts\Game\Xp\SyncXpServiceInterface;
use App\Models\Games\Mining\UserMiningLevel;
use Illuminate\Support\Facades\Redis;

class SyncXpService implements SyncXpServiceInterface
{
    public function syncXpToDatabase(): void
    {
        $now = time();
        $cutoff = $now - 600; // Consider users active in last 10 mins

        // Get recently active users
        $userIds = Redis::zrangebyscore('xp_pending_users', $cutoff, $now);

        // STEP 1 - Get userIds from Redis::zrangebyscore()
        // Step 2 - foreach userIds
        // -- $key = "user:{$userId}:mining_xp";
        //  - get ttl in ms
        //  -

        foreach ($userIds as $userId) {
            $key = "user:{$userId}:mining_xp";

            // Step 1: Get current TTL (in milliseconds)
            $ttl = Redis::pttl($key); // -1 = no TTL, -2 = key does not exist
            dump(['ttl' => $ttl]);

            // Step 2: Get and reset XP (atomic)
            $xp = Redis::getset($key, 0);

            dump(['id' => $userId, 'xp' => $xp]);

            // Step 3: Restore TTL if valid
            if ($ttl > 0) {
                Redis::pexpire($key, $ttl);
            } elseif ($ttl === -1) {
                // Key had no TTL → avoid stale keys by deleting
                Redis::del($key);
            }

            // Step 4: Process XP if non-zero
            if ($xp > 0) {
                echo 'updating xp';

                $userXp = UserMiningLevel::firstOrNew(['user_id' => $userId]);

                if ($userXp->exists) {
                    // Record exists, increment total_xp
                    $userXp->increment('total_xp', $xp);
                } else {
                    // New user, set initial XP
                    $userXp->total_xp = $xp;
                    $userXp->save();
                }
            }

            // Step 5: Remove from tracking set if XP key is now gone
            if (!Redis::exists($key)) {
                echo 'removing key';
                Redis::zrem('xp_pending_users', $userId);
            }
        }

        // Step 6: final cleanup of very old entries in sorted set
        $cleanupCutoff = $cutoff - 60;
        Redis::zremrangebyscore('xp_pending_users', '-inf', $cleanupCutoff);
    }
}
