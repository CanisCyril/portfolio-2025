<?php

namespace App\Services\Game;

use App\Contracts\Game\InventoryServiceInterface;
use App\Contracts\Game\MiningServiceInterface;
use App\Contracts\Game\Xp\ExperienceServiceInterface;
use App\Contracts\Game\Xp\CacheXpServiceInterface;
use App\Contracts\Game\Xp\SyncXpServiceInterface;

class MiningService implements MiningServiceInterface
{
    protected $inventoryService;
    protected $experienceService;
    protected $cacheXpService;
    protected $syncXpService;

    public function __construct(
        InventoryServiceInterface $inventoryService,
        ExperienceServiceInterface $experienceService,
        CacheXpServiceInterface $cacheXpService,
        SyncXpServiceInterface $syncXpService,
    ) {

        $this->inventoryService = $inventoryService;
        $this->experienceService = $experienceService;
        $this->cacheXpService = $cacheXpService;
        $this->syncXpService = $syncXpService;
    }

    public function mineOre(int $oreID): array
    {

        $weights = $this->oreWeight($oreID);

        // Assign a weight (likelihood) to each number from 1 to 10
        // Lower numbers have higher weights, so they’re picked more often

        // Total sum of all weights
        $totalWeight = array_sum($weights);

        // Generate a random number between 1 and the total weight
        $rand = rand(1, $totalWeight);

        // Walk through the weights cumulatively
        $cumulative = 0;
        foreach ($weights as $number => $weight) {
            $cumulative += $weight;

            // If the random number is within the current cumulative range,
            // we return that number
            if ($rand <= $cumulative) {

                $xpGained = $this->experienceService->xpGained($oreID, $number);
                break;
            }


        }
        $items = $this->inventoryService->addItems($oreID, $number);
        $xp = $this->cacheXpService->cacheXp($xpGained);

        return [
                  'xp' => $xp,
                  'items' => $items
              ];

        // Fallback in case something goes wrong (should never happen)
        // return 'ERROR, CONTACT SUPPORT!';
    }

    protected function oreWeight(int $oreID): array
    {

        return config("mining-game.weights.{$oreID}", []);
    }
}
