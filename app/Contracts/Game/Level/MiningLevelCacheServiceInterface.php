<?php

namespace App\Contracts\Game\Level;

interface MiningLevelCacheServiceInterface
{
    /**
     * Get the user's current mining level.
     *
     * @param int $userId
     * @return int
     */
    public function getMiningLevel(int $userId): int;

    /**
     * Set the user's mining level.
     *
     * @param int $userId
     * @param int $level
     * @return void
     */
    public function setMiningLevel(int $userId, string $miningLevelKey): int;
}
{

}
