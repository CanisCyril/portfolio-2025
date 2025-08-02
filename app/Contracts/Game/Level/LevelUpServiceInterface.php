<?php

namespace App\Contracts\Game\Level;

interface LevelUpServiceInterface
{
    /**
     * Get the required XP for the next level.
     *
     * @param int $level
     * @return int
     */
    public function xpRequiredForNextLevel(int $level): int;

    /**
     * Level up the user based on their current XP.
     *
     * @param int $userId
     * @return void
     */
    public function levelUp(int $userId): void;
}
{
}
