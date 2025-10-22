<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Game\Xp\SyncXpService;
use Illuminate\Support\Facades\Redis;
// use App\Models\Games\Mining\UserMiningLevel;
use Mockery;

class SyncXpServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     */

    public function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    public function test_example(): void
    {
        $now = time();
        $cutoff = $now - 600;
        $userIds = [1,2,3];
        $pttlMap = [
            1 => 30000,  // active cooldown
            2 => -1,     // key exists but no expiry
            3 => -2,     // key does not exist
        ];
        $xpPoints = [100, 0];


        // Step 1

        Redis::shouldReceive('zrangebyscore')
            ->once()
            ->with('xp_pending_users', Mockery::type('int'), Mockery::type('int'))
            ->andReturn($userIds);

        foreach ($pttlMap as $userId => $pttl) {

            $key = "user:{$userId}:mining_xp";

            Redis::shouldReceive('pttl')
                    ->once()
                    ->with($key)
                    ->andReturn($pttl);

            Redis::shouldReceive('getset')
            ->once()
            ->with($key, 0)
            ->andReturn($xp);

            if ($pttl > 0) {
                Redis::shouldReceive('pexpire')
                    ->once()
                    ->with($key, $pttl);
            } elseif ($pttl === -1) {
                // Key had no TTL → avoid stale keys by deleting
                Redis::shouldReceive('del')
                    ->once()
                    ->with($key);
            }


            echo 'test';

            foreach ($xpPoints as $xp) {
                if ($xp == 0) {

                }
            }
        }



        $service = new SyncXpService();
        $result = $service->syncXpToDatabase();





        // $this->assertTrue(true);
    }
}
