<?php

namespace App\Contracts\Game\Xp;

interface SyncXpServiceInterface
{
    public function syncXpToDatabase(): void;
}
