<?php

namespace App\Models\Helpdesk;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class TicketComment extends Model
{
    
    protected $table = 'ticket_comments';
    protected $fillable = ['author_id', 'ticket_id', 'body'];

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }
}
