<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieTicketSetting extends Model
{
    use HasFactory;

    const STATUS_PUBLIC = 1;
    const STATUS_PRIVATE = 0;

    const SALE_CHANNEL_ONLINE = 1;
    const SALE_CHANNEL_OTHER = 2;

    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->belongsTo(MovieTicket::class, 'movie_ticket_id');
    }

}
