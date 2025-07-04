<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\Game\MiningServiceInterface;
use App\Services\Game\MiningService;

use App\Contracts\Game\InventoryServiceInterface;
use App\Services\Game\InventoryService;

use App\Contracts\Game\ExperienceServiceInterface;
use App\Services\Game\ExperienceService;

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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
