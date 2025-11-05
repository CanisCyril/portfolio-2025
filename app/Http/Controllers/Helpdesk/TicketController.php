<?php

namespace App\Http\Controllers\Helpdesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Helpdesk\Ticket;
use Inertia\Inertia;
use App\Http\Requests\Helpdesk\TicketStoreRequest;

class TicketController extends Controller
{
    public function create()
    {
        return inertia('Helpdesk/CreateTicket');
    }

    public function store(TicketStoreRequest $request)
    {
        $request->merge(['user_id' => auth()->id()]);
        Ticket::create($request->all());

        return redirect()->route('helpdesk')->with('success', 'Ticket created successfully!');
    }

    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);

        return inertia('Helpdesk/ViewTicket', ['ticket' => $ticket]);
    }

    public function activeTab(Request $request)
    {
        $request->validate([
            'activeKey' => 'required|string',
        ]);

        //swith case for tickets

        switch ($request->activeKey) {
            case 'my':
                $tickets = Ticket::where('user_id', auth()->id())->latest()->paginate(10);
                break;
            case 'all':
                $tickets = Ticket::latest()->paginate(10);
                break;
            case 'assigned':
                // $tickets = 
                break;
        }


        // Store the active tab in the session
        // session(['helpdesk_active_tab' => $request->input('activeKey')]);

         return Inertia::render('Helpdesk', [
            // 'userTickets' => $userTickets,
            'tickets' => $tickets
            // 'permissions' => [
            //     'adminAccess' => request()->user()->can('helpdesk.admin.access'),
            //     'adminOptions' => request()->user()->can('helpdesk.view.admin.options'),
            // ],
        ]);
    }
}
