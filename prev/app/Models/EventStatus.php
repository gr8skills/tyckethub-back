<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $visible = ['id', 'name'];

    public function events()
    {
        return $this->hasMany(Event::class, 'event_status_id');
    }
}
