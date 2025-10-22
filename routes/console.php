<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\Game\Xp\SyncXpService;
use Illuminate\Support\Facades\Schedule;

// Schedule::call(function () {
//     echo 'running schedule', PHP_EOL;
//     app(SyncXpService::class)->syncXpToDatabase();
// })->everyMinute()->name('sync-xp-to-database')->withoutOverlapping();

Schedule::call(function () {
    echo "🔁 Running XP Sync Schedule...\n";

    try {
        app(SyncXpService::class)->syncXpToDatabase();
        echo "✅ Sync complete!\n";
    } catch (\Throwable $e) {
        echo "❌ Sync failed: {$e->getMessage()}\n";
        // Log::error('XP Sync Failed', ['error' => $e->getMessage()]);
    }
})->everyMinute()->name('sync-xp-to-database')->withoutOverlapping();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
