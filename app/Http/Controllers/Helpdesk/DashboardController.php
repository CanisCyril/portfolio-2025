<?php

namespace App\Http\Controllers\Helpdesk;

use App\Enums\Helpdesk\ActiveTabEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Helpdesk\Ticket;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $key = $request->enum('activeTab', ActiveTabEnum::class);

        $tickets = Ticket::activeTab($key, auth()->user()->id)
            ->with([
                'priority:id,slug,name,color', 
                'user:id,name', 
                'assignee:id,role_id,name',
                'assignee.role:id,name,display_name', 
                'comments:id,author_id,ticket_id,body,created_at',
                'comments.author:id,name',])
            ->paginate(8)
            ->withQueryString();

        $counts = [
            'open' => Ticket::open()->count(),
            'assigned' => Ticket::assigned()->count(),
            'recentlyClosed' => Ticket::closed()->count(),
        ];

        return Inertia::render('Helpdesk/Dashboard', [
            'tickets' => $tickets,
            'counts' => $counts,
            'permissions' => [
                'adminAccess' => request()->user()->can('helpdesk.admin.access'),
                'adminOptions' => request()->user()->can('helpdesk.view.admin.options'),
            ],
        ]);
    }
}
