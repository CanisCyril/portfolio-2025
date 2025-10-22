<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Game\MiningServiceInterface;
use App\Services\Game\MiningService;
use App\Contracts\Game\InventoryServiceInterface;
use App\Services\Game\InventoryService;
use App\Contracts\Game\Xp\ExperienceServiceInterface;
use App\Services\Game\Xp\ExperienceService;
use App\Contracts\Game\Xp\CacheXpServiceInterface;
use App\Services\Game\Xp\CacheXpService;
use App\Contracts\Game\Xp\SyncXpServiceInterface;
use App\Services\Game\Xp\SyncXpService;
use App\Contracts\Game\Level\MiningLevelCacheServiceInterface;
use App\Services\Game\Level\MiningLevelCacheService;
use App\Contracts\Game\Level\LevelUpServiceInterface;
use App\Services\Game\Level\LevelUpService;

class MiningGameSerivceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(MiningServiceInterface::class, MiningService::class);
        $this->app->singleton(InventoryServiceInterface::class, InventoryService::class);
        $this->app->singleton(ExperienceServiceInterface::class, ExperienceService::class);
        $this->app->singleton(CacheXpServiceInterface::class, CacheXpService::class);
        $this->app->singleton(SyncXpServiceInterface::class, SyncXpService::class);
        $this->app->singleton(MiningLevelCacheServiceInterface::class, MiningLevelCacheService::class);
        $this->app->singleton(LevelUpServiceInterface::class, LevelUpService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
