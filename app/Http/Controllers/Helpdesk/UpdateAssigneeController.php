<?php

namespace App\Http\Controllers\Helpdesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Helpdesk\Ticket;
use App\Models\Helpdesk\TicketComment;

class UpdateAssigneeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Ticket $ticket)
    {
        $request->merge([
            'status' => 'assigned'
        ]);

        $ticket->update($request->all());
        $ticket->load(relations: ['assignee:id,role_id,name', 'assignee.role:id,name,display_name']);

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'author_id' => auth()->id(),
            'body' => "Assigned to {$ticket->assignee->name}.",
        ]);

        $ticket->load('comments.author');

        return response()->json([
            'assignee'   => $ticket->assignee,
            'comments' => $ticket->comments,
        ]);
    }
}
