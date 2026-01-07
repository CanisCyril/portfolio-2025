<?php

namespace App\Http\Controllers\Helpdesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Helpdesk\Ticket;
use App\Models\Helpdesk\TicketComment;
use App\Http\Requests\Helpdesk\TicketCommentStoreRequest;

class TicketCommentController extends Controller
{
    public function store(TicketCommentStoreRequest $request, TicketComment $ticketComment)
    {

        $ticketId = $request->ticket_id;

        $comment = $ticketComment->create([
            'author_id' => auth()->id(),
            'ticket_id' => $ticketId,
            'body' => $request->body
        ]);

        return $comment->load('author:id,name');

    }
}
