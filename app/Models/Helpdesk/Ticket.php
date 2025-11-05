<?php

namespace App\Models\Helpdesk;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Helpdesk\ActiveTabEnum;
use App\Models\User;

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
            return $this->belongsTo(TicketPriority::class, 'priority_id');
        }

        public function status()
        {
            return $this->belongsTo(TicketStatus::class, 'status');
        }


        public function scopeOpen($q) {
            return $q->where('status','open');
        }

        public function scopeClosed($q) {
            return $q->where('status','closed');
        }

        public function scopeAssigned($q) {
            return $q->where('status','assigned')->whereNull('resolved_at');
        }

        public function scopeRecentlyClosed($q) {
            return $q->closed()->where('resolved_at', '>=', now()->subWeek());
        }

        public function scopeUserTickets($q, $userId) {
            return $q->where('user_id', $userId);
            // USER ID SHOULD BE CREATED BY ID
        }

        public function scopeActiveTab($q, ?ActiveTabEnum $key, $userId) {
            return match ($key) {
                ActiveTabEnum::OPEN => $q->open(),
                ActiveTabEnum::ASSIGNED => $q->assigned(),
                ActiveTabEnum::CLOSED => $q->recentlyClosed(),
                ActiveTabEnum::MY => $q->where(function ($subQ) use ($userId) {
                                        $subQ->where(fn ($q1) => $q1->open()->userTickets($userId))
                                            ->orWhere(fn ($q1) => $q1->recentlyClosed()->userTickets($userId));
                                    }),
                default  => $q->where(function ($subQ) use ($userId) {
                                        $subQ->where(fn ($q1) => $q1->open()->userTickets($userId))
                                            ->orWhere(fn ($q1) => $q1->recentlyClosed()->userTickets($userId));
                                    }),
            };
        }

}
