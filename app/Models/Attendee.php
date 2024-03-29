<?php

namespace App\Models;

use App\Scopes\AttendeeUserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends User
{
    use HasFactory;

    protected $table = 'users';

    protected static function booted()
    {
        parent::booted(); // TODO: Change the autogenerated stub
        static::addGlobalScope(new AttendeeUserScope);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'attendee_event_pivot', 'user_id', 'event_id')
            ->withPivot(['is_favorite', 'is_purchased']);
    }

    public function tickets()
    {
        return $this->belongsToMany(EventTicket::class, 'attendee_ticket_pivot', 'user_id', 'event_ticket_id')
            ->withPivot(['quantity', 'price']);
    }

    public function paidTickets()
    {
        return $this->belongsToMany(EventTicket::class, 'attendee_ticket_pivot',  'event_ticket_id')
            ->withPivot(['quantity', 'price']);
    }


    public function favoriteEventsScope()
    {
        return $this->events()->filter(function ($event) {
            return $event->pivot->is_favorite === Event::IS_FAVORITE_TRUE;
        });
    }

    public function paidEventsScope()
    {
        return $this->events()->filter(function ($event) {
            return $event->is_purchased === Event::IS_PURCHASED_TRUE;
        });
    }
}
