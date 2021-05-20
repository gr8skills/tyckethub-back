<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTag extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_tag_pivot', 'event_tag_id', 'event_id');
    }
}
