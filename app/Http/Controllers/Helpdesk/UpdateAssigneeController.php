<?php

namespace App\Http\Controllers\Helpdesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Helpdesk\Ticket;

class UpdateAssigneeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Ticket $ticket)
    {
        $ticket->update($request->all());
        $ticket->load(relations: ['assignee:id,role_id,name', 'assignee.role:id,name,display_name']);

        return $ticket->assignee;
    }
}
