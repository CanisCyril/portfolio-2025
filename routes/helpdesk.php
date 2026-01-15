<?php 

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Helpdesk
use App\Http\Controllers\Helpdesk\DemoAuthController;
use App\Http\Controllers\Helpdesk\DashboardController;
use App\Http\Controllers\Helpdesk\ReportController;
use App\Http\Controllers\Helpdesk\UserController as assigneeController;
use App\Http\Controllers\Helpdesk\UpdateAssigneeController;
use App\Http\Controllers\Helpdesk\TicketController;
use App\Http\Controllers\Helpdesk\TicketCommentController;
use App\Http\Controllers\Helpdesk\TicketAttachmentController;
use App\Http\Controllers\Helpdesk\DemoLogoutController;


// Guest 

Route::get('/helpdesk/demo-login', [DemoAuthController::class, 'index'])
    ->name('helpdesk.demo.index');
    Route::post('/helpdesk/demo/auth', [DemoAuthController::class, 'auth'])
        ->name('helpdesk.demo.auth');
//Auth

// * Helpdesk Routes * //

Route::middleware('helpdesk.auth')->group(function () {

    Route::get('api/assignee-list', [assigneeController::class, 'assignees'])
        ->name('helpdesk.assignees');

    //Auth


        



    Route::post('/helpdesk/demo/logout', DemoLogoutController::class)
        ->name('helpdesk.demo.logout');

    Route::get('/helpdesk', [DashboardController::class, 'index'])
        ->name('helpdesk');


    Route::get('/helpdesk/create-ticket', function () {
        return Inertia::render('Helpdesk/CreateTicket');
    })->name('helpdesk.create');


    Route::post('/helpdesk/store-ticket', [TicketController::class, 'store'])
        ->name('helpdesk.tickets.store');

    Route::post('/helpdesk/active-tab', [TicketController::class, 'activeTab'])
        ->name('helpdesk.tickets.activeTab');

    Route::get('/helpdesk/ticket/{ticket}', [TicketController::class, 'show'])
        ->name('helpdesk.ticket.show');

    Route::get('/helpdesk/fetch/tickets', [TicketController::class, 'fetchTickets'])
        ->name('helpdesk.tickets.fetch');

    Route::patch('/helpdesk/ticket/assignee/{ticket}', UpdateAssigneeController::class)
        ->name('helpdesk.ticket.update.assignee');

    Route::patch('/helpdesk/ticket/status/{ticket}', [TicketController::class, 'updateStatus'])
        ->name('helpdesk.ticket.update.status');

    // Route::post('/helpdesk/ticket/assignee/{ticket}', UpdateAssigneeController::class)
    //     ->name('helpdesk.ticket.update.assignee');

    Route::post('/helpdesk/ticket/store/comment', [TicketCommentController::class, 'store'])
        ->name('helpdesk.ticket.store.comment');

    Route::get('/helpdesk/ticket/attachment/{attachment}', [TicketAttachmentController::class, 'download'])
        ->name('helpdesk.ticket.download.attachment');

    Route::post('/helpdesk/ticket/attachment/{id}', [TicketAttachmentController::class, 'store'])
        ->name('helpdesk.ticket.store.attachment');

    Route::get('/helpdesk/reports', [ReportController::class, 'index'])
        ->name('helpdesk.report');

});