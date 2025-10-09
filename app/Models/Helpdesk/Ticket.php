<?php

namespace App\Models\Helpdesk;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
        protected $table = 'tickets';
        protected $fillable = [
            'title',
            'description',
            'user_id',
            'category_id',
            'priority_id',
            'status',
        ];

        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }

        public function comments()
        {
            return $this->hasMany(TicketComment::class, 'ticket_id');
        }

        public function attachments()
        {
            return $this->hasMany(TicketAttachment::class, 'ticket_id');
        }

        public function category()
        {
            return $this->belongsTo(TicketCategory::class, 'category_id');
        }

        public function priority()
        {
            return $this->belongsTo(TicketPriority::class, 'priority');
        }

        public function status()
        {
            return $this->belongsTo(TicketStatus::class, 'status');
        }

}
