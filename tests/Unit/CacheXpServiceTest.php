<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Game\Xp\CacheXpService;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Mockery;

class CacheXpServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     */

    public function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    public function test_it_caches_xp_and_returns_correct_total()
    {
        // Step 1: Set up fake user ID
        $userId = 42;
        Auth::shouldReceive('id')->andReturn($userId);

        // Step 2: Set up expected Redis keys
        $xpKey = "user:{$userId}:mining_xp";
        $baseXpKey = "user:{$userId}:base_mining_xp";
        $totalXpGained = 100;
        $cachedXp = 300;
        $baseXp = 200;

        // Step 3: Mock Redis calls
        Redis::shouldReceive('eval')
            ->once()
            ->andReturn($cachedXp); // Simulate XP incrementing

        Redis::shouldReceive('zadd')
        ->once()
        ->with('xp_pending_users', Mockery::type('int'), $userId);

        Redis::shouldReceive('get')
            ->once()
            ->with($xpKey)
            ->andReturn($cachedXp);

        // Step 4: Mock Redis::exists for base XP

        Redis::shouldReceive('exists')
            ->once()
            ->with($baseXpKey)
            ->andReturn($baseXpKey);

        // Step 5: Redis::get for base XP

        Redis::shouldReceive('get')
            ->once()
            ->with($baseXpKey)
            ->andReturn($baseXp);

        // Step 6: Run the service method
        $service = new CacheXpService();
        $result = $service->cacheXp($totalXpGained);

        // Step 7: Assert output
        $this->assertEquals([
            'gainedXp' => $totalXpGained,
            'totalCurrentXp' => $cachedXp + $baseXp,
        ], $result);

    }
}
