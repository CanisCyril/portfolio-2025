<?php

namespace App\Models\Helpdesk;

use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    protected $table = 'ticket_attachments';

    protected $fillable = [
        'ticket_id',
        'path',
        'original_name',
        'mime_type',
        'size',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
