<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventTicket extends Model
{
    use HasFactory, SoftDeletes;

    const PRICING_FIXED = 1;
    const PRICING_DYNAMIC = 2;

    const TYPE_FREE = 1;
    const TYPE_PAID = 2;
    const TYPE_INVITE = 3;

    const QUANTITY_LIMITED = 1;
    const QUANTITY_UNLIMITED = 2;

    protected $guarded = ['id'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function setting()
    {
        return $this->hasOne(EventTicketSetting::class, 'event_ticket_id');
    }

    public function attendees()
    {
        return $this->belongsToMany(Attendee::class, 'attendee_ticket_pivot', 'event_ticket_id', 'user_id');
    }
}
