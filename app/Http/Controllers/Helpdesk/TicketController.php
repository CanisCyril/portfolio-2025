<?php

namespace App\Http\Controllers\Helpdesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Helpdesk\Ticket;
use App\Http\Requests\Helpdesk\TicketRequest;

class TicketController extends Controller
{
    public function create()
    {
        return inertia('Helpdesk/CreateTicket');
    }

    public function store(TicketRequest $request)
    {

        Ticket::create($request->all());

        return redirect()->route('helpdesk')->with('success', 'Ticket created successfully!');
    }

    public function show($id = 0)
    {
        // $ticket = Ticket::with(['user', 'comments', 'attachments', 'category', 'priority'])->findOrFail($id);
        $ticket = 1;
        return inertia('Helpdesk/ViewTicket', ['ticket' => $ticket]);
    }
}
