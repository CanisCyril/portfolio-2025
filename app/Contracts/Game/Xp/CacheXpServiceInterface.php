<?php

namespace App\Contracts\Game\Xp;

interface CacheXpServiceInterface
{
    public function cacheXp(int $totalXp): array;
    public function getBaseXp(int $totalXp): int;
}
