<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_category_pivot', 'event_category_id', 'event_id');
    }
}
