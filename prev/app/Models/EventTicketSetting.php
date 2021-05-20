<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTicketSetting extends Model
{
    use HasFactory;

    const STATUS_PUBLIC = 1;
    const STATUS_PRIVATE = 0;

    const SALE_CHANNEL_ONLINE = 1;
    const SALE_CHANNEL_OTHER = 2;

    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->belongsTo(EventTicket::class, 'event_ticket_id');
    }

//    public function setting()
//    {
//        return $this->hasOne(EventTicketSetting::class, 'event_tick')
//    }
}
