<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ECommerce\DashboardController;
use App\Http\Controllers\ECommerce\ProductSearchController;
use App\Http\Controllers\Games\MiningController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/get-test-users', function () {
    return view('test-users', [
        'users' => [
            ['id' => 1, 'name' => 'Alice', 'email' => 'alice@example.com'],
            ['id' => 2, 'name' => 'Bob', 'email' => 'bob@example.com'],
            ['id' => 3, 'name' => 'Charlie', 'email' => 'charlie@example.com'],
        ]
    ]);
})->name('get-test-users');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/hello', function () {
    return 'Hello Docker 123!';
});


Route::get('ecommerce', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('ecommerce.dashboard');

Route::get('games/mining', [MiningController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('games.mining');

    Route::post('/mine', [MiningController::class, 'mine']);

Route::get('ecommerce/product-search', [ProductSearchController::class, 'search']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
