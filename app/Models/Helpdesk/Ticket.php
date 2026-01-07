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
        'assigned_to_id'
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

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function scopeOpen($q)
    {
        return $q->where('status', 'open');
    }

    public function scopeClosed($q)
    {
        return $q->where('status', 'closed');
    }

    public function scopeAssigned($q)
    {
        return $q->where('status', 'assigned')->whereNull('resolved_at');
    }

    public function scopeUnresolved($q)
    {
        return $q->whereNull('resolved_at');
    }

    public function scopeRecentlyClosed($q)
    {
        return $q->closed()->where('resolved_at', '>=', now()->subWeek());
    }

    public function scopeUserTickets($q, $userId)
    {
        if (request()->user()->can('helpdesk.admin.access')) {

            return $q->where('assigned_to_id', $userId);
        } else {

            return $q->where('user_id', $userId)->orWhere(function ($query) {
                $query->recentlyClosed();
            });
        }
        // USER ID SHOULD BE CREATED BY ID
    }

    public function scopeActiveTab($q, ?ActiveTabEnum $key, $userId)
    {
        return match ($key) {
            ActiveTabEnum::OPEN => $q->open(),
            ActiveTabEnum::ASSIGNED => $q->assigned(),
            ActiveTabEnum::CLOSED => $q->recentlyClosed(),
            ActiveTabEnum::MY => $q->userTickets($userId),
            default => $q->userTickets($userId)
        };
    }

    public function scopeSearch($query, $searchTerm)
    {
        $search = trim((string) $searchTerm);

        if ($search === '') {
            return $query;
        }

        if ($search == 'not assigned') {
            $search = $query->whereDoesntHave('assignee'); //no worky yet
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('id', $search)
                ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhereHas('assignee', fn($q) => $q->where('name', 'like', "%{$search}%"));
        });
    }

}
