<?php

namespace App\Http\Controllers\Helpdesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Helpdesk\Ticket;
use App\Models\Helpdesk\TicketAttachment;
use Inertia\Inertia;
use App\Http\Requests\Helpdesk\TicketStoreRequest;
use App\Enums\Helpdesk\ActiveTabEnum;
use Illuminate\Validation\Rule;

class TicketController extends Controller
{
    public function create()
    {
        return inertia('Helpdesk/CreateTicket');
    }

    public function store(TicketStoreRequest $request)
    {
        $request->merge(['user_id' => auth()->id()]);
        $ticket = Ticket::create($request->all());

        if ($request->hasFile('attachments')) {
            $files = $request->attachments;

            foreach ($files as $file) {

                $path = $file->store("helpdesk/attachments/{$ticket->id}", 'public');

                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('helpdesk')->with('success', 'Ticket created successfully!');
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

    public function fetchTickets(Request $request)
    {

        $validated = $request->validate([
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'sort_column' => ['nullable', Rule::in(['id', 'title', 'ticket_status', 'created_at'])],
            'sort_direction' => ['nullable', Rule::in(['asc', 'desc'])],
        ]);

        $key = $request->enum('activeTab', ActiveTabEnum::class);
        $page = $validated['page'] ?? 1;
        $perPage = $validated['per_page'] ?? 10;
        $sortColumn = $validated['sort_column'] ?? 'id';
        $sortDirection = $validated['sort_direction'] ?? 'desc';

        $tickets = Ticket::activeTab($key, auth()->user()->id)
            ->with([
                'priority:id,slug,name,color',
                'user:id,name',
                'assignee:id,role_id,name',
                'assignee.role:id,name,display_name',
                'comments:id,author_id,ticket_id,body,created_at',
                'comments.author:id,name',
                'attachments'
            ])
            ->search($request->input('search'))
            ->orderBy($sortColumn, $sortDirection)
            ->paginate($perPage, $columns = ['*'], $pageName = 'page', $page);

        return $tickets;
    }

}
