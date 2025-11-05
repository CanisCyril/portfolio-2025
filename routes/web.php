<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
// use App\Http\Controllers\ECommerce\DashboardController;
use App\Http\Controllers\ECommerce\ProductSearchController;
use App\Http\Controllers\Games\Mining\MiningController;
use App\Http\Controllers\Games\Mining\PickaxeController;
use App\Http\Controllers\Games\Mining\UserGoldController;
use App\Http\Controllers\Helpdesk\TicketController;

// Helpdesk
use App\Http\Controllers\Helpdesk\DemoAuthController;
use App\Http\Controllers\Helpdesk\DashboardController;
use App\Http\Controllers\Helpdesk\ReportController;
use App\Http\Controllers\Helpdesk\UserController as assigneController;


// * Helpdesk Routes * //

Route::get('api/assigne-list', [assigneController::class, 'assignes'])
    ->name('helpdesk.assignes');

Route::get('/helpdesk/demo-login', [DemoAuthController::class, 'index'])
    ->name('helpdesk.demo.index');

Route::post('/helpdesk/demo/auth', [DemoAuthController::class, 'auth'])
    ->name('helpdesk.demo.auth');

Route::middleware(['auth'])->get('/helpdesk', [DashboardController::class, 'index'])
    ->name('helpdesk');


Route::get('/helpdesk/create-ticket', function () {
    return Inertia::render('Helpdesk/CreateTicket');
})->name('helpdesk.create');

Route::get('/helpdesk/reports', [ReportController::class, 'index'])
    ->name('helpdesk.report');

Route::post('/helpdesk/store-ticket', [TicketController::class, 'store'])
    ->name('helpdesk.tickets.store');

Route::post('/helpdesk/active-tab', [TicketController::class, 'activeTab'])
    ->name('helpdesk.tickets.activeTab');

Route::get('/helpdesk/ticket/{ticket}', [TicketController::class, 'show'])
    ->name('helpdesk.ticket.show');

// * General Routes * //

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->name('home');

Route::get('/three', function () {
    return Inertia::render('Games/ThreeJs');
})->name('threejs');

Route::get('/new', function () {
    return Inertia::render('Games/New');
})->name('new');

Route::get('/third', function () {
    return Inertia::render('Games/Third');
})->name('third');

Route::get('/get-test-users', function () {
    return view('test-users', [
        'users' => [
            ['id' => 1, 'name' => 'Alice', 'email' => 'alice@example.com'],
            ['id' => 2, 'name' => 'Bob', 'email' => 'bob@example.com'],
            ['id' => 3, 'name' => 'Charlie', 'email' => 'charlie@example.com'],
        ]
    ]);
})->name('get-test-users');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

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

Route::get('/api/pickaxes', [PickaxeController::class, 'index']);


Route::post('/api/pickaxes/equip', [PickaxeController::class, 'equip']);

Route::post('/api/gold/sell-all', [UserGoldController::class, 'sellAll']);


Route::get('ecommerce/product-search', [ProductSearchController::class, 'search']);


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
