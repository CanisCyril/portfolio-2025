<?php

namespace App\Http\Controllers\Helpdesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Helpdesk\Ticket;
use App\Models\Helpdesk\TicketAttachment;
use Illuminate\Support\Facades\Storage;

class TicketAttachmentController extends Controller
{
    public function store($id, Request $request)
    {
         if($request->hasFile('attachments')) {
            $files = $request->attachments;

            foreach ($files as $file) {

                $path = $file->store("helpdesk/attachments/{$id}", 'public');

                TicketAttachment::create([
                    'ticket_id' => $id,
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        $ticket = Ticket::with([
                'priority:id,slug,name,color', 
                'user:id,name', 
                'assignee:id,role_id,name',
                'assignee.role:id,name,display_name', 
                'comments:id,author_id,ticket_id,body,created_at',
                'comments.author:id,name',
                'attachments'])->find($id);

        return $ticket;
    }

    public function download(TicketAttachment $attachment)
    {
        $path = Storage::disk('public')->path($attachment->path);
        
        return response()->download($path, $attachment->original_name);
    }
}
